<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<?php if ($error_install) { ?>
<div class="alert alert-danger"><?php echo $error_install; ?></div>
<?php } ?>
<?php if ($error_image) { ?>
<div class="alert alert-danger"><?php echo $error_image; ?></div>
<?php } ?>
<?php if ($error_image_cache) { ?>
<div class="alert alert-danger"><?php echo $error_image_cache; ?></div>
<?php } ?>
<?php if ($error_cache) { ?>
<div class="alert alert-danger"><?php echo $error_cache; ?></div>
<?php } ?>
<?php if ($error_download) { ?>
<div class="alert alert-danger"><?php echo $error_download; ?></div>
<?php } ?>
<?php if ($error_logs) { ?>
<div class="alert alert-danger"><?php echo $error_logs; ?></div>
<?php } ?>
<noscript>
    <div class="alert alert-danger"><?php echo $error_javascript; ?></div>
</noscript>
<div class="row">
	<div class="col-sm-6">
		<div class="panel panel-default">
			<div class="panel-heading clearfix">
				<div class="h2"><i class="fa fa-home"></i> <?php echo $text_overview; ?></div>
			</div>
			<div class="panel-body">
				<table class="table table-bordered">
					<tr>
						<td><?php echo $text_total_sale; ?></td>
						<td class="text-right"><span class="font-weight-bold"><?php echo $total_sale; ?></span></td>
					</tr>
					<tr>
						<td><?php echo $text_total_sale_year; ?></td>
						<td class="text-right"><span class="font-weight-bold"><?php echo $total_sale_year; ?></span></td>
					</tr>
					<tr>
						<td><?php echo $text_total_order; ?></td>
						<td class="text-right"><span class="font-weight-bold"><?php echo $total_order; ?></span></td>
					</tr>
					<tr>
						<td><?php echo $text_total_customer; ?></td>
						<td class="text-right"><span class="font-weight-bold"><?php echo $total_customer; ?></span></td>
					</tr>
				</table>
			</div>
		</div>
	</div>
	<div class="col-sm-6">
		<div class="panel panel-default">
			<div class="panel-heading clearfix">
				<div class="h2"><i class="fa fa-chart-bar"></i> <?php echo $text_statistics; ?></div>
			</div>
			<div class="panel-body">
				<ul class="nav nav-tabs" id="tabs-chart" title="<?php echo $entry_range; ?>">
					<li class="nav-item"><a class="nav-link" href="#day" data-toggle="tab"><?php echo $text_day; ?></a></li>
					<li class="nav-item"><a class="nav-link" href="#week" data-toggle="tab"><?php echo $text_week; ?></a></li>
					<li class="nav-item"><a class="nav-link" href="#month" data-toggle="tab"><?php echo $text_month; ?></a></li>
					<li class="nav-item"><a class="nav-link" href="#year" data-toggle="tab"><?php echo $text_year; ?></a></li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane" id="day"><canvas class="day" width="800" height="250"><canvas/></div>
					<div class="tab-pane" id="week"><canvas class="week" width="800" height="250"><canvas/></div>
					<div class="tab-pane" id="month"><canvas class="month" width="800" height="250"><canvas/></div>
					<div class="tab-pane" id="year"><canvas class="year" width="800" height="250"><canvas/></div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
