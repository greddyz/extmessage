<?php
// MODX Fast Router - get user fields
$key = $modx->getOption('fastrouter.paramsKey', null, 'fastrouter');

//Если использовать в качестве обработчика ID ресурса,
//то все именованные параметры попадут в массив fastrouter в глобальном массиве $_REQUEST
$params = isset($_REQUEST[$key]) ? $_REQUEST[$key] : array();

//В случае когда обработчиком является сниппет, все параметры будут доступны в $scriptProperties
//$params = $modx->getOption($key, $scriptProperties, array());

$msg_id = isset($params['msg_id']) ? $params['msg_id'] : null;

/* Routing completed */

$language = $modx->getOption('cultureKey');
$language = empty($language) ? 'ru' : $language;
$modx->lexicon->load($language.':extmessage:default');

if(!$user_auth = $modx->getAuthenticatedUser()){
		$modx->log(modX::LOG_LEVEL_ERROR,'ERROR: _extMessagePageMsg_ Not an authorized user!');
		return $modx->lexicon('extmessage_err_noauth');
	}

$output = '';

if ($msg_id){
	$modx->addPackage('extmessage', $modx->getOption('core_path').'components/extmessage/model/');

	$user = $user_auth->get('id');
	$tpl_message = empty($scriptProperties['tpl_message']) ? 'extmsg.message' : $scriptProperties['tpl_message'];
	$tpl_messageWrap = empty($scriptProperties['tpl_messageWrap']) ? 'extmsg.messageWrap' : $scriptProperties['tpl_messageWrap'];
	$tpl_formReply = empty($scriptProperties['tpl_formReply']) ? 'extmsg.formReply' : $scriptProperties['tpl_formReply'];

	// Определяем уникальный тип письма для выявления цепочки писем одной темы
	$message = $modx->getObject('ExtMessage', $msg_id);
	$msg_type = $message->get('type');

	$output .= $modx->setPlaceholder('msg_type', $msg_type);

/*	if($message->get('recipient') == $user){
		//$message->set('sender') = $message->get('recipient');
		$message_arr = $message->toArray();
		$message_arr['recipient'] = $message_arr['sender'];
	}*/

	$output .= $modx->getChunk($tpl_messageWrap); //, $message->toArray() // чанк обертка

	$query = $modx->newQuery('ExtMessage');

	$query->innerJoin('modUser', 'modUser', array('ExtMessage.sender = modUser.id'));
	$query->innerJoin('modUserProfile', 'Profile', array('modUser.id = Profile.internalKey'));
	$query->select(array(
		'ExtMessage.id',
		'ExtMessage.sender',
		'ExtMessage.recipient',
		'ExtMessage.subject',
		'ExtMessage.message',
		'ExtMessage.date_sent',
		'modUser.username',
		'Profile.fullname'
		)
	);
	$query->where(array(
		'ExtMessage.type:=' => $msg_type
		)
	);
	$query->sortby('ExtMessage.date_sent', 'ASC');

	$query->prepare();

	if($query->stmt && $query->stmt->execute()){
		$result = $query->stmt->fetchAll(PDO::FETCH_ASSOC);

		foreach ($result as $res){
			$output .= $modx->getChunk($tpl_message, $res);
		}
	}
	else {
		$modx->log(modX::LOG_LEVEL_ERROR,'ERROR: _extMessage_ The request newQuery(ExtMessage) failed');
		return $modx->lexicon('extmessage_err_request');
	}

	// Если в новом диалоге одно сообщение отправителя=текущему пользователю, то переназначаем получателя
	if($res['sender'] == $user){
		$res['sender'] = $res['recipient'];
		$res['recipient'] = $user;
	}

	$output .= $modx->setPlaceholders($res);
}
else {
	$output = $modx->lexicon('extmessage_err_request');
}

return $output;