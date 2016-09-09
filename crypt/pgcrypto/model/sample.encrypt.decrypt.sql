-- set config variables
SELECT set_config('sm.numbers.crypt.key', '1234567890abcdef', false);
SELECT set_config('sm.numbers.crypt.options', 'compress-algo=1, cipher-algo=aes256', false);

-- test encrypt and decrypt functions
SELECT sm_decrypt(sm_encrypt('some data'));