<div class="page-header">
	<h4>SMTP &amp; Email Setup</h4>
</div>

<?php echo form_open('', array('class'=>'form-horizontal alt1', 'id'=>'xform'))?>

<div class="control-group">
	<label class="control-label">SMTP Host <span class="important">*</span></label>
	<div class="controls">
		<input type="text" name="smpt_host" id="smtp_host" class="input-large" placeholder="SMTP Host" value="">
		<?php echo form_error('smpt_host')?>
	</div>
</div>

<div class="control-group">
	<label class="control-label">SMTP Port <span class="important">*</span></label>
	<div class="controls">
		<input type="text" name="smpt_port" id="smpt_port" class="input-large" placeholder="SMTP Host" value="">
		<?php echo form_error('smpt_port')?>
	</div>
</div>

<div class="control-group">
	<label class="control-label">SMTP Username <span class="important">*</span></label>
	<div class="controls">
		<input type="text" name="smpt_username" id="smpt_username" class="input-large" placeholder="SMTP Host" value="">
		<?php echo form_error('smpt_username')?>
	</div>
</div>

<div class="control-group">
	<label class="control-label">SMTP Password <span class="important">*</span></label>
	<div class="controls">
		<input type="password" name="smpt_password" id="smpt_password" class="input-large" placeholder="SMTP Host" value="">
		<?php echo form_error('smpt_password')?>
	</div>
</div>

<div class="form-actions">
	<button type="submit" id="submitBtn" class="btn btn-primary" title="Save as a draft">Let's attach some files</button>
	<button type="reset" id="resetBtn" class="btn">Reset Form</button>
</div>

<?php form_close()?>