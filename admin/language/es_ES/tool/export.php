<?php
// Heading
$_['heading_title'] = 'Exportador Contabilidad';

// Text
$_['text_contaplus'] = 'Exportar facturas a ContaPlus 2014';
$_['text_sage50'] = 'Exportar facturas a Sage 50';

// Entry
$_['entry_date_start'] = 'Fecha desde:';
$_['entry_date_end'] = 'Fecha hasta:';

// Warning (shown on the setup page, non-blocking)
$_['warning_no_sales_account'] = 'No se ha configurado la cuenta contable de ventas en Ajustes.';
$_['warning_no_customer_account'] = 'No se ha configurado la cuenta contable de clientes por defecto en Ajustes.';
$_['warning_no_tax_account'] = 'El tipo de IVA "%s" no tiene cuenta contable asignada (Localisation > Tax Rates).';

// Error (blocking, returned instead of the file)
$_['error_permission'] = 'Aviso: No tiene permiso para usar el exportador.';
$_['error_no_sales_account'] = 'Debes configurar la cuenta contable de ventas en Ajustes antes de exportar.';
$_['error_no_tax_account'] = 'Faltan cuentas contables de IVA para los siguientes tipos, configúralas en Localisation > Tax Rates:';
$_['error_no_invoices'] = 'No se encontraron facturas en el rango de fechas indicado.';
