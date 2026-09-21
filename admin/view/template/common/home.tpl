<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<style>
/* Solo tipografia: fuente Inter + escala de tamanos coherente (acotado a esta pagina) */
#home-type {
	font-family: "Inter", "Segoe UI", system-ui, -apple-system, Roboto, "Helvetica Neue", Arial, sans-serif;
	font-size: 0.9375rem;
	line-height: 1.5;
	letter-spacing: -0.005em;
	-webkit-font-smoothing: antialiased;
}
#home-type h5, #home-type .h2 { font-size: 1rem; font-weight: 600; line-height: 1.3; letter-spacing: -0.01em; }
#home-type .panel-heading { font-weight: 600; }
#home-type .table { font-size: 0.875rem; font-variant-numeric: tabular-nums; }
#home-type .table th { font-size: 0.75rem; font-weight: 600; letter-spacing: 0.02em; }
#home-type .font-weight-bold { font-weight: 600 !important; }
#home-type .btn, #home-type .form-control, #home-type .input-group-text { font-family: inherit; font-size: 0.875rem; }
#home-type .btn { font-weight: 500; }
#home-type h5.buton { font-size: 0.875rem; font-weight: 500; }
#home-type .nav-tabs .nav-link { font-size: 0.875rem; font-weight: 500; }
</style>
<div id="home-type">
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
<div class="row mb-3">
	<div class="col-sm-12">
		<div class="panel panel-default" id="search">
			<div class="panel-heading clearfix">
				<h5 class="pull-left"><i class="fa fa-search"></i> <?php echo $text_search; ?></h5>
				<div class="pull-right">
					<button type="button" id="btn-view-kanban" class="btn btn-default btn-sm" data-toggle="tooltip" title="<?php echo $text_view_kanban; ?>"><i class="fa fa-columns"></i></button>
						<button type="button" id="btn-view-dashboard" class="btn btn-default btn-sm active" data-toggle="tooltip" title="<?php echo $text_view_dashboard; ?>"><i class="fa fa-chart-bar"></i></button>
						<a href="<?php echo $this->url->link('common/calendar', 'token=' . $this->session->data['token'], 'SSL'); ?>" id="btn-view-calendar" class="btn btn-default btn-sm" data-toggle="tooltip" title="<?php echo $text_view_calendar; ?>"><i class="fa fa-calendar"></i></a>
					<button type="button" id="btn-view-claude-chat" class="btn btn-default btn-sm" data-toggle="tooltip" title="<?php echo $text_view_claude_chat; ?>"><i class="fa fa-robot"></i></button>
				</div>
			</div>
			<div class="panel-body">
			<div class="d-flex flex-column flex-sm-row gap-2">
				<div class="flex-fill">
					<div class="input-group">
						<span class="input-group-prepend"><span class="input-group-text"><?php echo $text_search_customer; ?></span></span>
						<input type="text" id="search-customer" class="form-control">
						<div class="input-group-append">
							<button class="btn btn-info" id="button-search-customer"><?php echo $button_search; ?></button>
						</div>
					</div>
				</div>
				<div class="flex-fill" style="margin-left: 5cm;">
					<div class="input-group">
						<span class="input-group-prepend"><span class="input-group-text"><?php echo $text_search_product; ?></span></span>
						<input type="text" id="search-product" class="form-control">
						<div class="input-group-append">
							<button class="btn btn-info" id="button-search-product"><?php echo $button_search; ?></button>
						</div>
					</div>
				</div>
			</div>
			</div>
		</div>
	</div>
