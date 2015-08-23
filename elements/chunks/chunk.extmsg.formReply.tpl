[[+fi.error.error_message]]
[[!+fi.error.hook_error]]
[$msg_type]: [[+msg_type]]
<form class="form-horizontal" method="post" action="[[~[[*id]]]]">
		<input type="hidden" name="recipient" value="[[+sender]]">
		<input type="hidden" name="subject" value="[[+subject]]">
		<div class="form-group">
				<div class="col-sm-12">
						<textarea id="message" class="form-control" name="message" placeholder="[[%extmessage_form_message_label]]"></textarea>
				</div>
		</div>
		<div class="form-group">
				<div class="col-sm-12">
					<input class="btn btn-default" type="submit" name="submit" value="[[%extmessage_form_submit_label]]">
				</div>
	</div>
</form>