<?php echo $header; ?>
<?php include DIR_TEMPLATE . 'common/template-header.tpl'; ?>
<div class="panel panel-default">
  <div class="panel-heading clearfix">
    <div class="h2"><i class="fas fa-user"></i> <?php echo $heading_title; ?></div>
  </div>
  <div class="panel-body">
    <div class="well" id="filter">
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
					<select name="filter_status_id" title="<?php echo $entry_status; ?>" class="form-control">
						<option value="0"><?php echo $text_all_status; ?></option>
						<?php foreach ($statuses as $status) { ?>
						<?php if ($status['status_id'] == $filter_status_id) { ?>
						<option value="<?php echo $status['status_id']; ?>" selected=""><?php echo $status['name']; ?></option>
						<?php } else { ?>
						<option value="<?php echo $status['status_id']; ?>"><?php echo $status['name']; ?></option>
						<?php } ?>
						<?php } ?>
					</select>
				</div>
        <div class="col-sm-4 text-right">
          <a href="<?php echo $print_list; ?>" target="_blank" class="btn btn-success"><i class="fas fa-print"></i> <?php echo $button_print; ?></a>
          <button type="button" id="clear" class="btn btn-default"><i class="fas fa-eraser"></i> <?php echo $button_clear; ?></button>
					<button type="button" onclick="filter();" id="filter" class="btn btn-info"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
				</div>
      </div>
    </div>
    <div class="table-responsive">
      <table class="table table-bordered table-striped">
        <thead>
          <tr>
            <th class="text-left"><?php echo $column_date_added; ?></th>
            <th class="text-left"><?php echo $column_customer; ?></th>
            <th class="text-left d-none d-sm-table-cell"><?php echo $column_product; ?></th>
            <th class="text-left d-none d-sm-table-cell"><?php echo $column_email; ?></th>
            <th class="text-left d-none d-sm-table-cell"><?php echo $column_phone; ?></th>
            <th class="text-right"><span class="hidden-xs"><?php echo $column_action; ?></span></th>
          </tr>
        </thead>
        <tbody>
          <?php if ($customers): ?>
            <?php foreach ($customers as $customer): ?>
              <tr>
                <td class="text-left"><?php echo $customer['date_added'] ?></td>
                <td class="text-left"><?php echo $customer['customer'] ?></td>
                <td class="text-left d-none d-sm-table-cell"><?php echo $customer['product'] ?></td>
                <td class="text-left d-none d-sm-table-cell"><?php echo $customer['email'] ?></td>
                <td class="text-left d-none d-sm-table-cell"><?php echo $customer['telephone'] ?></td>
                <td class="text-right"><a href="<?php echo $customer['href'] ?>" class="btn btn-info">
                  <i class="fas fa-eye"></i><span class="d-none d-sm-inline"> <?php echo $customer['text'] ?></span>
                </a></td>
              </tr>
            <?php endforeach ?>
          <?php else: ?>
            <tr>
              <td class="text-center" colspan="6"><?php echo $text_no_results ?></td>
            </tr>
          <?php endif ?>
        </tbody>
      </table>
    </div>
    <div class="pagination"><?php echo str_replace('....','',$pagination); ?></div>
  </div>
  </div>
</div>
<script type="text/javascript">
function filter() {
	url = 'index.php?route=report/customer_support&token=<?php echo $token; ?>';
	
	var filter_date_start = $('input[name="filter_date_start"]').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name="filter_date_end"]').val();
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}

  var filter_status_id = $('select[name="filter_status_id"] option:selected').val();

  if (filter_status_id != 0) {
    url += '&filter_status_id=' + encodeURIComponent(filter_status_id);
  }
	
	location = url;
} 
</script>
<script>
$('#clear').click(function(){
  $('.date').val('');
  $("select[name='filter_status_id']").prop("selectedIndex", 0);
});
</script>
<?php echo $footer; ?>