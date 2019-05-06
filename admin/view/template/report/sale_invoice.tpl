<?php echo $header; ?>

<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>

<div class="panel panel-default">

	<div class="panel-heading clearfix">
		<div class="h2"><i class="fa fa-file-alt"></i> <?php echo $heading_title; ?></div>

	</div>

	<div class="panel-body">
		<div id="filter" class="well">
			<div class="row">
				<div class="col-sm-3">
					<div class="input-group">
						<input type="text" class="form-control date" id="date-start" name="filter_date_start" value="<?php echo $filter_date_start ?>" placeholder="<?php echo $entry_date_start?>">
						<div class="input-group-append"><div class="input-group-text"><i class="fas fa-calendar"></i></div></div>
					</div>
				</div>
				<div class="col-sm-3">
					<div class="input-group">
						<input type="text" class="form-control date" id="date-end" name="filter_date_end" value="<?php echo $filter_date_end ?>" placeholder="<?php echo $entry_date_end?>">
						<div class="input-group-append"><div class="input-group-text"><i class="fas fa-calendar"></i></div></div>
					</div>
				</div>
				<div class="col-sm-2">
					<select name="filter_group" title="<?php echo $entry_group; ?>" class="form-control">
						<?php foreach ($groups as $groups) { ?>
						<?php if ($groups['value'] == $filter_group) { ?>
						<option value="<?php echo $groups['value']; ?>" selected=""><?php echo $groups['text']; ?></option>
						<?php } else { ?>
						<option value="<?php echo $groups['value']; ?>"><?php echo $groups['text']; ?></option>
						<?php } ?>
						<?php } ?>
					</select>
				</div>
				<div class="col-sm-2">
					<select name="filter_invoice_status_id" title="<?php echo $entry_status; ?>" class="form-control">
						<option value="0"><?php echo $text_all_status; ?></option>
						<?php foreach ($invoice_statuses as $invoice_status) { ?>
						<?php if ($invoice_status['invoice_status_id'] == $filter_invoice_status_id) { ?>
						<option value="<?php echo $invoice_status['invoice_status_id']; ?>" selected=""><?php echo $invoice_status['name']; ?></option>
						<?php } else { ?>
						<option value="<?php echo $invoice_status['invoice_status_id']; ?>"><?php echo $invoice_status['name']; ?></option>
						<?php } ?>
						<?php } ?>
					</select>
				</div>
				<div class="col-sm-2 text-right">
					<button type="button" onclick="filter();" class="btn btn-info"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
				</div>
			</div>
		</div>
		<div class="table-responsive">
		<table class="table table-bordered table-striped">
        <thead>
          <tr>
            <td class="left"><?php echo $column_invoiceid; ?></td>
			<td class="left"><?php echo $column_date_added; ?></td>
            <td class="left"><?php echo $column_customer; ?></td>
            <td class="left"><?php echo $column_city; ?></td>
            <td class="left"><?php echo $column_email; ?></td>
            <td class="left"><?php echo $column_phone; ?></td>
            <td class="left"><?php echo $column_status; ?></td>
            <td class="right"><?php echo $column_action; ?></td>
          </tr>
        </thead>
        <tbody>
          <?php if ($invoices) { ?>
          <?php foreach ($invoices as $invoice) { ?>
          <tr>
            <td class="text-left"><?php echo $invoice['invoice_id']; ?></td>
						<td class="text-left"><?php echo $invoice['date_added']; ?></td>
            <td class="text-left"><?php echo $invoice['customer']; ?></td>
            <td class="text-left"><?php echo $invoice['city']; ?></td>
            <td class="text-left"><?php echo $invoice['email']; ?></td>
            <td class="text-left"><?php echo $invoice['telephone']; ?></td>
            <td class="text-left"><?php echo $invoice['status']; ?></td>
            <td class="text-right"><?php foreach ($invoice['action'] as $action) { ?>
							<a href="<?php echo $action['href']; ?>" class="btn btn-info"><?php echo $action['icon']; ?> <?php echo $action['text']; ?></a>
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
      <div class="pagination"><?php echo $pagination; ?></div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
function filter() {
	url = 'index.php?route=report/sale_invoice&token=<?php echo $token; ?>';
	
	var filter_date_start = $('input[name=\'filter_date_start\']').attr('value');
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').attr('value');
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
	
	var filter_invoice_status_id = $('select[name=\'filter_invoice_status_id\']').attr('value');
	
	if (filter_invoice_status_id) {
		url += '&filter_invoice_status_id=' + encodeURIComponent(filter_invoice_status_id);
	}	

	location = url;
}
//--></script> 

<?php echo $footer; ?>