</div>
<div id="dashboard-view">
<?php if ($view['quick_action']) { ?>
<div class="row mb-3">
<div class="col-sm-12">
<div class="panel panel-default" id="actions">
	<div class="panel-heading clearfix"><h5><?php echo $text_actions; ?></h5></div>
	<div class="panel-body">
		<div class="card-group d-flex justify-content-center">
			<div class="card">
				<a href="<?php echo $view_inbox; ?>"><h5 class="buton"><i class="fas fa-inbox"></i> <span class="hidden-xs"><?php echo $text_view_inbox; ?></span></h5></a>
			</div>
			<div class="card">
				<a href="<?php echo $add_customer; ?>"><h5 class="buton"><i class="fas fa-user"></i> <span class="hidden-xs"><?php echo $text_add_customer; ?></span></h5></a>
			</div>
			<div class="card">
				<a href="<?php echo $new_invoice; ?>"><h5 class="buton"><i class="far fa-file-alt"></i> <span class="hidden-xs"><?php echo $text_new_invoice; ?></span></h5></a>
			</div>
			<div class="card">
				<a href="<?php echo $add_product; ?>"><h5 class="buton"><i class="fas fa-box-open"></i> <span class="hidden-xs"><?php echo $text_add_product; ?></span></h5></a>
			</div>
			<?php if ($view['pending_invoices']) { ?>
			<div class="card">
				<a href="<?php echo $pending_invoices; ?>"><h5 class="buton"><i class="fas fa-envelope-open-text"></i> <span class="hidden-xs"><?php echo $text_pending_invoices; ?></span></h5></a>
			</div>
			<?php } ?>
		</div>
	</div>
</div>
</div>
</div>
<?php } ?>
<?php if ($view['over']) { ?>
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
					<li class="nav-item"><a class="nav-link" href="#day" data-bs-toggle="tab"><?php echo $text_day; ?></a></li>
					<li class="nav-item"><a class="nav-link" href="#week" data-bs-toggle="tab"><?php echo $text_week; ?></a></li>
					<li class="nav-item"><a class="nav-link" href="#month" data-bs-toggle="tab"><?php echo $text_month; ?></a></li>
					<li class="nav-item"><a class="nav-link" href="#year" data-bs-toggle="tab"><?php echo $text_year; ?></a></li>
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
<?php } ?>
<div class="row">
<?php if($view['last_quotes']) { ?>
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
					<td><?php echo $quote['company']; ?></td>
					<td class="hidden-xs text-<?php echo strtolower($quote['status']); ?>"><?php echo $quote['status']; ?></td>
					<td class="hidden-xs"><?php echo $quote['date_added']; ?></td>
					<td class="text-right hidden-xs"><span class="font-weight-bold"><?php echo $quote['total']; ?></span></td>
					<td class="text-right"><?php foreach ($quote['action'] as $action) { ?>
						<a href="<?php echo $action['href']; ?>" class="btn btn-info"><i class="fas fa-eye"></i> <span class="hidden-xs"><?php echo $action['text']; ?></span></a>
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
<?php } ?>
<?php if ($view['last_invoice']) { ?>
<div class="col-sm-6"><div class="panel panel-default">
	<div class="panel-heading clearfix">
		<div class="h2"><i class="far fa-file-alt"></i> <?php echo $text_latest_10_orders; ?></div>
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
				<?php foreach ($invoices as $invoice) { ?>
				<tr>
					<td class="text-right hidden-xs"><?php echo $invoice['invoice_id']; ?></td>
					<td><?php echo $invoice['company']; ?></td>
					<td class="hidden-xs text-<?php echo strtolower($invoice['status']); ?>" style="background-color:rgb(<?php echo $invoice['color']; ?>)"><?php echo $invoice['status']; ?></td>
					<td class="hidden-xs"><?php echo $invoice['date_added']; ?></td>
					<td class="text-right hidden-xs"><span class="font-weight-bold"><?php echo $invoice['total']; ?></span></td>
					<td class="text-right"><?php foreach ($invoice['action'] as $action) { ?>
						<a href="<?php echo $action['href']; ?>" class="btn btn-info"><i class="fas fa-eye"></i> <span class="hidden-xs"><?php echo $action['text']; ?></span></a>
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
<?php } ?>
</div>
</div>
<div id="kanban-view" style="display:none;">
	<div class="row mb-3">
		<div class="col-sm-12">
			<div class="panel panel-default">
				<div class="panel-heading clearfix"><h5><i class="fa fa-columns"></i> <?php echo $text_kanban; ?></h5></div>
				<div class="panel-body d-flex" style="gap:16px; align-items:flex-start;">
				<div style="width:260px; flex:none; border:1px solid #e5e2da; border-radius:6px;">
					<div style="display:flex; justify-content:space-between; align-items:center; padding:8px 10px; border-bottom:1px solid #e5e2da; font-weight:600;">
						<span><i class="fa fa-briefcase"></i> <?php echo $text_kanban_projects; ?></span>
						<button type="button" class="btn btn-info btn-sm" id="kanban-project-new"><i class="fa fa-plus"></i></button>
					</div>
					<div id="kanban-projects" style="max-height:560px; overflow-y:auto;"></div>
				</div>
				<div style="flex:1; min-width:0;">
					<div id="kanban-add-row" class="input-group mb-3" style="max-width:500px;">
						<input type="text" id="kanban-new-title" class="form-control" placeholder="<?php echo $text_kanban_new_placeholder; ?>">
						<div class="input-group-append"><button type="button" class="btn btn-info" id="kanban-add"><?php echo $text_kanban_add; ?></button></div>
					</div>
					<div class="d-flex" style="gap:12px; align-items:flex-start;">
						<?php
						$kanban_icons = array(
							'pending'     => '<circle cx="12" cy="12" r="9"/>',
							'in_progress' => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
							'done'        => '<circle cx="12" cy="12" r="9"/><path d="M8 12.5l3 3 5-6"/>'
						);
						foreach ($kanban_columns as $status => $label) { ?>
						<div style="flex:1; min-width:0; background:#f4f5f7; border-radius:6px; padding:10px;">
							<div style="font-weight:600; margin-bottom:8px;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:-3px; margin-right:6px;"><?php echo $kanban_icons[$status]; ?></svg><?php echo $label; ?> <span class="badge bg-secondary kanban-count" data-status="<?php echo $status; ?>">0</span></div>
							<div class="kanban-col" data-status="<?php echo $status; ?>" style="min-height:120px;"></div>
						</div>
						<?php } ?>
					</div>
				</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="kanban-project-modal" tabindex="-1"><div class="modal-dialog"><div class="modal-content">
	<div class="modal-header"><h5 class="modal-title"><i class="fa fa-briefcase"></i> <?php echo $text_kanban_project_new; ?></h5><button type="button" class="btn btn-default btn-sm" data-bs-dismiss="modal">&times;</button></div>
	<div class="modal-body">
		<div class="mb-3"><label><?php echo $text_kanban_project_name; ?></label><input type="text" id="kanban-project-name" class="form-control"></div>
		<div class="d-flex" style="gap:12px;">
			<div style="flex:1;"><label><?php echo $text_kanban_project_start; ?></label><input type="date" id="kanban-project-start" class="form-control"></div>
			<div style="flex:1;"><label><?php echo $text_kanban_project_end; ?></label><input type="date" id="kanban-project-end" class="form-control"></div>
		</div>
	</div>
	<div class="modal-footer"><button type="button" class="btn btn-default" data-bs-dismiss="modal"><?php echo $text_kanban_close; ?></button><button type="button" class="btn btn-info" id="kanban-project-save"><?php echo $text_kanban_save; ?></button></div>
</div></div></div>
<div class="modal fade" id="kanban-share-modal" tabindex="-1"><div class="modal-dialog modal-sm"><div class="modal-content">
	<div class="modal-header"><h5 class="modal-title"><i class="fa fa-share-alt"></i> <?php echo $text_kanban_share; ?></h5><button type="button" class="btn btn-default btn-sm" data-bs-dismiss="modal">&times;</button></div>
	<div class="modal-body" id="kanban-share-users" style="max-height:320px; overflow-y:auto;"></div>
	<div class="modal-footer"><button type="button" class="btn btn-default" data-bs-dismiss="modal"><?php echo $text_kanban_close; ?></button><button type="button" class="btn btn-info" id="kanban-share-save"><?php echo $text_kanban_save; ?></button></div>
</div></div></div>
<div class="modal fade" id="kanban-choose-modal" tabindex="-1"><div class="modal-dialog modal-sm"><div class="modal-content">
	<div class="modal-header"><h5 class="modal-title"><?php echo $text_kanban_choose_type; ?></h5><button type="button" class="btn btn-default btn-sm" data-bs-dismiss="modal">&times;</button></div>
	<div class="modal-body text-center">
		<button type="button" class="btn btn-default kanban-choose" data-kind="text" style="width:30%;"><i class="fa fa-file-alt fa-2x"></i><br><?php echo $text_kanban_text; ?></button>
		<button type="button" class="btn btn-default kanban-choose" data-kind="photo" style="width:30%;"><i class="fa fa-image fa-2x"></i><br><?php echo $text_kanban_photo; ?></button>
		<button type="button" class="btn btn-default kanban-choose" data-kind="document" style="width:30%;"><i class="fa fa-file fa-2x"></i><br><?php echo $text_kanban_document; ?></button>
	</div>
</div></div></div>
<div class="modal fade" id="kanban-text-modal" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content">
	<div class="modal-header"><h5 class="modal-title"><i class="fa fa-file-alt"></i> <?php echo $text_kanban_text; ?></h5><button type="button" class="btn btn-default btn-sm" data-bs-dismiss="modal">&times;</button></div>
	<div class="modal-body"><textarea id="kanban-text-area" class="form-control" rows="14" placeholder="<?php echo $text_kanban_text_placeholder; ?>"></textarea></div>
	<div class="modal-footer"><button type="button" class="btn btn-danger me-auto" id="kanban-text-delete" style="margin-right:auto;"><i class="fa fa-trash"></i> <?php echo $text_kanban_delete_file; ?></button><button type="button" class="btn btn-default" data-bs-dismiss="modal"><?php echo $text_kanban_close; ?></button><button type="button" class="btn btn-info" id="kanban-text-save"><?php echo $text_kanban_save; ?></button></div>
</div></div></div>
<div class="modal fade" id="kanban-files-modal" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content">
	<div class="modal-header"><h5 class="modal-title" id="kanban-files-title"></h5><button type="button" class="btn btn-default btn-sm" data-bs-dismiss="modal">&times;</button></div>
	<div class="modal-body">
		<div class="input-group mb-3">
			<input type="file" id="kanban-files-input" class="form-control" multiple>
			<div class="input-group-append"><button type="button" class="btn btn-info" id="kanban-files-upload"><i class="fa fa-upload"></i> <?php echo $text_kanban_upload; ?></button></div>
		</div>
		<div id="kanban-files-grid" class="d-flex flex-wrap" style="gap:12px;"></div>
	</div>
</div></div></div>
<div id="claude-chat-view" style="display:none;">
	<div class="row mb-3">
		<div class="col-sm-12">
			<div class="panel panel-default" id="claude-chat">
				<div class="panel-heading clearfix"><h5><i class="fa fa-robot"></i> <?php echo $text_claude_chat; ?> (<?php echo $ai_chat_model; ?>)</h5></div>
				<div class="panel-body">
					<div id="claude-chat-messages" style="height:400px; overflow-y:auto; background:#faf9f7; border:1px solid #e5e2da; border-radius:6px; padding:15px; margin-bottom:15px;">
						<div class="claude-chat-message claude-chat-message-bot" style="background:#fff; border:1px solid #e5e2da; border-radius:8px; padding:10px 14px; max-width:80%; margin-bottom:10px;">
							<?php echo $text_claude_chat_placeholder; ?>
						</div>
					</div>
					<div class="input-group">
						<input type="text" id="claude-chat-input" class="form-control" placeholder="<?php echo $text_claude_chat_input_placeholder; ?>">
						<div class="input-group-append">
							<button type="button" class="btn btn-info" id="claude-chat-send"><i class="fa fa-paper-plane"></i></button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script src="view/javascript/chart/Chart.bundle.min.js"></script>
<script>
$('#tabs-chart a[data-bs-toggle="tab"]').on('shown.bs.tab',function(e){
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
			var invoiceData = [];
			$.each(json.invoice['data'], function(index, value){
				invoiceData.push(value[1]);
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
						label: json.invoice['label'],
						data: invoiceData,
						backgroundColor: "rgba(62, 149, 205,0.2)",
						borderColor: "#3e95cd"
					}]
				}
			});
		}
	});
});
bootstrap.Tab.getOrCreateInstance($('#tabs-chart a:first')[0]).show();
</script>
<script>
var token = '<?php echo $token; ?>';
$("#button-search-customer").click(function() {
	var url = 'index.php?route=sale/customer&token='+token+'&filter_company='+$('#search-customer').val();

	$(location)	.attr('href', url);
});

