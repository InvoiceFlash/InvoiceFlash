<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<style>
	#cal-layout { display: flex; gap: 1rem; align-items: flex-start; }
	#cal-sidebar { width: 220px; flex: 0 0 220px; }
	#cal-main { flex: 1 1 auto; min-width: 0; }
	.cal-item { display: flex; align-items: center; gap: .5rem; padding: .25rem 0; }
	.cal-item .cal-dot { width: 14px; height: 14px; border-radius: 3px; flex: 0 0 14px; }
	.cal-item label { flex: 1 1 auto; margin: 0; cursor: pointer; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
	.cal-item a { color: #888; cursor: pointer; }
	@media (max-width: 767px) { #cal-layout { flex-direction: column; } #cal-sidebar { width: 100%; flex: none; } }
</style>
<div class="panel panel-default">
	<div class="panel-heading clearfix">
		<div class="pull-left h2"><i class="hidden-xs fa fa-calendar"></i> <?php echo $heading_title; ?></div>
		<?php if ($can_modify) { ?>
		<div class="pull-right">
			<button type="button" class="btn btn-primary" id="cal-btn-new"><i class="fa fa-plus"></i> <?php echo $text_new_event; ?></button>
		</div>
		<?php } ?>
	</div>
	<div class="panel-body">
		<div id="cal-layout">
			<div id="cal-sidebar">
				<div class="d-flex justify-content-between align-items-center mb-2">
					<strong><?php echo $text_calendars; ?></strong>
					<?php if ($can_modify) { ?><a href="#" id="cal-add" title="<?php echo $text_new_calendar; ?>"><i class="fa fa-plus"></i></a><?php } ?>
				</div>
				<div id="cal-list">
					<?php foreach ($calendars as $c) { ?>
					<div class="cal-item" data-id="<?php echo $c['calendar_id']; ?>" data-name="<?php echo $c['name']; ?>" data-color="<?php echo $c['color']; ?>">
						<span class="cal-dot" style="background:<?php echo $c['color']; ?>;"></span>
						<input type="checkbox" class="cal-toggle" id="cal-chk-<?php echo $c['calendar_id']; ?>" value="<?php echo $c['calendar_id']; ?>" checked>
						<label for="cal-chk-<?php echo $c['calendar_id']; ?>"><?php echo $c['name']; ?></label>
						<?php if ($can_modify) { ?><a class="cal-edit" title="<?php echo $text_edit_event; ?>"><i class="fa fa-pencil"></i></a><?php } ?>
					</div>
					<?php } ?>
				</div>
			</div>
			<div id="cal-main"><div id="calendar"></div></div>
		</div>
	</div>
</div>

<!-- Modal evento -->
<div class="modal fade" id="calEventModal" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="calEventTitle"></h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
			</div>
			<div class="modal-body">
				<input type="hidden" id="ev-id">
				<div class="mb-2"><label class="form-label"><?php echo $entry_title; ?></label><input type="text" id="ev-title" class="form-control"></div>
				<div class="mb-2"><label class="form-label"><?php echo $entry_calendar; ?></label>
					<select id="ev-calendar" class="form-control">
						<?php foreach ($calendars as $c) { ?><option value="<?php echo $c['calendar_id']; ?>"><?php echo $c['name']; ?></option><?php } ?>
					</select>
				</div>
				<div class="mb-2"><label><input type="checkbox" id="ev-allday"> <?php echo $entry_all_day; ?></label></div>
				<div class="row mb-2">
					<div class="col-6"><label class="form-label"><?php echo $entry_start; ?></label><input type="datetime-local" id="ev-start" class="form-control"></div>
					<div class="col-6"><label class="form-label"><?php echo $entry_end; ?></label><input type="datetime-local" id="ev-end" class="form-control"></div>
				</div>
				<div class="mb-2"><label class="form-label"><?php echo $entry_location; ?></label><input type="text" id="ev-location" class="form-control"></div>
				<div class="mb-2"><label class="form-label"><?php echo $entry_description; ?></label><textarea id="ev-description" rows="3" class="form-control"></textarea></div>
				<div class="text-danger" id="ev-error"></div>
			</div>
			<div class="modal-footer">
				<?php if ($can_modify) { ?>
				<button type="button" class="btn btn-danger me-auto" id="ev-delete" style="display:none;"><i class="fa fa-trash"></i> <?php echo $button_delete; ?></button>
				<button type="button" class="btn btn-primary" id="ev-save"><i class="fa fa-save"></i> <?php echo $button_save; ?></button>
				<?php } ?>
				<button type="button" class="btn btn-default" data-bs-dismiss="modal"><?php echo $button_close; ?></button>
			</div>
		</div>
	</div>
</div>

<!-- Modal calendario -->
<div class="modal fade" id="calCalModal" tabindex="-1">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header"><h5 class="modal-title"><?php echo $text_calendars; ?></h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
			<div class="modal-body">
				<input type="hidden" id="cal-id">
				<div class="mb-2"><label class="form-label"><?php echo $entry_name; ?></label><input type="text" id="cal-name" class="form-control"></div>
				<div class="mb-2"><label class="form-label"><?php echo $entry_color; ?></label><input type="color" id="cal-color" class="form-control form-control-color" value="#3788d8"></div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger me-auto" id="cal-delete" style="display:none;"><i class="fa fa-trash"></i> <?php echo $button_delete; ?></button>
				<button type="button" class="btn btn-primary" id="cal-save"><?php echo $button_save; ?></button>
			</div>
		</div>
	</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.15/locales-all.global.min.js"></script>
<script>
$(function() {
	var urls = {
		events: <?php echo json_encode($url_events); ?>, save: <?php echo json_encode($url_save); ?>,
		move: <?php echo json_encode($url_move); ?>, del: <?php echo json_encode($url_delete); ?>,
		calSave: <?php echo json_encode($url_cal_save); ?>, calDel: <?php echo json_encode($url_cal_del); ?>
	};
	var canModify = <?php echo $can_modify ? 'true' : 'false'; ?>;
	var confirmText = $('<div>').html(<?php echo json_encode($text_confirm); ?>).text();
	var evModal = new bootstrap.Modal(document.getElementById('calEventModal'));
	var calModal = new bootstrap.Modal(document.getElementById('calCalModal'));
	var hidden = {};

	function pad(n) { return (n < 10 ? '0' : '') + n; }
	function fmtLocal(d) { return d.getFullYear() + '-' + pad(d.getMonth() + 1) + '-' + pad(d.getDate()) + 'T' + pad(d.getHours()) + ':' + pad(d.getMinutes()); }
	function fmtDate(d) { return fmtLocal(d).substr(0, 10); }
	function toInput(d, allDay) { return d ? (allDay ? fmtDate(d) + 'T00:00' : fmtLocal(d)) : ''; }

	var calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
		locale: <?php echo json_encode($locale); ?>,
		timeZone: 'local',
		firstDay: 1,
		height: 'auto',
		nowIndicator: true,
		navLinks: true,
		selectable: canModify,
		editable: canModify,
		dayMaxEvents: true,
		headerToolbar: { left: 'prev,next today', center: 'title', right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek' },
		buttonText: { today: <?php echo json_encode($text_today); ?>, month: <?php echo json_encode($text_month); ?>, week: <?php echo json_encode($text_week); ?>, day: <?php echo json_encode($text_day); ?>, list: <?php echo json_encode($text_list); ?> },
		events: function(info, ok, fail) {
			$.getJSON(urls.events, { start: info.startStr, end: info.endStr }, function(list) {
				ok($.grep(list, function(e) { return !hidden[e.extendedProps.calendar_id]; }));
			}).fail(fail);
		},
		select: function(info) { openEvent(null, info.start, info.end, info.allDay); },
		dateClick: function() {},
		eventClick: function(info) { openEvent(info.event); },
		eventDrop: moved,
		eventResize: moved
	});
	calendar.render();

	function moved(info) {
		var e = info.event;
		$.post(urls.move, {
			event_id: e.id, all_day: e.allDay ? 1 : 0,
			start: e.allDay ? fmtDate(e.start) : fmtLocal(e.start),
			end: e.end ? (e.allDay ? fmtDate(e.end) : fmtLocal(e.end)) : ''
		}, function(r) { if (r.error) { info.revert(); } }, 'json').fail(function() { info.revert(); });
	}

	function openEvent(ev, start, end, allDay) {
		$('#ev-error').text('');
		var isNew = !ev;
		$('#calEventTitle').text(isNew ? <?php echo json_encode($text_new_event); ?> : <?php echo json_encode($text_edit_event); ?>);
		$('#ev-delete').toggle(!isNew);
		$('#ev-id').val(isNew ? '' : ev.id);
		$('#ev-title').val(isNew ? '' : ev.title);
		$('#ev-calendar').val(isNew ? $('#ev-calendar option:first').val() : ev.extendedProps.calendar_id);
		$('#ev-location').val(isNew ? '' : ev.extendedProps.location);
		$('#ev-description').val(isNew ? '' : ev.extendedProps.description);
		var ad = isNew ? !!allDay : ev.allDay, s = isNew ? start : ev.start, e = isNew ? end : ev.end;
		if (isNew && !s) { s = new Date(); s.setMinutes(0, 0, 0); s.setHours(s.getHours() + 1); e = new Date(s.getTime() + 3600000); ad = false; }
		// FullCalendar da el fin de un todo-el-dia como exclusivo: mostrar el dia anterior.
		if (ad && e) { e = new Date(e.getTime() - 86400000); if (e < s) { e = s; } }
		$('#ev-allday').prop('checked', ad);
		$('#ev-start').val(toInput(s, ad));
		$('#ev-end').val(toInput(e, ad));
		evModal.show();
	}

	$('#cal-btn-new').on('click', function() { openEvent(null); });

	$('#ev-save').on('click', function() {
		var ad = $('#ev-allday').is(':checked'), s = $('#ev-start').val(), e = $('#ev-end').val();
		if (ad) {
			s = s.substr(0, 10);
			// Guardar el fin como exclusivo (dia siguiente), como espera FullCalendar.
			if (e) { var d = new Date(e.substr(0, 10) + 'T00:00'); d.setDate(d.getDate() + 1); e = fmtDate(d); }
		}
		$.post(urls.save, {
			event_id: $('#ev-id').val(), title: $('#ev-title').val(), calendar_id: $('#ev-calendar').val(),
			all_day: ad ? 1 : 0, start: s, end: e, location: $('#ev-location').val(), description: $('#ev-description').val()
		}, function(r) {
			if (r.error) { $('#ev-error').text(r.error); return; }
			evModal.hide(); calendar.refetchEvents();
		}, 'json');
	});

	$('#ev-delete').on('click', function() {
		if (!confirm(confirmText)) { return; }
		$.post(urls.del, { event_id: $('#ev-id').val() }, function() { evModal.hide(); calendar.refetchEvents(); }, 'json');
	});

	$('#ev-allday').on('change', function() {
		var v = $('#ev-start').val().substr(0, 10);
		if (this.checked && v) { $('#ev-start').val(v + 'T00:00'); }
	});

	$('#cal-list').on('change', '.cal-toggle', function() {
		hidden[this.value] = !this.checked;
		calendar.refetchEvents();
	});

	$('#cal-add').on('click', function(ev) {
		ev.preventDefault();
		$('#cal-id').val(''); $('#cal-name').val(''); $('#cal-color').val('#3788d8'); $('#cal-delete').hide();
		calModal.show();
	});

	$('#cal-list').on('click', '.cal-edit', function() {
		var $i = $(this).closest('.cal-item');
		$('#cal-id').val($i.data('id')); $('#cal-name').val($i.data('name')); $('#cal-color').val($i.data('color'));
		$('#cal-delete').show();
		calModal.show();
	});

	$('#cal-save').on('click', function() {
		$.post(urls.calSave, { calendar_id: $('#cal-id').val(), name: $('#cal-name').val(), color: $('#cal-color').val() }, function(r) {
			if (!r.error) { location.reload(); }
		}, 'json');
	});

	$('#cal-delete').on('click', function() {
		if (!confirm(confirmText)) { return; }
		$.post(urls.calDel, { calendar_id: $('#cal-id').val() }, function(r) {
			if (!r.error) { location.reload(); }
		}, 'json');
	});
});
</script>
<?php echo $footer; ?>
