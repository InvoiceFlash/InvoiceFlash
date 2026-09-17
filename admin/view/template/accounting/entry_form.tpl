<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<style>
	#entry-lines tr.unbalanced-row { background-color: #fff3b0; }
	#entry-lines tr.balanced-row { background-color: #ffffff; }
</style>
<div class="panel panel-default">
	<div class="panel-heading clearfix">
		<div class="pull-left h2"><i class="hidden-xs fa fa-book"></i> <?php echo $heading_title; ?></div>
		<div class="pull-right">
			<button type="submit" form="form-entry" class="btn btn-primary"><i class="fa fa-save"></i><span class="hidden-xs"> <?php echo $button_save; ?></span></button>
		</div>
	</div>
	<div class="panel-body">
		<?php if ($error_warning) { ?>
		<div class="alert alert-danger"><?php echo $error_warning; ?></div>
		<?php } ?>
		<?php if ($success) { ?>
		<div class="alert alert-success"><?php echo $success; ?></div>
		<?php } ?>

		<form id="form-entry" action="<?php echo $save; ?>" method="post">
			<input type="hidden" name="line_date" id="line_date_hidden" value="">

			<div class="row g-2 align-items-end mb-3">
				<div class="col-6 col-sm-2">
					<label class="control-label"><?php echo $entry_date; ?></label>
					<div class="input-group">
						<input type="text" id="input-date" class="form-control date" value="<?php echo date('d-m-Y'); ?>">
						<div class="input-group-append"><div class="input-group-text"><i class="fas fa-calendar"></i></div></div>
					</div>
				</div>
			</div>

			<div class="row g-2 align-items-end mb-2">
				<div class="col-12 col-sm-2">
					<label class="control-label"><?php echo $entry_account; ?></label>
					<input type="text" id="input-account" class="form-control conta-account" maxlength="<?php echo $conta_digits; ?>" placeholder="<?php echo $conta_digits; ?> d&iacute;gitos" autocomplete="off" autofocus>
				</div>
				<div class="col-12 col-sm-6">
					<label class="control-label"><?php echo $entry_concept; ?></label>
					<input type="text" id="input-concept" class="form-control" autocomplete="off">
				</div>
				<div class="col-6 col-sm-2">
					<label class="control-label"><?php echo $entry_debit; ?></label>
					<input type="text" id="input-debit" class="form-control text-right" autocomplete="off">
				</div>
				<div class="col-6 col-sm-2">
					<label class="control-label"><?php echo $entry_credit; ?></label>
					<input type="text" id="input-credit" class="form-control text-right" autocomplete="off">
				</div>
			</div>

			<table class="table table-bordered table-striped mb-0">
				<thead>
					<tr>
						<th style="width:140px;"><?php echo $column_account; ?></th>
						<th><?php echo $column_concept; ?></th>
						<th class="text-right" style="width:120px;"><?php echo $column_debit; ?></th>
						<th class="text-right" style="width:120px;"><?php echo $column_credit; ?></th>
						<th style="width:40px;"></th>
					</tr>
				</thead>
				<tbody id="entry-lines"></tbody>
				<tfoot>
					<tr>
						<td colspan="2" class="text-right"><strong><?php echo $text_total; ?></strong></td>
						<td class="text-right"><strong id="total-debit">0.00</strong></td>
						<td class="text-right"><strong id="total-credit">0.00</strong></td>
						<td></td>
					</tr>
				</tfoot>
			</table>
		</form>
	</div>
</div>
<script type="text/javascript"><!--
$(function() {
	$('#input-account').trigger('focus');
});

var entryConfig = {
	digits: <?php echo (int)$conta_digits; ?>,
	errorAccount: <?php echo json_encode($error_account_js); ?>,
	errorUnbalanced: <?php echo json_encode($error_unbalanced_js); ?>,
	errorNoLines: <?php echo json_encode($error_no_lines_js); ?>,
	errorBoth: <?php echo json_encode($error_both_js); ?>
};

function entryFormatNumber(n) {
	return (Math.round(n * 100) / 100).toFixed(2);
}

function entryEscape(text) {
	return $('<div>').text(text).html();
}

function entryPadAccount(el) {
	var val = el.value;
	var dotIndex = val.indexOf('.');
	var digits = entryConfig.digits;

	if (dotIndex !== -1) {
		// El punto se sustituye por ceros en su sitio: lo escrito antes del punto
		// es el prefijo, lo escrito despues queda fijo al final (p.ej. 430.28 -> 4300000028).
		var before = val.substring(0, dotIndex).replace(/[^0-9]/g, '');
		var after = val.substring(dotIndex + 1).replace(/[^0-9]/g, '');

		if (before.length + after.length > digits) {
			before = before.substr(0, Math.max(0, digits - after.length));
		}

		var zeros = '';
		while (before.length + zeros.length + after.length < digits) {
			zeros += '0';
		}

		el.value = (before + zeros + after).substr(0, digits);
	} else {
		// Sin punto: si se ha dejado vacio o a medias, se completa con ceros a la derecha al salir del campo.
		var digitsOnly = val.replace(/[^0-9]/g, '').substr(0, digits);

		while (digitsOnly.length < digits) {
			digitsOnly += '0';
		}

		el.value = digitsOnly;
	}
}

