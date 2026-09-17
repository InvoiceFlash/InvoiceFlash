<?php
// Heading
$_['heading_title']            = 'Proveedores';

// Text
$_['text_success']             = '&Eacute;xito: &iexcl;Ha modificado los proveedores!';
$_['text_no_results']          = '&iexcl;Sin resultados!';
$_['text_select']              = '--- Por favor, seleccione ---';
$_['text_none']                = '--- Ninguno ---';
$_['text_enabled']             = 'Activado';
$_['text_disabled']            = 'Desactivado';

// Column
$_['column_company']           = 'Empresa';
$_['column_name']               = 'Nombre de Contacto';
$_['column_email']             = 'E-Mail';
$_['column_telephone']         = 'Tel&eacute;fono';
$_['column_status']            = 'Estado';
$_['column_date_added']        = 'Fecha de Alta';
$_['column_action']            = 'Acci&oacute;n';
$_['column_contact_name']      = 'Nombre';
$_['column_contact_email']     = 'Email';
$_['column_comment']           = 'Comentario';
$_['column_user']              = 'Usuario';
$_['column_date']              = 'Fecha';

// Entry
$_['entry_firstname']          = 'Nombre:';
$_['entry_lastname']           = 'Apellidos:';
$_['entry_company']            = 'Empresa:';
$_['entry_company_id']         = 'ID de Empresa:';
$_['entry_tax_id']             = 'CIF/NIF:';
$_['entry_contable_account']   = 'Cuenta Contable:';
$_['entry_email']              = 'E-Mail:';
$_['entry_telephone']          = 'Tel&eacute;fono:';
$_['entry_fax']                = 'Fax:';
$_['entry_web']                = 'Web:';
$_['button_web']               = 'Ir a la web';
$_['error_web']                = 'Error: &iexcl;Introduzca una URL v&aacute;lida!';
$_['entry_address_1']          = 'Direcci&oacute;n:';
$_['entry_address_2']          = 'Direcci&oacute;n 2:';
$_['entry_city']               = 'Ciudad:';
$_['entry_postcode']           = 'C&oacute;digo Postal:';
$_['entry_country']            = 'Pa&iacute;s:';
$_['entry_zone']               = 'Regi&oacute;n / Estado:';
$_['entry_comment']            = 'Comentario:';
$_['entry_status']             = 'Estado:';

// Button
$_['button_insert']            = 'A&ntilde;adir Nuevo';
$_['button_delete']            = 'Eliminar';
$_['button_filter']            = 'Filtro';
$_['button_save']              = 'Guardar';
$_['button_cancel']            = 'Cancelar';
$_['button_add_note']          = 'A&ntilde;adir Nota';

// Tab
$_['tab_general']              = 'General';
$_['tab_notes']                 = 'Notas';
$_['tab_contacts']              = 'Contactos';
$_['tab_contracts']             = 'Documentos';
$_['tab_products']             = 'Productos';
$_['tab_invoices']             = 'Facturas';

// Products tab
$_['column_product_id']        = 'ID';
$_['column_product_name']      = 'Producto';
$_['column_invoice']           = 'Factura';
$_['column_invoice_date']      = 'Fecha';
$_['column_total']             = 'Total';

// Notes
$_['heading_title_note']       = 'Nota';
$_['entry_user']                = 'Usuario:';
$_['entry_date_note']           = 'Fecha:';

// Contacts
$_['button_add_contact']       = 'A&ntilde;adir Contacto';
$_['heading_contact']          = 'Contacto del Proveedor';
$_['entry_name']                = 'Nombre:';
$_['entry_telephone2']          = 'Tel&eacute;fono 2:';
$_['entry_puesto']              = 'Cargo:';
$_['entry_notas']                = 'Notas:';
$_['text_delete']               = 'Eliminar';
$_['text_edit']                 = 'Editar';

// Documents (antes "Contracts" - mismo patron que sale/customer.php)
$_['heading_title_contract']   = 'Documentos';
$_['column_filename']          = 'Nombre de Archivo';
$_['button_add_contract']      = 'A&ntilde;adir Documento';
$_['entry_document']           = 'Documento (PDF o XLSX):';
$_['button_upload']            = 'Subir';
$_['button_view']              = 'Ver';
$_['text_no_documents']        = 'No hay documentos adjuntos';
$_['error_upload']             = 'Advertencia: &iexcl;Debe seleccionar un archivo!';
$_['error_document_type']      = 'Advertencia: &iexcl;Solo se permiten archivos PDF o XLSX!';
$_['error_document_upload']    = 'Advertencia: &iexcl;No se pudo subir el archivo!';

// Emails (mismo patron que sale/customer.php)
$_['tab_email']                 = 'Emails';
$_['column_email_subject']      = 'Asunto';
$_['column_email_sender']       = 'Remitente';
$_['text_to']                   = 'Para:';
$_['text_subject']              = 'Asunto:';
$_['text_message']              = 'Mensaje:';
$_['button_new_email']          = 'Nuevo Email';
$_['button_send']               = 'Enviar';
$_['text_success_email']        = 'Correo enviado correctamente al proveedor';
$_['error_to']                  = '&iexcl;El email de destino no es v&aacute;lido!';
$_['error_subject']             = '&iexcl;El asunto no puede estar vac&iacute;o!';
$_['error_message']             = '&iexcl;El mensaje no puede estar vac&iacute;o!';
$_['error_permission_email']    = 'Advertencia: &iexcl;No tiene permiso para enviar emails!';

// Pedidos / Recepciones (mismo patron que sale/customer.php: reutiliza purchase_order,
// "Recepciones" filtra a los ya recibidos - ver ModelPurchaseSupplier)
$_['tab_orders']                = 'Pedidos';
$_['tab_recepciones']           = 'Recepciones';
$_['column_order']              = 'N&ordm; Pedido';
$_['column_quantity']           = 'Cantidad';

// Error
$_['error_warning']            = 'Advertencia: &iexcl;Por favor, revise el formulario cuidadosamente en busca de errores!';
$_['error_permission']         = 'Advertencia: &iexcl;No tiene permiso para modificar proveedores!';
$_['error_company']            = '&iexcl;La Empresa debe tener entre 1 y 92 caracteres!';
$_['error_email']              = '&iexcl;La direcci&oacute;n de E-Mail no parece ser v&aacute;lida!';
$_['error_contact_name']       = 'Advertencia: &iexcl;El campo Nombre debe tener entre 3 y 50 caracteres!';
?>
