<?php
/**
 * default properties:
 * Required
 * &param : inbox, outbox, newmsg
 *
 * Not required
 *
 * Inbox page:
 * &tpl_inboxItem : extmsg.inboxItem
 * &tpl_inWrap : extmsg.inboxWrap
 * Outbox page:
 * &tpl_outItem : extmsg.outboxItem
 * &tpl_outWrap : extmsg.outboxWrap
 * New message page:
 * &tpl_formsent : extmsg.formSent
 */

$modx->addPackage('extmessage', $modx->getOption('core_path').'components/extmessage/model/');

$language = $modx->getOption('cultureKey');
$language = empty($language) ? 'ru' : $language;
$modx->lexicon->load($language.':extmessage:default');

if(!$user_auth = $modx->getAuthenticatedUser()){
	$modx->log(modX::LOG_LEVEL_ERROR,'ERROR: _extMessage_ Not an authorized user!');
	return $modx->lexicon('extmessage_err_noauth');
}

$output = '';
$user = $user_auth->get('id');
$param = $scriptProperties['param'];
$tpl_inboxItem = empty($scriptProperties['tpl_inboxItem']) ? 'extmsg.inboxItem' : $scriptProperties['tpl_inboxItem'];
$tpl_outItem = empty($scriptProperties['tpl_outItem']) ? 'extmsg.outboxItem' : $scriptProperties['tpl_outItem'];
$tpl_inWrap = empty($scriptProperties['tpl_inWrap']) ? 'extmsg.inboxWrap' : $scriptProperties['tpl_inWrap'];
$tpl_outWrap = empty($scriptProperties['tpl_outWrap']) ? 'extmsg.outboxWrap' : $scriptProperties['tpl_outWrap'];
$tpl_formsent = empty($scriptProperties['tpl_formsent']) ? 'extmsg.formSent' : $scriptProperties['tpl_formsent'];


if (empty($param)) {
	$modx->log(modX::LOG_LEVEL_ERROR,'ERROR: _extMessage_ Absent or empty propertie: param.');
	return;
}

	switch ($param) {
		case 'testproc': ////////////////////////////////////////// test run processor
			$processorProps = array(
				'classKey' => 'ExtMessage'
			);
			$otherProps = array(
			'processors_path' => $modx->getOption('core_path') . 'components/extmessage/processors/'
			);
			$response = $modx->runProcessor('messagegetlist', $processorProps, $otherProps);

			$res = json_decode($response->response);
			if($res->success == 1){
				foreach ($res->results as $value) {
					//$out .= $value->subject;
					$out .= $modx->getChunk($tpl_inboxItem, $value);
				}

			}
			return var_dump($out);

		break;
		case 'inbox':
			// xPDO
			// формируем запрос к таблице (классу) ExtMessage с параметрами выбоки
			$query = $modx->newQuery('ExtMessage');

			$query->innerJoin('modUser', 'modUser', array('ExtMessage.sender = modUser.id'));
			$query->innerJoin('modUserProfile', 'Profile', array('modUser.id = Profile.internalKey'));
			$query->select(array(
				'ExtMessage.id',
				'ExtMessage.type',
				'ExtMessage.subject',
				'ExtMessage.date_sent',
				'modUser.username',
				'Profile.fullname'
				)
			);
			$query->where(array(
				'ExtMessage.sender:=' => $user,
				'OR:ExtMessage.recipient:=' => $user
				)
			);
			$query->sortby('ExtMessage.date_sent', 'DESC');

			$query->prepare();

			if($query->stmt && $query->stmt->execute()){
				$result = $query->stmt->fetchAll(PDO::FETCH_ASSOC);

				$output .= $modx->getChunk($tpl_inWrap); // чанк обертка

				$dialogs = array();
				// Формируем массив диалогов. В диалог входят письма с одинаковой темой.
				foreach ($result as $res) {
					// тест вывод всех писем
					//$output .= $modx->getChunk($tpl_inboxItem, $res);

					if(array_key_exists($res['type'], $dialogs) === false){
						$dialogs[$res['type']] = $res;
					}
				}
				// Вывод сформированных диалогов
				foreach ($dialogs as $dialog) {
					$output .= $modx->getChunk($tpl_inboxItem, $dialog);
				}
			}
			else {
				$modx->log(modX::LOG_LEVEL_ERROR,'ERROR: _extMessage_ The request failed');
				return $modx->lexicon('extmessage_err_request');
			}

			if(!$output) {$output = $modx->lexicon('extmessage_no_message');}

			break;
		case 'outbox':
			$query = $modx->newQuery('ExtMessage');

			$query->innerJoin('modUser', 'modUser', array('ExtMessage.sender = modUser.id'));
			$query->innerJoin('modUserProfile', 'Profile', array('modUser.id = Profile.internalKey'));
			$query->select(array(
				'ExtMessage.id',
				'ExtMessage.recipient',
				'ExtMessage.subject',
				'ExtMessage.date_sent',
				'modUser.username',
				'Profile.fullname'
				)
			);
			$query->where(array(
				'ExtMessage.sender:=' => $user
				)
			);

			$query->prepare();

			if($query->stmt && $query->stmt->execute()){
				$result = $query->stmt->fetchAll(PDO::FETCH_ASSOC);
				$output .= $modx->getChunk($tpl_inWrap); // чанк оберка

				foreach ($result as $res) {
					$output .= $modx->getChunk($tpl_inboxItem, $res);
				}
			}
			else {
				$modx->log(modX::LOG_LEVEL_ERROR,'ERROR: _extMessage_ The request failed');
				return $modx->lexicon('extmessage_err_request');
			}

			if(!$output) {$output = $modx->lexicon('extmessage_no_message');}

			break;
		case 'newmsg':
			$output = $modx->runSnippet('FormIt', array(
			'hooks' => 'extMessageFormSent,spam,redirect',
			'newmessage' => '1',
			'redirectTo' => '40',
			'validate' => 'message:required'
				)
			);
			$output .= $modx->getChunk($tpl_formsent);

			break;
}

return $output;