// Mientras se escribe solo se filtran caracteres invalidos (se admite el punto).
// El relleno con ceros se aplica al salir del campo (blur), no al teclear el punto.
$('#input-account').on('input', function() {
	this.value = this.value.replace(/[^0-9.]/g, '');
});

$('#input-account').on('blur', function() {
	entryPadAccount(this);
});

$('#input-account').on('keypress', function(e) {
	if (e.which === 13) {
		e.preventDefault();
		$('#input-concept').trigger('focus');
	}
});

$('#input-debit, #input-credit').on('input', function() {
	this.value = this.value.replace(/[^0-9.,]/g, '');
});

function entryGetTotals() {
	var totalDebit = 0;
	var totalCredit = 0;

	$('#entry-lines tr').each(function() {
		totalDebit += parseFloat($(this).attr('data-debit')) || 0;
		totalCredit += parseFloat($(this).attr('data-credit')) || 0;
	});

	return { debit: totalDebit, credit: totalCredit };
}

function entryUpdateTotals() {
	var totals = entryGetTotals();

	$('#total-debit').text(entryFormatNumber(totals.debit));
	$('#total-credit').text(entryFormatNumber(totals.credit));

	var rows = $('#entry-lines tr');
	var balanced = (rows.length > 0) && (Math.abs(totals.debit - totals.credit) < 0.005);

	rows.toggleClass('unbalanced-row', !balanced).toggleClass('balanced-row', balanced);
}

function entryAddLine() {
	var account = $('#input-account').val();
	var concept = $('#input-concept').val();
	var debit = parseFloat(($('#input-debit').val() || '0').replace(',', '.')) || 0;
	var credit = parseFloat(($('#input-credit').val() || '0').replace(',', '.')) || 0;

	if (!account && debit === 0 && credit === 0) {
		// Fila totalmente vacia (p.ej. doble disparo al salir de Haber y luego
		// pulsar "Nuevo", o tabular sin escribir nada): no hacemos nada, sin avisos.
		return;
	}

	if (account.length !== entryConfig.digits) {
		alert(entryConfig.errorAccount);
		$('#input-account').trigger('focus');
		return;
	}

	if (debit === 0 && credit === 0) {
		return;
	}

	if (debit > 0 && credit > 0) {
		alert(entryConfig.errorBoth);
		return;
	}

	var conceptEsc = entryEscape(concept);

	var row = $('<tr>').attr('data-debit', debit).attr('data-credit', credit).html(
		'<td>' + account + '<input type="hidden" name="account[]" value="' + account + '"></td>' +
		'<td>' + conceptEsc + '<input type="hidden" name="concept[]" value="' + conceptEsc + '"></td>' +
		'<td class="text-right">' + (debit ? entryFormatNumber(debit) : '') + '<input type="hidden" name="debit[]" value="' + debit + '"></td>' +
		'<td class="text-right">' + (credit ? entryFormatNumber(credit) : '') + '<input type="hidden" name="credit[]" value="' + credit + '"></td>' +
		'<td class="text-center"><a href="#" class="text-danger" onclick="$(this).closest(\'tr\').remove(); entryUpdateTotals(); return false;"><i class="fa fa-trash"></i></a></td>'
	);

	$('#entry-lines').append(row);

	entryUpdateTotals();

	var totals = entryGetTotals();
	var balanced = Math.abs(totals.debit - totals.credit) < 0.005;

	$('#input-account, #input-debit, #input-credit').val('');

	// El concepto se mantiene (para no repetirlo linea a linea) hasta que el asiento cuadra.
	if (balanced) {
		$('#input-concept').val('');
	}

	$('#input-account').trigger('focus');
}

// Al salir de Haber (tabulando o con el raton) la linea se anade sola y el foco vuelve a Cuenta.
$('#input-credit').on('blur', function() {
	entryAddLine();
});

$('#input-credit, #input-debit').on('keypress', function(e) {
	if (e.which === 13) {
		e.preventDefault();
		entryAddLine();
	}
});

$(document).on('keydown', function(e) {
	if (e.ctrlKey && !e.altKey && (e.key === 'n' || e.key === 'N')) {
		e.preventDefault();
		entryAddLine();
	}
});

$('#form-entry').on('submit', function(e) {
	if ($('#entry-lines tr').length === 0) {
		alert(entryConfig.errorNoLines);
		e.preventDefault();
		return;
	}

	var totals = entryGetTotals();

	if (Math.abs(totals.debit - totals.credit) >= 0.005) {
		alert(entryConfig.errorUnbalanced);
		e.preventDefault();
		return;
	}

	$('#line_date_hidden').val($('#input-date').val());
});
//--></script>
<?php echo $footer; ?>