$('#button-search-product').click(function(){
	var url = "index.php?route=catalog/product&token="+token+"&filter_name="+$('#search-product').val();
	$(location).attr('href', url);
});

function homeShowView(view) {
	$('#dashboard-view, #claude-chat-view, #kanban-view').hide();
	$('#' + view + '-view').show();
	$('#btn-view-dashboard, #btn-view-claude-chat, #btn-view-kanban').removeClass('active');
	$('#btn-view-' + view).addClass('active');
}

$('#btn-view-dashboard').on('click', function() { homeShowView('dashboard'); });
$('#btn-view-claude-chat').on('click', function() { homeShowView('claude-chat'); });
$('#btn-view-kanban').on('click', function() { homeShowView('kanban'); kanbanCall({action: 'list'}); });

var kanbanUrl = '<?php echo addslashes($kanban_url); ?>';
var kanbanDragId = null;

var kanbanProjectId = 0;
var kanbanProjects = [];
var kanbanEditingProject = 0;
var kanbanSharedTotal = 0;
var kanbanCanManage = true;

function kanbanCall(data) {
	data.project_id = kanbanProjectId;
	$.post(kanbanUrl, data, function(json) {
		if (json.projects) {
			kanbanProjects = json.projects;
			kanbanProjectId = json.project_id;
			kanbanSharedTotal = json.shared_total || 0;
			$('#kanban-add-row').toggle(kanbanProjectId != -1);
			kanbanRenderProjects();
		}
		if (json.cards) { kanbanRender(json.cards); }
	}, 'json');
}

