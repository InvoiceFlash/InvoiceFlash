<?php
// Heading
$_['heading_title']              = 'FACe';
$_['heading_setting']            = 'FACe settings';

// Text
$_['text_home']                  = 'Home';
$_['text_success_setting']       = 'FACe settings saved.';
$_['text_success_sent']          = 'Invoice sent to FACe. Registry number: %s.';
$_['text_success_refresh']       = 'Status updated from FACe.';
$_['text_success_refresh_all']   = '%d invoices updated.';
$_['text_success_cancel']        = 'Cancellation request sent to FACe. The accounting office accepts or rejects it.';
$_['text_connection_ok']         = 'Connection to FACe OK: certificate accepted, %d invoice statuses available.';
$_['text_no_results']            = 'No invoice has been sent to FACe yet.';
$_['text_all']                   = 'All';
$_['text_yes']                   = 'Yes';
$_['text_no']                    = 'No';
$_['text_test']                  = 'Test';
$_['text_production']            = 'Production';
$_['text_inactive']              = 'FACe is disabled in its settings: enable it to send invoices.';
$_['text_status_pending']        = 'Registered (not queried yet)';
$_['text_status_error']          = 'Sending error';
$_['text_status_1200']           = 'Registered';
$_['text_status_1300']           = 'Registered in RCF';
$_['text_status_2400']           = 'Payment obligation booked';
$_['text_status_2500']           = 'Paid';
$_['text_status_2600']           = 'Rejected';
$_['text_status_3100']           = 'Cancelled';
$_['text_confirm_send']          = 'Send this invoice to FACe? Once registered, it can only be corrected by requesting its cancellation.';
$_['text_confirm_resend']        = 'Send this invoice to FACe again?';
$_['text_confirm_cancel']        = 'Ask FACe to cancel this invoice?';
$_['text_prompt_reason']         = 'Cancellation reason (required):';
$_['text_certificate_note']      = 'The same certificate (.p12/.pfx) and password used to sign the Facturae invoice are used, in System &gt; Settings. That certificate must be registered as the supplier certificate in the FACe portal.';
$_['text_email_note']            = 'Supplier email address FACe sends the notices of each invoice to.';
$_['text_dir3_note']             = 'Each public customer needs the DIR3 codes of accounting office, managing body and processing unit on its record; FACe rejects the invoice without them.';
$_['text_requirements_ok']       = 'The server has what it needs to sign and send to FACe.';
$_['text_pagination']            = 'Showing {start} to {end} of {total} ({pages} Pages)';

// Column
$_['column_invoice']             = 'Invoice';
$_['column_customer']            = 'Customer';
$_['column_date']                = 'Date';
$_['column_total']               = 'Total';
$_['column_registry']            = 'Registry no.';
$_['column_environment']         = 'Environment';
$_['column_status']              = 'Status';
$_['column_action']              = 'Action';

// Entry
$_['entry_status']               = 'Status';
$_['entry_active']               = 'Send to FACe';
$_['entry_environment']          = 'Environment';
$_['entry_email']                = 'Supplier email';

// Button
$_['button_setting']             = 'Settings';
$_['button_refresh']             = 'Check status';
$_['button_refresh_all']         = 'Update statuses';
$_['button_resend']              = 'Resend';
$_['button_cancel_invoice']      = 'Cancel';
$_['button_xml']                 = 'XML';
$_['button_filter']              = 'Filter';
$_['button_save']                = 'Save';
$_['button_cancel']              = 'Back';
$_['button_test']                = 'Test connection';
$_['button_send']                = 'Send to FACe';

// Error
$_['error_permission']           = 'Warning: You do not have permission to modify FACe!';
$_['error_warning']              = 'Warning: please check the marked fields.';
$_['error_email']                = 'Enter a valid email address.';
$_['error_extension']            = 'The PHP extension %s is missing and is required to send to FACe.';
$_['error_library']              = 'The file system/library/face.php is missing.';
$_['error_certificate']          = 'Upload the digital certificate and set its password in System > Settings before sending to FACe.';
$_['error_already_sent']         = 'The invoice is already registered in FACe with number %s. To correct it, request its cancellation and issue another one.';
$_['error_not_registered']       = 'The invoice is not registered in FACe.';
$_['error_reason']               = 'Enter the cancellation reason.';
$_['error_inactive']             = 'FACe is disabled in Sales > FACe > Settings.';
$_['error_no_dir3']              = 'The customer has no DIR3 codes (accounting office, managing body and processing unit) on its record: FACe would reject the invoice.';
$_['error_invoice']              = 'The invoice does not exist.';
