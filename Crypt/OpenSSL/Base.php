<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\Crypt\OpenSSL;

#[\AllowDynamicProperties]
class Base extends \Numbers\Backend\Crypt\Common\Base
{
    /**
     * File encryption blocks
     *
     * @var int
     */
    public const FILE_ENCRYPTION_BLOCKS = 10000;

    /**
     * File cipher
     *
     * @var string
     */
    public const FILE_CIPHER = 'aes-256-cbc';

    /**
     * Constructing
     *
     * @param string $crypt_link
     * @param array $options
     */
    public function __construct(string $crypt_link, array $options = [])
    {
        $this->crypt_link = $crypt_link;
        $this->token_key = $options['token_key'] ?? sha1('key');
        $this->encryption_key = $options['encryption_key'] ?? sha1('key');
        $this->bearer_key = $options['bearer_key'] ?? sha1('bearer');
        $this->salt = $options['salt'] ?? 'salt';
        $this->hash = $options['hash'] ?? 'sha1';
        $this->cipher = $options['cipher'] ?? 'aes256'; // its a string encryption method for openssl
        $this->mode = null; // not applicable
        $this->base64 = !empty($options['base64']);
        $this->check_ip = !empty($options['check_ip']);
        $this->valid_hours = $options['valid_hours'] ?? 2;
        if (!empty($options['password'])) {
            $this->password = constant($options['password']);
        }
    }

    /**
     * @see Crypt::encrypt();
     */
    public function encrypt(string $data, ?string $encryption_key = null, bool $base64 = false): string
    {
        $ivlen = openssl_cipher_iv_length($this->cipher);
        $iv = openssl_random_pseudo_bytes($ivlen);
        $encrypted = $iv . openssl_encrypt($data, $this->cipher, $encryption_key ?? $this->encryption_key, OPENSSL_RAW_DATA, $iv);
        if ($this->base64 || $base64) {
            return base64_encode($encrypted);
        } else {
            return $encrypted;
        }
    }

    /**
     * @see Crypt::decrypt();
     */
    public function decrypt(string $data, ?string $encryption_key = null): string|bool
    {
        if (is_base64($data)) {
            $decoded = base64_decode($data);
        } else {
            $decoded = $data;
        }
        $ivlen = openssl_cipher_iv_length($this->cipher);
        $iv = substr($decoded, 0, $ivlen);
        $decoded = substr($decoded, $ivlen);
        return openssl_decrypt($decoded, $this->cipher, $encryption_key ?? $this->encryption_key, OPENSSL_RAW_DATA, $iv);
    }

    /**
     * @see Crypt::compress();
     */
    public function compress(string $data)
    {
        return gzcompress($data, 9);
    }

    /**
     * @see Crypt::uncompress();
     */
    public function uncompress(string $data)
    {
        return gzuncompress($data);
    }

    /**
     * Encrypt file
     *
     * @param string $source_file
     * @param string $destination_file
     * @param mixed $encryption_key
     * @return bool
     */
    public function encryptFile(string $source_file, string $destination_file, ?string $encryption_key = null): bool
    {
        $iv_length = openssl_cipher_iv_length(self::FILE_CIPHER);
        $iv = openssl_random_pseudo_bytes($iv_length);
        // open files
        $source_opened = fopen($source_file, 'rb');
        $destination_opened = fopen($destination_file, 'w');
        // write IV
        fwrite($destination_opened, $iv);
        // encrypt in chunks
        while (!feof($source_opened)) {
            $plain_text = fread($source_opened, $iv_length * self::FILE_ENCRYPTION_BLOCKS);
            $cipher_text = openssl_encrypt($plain_text, self::FILE_CIPHER, $encryption_key ?? $this->encryption_key, OPENSSL_RAW_DATA, $iv);
            $iv = substr($cipher_text, 0, $iv_length);
            fwrite($destination_opened, $cipher_text);
        }
        // close handlers
        fclose($source_opened);
        fclose($destination_opened);
        return true;
    }

    /**
     * Decrypt file
     *
     * @param string $source_file
     * @param string $destination_file
     * @param mixed $encryption_key
     * @return bool
     */
    public function decryptFile(string $source_file, string $destination_file, ?string $encryption_key = null): bool
    {
        $iv_length = openssl_cipher_iv_length(self::FILE_CIPHER);
        // open files
        $source_opened = fopen($source_file, 'rb');
        $destination_opened = fopen($destination_file, 'w');
        // read IV
        $iv = fread($source_opened, $iv_length);
        // decrypt in chunks
        while (! feof($source_opened)) {
            $cipher_text = fread($source_opened, $iv_length * (self::FILE_ENCRYPTION_BLOCKS + 1));
            $plain_text = openssl_decrypt($cipher_text, self::FILE_CIPHER, $encryption_key ?? $this->encryption_key, OPENSSL_RAW_DATA, $iv);
            $iv = substr($plain_text, 0, $iv_length);
            fwrite($destination_opened, $plain_text);
        }
        // close handlers
        fclose($source_opened);
        fclose($destination_opened);
        return true;
    }
}
