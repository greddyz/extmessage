[[+fi.error.error_message]]
[[!+fi.error.hook_error]]
<form class="form-horizontal" method="post" action="[[~[[*id]]]]">
	<div class="form-group">
		<label for="recipient" class="col-sm-2 control-label">[[%extmessage_form_recipient_label]]</label>
		<div class="col-sm-10">
		[[!+fi.error.recipient]]
			<input id="recipient" class="form-control" type="text" name="recipient" placeholder="[[%extmessage_form_recipient_label]]" value="[[!+fi.recipient]]">
		</div>
	</div>
	<div class="form-group">
		<label for="subject" class="col-sm-2 control-label">[[%extmessage_form_subject_label]]</label>
		<div class="col-sm-10">
			<input id="subject" class="form-control" type="text" name="subject" placeholder="[[%extmessage_form_subject_label]]">
		</div>
	</div>
	<div class="form-group">
				<label for="message" class="col-sm-2 control-label">[[%extmessage_form_message_label]]</label>
				<div class="col-sm-10">
						[[!+fi.error.message]]
						<textarea id="message" class="form-control" name="message" placeholder="[[%extmessage_form_message_label]]"></textarea>
				</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<input class="btn btn-default" type="submit" name="submit" value="[[%extmessage_form_submit_label]]">
		</div>
	</div>
</form>