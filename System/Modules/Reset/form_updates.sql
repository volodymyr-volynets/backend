SELECT *
FROM public.sm_form_updates
ORDER BY sm_frmupdate_id;

SELECT sm_frmupdate_operation_name AS operation_name, COUNT(*) AS operation_counter
FROM public.sm_form_updates AS a
WHERE a.sm_frmupdate_tenant_id = 2
	AND sm_frmupdate_tenant_id = 2
	AND sm_frmupdate_form_code = 'on_organizations_collection__on_organizations'
	AND sm_frmupdate_form_pk = '2_1'
	AND sm_frmupdate_inserted_timestamp > '2025-05-31 19:50:06.483622'
GROUP BY operation_name;

DELETE FROM public.sm_form_updates;