function kanbanRenderProjects() {
	var $box = $('#kanban-projects').empty();
	if (kanbanSharedTotal > 0 || kanbanProjectId == -1) {
		var sharedActive = (kanbanProjectId == -1);
		$box.append($('<div class="kanban-project" style="display:flex; align-items:center; gap:6px; padding:8px 10px; border-bottom:1px solid #f0eee8; cursor:pointer;">')
			.css({background: sharedActive ? '#eef6fb' : '#fff', borderLeft: '4px solid ' + (sharedActive ? '#17a2b8' : '#ced4da')}).attr('data-id', -1)
			.append($('<i class="fa fa-share-alt">')).append($('<div style="flex:1;">').css('color', '#0d6efd').text(kanbanLabels.shared_with_me)));
	}
	$.each(kanbanProjects, function(i, p) {
		var active = (p.id == kanbanProjectId);
		var $row = $('<div class="kanban-project" style="display:flex; align-items:center; gap:6px; padding:8px 10px; border-bottom:1px solid #f0eee8; cursor:pointer;">')
			.css({background: active ? '#eef6fb' : '#fff', borderLeft: '4px solid ' + (active ? '#17a2b8' : '#ced4da')}).attr('data-id', p.id);
		var $info = $('<div style="flex:1; min-width:0;">').append($('<div>').css({wordBreak:'break-word', color:'#0d6efd'}).text(p.name));
		if (p.date_start || p.date_end) {
			$info.append($('<div style="font-size:11px; font-style:italic; color:#6c757d;">').text((p.date_start || '') + (p.date_end ? ' → ' + p.date_end : '')));
		}
		$row.append($info);
		$row.append($('<a href="#" class="kanban-project-edit text-success">').html('<i class="fa fa-pencil-alt"></i>'));
		$row.append($('<a href="#" class="kanban-project-del text-danger">').html('<i class="fa fa-trash"></i>'));
		$box.append($row);
	});
}

