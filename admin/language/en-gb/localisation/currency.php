<?php
// Heading
$_['heading_title']        = 'Currency';  

// Text
$_['text_success']         = 'Success: You have modified currencies!';

// Column
$_['column_title']         = 'Currency Title';
$_['column_code']          = 'Code'; 
$_['column_value']         = 'Value';
$_['column_date_modified'] = 'Last Updated';
$_['column_action']        = 'Action';

// Entry
$_['entry_title']          = 'Currency Title:';
$_['entry_code']           = '<a data-toggle="tooltip" title="Do not change if this is your default currency. Must be valid."><i class="fas fa-question-circle"></i></a> ISO Code';
$_['entry_value']          = '<a data-toggle="tooltip" title="Set to 1.00000 if this is your default currency."><i class="fas fa-question-circle"></i></a> Value';
$_['entry_symbol_left']    = 'Symbol Left:';
$_['entry_symbol_right']   = 'Symbol Right:';
$_['entry_decimal_place']  = 'Decimal Places:';
$_['entry_status']         = 'Status:';

// Error
$_['error_permission']     = 'Warning: You do not have permission to modify currencies!';
$_['error_title']          = 'Currency Title must be between 3 and 32 characters!';
$_['error_code']           = 'Currency Code must contain 3 characters!';
$_['error_default']        = 'Warning: This currency cannot be deleted as it is currently assigned as the default store currency!';
$_['error_store']          = 'Warning: This currency cannot be deleted as it is currently assigned to %s stores!';
$_['error_order']          = 'Warning: This currency cannot be deleted as it is currently assigned to %s orders!';
?>