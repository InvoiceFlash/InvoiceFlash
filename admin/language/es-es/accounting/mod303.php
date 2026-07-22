<?php
// Heading
$_['heading_title'] = 'Modelo 303';

// Column
$_['column_code']       = 'Casilla';
$_['column_name']       = 'Concepto';
$_['column_order_code'] = 'Orden';
$_['column_action']     = 'Acci&oacute;n';

// Entry
$_['entry_code']       = 'Casilla:';
$_['entry_order_code'] = 'C&oacute;digo de orden:';
$_['entry_name']       = 'Concepto:';
$_['entry_accounts']   = 'Cuentas / F&oacute;rmula:';
$_['entry_level']      = 'Destacado:';
$_['entry_list_after'] = 'Listar despu&eacute;s de:';

// Help
$_['help_accounts'] = 'Cuentas contables separadas por espacio (suma Haber - Debe) para casillas de cuota devengada (477), o con un "-" delante para invertir el signo (Debe - Haber, para cuentas de IVA soportado 472). Tambi&eacute;n admite f&oacute;rmula referenciando otras casillas con el prefijo C (ej. C03+C06+C09, o C03/0.04 para calcular una base a partir de una cuota).';
$_['help_level']    = '0 = l&iacute;nea normal, 1 = l&iacute;nea destacada en negrita (totales/resultado).';

// Text
$_['text_success']    = '&iexcl;Guardado correctamente!';
$_['text_no_results'] = 'No se han encontrado resultados.';
$_['text_edit']       = 'Editar';

// Button
$_['button_new']    = 'Nuevo';
$_['button_save']   = 'Guardar';
$_['button_cancel'] = 'Cancelar';
$_['button_delete'] = 'Eliminar';

// Error
$_['error_permission']  = '&iexcl;Aviso: no tiene permiso para modificar la configuraci&oacute;n del Modelo 303!';
$_['error_code']        = '&iexcl;Aviso: la casilla debe tener entre 1 y 12 caracteres!';
$_['error_code_exists'] = '&iexcl;Aviso: ya existe una l&iacute;nea con esa casilla!';
$_['error_name']        = '&iexcl;Aviso: el concepto debe tener entre 1 y 255 caracteres!';