function kanbanOpenProjectModal(p) {
	kanbanEditingProject = p ? p.id : 0;
	$('#kanban-project-name').val(p ? p.name : '');
	var now = new Date();
	var today = now.getFullYear() + '-' + ('0' + (now.getMonth() + 1)).slice(-2) + '-' + ('0' + now.getDate()).slice(-2);
	$('#kanban-project-start').val(p ? p.date_start : today);
	$('#kanban-project-end').val(p ? p.date_end : '');
	bootstrap.Modal.getOrCreateInstance(document.getElementById('kanban-project-modal')).show();
}

function kanbanProjectById(id) {
	var found = null;
	$.each(kanbanProjects, function(i, p) { if (p.id == id) { found = p; } });
	return found;
}

$('#kanban-project-modal').on('shown.bs.modal', function() { $('#kanban-project-name').focus(); });
$('#kanban-project-new').on('click', function() { kanbanOpenProjectModal(null); });
$(document).on('click', '.kanban-project', function() { kanbanProjectId = $(this).data('id'); kanbanCall({action: 'list'}); });
$(document).on('click', '.kanban-project-edit', function(e) {
	e.preventDefault(); e.stopPropagation();
	kanbanOpenProjectModal(kanbanProjectById($(this).closest('.kanban-project').data('id')));
});
$(document).on('click', '.kanban-project-del', function(e) {
	e.preventDefault(); e.stopPropagation();
	if (confirm('<?php echo addslashes(html_entity_decode($text_kanban_project_delete_confirm, ENT_QUOTES, 'UTF-8')); ?>')) {
		kanbanCall({action: 'project_delete', id: $(this).closest('.kanban-project').data('id')});
	}
});
$('#kanban-project-save').on('click', function() {
	var name = $('#kanban-project-name').val().trim();
	if (!name) { return; }
	kanbanCall({action: 'project_save', id: kanbanEditingProject, name: name, date_start: $('#kanban-project-start').val(), date_end: $('#kanban-project-end').val()});
	bootstrap.Modal.getOrCreateInstance(document.getElementById('kanban-project-modal')).hide();
});

var kanbanCurrentId = 0;
var kanbanFilesType = 'photo';
var kanbanUploadUrl = '<?php echo addslashes($kanban_upload_url); ?>';
var kanbanLabels = {
	photo: '<?php echo addslashes(html_entity_decode($text_kanban_photos, ENT_QUOTES, 'UTF-8')); ?>',
	document: '<?php echo addslashes(html_entity_decode($text_kanban_documents, ENT_QUOTES, 'UTF-8')); ?>',
	none: '<?php echo addslashes(html_entity_decode($text_kanban_no_files, ENT_QUOTES, 'UTF-8')); ?>',
	del: '<?php echo addslashes(html_entity_decode($text_kanban_delete_file, ENT_QUOTES, 'UTF-8')); ?>',
	attach: '<?php echo addslashes(html_entity_decode($text_kanban_attach, ENT_QUOTES, 'UTF-8')); ?>',
	shared_with_me: '<?php echo addslashes(html_entity_decode($text_kanban_shared_with_me, ENT_QUOTES, 'UTF-8')); ?>',
	share: '<?php echo addslashes(html_entity_decode($text_kanban_share, ENT_QUOTES, 'UTF-8')); ?>',
	no_users: '<?php echo addslashes(html_entity_decode($text_kanban_share_no_users, ENT_QUOTES, 'UTF-8')); ?>',
	owner: '<?php echo addslashes(html_entity_decode($text_kanban_owner, ENT_QUOTES, 'UTF-8')); ?>',
	project: '<?php echo addslashes(html_entity_decode($text_kanban_project, ENT_QUOTES, 'UTF-8')); ?>',
	text: '<?php echo addslashes(html_entity_decode($text_kanban_text, ENT_QUOTES, 'UTF-8')); ?>'
};

