<?php
// Heading
$_['heading_title']                           = 'Remittances';

// Text
$_['text_success']                            = 'Success: You have modified remittances!';
$_['text_select']                             = 'Please, select a remittance';
$_['text_select_one']                         = 'You can only select a remittance';
$_['text_remittance_id']                      = 'Remittance ID:';
$_['text_bank']                               = 'Bank:';
$_['text_iban']                               = 'IBAN:';
$_['text_bic']                                = 'BIC:';
$_['text_telephone']                          = 'Telephone:';
$_['text_date']                               = 'Date:';

//Column
$_['column_remittance_id']                    = 'Remittance ID';
$_['column_customer_id']                      = 'Customer ID';
$_['column_customer']                         = 'Customer';
$_['column_status']                           = 'Status';
$_['column_date_added']                       = 'Date Added';
$_['column_date_modified']                    = 'Date Modified';
$_['column_total']                            = 'Total';
$_['column_bank_cc']                          = 'Bank Account';
$_['column_amount']                           = 'Amount';
$_['column_date_due']                         = 'Date Due';
$_['column_invoice_no']                       = 'Invoice No.';
$_['column_action']							  = 'Action';

$_['heading_title_lines']					  = 'Remittance Lines';
$_['column_remittance']					  	  = 'Receipt ID';
$_['column_amount']							  = 'Amount';
$_['column_date']							  = 'Date';

//Button
$_['button_generate']                         = 'SEPA SDD';
$_['button_remittance']						  = 'Print Remittance';

// SEPA
$_['heading_sepa_errors']        = 'The SEPA file could not be generated';
$_['text_sepa_errors_intro']     = 'Fix the following and try again. No file has been generated.';
$_['button_sepa_back']           = 'Back to remittances';
$_['error_sepa_permission']      = 'You do not have permission to generate SEPA files.';
$_['error_sepa_select']          = 'Select at least one existing remittance.';
$_['error_sepa_empty']           = 'The selected remittances have no receipts.';
$_['error_sepa_creditor_id']     = 'The SEPA creditor identifier is missing (Settings &gt; Bank).';
$_['error_sepa_creditor_iban']   = 'The IBAN of your company\'s default bank is missing or invalid (Settings &gt; Bank).';
$_['error_sepa_line_customer']   = 'Receipt %s: the customer of the invoice was not found.';
$_['error_sepa_line_iban']       = 'Receipt %s: the customer\'s IBAN is missing or invalid (customer record).';
$_['error_sepa_line_bic']        = 'Receipt %s: the customer\'s BIC is not valid (leave it empty if unknown).';
$_['error_sepa_line_amount']     = 'Receipt %s: the amount must be greater than zero.';
$_['error_sepa_line_currency']   = 'Receipt %s: SEPA direct debits only accept euros.';

?>