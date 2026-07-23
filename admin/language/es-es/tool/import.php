<?php
// Heading
$_['heading_title']       = 'Importador';

// Text
$_['text_home']           = 'Inicio';
$_['text_form']           = 'Importar';
$_['text_success']        = 'Éxito: %d registro(s) importado(s), %d actualizado(s)!';
$_['text_success_saconta'] = 'Éxito: %d cuentas del plan contable, %d subcuentas (%d clientes nuevos) y %d líneas de asientos importadas!';
$_['text_example']        = 'Ejemplo de fichero Excel';
$_['text_example_help']   = 'La primera fila debe ser la cabecera y será ignorada. Las columnas deben seguir este orden exacto:';
$_['text_saconta_help']   = 'Ruta, en este mismo servidor, a la carpeta del ejercicio de SaConta (debe contener ctab6.dbf, ctab61.dbf y ctab8.dbf). Se rellenarán el Plan Contable, las Subcuentas (creando también los clientes de las cuentas 430) y el Libro Diario.';
$_['text_type_product']   = 'Productos';
$_['text_type_customer']  = 'Clientes';
$_['text_type_supplier']  = 'Proveedores';
$_['text_type_saconta']   = 'FLASH SaConta';

// Entry
$_['entry_type']          = 'Tipo de Importación:';
$_['entry_file']          = 'Fichero Excel:';
$_['entry_path']          = 'Path ejercicio:';
$_['entry_related_history_code'] = 'C&oacute;digo de Hist&oacute;rico Relacionado:';

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

// Error
$_['error_permission']    = 'Aviso: No tiene permiso para importar!';
$_['error_upload']        = 'Aviso: Debe seleccionar un fichero para subir!';
$_['error_extension']     = 'Aviso: El fichero debe ser un Excel .xlsx!';
$_['error_file']          = 'Aviso: No se pudo leer el fichero Excel subido!';
$_['error_row']           = 'Fila %d: %s';
$_['error_required']      = 'Código de Artículo y Descripción son obligatorios';
$_['error_required_customer'] = 'Empresa y Email son obligatorios';
$_['error_path']          = 'Aviso: Debe indicar una ruta de carpeta válida y accesible en el servidor!';
$_['error_saconta_file']  = 'Aviso: No se encontró %s en esa carpeta!';
?>