function kanbanRender(cards) {
	$('.kanban-col').empty();
	var counts = {};
	$.each(cards, function(i, c) {
		counts[c.status] = (counts[c.status] || 0) + 1;
		var $card = $('<div class="kanban-card">').attr('data-id', c.id).attr('draggable', c.can_manage ? 'true' : 'false')
			.css({background:'#fff', border:'1px solid #e5e2da', borderRadius:'6px', padding:'8px 10px', marginBottom:'8px', cursor: c.can_manage ? 'grab' : 'default'});
		var $top = $('<div>').css({display:'flex', justifyContent:'space-between', gap:'8px'});
		$top.append($('<span>').css('word-break', 'break-word').text(c.title));
		if (c.can_manage) { $top.append($('<a href="#" class="kanban-del text-danger">').html('<i class="fa fa-times"></i>')); }
		if (c.owner) {
			$card.append($('<div style="font-size:11px; color:#6c757d; margin-bottom:2px; display:flex; justify-content:space-between; gap:8px;">')
				.append($('<span>').text(kanbanLabels.owner + ': ' + c.owner))
				.append($('<span>').css('text-align', 'right').text(kanbanLabels.project + ': ' + (c.project || ''))));
		}
		var $icons = $('<div>').css({marginTop:'6px', display:'flex', gap:'10px', alignItems:'center'});
		if (c.has_text) { $icons.append($('<a href="#" class="kanban-open-text">').attr('title', kanbanLabels.text).html('<i class="fa fa-file-alt"></i>')); }
		if (c.photos) { $icons.append($('<a href="#" class="kanban-open-files" data-type="photo">').html('<i class="fa fa-image"></i> ' + c.photos)); }
		if (c.docs) { $icons.append($('<a href="#" class="kanban-open-files" data-type="document">').html('<i class="fa fa-file"></i> ' + c.docs)); }
		var $tools = $('<span style="margin-left:auto; display:flex; gap:10px;">');
		if (c.is_owner) { $tools.append($('<a href="#" class="kanban-share text-muted">').attr('title', kanbanLabels.share).html('<i class="fa fa-share-alt' + (c.shares ? ' text-info' : '') + '"></i>')); }
		if (c.can_manage) { $tools.append($('<a href="#" class="kanban-attach text-muted">').attr('title', kanbanLabels.attach).html('<i class="fa fa-paperclip"></i>')); }
		$icons.append($tools);
		$card.append($top).append($icons);
		$('.kanban-col[data-status="' + c.status + '"]').append($card);
	});
	$('.kanban-count').each(function() { $(this).text(counts[$(this).data('status')] || 0); });
}

function kanbanModal(id) { return bootstrap.Modal.getOrCreateInstance(document.getElementById(id)); }
function kanbanCardId(el) { return $(el).closest('.kanban-card').data('id'); }

function kanbanOpenText() {
	$('#kanban-text-area').val('');
	$.post(kanbanUrl, {action: 'detail', id: kanbanCurrentId}, function(json) {
		var manage = !!json.can_manage;
		$('#kanban-text-area').val(json.text || '').prop('readonly', !manage);
		$('#kanban-text-save, #kanban-text-delete').toggle(manage);
	}, 'json');
	kanbanModal('kanban-choose-modal').hide();
	kanbanModal('kanban-text-modal').show();
}

function kanbanFileIcon(name) {
	var ext = name.split('.').pop().toLowerCase();
	var map = {pdf: 'fa-file-pdf', doc: 'fa-file-word', docx: 'fa-file-word', xls: 'fa-file-excel', xlsx: 'fa-file-excel', csv: 'fa-file-excel', txt: 'fa-file-alt'};
	return map[ext] || 'fa-file';
}

function kanbanRenderFiles(atts) {
	var $grid = $('#kanban-files-grid').empty();
	var n = 0;
	$.each(atts || [], function(i, a) {
		if (a.type !== kanbanFilesType) { return; }
		n++;
		var $item = $('<div>').css({width:'110px', textAlign:'center', position:'relative'});
		var $link = $('<a target="_blank">').attr('href', a.url).css({display:'block', textDecoration:'none'});
		if (a.type === 'photo') {
			$link.append($('<img>').attr('src', a.url).css({width:'100px', height:'100px', objectFit:'cover', borderRadius:'6px', border:'1px solid #e5e2da'}));
		} else {
			$link.append($('<i class="fa fa-3x">').addClass(kanbanFileIcon(a.name)).css({lineHeight:'100px'}));
		}
		$link.append($('<div>').css({fontSize:'12px', wordBreak:'break-all'}).text(a.name));
		$item.append($link);
		if (kanbanCanManage) { $item.append($('<a href="#" class="kanban-att-del text-danger" style="position:absolute; top:0; right:0;">').attr({'data-id': a.id, title: kanbanLabels.del}).html('<i class="fa fa-times-circle"></i>')); }
		$grid.append($item);
	});
	if (!n) { $grid.append($('<div class="text-muted">').text(kanbanLabels.none)); }
}

