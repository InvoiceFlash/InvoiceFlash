<?php
// Heading
$_['heading_title']            = 'Suppliers';

// Text
$_['text_success']             = 'Success: You have modified suppliers!';
$_['text_no_results']          = 'No results!';
$_['text_select']              = '--- Please Select ---';
$_['text_none']                = '--- None ---';
$_['text_enabled']             = 'Enabled';
$_['text_disabled']            = 'Disabled';

// Column
$_['column_company']           = 'Company';
$_['column_name']               = 'Contact Name';
$_['column_email']             = 'E-Mail';
$_['column_telephone']         = 'Telephone';
$_['column_status']            = 'Status';
$_['column_date_added']        = 'Date Added';
$_['column_action']            = 'Action';
$_['column_contact_name']      = 'Name';
$_['column_contact_email']     = 'Email';
$_['column_comment']           = 'Comment';
$_['column_user']              = 'User';
$_['column_date']              = 'Date';

// Entry
$_['entry_firstname']          = 'First Name:';
$_['entry_lastname']           = 'Last Name:';
$_['entry_company']            = 'Company:';
$_['entry_company_id']         = 'Company ID:';
$_['entry_tax_id']             = 'Tax ID:';
$_['entry_contable_account']   = 'Accounting Account:';
$_['entry_email']              = 'E-Mail:';
$_['entry_telephone']          = 'Telephone:';
$_['entry_fax']                = 'Fax:';
$_['entry_web']                = 'Web:';
$_['button_web']               = 'Go to web';
$_['error_web']                = 'Error: Enter a valid url!';
$_['entry_address_1']          = 'Address:';
$_['entry_address_2']          = 'Address 2:';
$_['entry_city']               = 'City:';
$_['entry_postcode']           = 'Postcode:';
$_['entry_country']            = 'Country:';
$_['entry_zone']               = 'Region / State:';
$_['entry_comment']            = 'Comment:';
$_['entry_status']             = 'Status:';

// Button
$_['button_insert']            = 'Add New';
$_['button_delete']            = 'Delete';
$_['button_filter']            = 'Filter';
$_['button_save']              = 'Save';
$_['button_cancel']            = 'Cancel';
$_['button_add_note']          = 'Add Note';

// Tab
$_['tab_general']              = 'General';
$_['tab_notes']                 = 'Notes';
$_['tab_contacts']              = 'Contacts';
$_['tab_contracts']             = 'Documents';
$_['tab_products']             = 'Products';
$_['tab_invoices']             = 'Invoices';

// Products tab
$_['column_product_id']        = 'ID';
$_['column_product_name']      = 'Product';
$_['column_invoice']           = 'Invoice';
$_['column_invoice_date']      = 'Date';
$_['column_total']             = 'Total';

// Notes
$_['heading_title_note']       = 'Note';
$_['entry_user']                = 'User:';
$_['entry_date_note']           = 'Date:';

// Contacts
$_['button_add_contact']       = 'Add Contact';
$_['heading_contact']          = 'Supplier Contact';
$_['entry_name']                = 'Name:';
$_['entry_telephone2']          = 'Telephone 2:';
$_['entry_puesto']              = 'Job:';
$_['entry_notas']                = 'Notes:';
$_['text_delete']               = 'Delete';
$_['text_edit']                 = 'Edit';

// Documents (previously "Contracts" - same pattern as sale/customer.php)
$_['heading_title_contract']   = 'Documents';
$_['column_filename']          = 'Filename';
$_['button_add_contract']      = 'Add Document';
$_['entry_document']           = 'Document (PDF or XLSX):';
$_['button_upload']            = 'Upload';
$_['button_view']              = 'View';
$_['text_no_documents']        = 'No documents attached';
$_['error_upload']             = 'Warning: You must select a file!';
$_['error_document_type']      = 'Warning: Only PDF or XLSX files are allowed!';
$_['error_document_upload']    = 'Warning: The file could not be uploaded!';

// Emails (same pattern as sale/customer.php)
$_['tab_email']                 = 'Emails';
$_['column_email_subject']      = 'Subject';
$_['column_email_sender']       = 'Sender';
$_['text_to']                   = 'To:';
$_['text_subject']              = 'Subject:';
$_['text_message']              = 'Message:';
$_['button_new_email']          = 'New Email';
$_['button_send']               = 'Send';
$_['text_success_email']        = 'Email successfully sent to the supplier';
$_['error_to']                  = 'The destination email is not valid!';
$_['error_subject']             = 'The subject cannot be empty!';
$_['error_message']             = 'The message cannot be empty!';
$_['error_permission_email']    = 'Warning: You do not have permission to send emails!';

// Orders / Receipts (same pattern as sale/customer.php: reuses purchase_order,
// "Receipts" filters to the ones already received - see ModelPurchaseSupplier)
$_['tab_orders']                = 'Orders';
$_['tab_recepciones']           = 'Receipts';
$_['column_order']              = 'Order No.';
$_['column_quantity']           = 'Quantity';

// Error
$_['error_warning']            = 'Warning: Please check the form carefully for errors!';
$_['error_permission']         = 'Warning: You do not have permission to modify suppliers!';
$_['error_company']            = 'Company must be between 1 and 92 characters!';
$_['error_email']              = 'E-Mail Address does not appear to be valid!';
$_['error_contact_name']       = 'Warning: Name field must have between 3 and 50 characters!';
?>
