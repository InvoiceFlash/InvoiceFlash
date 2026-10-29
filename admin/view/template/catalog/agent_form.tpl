<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="card page-card">
	<?php $fa = 'robot'; include(DIR_TEMPLATE . 'common/template-title-form.tpl'); ?>
	<div class="card-body">
		<form class="form-classic" action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
			<div class="form-group row">
				<label class="col-form-label col-sm-10 col-md-2"><span class="required">*</span> <?php echo $entry_ai_provider; ?></label>
				<div class="col-sm-6">
					<select name="agent_ai_provider" class="form-select" style="max-width:420px;">
						<?php if ($ai_provider_missing) { ?>
							<option value="<?php echo htmlspecialchars($agent_ai_provider, ENT_QUOTES, 'UTF-8'); ?>" selected><?php echo htmlspecialchars($ai_provider_missing, ENT_QUOTES, 'UTF-8'); ?> - <?php echo $text_no_key_suffix; ?></option>
						<?php } ?>
						<?php foreach ($ai_providers as $provider => $provider_name) { ?>
							<option value="<?php echo $provider; ?>"<?php echo ($provider == $agent_ai_provider) ? ' selected' : ''; ?>><?php echo htmlspecialchars($provider_name, ENT_QUOTES, 'UTF-8'); ?></option>
						<?php } ?>
					</select>
					<div class="form-text"><?php echo $text_help_ai_provider; ?></div>
					<?php if ($error_ai_provider) { ?>
						<div class="form-text text-danger"><?php echo $error_ai_provider; ?></div>
					<?php } ?>
				</div>
			</div>
			<div class="form-group row">
				<label class="col-form-label col-sm-10 col-md-2"><span class="required">*</span> <?php echo $entry_name; ?></label>
				<div class="col-sm-6">
					<input type="text" name="agent_name" maxlength="128" class="form-control" value="<?php echo $agent_name; ?>">
					<?php if ($error_name) { ?>
						<div class="form-text text-danger"><?php echo $error_name; ?></div>
					<?php } ?>
				</div>
			</div>
			<div class="form-group row">
				<label class="col-form-label col-sm-10 col-md-2"><span class="required">*</span> <?php echo $entry_description; ?></label>
				<div class="col-sm-8">
					<textarea name="agent_description" rows="14" class="form-control"><?php echo $agent_description; ?></textarea>
					<div class="form-text"><?php echo $text_help_description; ?></div>
					<?php if ($error_description) { ?>
						<div class="form-text text-danger"><?php echo $error_description; ?></div>
					<?php } ?>
				</div>
			</div>
			<div class="form-group row">
				<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_access; ?></label>
				<div class="col-sm-6">
					<select name="agent_access" class="form-select" style="max-width:320px;">
						<option value="read"<?php echo ($agent_access != 'write') ? ' selected' : ''; ?>><?php echo $text_access_read; ?></option>
						<option value="write"<?php echo ($agent_access == 'write') ? ' selected' : ''; ?>><?php echo $text_access_write; ?></option>
					</select>
					<div class="form-text"><?php echo $text_help_access; ?></div>
				</div>
			</div>
			<div class="form-group row">
				<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_schedule; ?></label>
				<div class="col-sm-6">
					<div class="form-check">
						<input type="radio" class="form-check-input" name="agent_schedule" id="agent-schedule-time" value="time"<?php echo ($agent_schedule != 'always') ? ' checked' : ''; ?>>
						<label class="form-check-label" for="agent-schedule-time"><?php echo $text_schedule_time_opt; ?></label>
					</div>
					<div class="form-check">
						<input type="radio" class="form-check-input" name="agent_schedule" id="agent-schedule-always" value="always"<?php echo ($agent_schedule == 'always') ? ' checked' : ''; ?>>
						<label class="form-check-label" for="agent-schedule-always"><?php echo $text_schedule_always_opt; ?></label>
					</div>
					<div class="form-text"><?php echo $text_help_always; ?></div>
				</div>
			</div>
			<div class="form-group row" id="agent-time-group">
				<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_run_time; ?></label>
				<div class="col-sm-2">
					<input type="time" name="agent_run_time" class="form-control" value="<?php echo $agent_run_time; ?>">
					<?php if ($error_run_time) { ?>
						<div class="form-text text-danger"><?php echo $error_run_time; ?></div>
					<?php } ?>
				</div>
			</div>
			<div class="form-group row">
				<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_status; ?></label>
				<div class="col-sm-2">
					<select name="agent_status" class="form-select">
						<option value="1"<?php echo $agent_status ? ' selected' : ''; ?>><?php echo $text_enabled; ?></option>
						<option value="0"<?php echo $agent_status ? '' : ' selected'; ?>><?php echo $text_disabled; ?></option>
					</select>
				</div>
			</div>
		</form>
	</div>
</div>
<script>
function agentScheduleToggle() {
	$('#agent-time-group').toggle($('input[name="agent_schedule"]:checked').val() != 'always');
}

$('input[name="agent_schedule"]').change(agentScheduleToggle);
agentScheduleToggle();
</script>
<?php echo $footer; ?>
