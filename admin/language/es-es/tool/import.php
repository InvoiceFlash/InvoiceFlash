<?php
// Heading
$_['heading_title']       = 'Importador';

// Text
$_['text_home']           = 'Inicio';
$_['text_form']           = 'Importar';
$_['text_success']        = 'Éxito: %d registro(s) importado(s), %d actualizado(s)!';
$_['text_example']        = 'Ejemplo de fichero Excel';
$_['text_example_help']   = 'La primera fila debe ser la cabecera y será ignorada. Las columnas deben seguir este orden exacto:';
$_['text_type_product']   = 'Productos';
$_['text_type_customer']  = 'Clientes';
$_['text_type_supplier']  = 'Proveedores (próximamente)';

// Entry
$_['entry_type']          = 'Tipo de Importación:';
$_['entry_file']          = 'Fichero Excel:';

// Column (productos)
$_['column_code']         = 'Código Artículo';
$_['column_description']  = 'Descripción';
$_['column_price']        = 'Precio';
$_['column_quantity']     = 'Cantidad';
$_['column_status']       = 'Estado (1 = Habilitado, 0 = Deshabilitado)';

// Column (clientes)
$_['column_company']      = 'Empresa';
$_['column_nif']          = 'NIF/CIF';
$_['column_email']        = 'Email';
$_['column_telephone']    = 'Teléfono';
$_['column_address']      = 'Dirección';
$_['column_city']         = 'Ciudad';
$_['column_postcode']     = 'Código Postal';
$_['column_country']      = 'País';

// Button
$_['button_import']       = 'Importar';
$_['button_template']     = 'Descargar Plantilla';

// Error
$_['error_permission']    = 'Aviso: No tiene permiso para importar!';
$_['error_upload']        = 'Aviso: Debe seleccionar un fichero para subir!';
$_['error_extension']     = 'Aviso: El fichero debe ser un Excel .xlsx!';
$_['error_file']          = 'Aviso: No se pudo leer el fichero Excel subido!';
$_['error_row']           = 'Fila %d: %s';
$_['error_required']      = 'Código de Artículo y Descripción son obligatorios';
$_['error_required_customer'] = 'Empresa y Email son obligatorios';
?>