function kanbanOpenFiles(type) {
	kanbanFilesType = type;
	$('#kanban-files-title').text(kanbanLabels[type]);
	$('#kanban-files-input').val('').attr('accept', type === 'photo' ? 'image/*' : '.pdf,.doc,.docx,.xls,.xlsx,.txt,.csv');
	$('#kanban-files-grid').empty();
	$.post(kanbanUrl, {action: 'detail', id: kanbanCurrentId}, function(json) {
		kanbanCanManage = !!json.can_manage;
		$('#kanban-files-input').closest('.input-group').toggle(kanbanCanManage);
		kanbanRenderFiles(json.attachments);
	}, 'json');
	kanbanModal('kanban-choose-modal').hide();
	kanbanModal('kanban-files-modal').show();
}

$(document).on('click', '.kanban-attach', function(e) {
	e.preventDefault();
	kanbanCurrentId = kanbanCardId(this);
	kanbanModal('kanban-choose-modal').show();
});
$(document).on('click', '.kanban-choose', function() {
	var kind = $(this).data('kind');
	if (kind === 'text') { kanbanOpenText(); } else { kanbanOpenFiles(kind); }
});
$(document).on('click', '.kanban-open-text', function(e) { e.preventDefault(); kanbanCurrentId = kanbanCardId(this); kanbanOpenText(); });
$(document).on('click', '.kanban-open-files', function(e) { e.preventDefault(); kanbanCurrentId = kanbanCardId(this); kanbanOpenFiles($(this).data('type')); });

$(document).on('click', '.kanban-share', function(e) {
	e.preventDefault();
	kanbanCurrentId = kanbanCardId(this);
	$('#kanban-share-users').empty();
	$.post(kanbanUrl, {action: 'share_get', id: kanbanCurrentId}, function(json) {
		var $box = $('#kanban-share-users');
		if (!json.users || !json.users.length) { $box.append($('<div class="text-muted">').text(kanbanLabels.no_users)); }
		$.each(json.users || [], function(i, u) {
			var $l = $('<label style="display:block; cursor:pointer; margin-bottom:6px;">');
			$l.append($('<input type="checkbox" class="kanban-share-user" style="margin-right:8px;">').val(u.id).prop('checked', u.checked));
			$l.append(document.createTextNode(u.username));
			$box.append($l);
		});
	}, 'json');
	kanbanModal('kanban-share-modal').show();
});

$('#kanban-share-save').on('click', function() {
	var ids = $('.kanban-share-user:checked').map(function() { return $(this).val(); }).get();
	kanbanCall({action: 'share_save', id: kanbanCurrentId, users: ids});
	kanbanModal('kanban-share-modal').hide();
});

$('#kanban-text-delete').on('click', function() {
	$('#kanban-text-area').val('');
	$('#kanban-text-save').click();
});

$('#kanban-text-save').on('click', function() {
	$.post(kanbanUrl, {action: 'save_text', project_id: kanbanProjectId, id: kanbanCurrentId, text: $('#kanban-text-area').val()}, function(json) {
		if (json.cards) { kanbanRender(json.cards); }
		kanbanModal('kanban-text-modal').hide();
	}, 'json');
});

$('#kanban-files-upload').on('click', function() {
	var files = $('#kanban-files-input')[0].files;
	if (!files.length) { return; }
	var $btn = $(this).prop('disabled', true);
	var fd = new FormData();
	fd.append('id', kanbanCurrentId);
	fd.append('type', kanbanFilesType);

	var jobs = $.map(files, function(f) {
		var d = $.Deferred();
		if (kanbanFilesType === 'photo' && /^image\/(jpeg|png)$/.test(f.type) && f.size > 1024 * 1024) {
			var img = new Image();
			img.onload = function() {
				var max = 2000, r = Math.min(1, max / Math.max(img.width, img.height));
				var cv = document.createElement('canvas');
				cv.width = Math.round(img.width * r);
				cv.height = Math.round(img.height * r);
				cv.getContext('2d').drawImage(img, 0, 0, cv.width, cv.height);
				cv.toBlob(function(blob) {
					fd.append('file[]', blob, f.name.replace(/\.[^.]+$/, '') + '.jpg');
					d.resolve();
				}, 'image/jpeg', 0.85);
			};
			img.onerror = function() { fd.append('file[]', f); d.resolve(); };
			img.src = URL.createObjectURL(f);
		} else {
			fd.append('file[]', f);
			d.resolve();
		}
		return d.promise();
	});

	$.when.apply($, jobs).then(function() {
		$.ajax({url: kanbanUploadUrl, type: 'POST', data: fd, processData: false, contentType: false, dataType: 'text',
			success: function(text) {
				var json;
				try { json = JSON.parse(text.substring(text.indexOf('{'))); } catch (e) { alert('Error: ' + text.replace(/<[^>]*>/g, ' ').substring(0, 300)); return; }
				$('#kanban-files-input').val('');
				kanbanRenderFiles(json.attachments);
				if (json.errors && json.errors.length) { alert(json.errors.join('\n')); }
				kanbanCall({action: 'list'});
			},
			error: function(xhr) { alert('Error ' + xhr.status + ' ' + xhr.statusText); },
			complete: function() { $btn.prop('disabled', false); }
		});
	});
});

