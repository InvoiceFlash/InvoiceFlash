<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="panel panel-default">
	<div class="panel-heading clearfix">
		<div class="pull-left h2"><i class="hidden-xs fa fa-shopping-cart"></i> <?php echo $heading_title; ?></div>
		<div class="pull-right">
			<button onClick="validate();" class="btn btn-success btn-spacer"><i class="fa fa-print"></i><span class="hidden-xs"> <?php echo $button_remittance; ?></span></button>
			<a onclick="generate();" class="btn btn-primary"><?php echo $button_generate; ?></a>
			<button type="submit" form="form" formaction="<?php echo $delete; ?>" id="btn-delete" class="btn btn-danger"><i class="fa fa-trash "></i><span class="hidden-xs"> <?php echo $button_delete; ?></span></button>
	    </div>
    </div>
    <div class="panel-body">
      <form class="foe" action="<?php echo $invoice; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="table table-bordered table-striped table-hover">
          <thead>
            <tr>
              <th width="40" class="text-center"><input type="checkbox" data-toggle="selected"></th>
              <th class="text-right"><?php if ($sort == 'o.order_id') { ?>
                <a href="<?php echo $sort_order; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_order_id; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_order; ?>"><?php echo $column_order_id; ?></a>
                <?php } ?></th>
              <th class="text-right"><?php if ($sort == 'o.total') { ?>
                <a href="<?php echo $sort_total; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_total; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_total; ?>"><?php echo $column_total; ?></a>
                <?php } ?></th>
              <th class="text-left"><?php if ($sort == 'o.date_added') { ?>
                <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_added; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>
                <?php } ?></th>
              <th class="text-right"><?php echo $column_action; ?></th>
            </tr>
          </thead>
          <tbody data-link="row" class="rowlink">
           <tr id="filter" class="info">
              <td></td>
              <td class="text-right"><input type="text" class="form-control" name="filter_order_id" value="<?php echo $filter_order_id; ?>"></td>
              <td class="text-right"><input type="text" class="form-control" name="filter_total" value="<?php echo $filter_total; ?>"></td>
              <td class="hidden-xs"><div class="input-group">
							<input type="text" name="filter_date_added" class="form-control date"/>
							<div class="input-group-append">
							<div class="input-group-text"><i class="fas fa-calendar"></i></div>
							</div>
						</div></td>
              <td class="text-right"><button type="button" onclick="filter();" class="btn btn-info"><?php echo $button_filter; ?></a></td>
            </tr>
            <?php if ($orders) { ?>
            <?php foreach ($orders as $order) { ?>
            <tr>
               <td class="rowlink-skip text-center"><?php if ($order['selected']) { ?>
					<input type="checkbox" name="selected[]" value="<?php echo $order['order_id']; ?>" checked="">
					<?php } else { ?>
					<input type="checkbox" name="selected[]" value="<?php echo $order['order_id']; ?>">
					<?php } ?></td>
              <td class="text-right"><?php echo $order['order_id']; ?></td>
              <td class="text-right"><?php echo $order['total']; ?></td>
              <td class="text-left"><?php echo $order['date_added']; ?></td>
              <td class="text-right"><?php foreach ($order['action'] as $action) { ?>
                [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
                <?php } ?></td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="text-center" colspan="5"><?php echo $text_no_results; ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </form>
      <div class="pagination"><?php echo $pagination; ?></div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
function filter() {
	url = 'index.php?route=sale/remittances&token=<?php echo $token; ?>';
	
	var filter_order_id = $('input[name=\'filter_order_id\']').attr('value');
	
	if (filter_order_id) {
		url += '&filter_order_id=' + encodeURIComponent(filter_order_id);
	}
	
	var filter_order_status_id = $('select[name=\'filter_order_status_id\']').attr('value');
	
	if (filter_order_status_id != '*') {
		url += '&filter_order_status_id=' + encodeURIComponent(filter_order_status_id);
	}	

	var filter_total = $('input[name=\'filter_total\']').attr('value');

	if (filter_total) {
		url += '&filter_total=' + encodeURIComponent(filter_total);
	}	
	
	var filter_date_added = $('input[name=\'filter_date_added\']').attr('value');
	
	if (filter_date_added) {
		url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
	}
	
	var filter_date_modified = $('input[name=\'filter_date_modified\']').attr('value');
	
	if (filter_date_modified) {
		url += '&filter_date_modified=' + encodeURIComponent(filter_date_modified);
	}
				
	location = url;
}
function generate() {
	var nCon = 0 ;
	var id = 0 ;
	$("form input:checkbox:checked:enabled").each(
		function() { 
			nCon = nCon + 1 ;
			id = $(this).attr('value');
		} 
	);
	if (nCon > 1) {
		alert ('Solo puedes seleccionar una remesa') ;
	}
	if (nCon == 0) {
		alert ('Debes seleccionar alguna remesa') ;
	}
	if (nCon == 1) {
		url = 'index.php?route=sale/remittances/generateC19&token=<?php echo $token; ?>&remittance='+id ;
		location = url;
	}
}
//--></script>  
<script type="text/javascript"><!--
$('#form input').keydown(function(e) {
	if (e.keyCode == 13) {
		filter();
	}
});
$("#btn-delete").click(function(){
    if (!$('input[type="checkbox"]').is(':checked')) {
		alert('Select almost a remittance to delete');
	}
});
//--></script>
<script>
function validate() {
	if (!$('input[type="checkbox"]').is(':checked')) {
		alert('Seleccione una remensa');
	} else {
		$('#form').attr('target', '_blank');
		$('#form').submit();
	}
}
</script>
<?php echo $footer; ?>