<div class="col-sm-6"><div class="panel panel-default">
	<div class="panel-heading clearfix">
		<div class="h2"><i class="fa fa-edit"></i> <?php echo $text_latest_10_quotes; ?></div>
	</div>
	<div class="panel-body">
		<table class="table table-bordered table-striped table-hover">
			<thead>
				<tr>
					<th class="text-right hidden-xs"><?php echo $column_quote; ?></th>
					<th><?php echo $column_customer; ?></th>
					<th class="hidden-xs"><?php echo $column_status; ?></th>
					<th class="hidden-xs"><?php echo $column_date_added; ?></th>
					<th class="text-right hidden-xs"><?php echo $column_total; ?></th>
					<th class="text-right"><span class="hidden-xs"><?php echo $column_action; ?></span></th>
				</tr>
			</thead>
			<tbody data-link="row" class="rowlink">
				<?php if ($quotes) { ?>
				<?php foreach ($quotes as $quote) { ?>
				<tr>
					<td class="text-right hidden-xs"><?php echo $quote['quote_id']; ?></td>
					<td><?php echo $quote['customer']; ?></td>
					<td class="hidden-xs text-<?php echo strtolower($quote['status']); ?>"><?php echo $quote['status']; ?></td>
					<td class="hidden-xs"><?php echo $quote['date_added']; ?></td>
					<td class="text-right hidden-xs"><span class="font-weight-bold"><?php echo $quote['total']; ?></span></td>
					<td class="text-right"><?php foreach ($quote['action'] as $action) { ?>
						<span class="bracket"><a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a></span>
					<?php } ?></td>
				</tr>
				<?php } ?>
				<?php } else { ?>
				<tr>
					<td class="text-center" colspan="6"><?php echo $text_no_results; ?></td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
</div></div>
<div class="col-sm-6"><div class="panel panel-default">
	<div class="panel-heading clearfix">
		<div class="h2"><i class="fa fa-shopping-cart"></i> <?php echo $text_latest_10_orders; ?></div>
	</div>
	<div class="panel-body">
		<table class="table table-bordered table-striped table-hover">
			<thead>
				<tr>
					<th class="text-right hidden-xs"><?php echo $column_order; ?></th>
					<th><?php echo $column_customer; ?></th>
					<th class="hidden-xs"><?php echo $column_status; ?></th>
					<th class="hidden-xs"><?php echo $column_date_added; ?></th>
					<th class="text-right hidden-xs"><?php echo $column_total; ?></th>
					<th class="text-right"><span class="hidden-xs"><?php echo $column_action; ?></span></th>
				</tr>
			</thead>
			<tbody data-link="row" class="rowlink">
				<?php if ($invoices) { ?>
				<?php foreach ($invoices as $order) { ?>
				<tr>
					<td class="text-right hidden-xs"><?php echo $order['invoice_id']; ?></td>
					<td><?php echo $order['customer']; ?></td>
					<td class="hidden-xs text-<?php echo strtolower($order['status']); ?>"><?php echo $order['status']; ?></td>
					<td class="hidden-xs"><?php echo $order['date_added']; ?></td>
					<td class="text-right hidden-xs"><span class="font-weight-bold"><?php echo $order['total']; ?></span></td>
					<td class="text-right"><?php foreach ($order['action'] as $action) { ?>
						<span class="bracket"><a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a></span>
					<?php } ?></td>
				</tr>
				<?php } ?>
				<?php } else { ?>
				<tr>
					<td class="text-center" colspan="6"><?php echo $text_no_results; ?></td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
</div></div>
</div>
<script src="view/javascript/chart/chart.min.js"></script>
<script>
$('#tabs-chart a[data-toggle="tab"]').on('shown.bs.tab',function(e){
	var $this=$(this);
	var range=$this.attr('href').substr(1);
	$.ajax({
		type:'get',
		url:'index.php?route=common/home/chart&token=<?php echo $token; ?>&range='+range,
		dataType:'json',
		success:function(json){
			//Labels
			var labels = [];
			$.each(json.xaxis, function(index, value){
				labels.push(value[1]);
			});
			// Datos Clientes
			var customerData = [];
			$.each(json.customer['data'], function(index, value){
				customerData.push(value[1]);
			});

			// Datos Pedidos
			var orderData = [];
			$.each(json.order['data'], function(index, value){
				orderData.push(value[1]);
			});

			// crear el grafico
			var ctx = $('.'+range);
			var myChart = new Chart(ctx, {
				type:'line',
				data: {
					labels: labels,
					datasets: [{
						label: json.customer['label'],
						data: customerData,
						backgroundColor: "rgba(248, 148, 6,0.2)",
						borderColor: "#f89406"
					},
					{
						label: json.order['label'],
						data: orderData,
						backgroundColor: "rgba(62, 149, 205,0.2)",
						borderColor: "#3e95cd"
					}]
				}
			});
		}
	});
});
$('#tabs-chart a:first').tab('show');
</script>
<?php echo $footer; ?>