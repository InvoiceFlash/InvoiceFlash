<?php
// Heading
$_['heading_title'] = 'Modelo 303';

// Column
$_['column_code']       = 'Box';
$_['column_name']       = 'Concept';
$_['column_order_code'] = 'Order';
$_['column_action']     = 'Action';

// Entry
$_['entry_code']       = 'Box:';
$_['entry_order_code'] = 'Order code:';
$_['entry_name']       = 'Concept:';
$_['entry_accounts']   = 'Accounts / Formula:';
$_['entry_level']      = 'Highlighted:';
$_['entry_list_after'] = 'List after:';

// Help
$_['help_accounts'] = 'Space-separated account codes (sums Credit - Debit) for output VAT boxes (477), or prefixed with "-" to flip the sign (Debit - Credit, for input VAT accounts 472). Also accepts a formula referencing other boxes with the C prefix (e.g. C03+C06+C09, or C03/0.04 to derive a taxable base from its VAT amount).';
$_['help_level']    = '0 = normal line, 1 = highlighted bold line (totals/result).';

// Text
$_['text_success']    = 'Success!';
$_['text_no_results'] = 'No results found.';
$_['text_edit']       = 'Edit';

// Button
$_['button_new']    = 'New';
$_['button_save']   = 'Save';
$_['button_cancel'] = 'Cancel';
$_['button_delete'] = 'Delete';

// Error
$_['error_permission']  = 'Warning: You do not have permission to modify the Modelo 303 configuration!';
$_['error_code']        = 'Box Required! Must be between 1 and 12 characters!';
$_['error_code_exists'] = 'Warning: A line with that box already exists!';
$_['error_name']        = 'Concept must be between 1 and 255 characters!';
