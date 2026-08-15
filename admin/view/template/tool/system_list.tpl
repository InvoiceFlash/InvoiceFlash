<?php echo $header; ?>

<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>

<div class="panel panel-default">

	<div class="panel-heading clearfix">
		<div class="pull-left h2"><i class="fa fa-server"></i> <?php echo $heading_title; ?></div>
	</div>

	<div class="panel-body">
		<div class="table-responsive">
			<table class="table table-bordered table-striped">
				<tbody>
					<tr>
						<td style="width:40%;"><?php echo $text_php_version; ?></td>
						<td><?php echo $php_version; ?></td>
					</tr>
					<tr>
						<td><?php echo $text_db_version; ?></td>
						<td><?php echo $db_version; ?></td>
					</tr>
					<tr>
						<td><?php echo $text_ram; ?></td>
						<td><?php echo $ram_total ? $ram_total : $text_not_available; ?></td>
					</tr>
					<tr>
						<td><?php echo $text_gpu; ?></td>
						<td>
							<?php if ($gpus) { ?>
								<?php foreach ($gpus as $gpu) { ?>
								<div><?php echo $gpu['name']; ?> <small class="text-muted">(<?php echo $gpu['vram'] ? $gpu['vram'] : $text_not_available; ?>)</small></div>
								<?php } ?>
							<?php } else { ?>
								<?php echo $text_not_available; ?>
							<?php } ?>
						</td>
					</tr>
					<tr>
						<td><?php echo $text_ollama_status; ?></td>
						<td>
							<?php if ($ollama_installed) { ?>
							<span class="badge bg-success text-white"><?php echo $text_yes; ?></span> <small class="text-muted"><?php echo $ollama_base; ?></small>
							<?php } else { ?>
							<span class="badge bg-danger text-white"><?php echo $text_no; ?></span>
							<?php } ?>
						</td>
					</tr>
					<tr>
						<td><?php echo $text_ai_model; ?></td>
						<td><?php if ($ai_enabled) { ?><?php echo $ai_model; ?><?php } else { ?><span class="badge bg-danger text-white"><?php echo $text_no; ?></span><?php } ?></td>
					</tr>
					<tr>
						<td><?php echo $text_rag_status; ?></td>
						<td>
							<?php if ($rag_enabled) { ?>
							<span class="badge bg-success text-white"><?php echo $text_yes; ?></span>
							<?php } else { ?>
							<span class="badge bg-danger text-white"><?php echo $text_no; ?></span>
							<?php } ?>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>
<?php echo $footer; ?>
