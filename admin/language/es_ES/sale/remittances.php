<?php
// Heading
$_['heading_title']                           = 'Remesas';

// Text
$_['text_success']                            = '&Eacute;xito: &iexcl;Ha modificado las remesas!';
$_['text_select']                             = 'Por favor, seleccione una remesa';
$_['text_select_one']                         = 'Solo puede seleccionar una remesa';
$_['text_remittance_id']                      = 'ID Remesa:';
$_['text_bank']                               = 'Banco:';
$_['text_iban']                               = 'IBAN:';
$_['text_bic']                                = 'BIC:';
$_['text_telephone']                          = 'Tel&eacute;fono:';
$_['text_date']                               = 'Fecha:';

//Column
$_['column_remittance_id']                    = 'ID Remesa';
$_['column_customer_id']                      = 'ID Cliente';
$_['column_customer']                         = 'Cliente';
$_['column_status']                           = 'Estado';
$_['column_date_added']                       = 'Fecha de Alta';
$_['column_date_modified']                    = 'Fecha de Modificaci&oacute;n';
$_['column_total']                            = 'Total';
$_['column_bank_cc']                          = 'Cuenta Bancaria';
$_['column_amount']                           = 'Importe';
$_['column_date_due']                         = 'Fecha de Vencimiento';
$_['column_invoice_no']                       = 'N&ordm; de Factura';
$_['column_action']							  = 'Acci&oacute;n';

$_['heading_title_lines']					  = 'L&iacute;neas de la Remesa';
$_['column_remittance']					  	  = 'ID Recibo';
$_['column_amount']							  = 'Importe';
$_['column_date']							  = 'Fecha';

//Button
$_['button_generate']                         = 'SEPA SDD';
$_['button_remittance']						  = 'Imprimir Remesa';

// SEPA
$_['heading_sepa_errors']        = 'No se pudo generar el fichero SEPA';
$_['text_sepa_errors_intro']     = 'Corrige lo siguiente y vuelve a intentarlo. No se ha generado ning&uacute;n fichero.';
$_['button_sepa_back']           = 'Volver a remesas';
$_['error_sepa_permission']      = 'No tienes permiso para generar ficheros SEPA.';
$_['error_sepa_select']          = 'Selecciona al menos una remesa existente.';
$_['error_sepa_empty']           = 'Las remesas seleccionadas no tienen recibos.';
$_['error_sepa_creditor_id']     = 'Falta el identificador de acreedor SEPA (Ajustes &gt; Banco).';
$_['error_sepa_creditor_iban']   = 'El IBAN del banco por defecto de tu empresa falta o no es v&aacute;lido (Ajustes &gt; Banco).';
$_['error_sepa_line_customer']   = 'Recibo %s: no se encuentra el cliente de la factura.';
$_['error_sepa_line_iban']       = 'Recibo %s: el IBAN del cliente falta o no es v&aacute;lido (ficha del cliente).';
$_['error_sepa_line_bic']        = 'Recibo %s: el BIC del cliente no es v&aacute;lido (d&eacute;jalo vac&iacute;o si no lo conoces).';
$_['error_sepa_line_amount']     = 'Recibo %s: el importe debe ser mayor que cero.';
$_['error_sepa_line_currency']   = 'Recibo %s: los adeudos SEPA solo admiten euros.';

?>
