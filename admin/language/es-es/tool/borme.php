<?php
// Heading
$_['heading_title']         = 'BORME - Empresas de nueva creación';

// Text
$_['text_home']              = 'Inicio';
$_['text_instruction']       = 'Busca en el BORME las sociedades de nueva constitución publicadas 7 días antes de hoy en la provincia indicada, y para cada una intenta localizar su web y un email de contacto mediante el API de Claude. El proceso corre en segundo plano; puedes cerrar esta pantalla y volver más tarde.';
$_['text_started']           = 'Proceso lanzado en segundo plano.';
$_['text_status_found']      = 'Encontrado';
$_['text_status_not_found']  = 'No encontrado';
$_['text_no_results']        = 'Todavía no hay resultados.';
$_['text_pagination']        = 'Mostrando %d a %d de %d (%d páginas)';
$_['text_edit_email']        = 'Añadir email manualmente';
$_['text_to']                = 'Para';
$_['text_subject']           = 'Asunto';
$_['text_message']           = 'Mensaje';
$_['text_success_email']     = 'Correo enviado correctamente.';

// Entry
$_['entry_date']              = 'Fecha BORME';
$_['entry_province']         = 'Provincia';
$_['entry_max_emails']       = 'Parar al encontrar (nº de emails)';
$_['entry_email']            = 'Email';
$_['entry_website']          = 'Web';

// Column
$_['column_date']            = 'Fecha BORME';
$_['column_province']        = 'Provincia';
$_['column_company']         = 'Empresa';
$_['column_city']            = 'Localidad';
$_['column_website']         = 'Web';
$_['column_email']           = 'Email';
$_['column_status']          = 'Estado';
$_['column_action']          = 'Acción';

// Button
$_['button_run']             = 'Lanzar';
$_['button_save']            = 'Guardar';
$_['button_close']           = 'Cerrar';
$_['button_email']           = 'E-mail';
$_['button_send']            = 'Enviar';

// Error
$_['error_permission']       = 'No tiene permiso para modificar este módulo.';
$_['error_no_api_key']       = 'No hay ninguna API key de Claude configurada (Ajustes > IA). Configúrala antes de lanzar el módulo.';
$_['error_already_running']  = 'Ya hay un proceso en marcha. Espera a que termine antes de lanzar otro.';
$_['error_launch']           = 'No se ha podido lanzar el proceso (no se encuentra el intérprete de Python).';
$_['error_invalid_email']    = 'Introduce un email válido.';
$_['error_to']               = 'Introduce un email de destino válido.';
$_['error_subject']          = 'Introduce un asunto.';
$_['error_message']          = 'Introduce un mensaje.';
