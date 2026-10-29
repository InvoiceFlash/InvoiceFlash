<?php
// Heading
$_['heading_title']             = 'Agentes';

// Text
$_['text_success']              = '&Eacute;xito: &iexcl;ha modificado los agentes!';
$_['text_list']                 = 'Lista de agentes';
$_['text_show_all']             = 'Mostrar tambi&eacute;n los agentes inactivos';
$_['text_show_active']          = 'Mostrar solo los agentes activos';
$_['text_cron_note']            = 'Los agentes usan la IA configurada en Ajustes &gt; IA. Se lanzan desde la tarea programada &laquo;agents&raquo; (Ajustes &gt; Cron), que debe ejecutarse cada minuto.';
$_['text_schedule_time']        = 'Cada d&iacute;a a las %s';
$_['text_schedule_always']      = 'Siempre en ejecuci&oacute;n (cada minuto)';
$_['text_schedule_time_opt']    = 'A una hora determinada (una vez al d&iacute;a)';
$_['text_schedule_always_opt']  = 'Siempre en ejecuci&oacute;n';
$_['text_help_description']     = 'Describa con el detalle que quiera qu&eacute; actividad realiza este agente. La IA seguir&aacute; estas instrucciones, consultando y, si hace falta, modificando datos de la base de datos. No hay l&iacute;mite de longitud.';
$_['text_help_always']          = 'Un agente &laquo;siempre en ejecuci&oacute;n&raquo; se lanza en cada pasada del cron (cada minuto) y cada ejecuci&oacute;n consume llamadas a la IA.';
$_['text_run_confirm']          = '&iquest;Ejecutar el agente ahora? Puede tardar un par de minutos y consume llamadas a la IA.';
$_['text_run_ok']               = 'Agente ejecutado. Consulte el historial para ver el resultado.';
$_['text_run_error']            = 'La ejecuci&oacute;n del agente ha fallado.';
$_['text_runs_title']           = '&Uacute;ltimas ejecuciones';
$_['text_no_runs']              = 'Este agente todav&iacute;a no se ha ejecutado.';
$_['button_stop']               = 'PARAR (antip&aacute;nico)';
$_['text_stop_ok']              = 'Parada solicitada: el agente se detiene en cuanto termine el paso actual (como mucho unos segundos) y queda desactivado. Para reactivarlo, ed&iacute;telo (&laquo;Mostrar todos&raquo; si no lo ve).';
$_['text_stop_idle']            = 'El agente no estaba ejecut&aacute;ndose; queda desactivado para que no se lance.';
$_['text_run_status_stopped']   = 'Detenido';
$_['text_run_status_stopping']  = 'Deteniendo';
$_['entry_web_access']          = 'Internet:';
$_['text_web_access']           = 'Puede buscar en Internet';
$_['text_help_web_access']      = 'Solo con Claude o DeepSeek. Lo que encuentre se trata como datos, no como instrucciones, y las b&uacute;squedas quedan en el log. M&aacute;ximo 5 por consulta.';
$_['error_web_access']          = 'La b&uacute;squeda en Internet solo est&aacute; disponible con Claude o DeepSeek.';
$_['text_web_badge']            = 'Internet';
$_['entry_ai_provider']         = 'IA a utilizar:';
$_['text_help_ai_provider']     = 'Solo aparecen las IA que tienen API KEY en Ajustes &gt; IA.';
$_['text_no_key_suffix']        = 'sin API KEY';
$_['error_ai_provider']         = 'Elija una IA que tenga API KEY en Ajustes &gt; IA.';
$_['text_email_sent']          = 'Correo enviado';
$_['text_success_runs']         = '&Eacute;xito: &iexcl;ha borrado el log!';
$_['text_confirm_delete_runs']  = '&iquest;Borrar las entradas marcadas del log?';
$_['text_confirm_clear_runs']   = '&iquest;Vaciar todo el log de este agente? No se puede deshacer.';
$_['button_clear_log']          = 'Vaciar log';
$_['error_no_runs_selected']    = 'Aviso: marque al menos una entrada del log.';
$_['text_no_changes']           = 'Sin cambios en la base de datos';
$_['text_run_status_ok']        = 'Correcta';
$_['text_run_status_error']     = 'Error';
$_['text_run_status_running']   = 'En curso';
$_['text_trigger_cron']         = 'Programada';
$_['text_trigger_manual']       = 'Manual';

$_['text_access_read']          = 'Solo lectura';
$_['text_access_write']         = 'Lectura y escritura';
$_['text_help_access']          = 'Un agente de solo lectura consulta datos pero no puede cambiar nada. Uno de lectura y escritura puede insertar, modificar y borrar registros (con l&iacute;mites; todo queda en el log de acciones).';

// Column
$_['column_access']             = 'Acceso';
$_['column_model']              = 'IA / Modelo';
$_['column_name']               = 'Nombre';
$_['column_description']        = 'Actividad';
$_['column_schedule']           = 'Ejecuci&oacute;n';
$_['column_status']             = 'Estado';
$_['column_last_run']           = '&Uacute;ltima ejecuci&oacute;n';
$_['column_last_result']        = '&Uacute;ltimo resultado';
$_['column_action']             = 'Acci&oacute;n';
$_['column_started']            = 'Inicio';
$_['column_finished']           = 'Fin';
$_['column_run_status']         = 'Estado';
$_['column_trigger']            = 'Origen';
$_['column_summary']            = 'Resumen';
$_['column_changes']            = 'Cambios en la base de datos';

// Entry
$_['entry_access']              = 'Acceso a la base de datos:';
$_['entry_name']                = 'Nombre:';
$_['entry_description']         = 'Actividad que realiza:';
$_['entry_schedule']            = 'Ejecuci&oacute;n:';
$_['entry_run_time']            = 'Hora:';
$_['entry_status']              = 'Estado:';

// Button
$_['button_run']                = 'Ejecutar ahora';
$_['button_history']            = 'Log de acciones';
$_['button_edit']               = 'Editar';

// Error
$_['error_permission']          = 'Aviso: &iexcl;No tiene permiso para modificar los agentes!';
$_['error_no_api_key']          = 'Hay que poner la API KEY de la IA en Ajustes &gt; IA (y tener activado &laquo;Usar IA&raquo;) para poder crear y ejecutar agentes.';
$_['error_name']                = 'Aviso: &iexcl;El nombre debe tener entre 3 y 128 caracteres!';
$_['error_description']         = 'Aviso: &iexcl;Debe describir la actividad del agente!';
$_['error_run_time']            = 'Aviso: &iexcl;Indique una hora v&aacute;lida (HH:MM)!';
$_['error_not_found']           = 'No existe ese agente.';
$_['error_running']             = 'Este agente ya se est&aacute; ejecutando.';
