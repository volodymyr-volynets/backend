SELECT sm_log_tenant_id, sm_log_id, sm_log_group_id, sm_log_originated_id, sm_log_host, sm_log_year, sm_log_user_id, sm_log_user_ip, sm_log_type, sm_log_level, sm_log_status, sm_log_message, sm_log_trace, sm_log_controller_name, sm_log_form_name, sm_log_notifications, sm_log_affected_users, sm_log_affected_rows, sm_log_operation, sm_log_inactive, sm_log_inserted_timestamp, sm_log_inserted_user_id, sm_log_chanel, sm_log_request_url, sm_log_content_type, sm_log_form_statistics, sm_log_error_rows, sm_log_sql, sm_log_ajax, sm_log_duration, sm_log_mail_sent, sm_log_other
FROM public.sm_logs_generated_year_2026
WHERE sm_log_inserted_timestamp::date >= '2026-05-01'
    AND sm_log_type = 'Db Query (Slow)'
    AND sm_log_duration > 10
ORDER BY sm_log_duration DESC
