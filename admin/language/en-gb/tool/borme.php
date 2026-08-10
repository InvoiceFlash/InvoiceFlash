<?php
// Heading
$_['heading_title']         = 'BORME - Newly created companies';

// Text
$_['text_home']              = 'Home';
$_['text_instruction']       = 'Searches BORME for companies incorporated 7 days ago in the given province, and tries to find each one\'s website and a contact email using the Claude API. This runs in the background; you can leave this screen and come back later.';
$_['text_started']           = 'Process launched in the background.';
$_['text_status_found']      = 'Found';
$_['text_status_not_found']  = 'Not found';
$_['text_no_results']        = 'No results yet.';
$_['text_pagination']        = 'Showing %d to %d of %d (%d pages)';
$_['text_edit_email']        = 'Add email manually';
$_['text_to']                = 'To';
$_['text_subject']           = 'Subject';
$_['text_message']           = 'Message';
$_['text_success_email']     = 'Email sent successfully.';
$_['text_success']           = 'Deleted successfully.';

// Entry
$_['entry_date']              = 'BORME date';
$_['entry_province']         = 'Province';
$_['entry_max_emails']       = 'Stop after finding (nº of emails)';
$_['entry_email']            = 'Email';
$_['entry_website']          = 'Website';

// Column
$_['column_date']            = 'BORME date';
$_['column_last_search']     = 'Last search';
$_['column_province']        = 'Province';
$_['column_company']         = 'Company';
$_['column_city']            = 'City';
$_['column_website']         = 'Website';
$_['column_email']           = 'Email';
$_['column_status']          = 'Status';
$_['column_action']          = 'Action';

// Button
$_['button_run']             = 'Run';
$_['button_save']            = 'Save';
$_['button_close']           = 'Close';
$_['button_email']           = 'E-mail';
$_['button_send']            = 'Send';
$_['button_delete']          = 'Delete';
$_['button_search_again']    = 'Search again';

// Error
$_['error_permission']       = 'You do not have permission to modify this module.';
$_['error_no_api_key']       = 'No Claude API key is configured (Settings > AI). Configure it before running this module.';
$_['error_already_running']  = 'A process is already running. Wait for it to finish before launching another.';
$_['error_launch']           = 'Could not launch the process (Python interpreter not found).';
$_['error_invalid_email']    = 'Enter a valid email address.';
$_['error_to']               = 'Enter a valid recipient email address.';
$_['error_subject']          = 'Enter a subject.';
$_['error_message']          = 'Enter a message.';
$_['error_select_warning']   = 'Select at least one row.';
