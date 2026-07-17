<?php
// Heading
$_['heading_title'] = 'Exporter';

// Text
$_['text_contaplus'] = 'Export invoices to ContaPlus 2014';
$_['text_sage50'] = 'Export invoices to Sage 50';

// Entry
$_['entry_date_start'] = 'Date from:';
$_['entry_date_end'] = 'Date to:';

// Warning (shown on the setup page, non-blocking)
$_['warning_no_sales_account'] = 'The sales accounting account has not been configured in Settings.';
$_['warning_no_customer_account'] = 'The default customer accounting account has not been configured in Settings.';
$_['warning_no_tax_account'] = 'The tax rate "%s" has no accounting account assigned (Localisation > Tax Rates).';

// Error (blocking, returned instead of the file)
$_['error_permission'] = 'Warning: You do not have permission to use the exporter!';
$_['error_no_sales_account'] = 'You must configure the sales accounting account in Settings before exporting.';
$_['error_no_tax_account'] = 'Missing tax accounting accounts for the following types, configure them in Localisation > Tax Rates:';
$_['error_no_invoices'] = 'No invoices were found in the specified date range.';
