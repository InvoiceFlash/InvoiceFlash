<?php
// Heading
$_['heading_title']              = 'FACe';
$_['heading_setting']            = 'Ajustes de FACe';

// Text
$_['text_home']                  = 'Inicio';
$_['text_success_setting']       = 'Ajustes de FACe guardados.';
$_['text_success_sent']          = 'Factura enviada a FACe. N&uacute;mero de registro: %s.';
$_['text_success_refresh']       = 'Estado actualizado desde FACe.';
$_['text_success_refresh_all']   = 'Se han actualizado %d facturas.';
$_['text_success_cancel']        = 'Solicitud de anulaci&oacute;n enviada a FACe. La acepta o rechaza la oficina contable.';
$_['text_connection_ok']         = 'Conexi&oacute;n correcta con FACe: certificado aceptado, %d estados de factura disponibles.';
$_['text_no_results']            = 'Todav&iacute;a no se ha enviado ninguna factura a FACe.';
$_['text_all']                   = 'Todos';
$_['text_yes']                   = 'S&iacute;';
$_['text_no']                    = 'No';
$_['text_test']                  = 'Pruebas';
$_['text_production']            = 'Producci&oacute;n';
$_['text_inactive']              = 'FACe est&aacute; desactivado en sus ajustes: act&iacute;velo para poder enviar facturas.';
$_['text_status_pending']        = 'Registrada (pendiente de consultar)';
$_['text_status_error']          = 'Error de env&iacute;o';
$_['text_status_1200']           = 'Registrada';
$_['text_status_1300']           = 'Registrada en RCF';
$_['text_status_2400']           = 'Contabilizada la obligaci&oacute;n';
$_['text_status_2500']           = 'Pagada';
$_['text_status_2600']           = 'Rechazada';
$_['text_status_3100']           = 'Anulada';
$_['text_confirm_send']          = '&iquest;Enviar esta factura a FACe? Una vez registrada, solo se puede corregir solicitando su anulaci&oacute;n.';
$_['text_confirm_resend']        = '&iquest;Enviar de nuevo esta factura a FACe?';
$_['text_confirm_cancel']        = '&iquest;Solicitar a FACe la anulaci&oacute;n de esta factura?';
$_['text_prompt_reason']         = 'Motivo de la anulaci&oacute;n (obligatorio):';
$_['text_certificate_note']      = 'Se usa el mismo certificado (.p12/.pfx) y contrase&ntilde;a con el que se firma la factura Facturae, en Sistema &gt; Ajustes. Ese certificado tiene que estar dado de alta como certificado del proveedor en el portal de FACe.';
$_['text_email_note']            = 'Correo del proveedor al que FACe env&iacute;a los avisos de cada factura.';
$_['text_dir3_note']             = 'Cada cliente p&uacute;blico necesita en su ficha los c&oacute;digos DIR3 de oficina contable, &oacute;rgano gestor y unidad tramitadora; si faltan, FACe rechaza la factura.';
$_['text_requirements_ok']       = 'El servidor tiene lo necesario para firmar y enviar a FACe.';
$_['text_pagination']            = 'Mostrando {start} a {end} de {total} ({pages} P&aacute;ginas)';

// Column
$_['column_invoice']             = 'Factura';
$_['column_customer']            = 'Cliente';
$_['column_date']                = 'Fecha';
$_['column_total']               = 'Total';
$_['column_registry']            = 'N&ordm; de registro';
$_['column_environment']         = 'Entorno';
$_['column_status']              = 'Estado';
$_['column_action']              = 'Acci&oacute;n';

// Entry
$_['entry_status']               = 'Estado';
$_['entry_active']               = 'Enviar a FACe';
$_['entry_environment']          = 'Entorno';
$_['entry_email']                = 'Correo del proveedor';

// Button
$_['button_setting']             = 'Ajustes';
$_['button_refresh']             = 'Consultar estado';
$_['button_refresh_all']         = 'Actualizar estados';
$_['button_resend']              = 'Reenviar';
$_['button_cancel_invoice']      = 'Anular';
$_['button_xml']                 = 'XML';
$_['button_filter']              = 'Filtrar';
$_['button_save']                = 'Guardar';
$_['button_cancel']              = 'Volver';
$_['button_test']                = 'Probar conexi&oacute;n';
$_['button_send']                = 'Enviar a FACe';

// Error
$_['error_permission']           = 'Advertencia: &iexcl;No tiene permiso para modificar FACe!';
$_['error_warning']              = 'Advertencia: revise los campos marcados.';
$_['error_email']                = 'Indique un correo electr&oacute;nico v&aacute;lido.';
$_['error_extension']            = 'Falta la extensi&oacute;n de PHP %s, necesaria para enviar a FACe.';
$_['error_library']              = 'Falta el fichero system/library/face.php.';
$_['error_certificate']          = 'Suba el certificado digital y establezca su contrase&ntilde;a en Sistema &gt; Ajustes antes de enviar a FACe.';
$_['error_already_sent']         = 'La factura ya est&aacute; registrada en FACe con el n&uacute;mero %s. Para corregirla, solicite su anulaci&oacute;n y emita otra.';
$_['error_not_registered']       = 'La factura no est&aacute; registrada en FACe.';
$_['error_reason']               = 'Indique el motivo de la anulaci&oacute;n.';
$_['error_inactive']             = 'FACe est&aacute; desactivado en Ventas &gt; FACe &gt; Ajustes.';
$_['error_no_dir3']              = 'El cliente no tiene c&oacute;digos DIR3 (oficina contable, &oacute;rgano gestor y unidad tramitadora) en su ficha: FACe rechazar&iacute;a la factura.';
$_['error_invoice']              = 'La factura no existe.';
