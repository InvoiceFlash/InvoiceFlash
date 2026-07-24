<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="panel panel-default">
	<div class="panel-heading clearfix">
		<div class="pull-left h2"><i class="hidden-xs fa fa-file-alt"></i> <?php echo $heading_title; ?></div>
		<div class="pull-right">
			<button type="button" onclick="draftOpenPrintModal('');" class="btn btn-default btn-spacer"><i class="fa fa-eye"></i><span class="hidden-xs"> <?php echo $button_view; ?></span></button>
			<button type="button" onclick="draftOpenPrintModal('pdf');" class="btn btn-default btn-spacer"><i class="fa fa-file-pdf"></i><span class="hidden-xs"> <?php echo $button_print; ?></span></button>
			<button type="submit" form="form" formaction="<?php echo $convert; ?>" onclick="return confirm(text_confirm);" id="btn-convert" class="btn btn-success btn-spacer"><i class="fa fa-exchange-alt"></i><span class="hidden-xs"> <?php echo $button_convert; ?></span></button>
			<a href="<?php echo $insert; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i><span class="hidden-xs"> <?php echo $button_insert; ?></span></a>
			<button type="submit" form="form" formaction="<?php echo $delete; ?>" id="btn-delete" class="btn btn-danger"><i class="fa fa-trash "></i><span class="hidden-xs"> <?php echo $button_delete; ?></span></button>
		</div>
	</div>
	<div class="panel-body">
		<form class="foe" action="<?php echo $draft; ?>" method="post" enctype="multipart/form-data" id="form" name="form">
			<table class="table table-bordered table-striped table-hover">
				<thead>
					<tr>
						<th width="40" class="text-center"><input type="checkbox" data-toggle="selected"></th>
						<th class="text-right"><a href="<?php echo $sort_draft; ?>"><?php echo $column_draft_id; echo ($sort == 'o.draft_id') ? '<i class="caret caret-' . strtolower($order) . '"></i>' : ''; ?></a></th>
						<th><a href="<?php echo $sort_company; ?>"><?php echo $column_customer; echo ($sort == 'company') ? '<i class="caret caret-' . strtolower($order) . '"></i>' : ''; ?></a></th>
						<th class="hidden-xs"><?php echo $column_simplified; ?></th>
						<th class="text-right hidden-xs"><a href="<?php echo $sort_total; ?>"><?php echo $column_total; echo ($sort == 'o.total') ? '<i class="caret caret-' . strtolower($order) . '"></i>' : ''; ?></a></th>
						<th class="hidden-xs"><a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; echo ($sort == 'o.date_added') ? '<i class="caret caret-' . strtolower($order) . '"></i>' : ''; ?></a></th>
						<th class="hidden-xs hidden-sm"><a href="<?php echo $sort_date_modified; ?>"><?php echo $column_date_modified; echo ($sort == 'o.date_modified') ? '<i class="caret caret-' . strtolower($order) . '"></i>' : ''; ?></a></th>
						<th class="text-right"><span class="hidden-xs"><?php echo $column_action; ?></span></th>
					</tr>
				</thead>
				<tbody data-link="row" class="rowlink">
					<tr id="filter" class="info">
						<td class="text-center"><a class="btn btn-default btn-block" href="index.php?route=sale/draft&token=<?php echo $token; ?>" rel="tooltip" title="Reset"><i class="fa fa-power-off fa-fw"></i></a></td>
						<td class="text-right"><input type="text" name="filter_draft_id" value="<?php echo $filter_draft_id; ?>" class="form-control text-right"></td>
						<td><input type="text" name="filter_company" value="<?php echo $filter_company; ?>" class="form-control" data-target="company" data-url="sale/customer" class="form-control"></td>
						<td class="hidden-xs"></td>
						<td class="text-right hidden-xs"><input type="text" name="filter_total" value="<?php echo $filter_total; ?>" class="form-control text-right"></td>
						<td class="hidden-xs"><div class="input-group">
							<input type="text" name="filter_date_added" class="form-control date"/>
							<div class="input-group-append">
							<div class="input-group-text"><i class="fas fa-calendar"></i></div>
							</div>
						</div></td>
						<td class="hidden-xs"><div class="input-group">
							<input type="text" name="filter_date_modified" class="form-control date"/>
							<div class="input-group-append">
							<div class="input-group-text"><i class="fas fa-calendar"></i></div>
							</div>
						</div></td>
						<td class="text-right"><button type="button" onclick="filter();" class="btn btn-info"><i class="fa fa-search"></i><span class="hidden-xs"> <?php echo $button_filter; ?></span></button></td>
					</tr>
					<?php if ($drafts) { ?>
					<?php foreach ($drafts as $draft) { ?>
					<tr>
						<td class="rowlink-skip text-center"><?php if ($draft['selected']) { ?>
							<input type="checkbox" name="selected[]" value="<?php echo $draft['draft_id']; ?>" checked="">
							<?php } else { ?>
							<input type="checkbox" name="selected[]" value="<?php echo $draft['draft_id']; ?>">
							<?php } ?></td>
						<td class="text-right"><?php echo $draft['draft_id']; ?></td>
						<td><?php echo $draft['company']; ?></td>
						<td class="hidden-xs"><?php echo ($draft['simplified']) ? $text_simplified : $text_normal; ?></td>
						<td class="text-right hidden-xs"><?php echo $draft['total']; ?></td>
						<td class="hidden-xs"><?php echo $draft['date_added']; ?></td>
						<td class="hidden-xs hidden-sm"><?php echo $draft['date_modified']; ?></td>
						<td class="text-right"><?php foreach ($draft['action'] as $action) { ?>
							<a href="<?php echo $action['href']; ?>" class="btn btn-<?php echo $action['color']; ?>"><i class="<?php echo $action['icon']; ?>"></i></a>
						<?php } ?></td>
					</tr>
					<?php } ?>
					<?php } else { ?>
					<tr>
						<td class="text-center" colspan="8"><?php echo $text_no_results; ?></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</form>
		<div class="pagination"><?php echo str_replace('....','',$pagination); ?></div>
	</div>
</div>
<?php include DIR_TEMPLATE . 'sale/print_modal.tpl'; ?>
<script>
function draftOpenPrintModal(format) {
	if (!$('input[type="checkbox"]').is(':checked')) {
		alert('<?php echo $error_no_selection; ?>');
	} else {
		$('#formPrint>input[name="selected[]"]').remove();
		$('input[type="checkbox"]:checked').each(function(){
			$('<input type="hidden" name="selected[]" value="'+$(this).val()+'">').appendTo('#formPrint');
		});

		var base = <?php echo json_encode(html_entity_decode($print, ENT_QUOTES, 'UTF-8')); ?>;
		$('#formPrint').attr('action', format ? (base + '&format=' + format) : base);

		bootstrap.Modal.getOrCreateInstance(document.getElementById('PrintModal')).toggle();
	}
}
$(document).ready(function(){
     $("#PrintModal").on('hidden.bs.modal', function () {
        $('#formPrint>input[name="selected[]"]').remove();
    });
});
</script>
<?php echo $footer; ?>