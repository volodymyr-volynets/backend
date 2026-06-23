BEGIN;
DELETE FROM public.ai_embeddings WHERE ai_embedding_ai_ragtype_code = 'SM::MENU_RESOURCES';
DELETE FROM public.tm_batch_records WHERE tm_batchrecord_tm_batchtype_code = 'AI_EMBEDDINGS';
DELETE FROM public.tm_batch_entries WHERE tm_batchentry_tm_batchtype_code = 'AI_EMBEDDINGS';
COMMIT;
