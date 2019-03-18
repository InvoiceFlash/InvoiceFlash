<?php
// Heading
$_['heading_title']                           = 'Invoices';

// Text
$_['text_success']                            = 'Success: You have modified invoices!';
$_['text_invoice_id']                         = 'Invoice ID:';
$_['text_invoice_no']                         = 'Invoice No.:';
$_['text_invoice_date']                       = 'Invoice Date:';
$_['text_store_name']                         = 'Store Name:';
$_['text_store_url']                          = 'Store Url:';
$_['text_customer']                           = 'Customer:';
$_['text_customer_group']                     = 'Customer Group:';
$_['text_email']                              = 'E-Mail:';
$_['text_telephone']                          = 'Telephone:';
$_['text_fax']                                = 'Fax:';
$_['text_shipping_method']                    = 'Shipping Method:';
$_['text_payment_method']                     = 'Payment Method:';
$_['text_total']                              = 'Total:';
$_['text_invoice_status']                     = 'Invoice Status:';
$_['text_comment']                            = 'Comment:';
$_['text_commission']                         = 'Commission:';
$_['text_date_added']                         = 'Date Added:';
$_['text_date_modified']                      = 'Date Modified:';
$_['text_company']                            = 'Company:';
$_['text_company_id']                         = 'Company ID:';
$_['text_tax_id']                             = 'Tax ID:';
$_['text_address_1']                          = 'Address 1:';
$_['text_address_2']                          = 'Address 2:';
$_['text_postcode']                           = 'Postcode:';
$_['text_city']                               = 'City:';
$_['text_zone']                               = 'Region / State:';
$_['text_zone_code']                          = 'Region / State Code:';
$_['text_country']                            = 'Country:';
$_['text_invoice']                            = 'Invoice';
$_['text_to']                                 = 'To';
$_['text_ship_to']                            = 'Ship To (if different address)';
$_['text_missing']                            = 'Missing Invoices';
$_['text_default']                            = 'Default';
$_['text_wait']                               = 'Please Wait!';
$_['text_product']                            = 'Add Product(s)';
$_['text_voucher']                            = 'Add Voucher(s)';
$_['text_invoice_details']                    = 'Invoice Details';
$_['text_generate']                           = 'Generate';
$_['text_commission_add']                     = 'Add Commission';
$_['text_commission_added']                   = 'Success: Commission added!';
$_['text_commission_remove']                  = 'Remove Commission';
$_['text_commission_removed']                 = 'Success: Commission removed!';
$_['text_credit_add']                         = 'Add Credit';
$_['text_credit_added']                       = 'Success: Account credit added!';
$_['text_credit_remove']                      = 'Remove Credit';
$_['text_credit_removed']                     = 'Success: Account credit removed!';
$_['text_upload']                             = 'Your file was successfully uploaded!';
$_['text_free_mail']                          = 'Free Mail:<br /><span class="help">Whether e-mail is from free e-mail provider (free e-mail = higher risk).</span>';
$_['text_carder_email']                       = 'Carder Email:<br /><span class="help">Whether e-mail is in database of high risk e-mails.</span>';
$_['text_high_risk_username']                 = 'High Risk Username:<br /><span class="help">Whether usernameMD5 input is in database of high risk usernames. Only returned if usernameMD5 is included in inputs.</span>';
$_['text_high_risk_password']                 = 'High Risk Password:<br /><span class="help">Whether passwordMD5 input is in database of high risk passwords. Only returned if passwordMD5 is included in inputs.</span>';
$_['text_bin_match']                          = 'Bin Match:<br /><span class="help">Whether country of issuing bank based on BIN number matches billing address country.</span>';
$_['text_bin_country']                        = 'Bin Country:<br /><span class="help">Country Code of the bank which issued the credit card based on BIN number.</span>';
$_['text_bin_name_match']                     = 'Bin Name Match:<br /><span class="help">Whether name of issuing bank matches inputted  BIN name. A return value of Yes provides a positive indication that cardholder is in possession of credit card.</span>';
$_['text_bin_name']                           = 'Bin Name:<br /><span class="help">Name of the bank which issued the credit card based on BIN number. Available for approximately 96% of BIN numbers.</span>';
$_['text_bin_phone_match']                    = 'Bin Phone Match:<br /><span class="help">Whether customer service phone number matches inputed BIN Phone. A return value of Yes provides a positive indication that cardholder is in possession of credit card.</span>';
$_['text_bin_phone']                          = 'Bin Phone:<br /><span class="help">Customer service phone number listed on back of credit card. Available for approximately 75% of BIN numbers. In some cases phone number returned may be outdated.</span>';
$_['text_customer_phone_in_billing_location'] = 'Customer Phone Number in Billing Location:<br /><span class="help">Whether the customer phone number is in the billing zip code. A return value of Yes provides a positive indication that the phone number listed belongs to the cardholder. A return value of No indicates that the phone number may be in a different area, or may not be listed in our database. NotFound is returned when the phone number prefix cannot be found in our database at all. Currently we only support US Phone numbers.</span>';
$_['text_ship_forward']                       = 'Shipping Forward:<br /><span class="help">Whether shipping address is in database of known mail drops.</span>';
$_['text_city_postal_match']                  = 'City Postal Match:<br /><span class="help">Whether billing city and state match zipcode. Currently available for US addresses only, returns empty string outside the US.</span>';
$_['text_ship_city_postal_match']             = 'Shipping City Postal Match:<br /><span class="help">Whether shipping city and state match zipcode. Currently available for US addresses only, returns empty string outside the US.</span>';
$_['text_score']                              = 'Score:<br /><span class="help">Overall fraud score based on outputs listed above. This is the original fraud score, and is based on a simple formula. It has been replaced with risk score (see below), but is kept for backwards compatibility.</span>';
$_['text_explanation']                        = 'Explanation:<br /><span class="help">A brief explanation of the score, detailing what factors contributed to it, according to our formula. Please note this corresponds to the score, not the riskScore.</span>';
$_['text_risk_score']                         = 'Risk Score:<br /><span class="help">New fraud score representing the estimated probability that the invoice is fraud, based off of analysis of past minFraud transactions. Requires an upgrade for clients who signed up before February 2007.</span>';
$_['text_queries_remaining']                  = 'Queries Remaining:<br /><span class="help">Number of queries remaining in your account, can be used to alert you when you may need to add more queries to your account.</span>';
$_['text_maxmind_id']                         = 'Maxmind ID:<br /><span class="help">Unique identifier, used to reference transactions when reporting fraudulent activity back to MaxMind. This reporting will help MaxMind improve its service to you and will enable a planned feature to customize the fraud scoring formula based on your chargeback history.</span>';
$_['text_error']                              = 'Error:<br /><span class="help">Returns an error string with a warning message or a reason why the request failed.</span>';
$_['text_success_email']                      = 'Success: The email has been sent without problems!';

