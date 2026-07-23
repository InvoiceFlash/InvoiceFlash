<?php
// Heading
$_['heading_title']       = 'Importer';

// Text
$_['text_home']           = 'Home';
$_['text_form']           = 'Import';
$_['text_success']        = 'Success: %d record(s) imported, %d updated!';
$_['text_success_saconta'] = 'Success: %d chart of accounts lines, %d sub-accounts (%d new customers) and %d journal entry lines imported!';
$_['text_example']        = 'Excel file example';
$_['text_example_help']   = 'The first row must be the header and will be ignored. Columns must follow this exact order:';
$_['text_saconta_help']   = 'Path, on this same server, to the SaConta exercise folder (must contain ctab6.dbf, ctab61.dbf and ctab8.dbf). This will populate the Chart of Accounts, the Sub-Accounts (also creating customers for the 430 accounts) and the Journal Book.';
$_['text_type_product']   = 'Products';
$_['text_type_customer']  = 'Customers';
$_['text_type_supplier']  = 'Suppliers';
$_['text_type_saconta']   = 'FLASH SaConta';

// Entry
$_['entry_type']          = 'Import Type:';
$_['entry_file']          = 'Excel File:';
$_['entry_path']          = 'Path ejercicio:';
$_['entry_related_history_code'] = 'Related History Code:';

// Column (products)
$_['column_code']         = 'Item Code';
$_['column_description']  = 'Description';
$_['column_price']        = 'Price';
$_['column_quantity']     = 'Quantity';
$_['column_status']       = 'Status (1 = Enabled, 0 = Disabled)';

// Column (customers)
$_['column_company']      = 'Company';
$_['column_nif']          = 'Tax ID';
$_['column_email']        = 'Email';
$_['column_telephone']    = 'Telephone';
$_['column_address']      = 'Address';
$_['column_city']         = 'City';
$_['column_postcode']     = 'Postcode';
$_['column_country']      = 'Country';

// Button
$_['button_import']       = 'Import';

// Error
$_['error_permission']    = 'Warning: You do not have permission to import!';
$_['error_upload']        = 'Warning: You must select a file to upload!';
$_['error_extension']     = 'Warning: The file must be a .xlsx Excel file!';
$_['error_file']          = 'Warning: Could not read the uploaded Excel file!';
$_['error_row']           = 'Row %d: %s';
$_['error_required']      = 'Item Code and Description are required';
$_['error_required_customer'] = 'Company and Email are required';
$_['error_path']          = 'Warning: You must enter a valid, accessible server folder path!';
$_['error_saconta_file']  = 'Warning: %s was not found in that folder!';
?>
