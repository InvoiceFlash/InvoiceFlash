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

// Entry
$_['entry_date']              = 'BORME date';
$_['entry_province']         = 'Province';
$_['entry_max_emails']       = 'Stop after finding (nº of emails)';
$_['entry_email']            = 'Email';

// Column
$_['column_date']            = 'BORME date';
$_['column_province']        = 'Province';
$_['column_company']         = 'Company';
$_['column_city']            = 'City';
$_['column_website']         = 'Website';
$_['column_email']           = 'Email';
$_['column_status']          = 'Status';

// Button
$_['button_run']             = 'Run';
$_['button_save']            = 'Save';
$_['button_close']           = 'Close';

// Error
$_['error_permission']       = 'You do not have permission to modify this module.';
$_['error_no_api_key']       = 'No Claude API key is configured (Settings > AI). Configure it before running this module.';
$_['error_already_running']  = 'A process is already running. Wait for it to finish before launching another.';
$_['error_launch']           = 'Could not launch the process (Python interpreter not found).';
$_['error_invalid_email']    = 'Enter a valid email address.';