// Column
$_['column_invoice_id']                       = 'Invoice ID';
$_['column_customer']                         = 'Customer';
$_['column_status']                           = 'Status';
$_['column_date_added']                       = 'Date Added';
$_['column_date_modified']                    = 'Date Modified';
$_['column_total']                            = 'Total';
$_['column_product']                          = 'Product';
$_['column_model']                            = 'Model';
$_['column_quantity']                         = 'Quantity';
$_['column_price']                            = 'Unit Price';
$_['column_filename']                         = 'Filename';
$_['column_remaining']                        = 'Remaining Downloads';
$_['column_comment']                          = 'Comment';
$_['column_notify']                           = 'Customer Notified';
$_['column_action']                           = 'Action';
$_['entry_vat']                               = 'VAT number:';

// Entry
$_['entry_store']                             = 'Store:';
$_['entry_customer']                          = 'Customer:';
$_['entry_customer_group']                    = 'Group:';
$_['entry_email']                             = 'E-Mail:';
$_['entry_telephone']                         = 'Telephone:';
$_['entry_fax']                               = 'Fax:';
$_['entry_address']                           = 'Choose Address:';
$_['entry_company']                           = 'Company:';
$_['entry_company_id']                        = 'Company ID:';
$_['entry_tax_id']                            = 'Tax ID:';
$_['entry_address_1']                         = 'Address 1:';
$_['entry_address_2']                         = 'Address 2:';
$_['entry_city']                              = 'City:';
$_['entry_postcode']                          = 'Postcode:';
$_['entry_country']                           = 'Country:';
$_['entry_zone']                              = 'Region / State:';
$_['entry_zone_code']                         = 'Region / State Code:';
$_['entry_product']                           = 'Choose Product:';
$_['entry_option']                            = 'Choose Option(s):';
$_['entry_quantity']                          = 'Quantity:';
$_['entry_to_name']                           = 'Recipient\'s Name:';
$_['entry_to_email']                          = 'Recipient\'s Email:';
$_['entry_from_name']                         = 'Senders Name:';
$_['entry_from_email']                        = 'Senders Email:';
$_['entry_theme']                             = 'Gift Certificate Theme:';
$_['entry_message']                           = 'Message:';
$_['entry_amount']                            = 'Amount:';
$_['entry_invoice_status']                      = 'Invoice Status:';
$_['entry_notify']                            = 'Notify Customer:';
$_['entry_comment']                           = 'Comment:';
$_['entry_shipping']                          = 'Shipping Method:';
$_['entry_payment']                           = 'Payment Method:';
$_['entry_coupon']                            = 'Coupon:';
$_['entry_voucher']                           = 'Voucher:';
$_['entry_name_ext']          				  = 'Descripcion:';
$_['entry_price']             				  = 'Nuevo Precio:';
$_['error_required']    = '%s required!';

//add
$_['tab_invoice']             				  = 'Invoice';

// Error
$_['error_warning']                           = 'Warning: Please check the form carefully for errors!';
$_['error_permission']                        = 'Warning: You do not have permission to modify invoices!';
$_['error_email']                             = 'E-Mail Address does not appear to be valid!';
$_['error_telephone']                         = 'Telephone must be between 3 and 32 characters!';
$_['error_password']                          = 'Password must be between 3 and 20 characters!';
$_['error_confirm']                           = 'Password and password confirmation do not match!';
$_['error_company_id']                        = 'Company ID required!';
$_['error_tax_id']                            = 'Tax ID required!';
$_['error_vat']                               = 'VAT number is invalid!';
$_['error_address_1']                         = 'Address 1 must be between 3 and 128 characters!';
$_['error_city']                              = 'City must be between 3 and 128 characters!';
$_['error_postcode']                          = 'Postcode must be between 2 and 10 characters for this country!';
$_['error_country']                           = 'Please select a country!';
$_['error_zone']                              = 'Please select a region / state!';
$_['error_shipping']                          = 'Warning: Shipping method required!';
$_['error_payment']                           = 'Warning: Payment method required!';
$_['error_upload']                            = 'Upload required!';
$_['error_filename']                          = 'Filename must be between 3 and 128 characters!';
$_['error_filetype']                          = 'Invalid file type!';
$_['error_action']                            = 'Warning: Could not complete this action!';

$_['error_to']                                = 'E-mail to does no seem to be valid!';
$_['error_subject']                           = 'Subject can not be empty!';
$_['error_message']                           = 'Message can not be empty!';

$_['button_invoice'] 						  = 'Print Invoice';

?>