$(document).on('click', '.kanban-att-del', function(e) {
	e.preventDefault();
	$.post(kanbanUrl, {action: 'att_delete', id: $(this).data('id')}, function(json) {
		kanbanRenderFiles(json.attachments);
		kanbanCall({action: 'list'});
	}, 'json');
});

$('#kanban-add').on('click', function() {
	var title = $('#kanban-new-title').val().trim();
	if (title) { $('#kanban-new-title').val(''); kanbanCall({action: 'add', title: title}); }
});
$('#kanban-new-title').on('keypress', function(e) { if (e.which === 13) { $('#kanban-add').click(); } });

$(document).on('click', '.kanban-del', function(e) {
	e.preventDefault();
	if (confirm('<?php echo addslashes(html_entity_decode($text_kanban_delete_confirm, ENT_QUOTES, 'UTF-8')); ?>')) {
		kanbanCall({action: 'delete', id: $(this).closest('.kanban-card').data('id')});
	}
});

$(document).on('dragstart', '.kanban-card', function(e) {
	kanbanDragId = $(this).data('id');
	e.originalEvent.dataTransfer.setData('text/plain', kanbanDragId);
});
$(document).on('dragover', '.kanban-col', function(e) { e.preventDefault(); });
$(document).on('drop', '.kanban-col', function(e) {
	e.preventDefault();
	if (!kanbanDragId) { return; }
	var $col = $(this);
	var $dragged = $('.kanban-card[data-id="' + kanbanDragId + '"]');
	var $after = null;
	$col.children('.kanban-card').not($dragged).each(function() {
		var r = this.getBoundingClientRect();
		if (e.originalEvent.clientY > r.top + r.height / 2) { $after = $(this); }
	});
	if ($after) { $dragged.insertAfter($after); } else { $col.prepend($dragged); }
	var order = $col.children('.kanban-card').map(function() { return $(this).data('id'); }).get();
	var id = kanbanDragId;
	kanbanDragId = null;
	kanbanCall({action: 'move', id: id, status: $col.data('status'), order: order});
});

var claudeChatUrl = '<?php echo addslashes($claude_chat_url); ?>';
var claudeChatMessages = [];
var claudeChatBusy = false;

function claudeChatAppend(role, text) {
	var css = (role === 'user')
		? 'background:#eef6fb; border:1px solid #cfe3ef; margin-left:auto;'
		: 'background:#fff; border:1px solid #e5e2da;';

	var $bubble = $('<div>')
		.addClass('claude-chat-message claude-chat-message-' + (role === 'user' ? 'user' : 'bot'))
		.attr('style', css + ' border-radius:8px; padding:10px 14px; max-width:80%; margin-bottom:10px; white-space:pre-wrap;')
		.text(text);

	$('#claude-chat-messages').append($bubble);
	$('#claude-chat-messages').scrollTop($('#claude-chat-messages')[0].scrollHeight);
}

function claudeChatShowTyping() {
	var $bubble = $('<div>')
		.attr('id', 'claude-chat-typing')
		.attr('style', 'background:#fff; border:1px solid #e5e2da; border-radius:8px; padding:8px 14px; max-width:80%; margin-bottom:10px;')
		.html('<img src="view/image/ajax-loader.gif" width="20" height="20" alt="">');

	$('#claude-chat-messages').append($bubble);
	$('#claude-chat-messages').scrollTop($('#claude-chat-messages')[0].scrollHeight);
}

function claudeChatHideTyping() {
	$('#claude-chat-typing').remove();
}

function claudeChatSend() {
	var message = $('#claude-chat-input').val().trim();

	if (!message || claudeChatBusy) {
		return;
	}

	claudeChatAppend('user', message);
	$('#claude-chat-input').val('');

	claudeChatBusy = true;
	$('#claude-chat-send').prop('disabled', true);
	claudeChatShowTyping();

	fetch(claudeChatUrl, {
		method:  'POST',
		headers: { 'Content-Type': 'application/json' },
		body:    JSON.stringify({ messages: claudeChatMessages, message: message })
	})
	.then(function(r) { return r.json(); })
	.then(function(data) {
		claudeChatBusy = false;
		$('#claude-chat-send').prop('disabled', false);
		claudeChatHideTyping();

		if (data.error) {
			claudeChatAppend('bot', data.error);
			return;
		}

		claudeChatMessages = data.messages || claudeChatMessages;
		claudeChatAppend('bot', data.reply || '');
	})
	.catch(function() {
		claudeChatBusy = false;
		$('#claude-chat-send').prop('disabled', false);
		claudeChatHideTyping();
		claudeChatAppend('bot', '<?php echo $error_claude_chat_connection; ?>');
	});
}

$('#claude-chat-send').on('click', claudeChatSend);

$('#claude-chat-input').on('keypress', function(e) {
	if (e.which === 13) {
		claudeChatSend();
	}
});
</script>
</div><!-- /#home-type -->
<?php echo $footer; ?>