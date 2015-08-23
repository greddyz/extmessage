<?php
$modx->addPackage('extmessage', $modx->getOption('core_path').'components/extmessage/model/');

if(!$user_auth = $modx->getAuthenticatedUser()){
	$modx->log(modX::LOG_LEVEL_ERROR,'ERROR: _extMessageFormSent_ Not an authorized user!');
	return false;
}

$user = $user_auth->get('id');
// Переданный параметр от FormIt, если newmessage = 1, то создается новое письмо
$fi_newmessage = !empty($scriptProperties['newmessage']) ? $scriptProperties['newmessage'] : null;
$msg_type = !empty($scriptProperties['msg_type']) ? $scriptProperties['msg_type'] : null;

if(!empty($_POST['recipient']) && !empty($_POST['subject']) && !empty($_POST['message'])){

	// Проверка на отправку нового сообщения самому себе
	if($_POST['recipient'] == $user){
		// FormIt
		$hook->addError('recipient',$modx->lexicon('extmessage_form_err_recipient_yourself'));
		return false;
	}

	if ($fi_newmessage){
		$msg_type = md5($user.date(YmdHis).$_POST['subject']);
	}

	if (!$msg_type) {
		$modx->log(modX::LOG_LEVEL_ERROR,'ERROR: _extMessageFormSent_ msg_type is absent');
		$hook->addError('hook_error',$modx->lexicon('extmessage_err_request'));
		return false;
	}

	$item = $modx->newObject('ExtMessage');

	$item->set('type', $msg_type);
	$item->set('sender', $user);
	$item->set('recipient', $_POST['recipient']);
	$item->set('subject', $_POST['subject']);
	$item->set('message', $_POST['message']);
	$item->set('private', 1);
	$item->set('date_sent', date('Y-m-d H:i:s'));
	$item->save();

	$output = true;
}
else {
	$modx->log(modX::LOG_LEVEL_ERROR,'ERROR: _extMessageFormSent_ Absent or empty properties: recipient, subject, message.');
	$output = false;
}

return $output;