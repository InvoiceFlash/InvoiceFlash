<?php
// Heading
$_['heading_title']             = 'Agents';

// Text
$_['text_success']              = 'Success: you have modified the agents!';
$_['text_list']                 = 'Agent list';
$_['text_show_all']             = 'Also show inactive agents';
$_['text_show_active']          = 'Show active agents only';
$_['text_cron_note']            = 'Agents use the AI configured in Settings &gt; AI. They are launched by the scheduled task "agents" (Settings &gt; Cron), which must run every minute.';
$_['text_schedule_time']        = 'Every day at %s';
$_['text_schedule_always']      = 'Always running (every minute)';
$_['text_schedule_time_opt']    = 'At a given time (once a day)';
$_['text_schedule_always_opt']  = 'Always running';
$_['text_help_description']     = 'Describe in as much detail as you like the activity this agent performs. The AI will follow these instructions, querying and, if needed, changing data in the database. There is no length limit.';
$_['text_help_always']          = 'An "always running" agent is launched on every cron pass (every minute) and each run uses AI calls.';
$_['text_run_confirm']          = 'Run the agent now? It may take a couple of minutes and uses AI calls.';
$_['text_run_ok']               = 'Agent executed. Check the history for the result.';
$_['text_run_error']            = 'The agent run failed.';
$_['text_runs_title']           = 'Latest runs';
$_['text_no_runs']              = 'This agent has not run yet.';
$_['button_stop']               = 'STOP (panic button)';
$_['text_stop_ok']              = 'Stop requested: the agent stops as soon as its current step ends (a few seconds at most) and is disabled. To re-enable it, edit it (&laquo;Show all&raquo; if you cannot see it).';
$_['text_stop_idle']            = 'The agent was not running; it is now disabled so it will not start.';
$_['text_run_status_stopped']   = 'Stopped';
$_['text_run_status_stopping']  = 'Stopping';
$_['entry_web_access']          = 'Internet:';
$_['text_web_access']           = 'Can search the Internet';
$_['text_help_web_access']      = 'Claude or DeepSeek only. What it finds is treated as data, not instructions, and searches are recorded in the log. Up to 5 per request.';
$_['error_web_access']          = 'Internet search is only available with Claude or DeepSeek.';
$_['text_web_badge']            = 'Internet';
$_['entry_ai_provider']         = 'AI to use:';
$_['text_help_ai_provider']     = 'Only the AIs that have an API KEY in Settings &gt; AI are listed.';
$_['text_no_key_suffix']        = 'no API KEY';
$_['error_ai_provider']         = 'Choose an AI that has an API KEY in Settings &gt; AI.';
$_['text_email_sent']          = 'Email sent';
$_['text_success_runs']         = 'Success: you have deleted the log!';
$_['text_confirm_delete_runs']  = 'Delete the selected log entries?';
$_['text_confirm_clear_runs']   = 'Clear the whole log of this agent? This cannot be undone.';
$_['button_clear_log']          = 'Clear log';
$_['error_no_runs_selected']    = 'Warning: select at least one log entry.';
$_['text_no_changes']           = 'No database changes';
$_['text_run_status_ok']        = 'Successful';
$_['text_run_status_error']     = 'Error';
$_['text_run_status_running']   = 'Running';
$_['text_trigger_cron']         = 'Scheduled';
$_['text_trigger_manual']       = 'Manual';

$_['text_access_read']          = 'Read only';
$_['text_access_write']         = 'Read and write';
$_['text_help_access']          = 'A read-only agent queries data but cannot change anything. A read-and-write agent can insert, update and delete records (with limits; everything is kept in the action log).';

// Column
$_['column_access']             = 'Access';
$_['column_model']              = 'AI / Model';
$_['column_name']               = 'Name';
$_['column_description']        = 'Activity';
$_['column_schedule']           = 'Execution';
$_['column_status']             = 'Status';
$_['column_last_run']           = 'Last run';
$_['column_last_result']        = 'Last result';
$_['column_action']             = 'Action';
$_['column_started']            = 'Started';
$_['column_finished']           = 'Finished';
$_['column_run_status']         = 'Status';
$_['column_trigger']            = 'Trigger';
$_['column_summary']            = 'Summary';
$_['column_changes']            = 'Database changes';

// Entry
$_['entry_access']              = 'Database access:';
$_['entry_name']                = 'Name:';
$_['entry_description']         = 'Activity it performs:';
$_['entry_schedule']            = 'Execution:';
$_['entry_run_time']            = 'Time:';
$_['entry_status']              = 'Status:';

// Button
$_['button_run']                = 'Run now';
$_['button_history']            = 'Action log';
$_['button_edit']               = 'Edit';

// Error
$_['error_permission']          = 'Warning: you do not have permission to modify agents!';
$_['error_no_api_key']          = 'The AI API KEY must be set in Settings &gt; AI (with "Use AI" enabled) to create and run agents.';
$_['error_name']                = 'Warning: the name must be between 3 and 128 characters!';
$_['error_description']         = 'Warning: you must describe the agent\'s activity!';
$_['error_run_time']            = 'Warning: enter a valid time (HH:MM)!';
$_['error_not_found']           = 'That agent does not exist.';
$_['error_running']             = 'This agent is already running.';
