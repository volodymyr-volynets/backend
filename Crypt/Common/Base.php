<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\Crypt\Common;

use Object\Content\Messages;
use Object\Error\UserException;

abstract class Base
{
    /**
     * Crypt link
     *
     * @var string
     */
    public $crypt_link;

    /**
     * Key (Encryption)
     *
     * @var string
     */
    public $encryption_key;

    /**
     * Key (Token)
     *
     * @var string
     */
    public $token_key;

    /**
     * Key (Baerer)
     *
     * @var string
     */
    public $bearer_key;

    /**
     * Cipher
     *
     * @var string
     */
    public $cipher;

    /**
     * Mode
     *
     * @var string
     */
    public $mode;

    /**
     * Salt
     *
     * @var string
     */
    public $salt;

    /**
     * Hash method
     *
     * @var string
     */
    public $hash = 'sha1';

    /**
     * Base64
     *
     * @var boolean
     */
    public $base64 = false;

    /**
     * Check ip
     *
     * @var boolean
     */
    public $check_ip = false;

    /**
     * Valid hours
     *
     * @var int
     */
    public $valid_hours = 2;

    /**
     * Password hash algorithm
     *
     * @var int
     */
    public $password = PASSWORD_DEFAULT;

    /**
     * Construct
     *
     * @param string $crypt_link
     * @param array $options
     */
    abstract public function __construct(string $crypt_link, array $options = []);

    /**
     * @see Crypt::encrypt();
     */
    abstract public function encrypt(string $data): string;

    /**
     * @see Crypt::decrypt();
     */
    abstract public function decrypt(string $data): string;

    /**
     * @see Crypt::compress();
     */
    abstract public function compress(string $data);

    /**
     * @see Crypt::uncompress();
     */
    abstract public function uncompress(string $data);

    /**
     * @see Crypt::hash();
     */
    public function hash($data)
    {
        // serilializing array or object
        if (is_array($data) || is_object($data)) {
            // for array we need to sort by key
            if (is_array($data)) {
                ksort($data);
            }
            // serialize
            $data = serialize($data);
        }
        if ($this->hash == 'md5' || $this->hash == 'sha1') {
            $method = $this->hash;
            return $method($data);
        } else {
            return hash($this->hash, $data);
        }
    }

    /**
     * @see Crypt::hashFile();
     */
    public function hashFile($path)
    {
        if ($this->hash == 'md5' || $this->hash == 'sha1') {
            $method = $this->hash . '_file';
            return $method($path);
        } else {
            return hash_file($this->hash, $path);
        }
    }

    /**
     * @see Crypt::tokenCreate();
     *
     * By default we provide AuthTkt implementation
     */
    public function tokenCreate($id, $token = null, $data = null, $options = [])
    {
        $time = $options['time'] ?? time();
        $ip = $options['ip'] ?? \Request::ip();
        if (empty($this->check_ip)) {
            $packed = pack('NN', 0, $time);
        } else {
            $packed = pack('NN', ip2long($ip), $time);
        }
        if (isset($data) && $data != '') {
            $data = base64_encode(serialize($data));
        } else {
            $data = '';
        }
        $digest0 = md5($packed . $this->token_key . $id . "\0" . $token . "\0" . $data);
        $digest = md5($digest0 . $this->token_key);
        $result = sprintf('%s%08x%s!%s!%s', $digest, $time, $id, $token, $data);
        if ($this->base64) {
            return urlencode(base64_encode($result));
        } else {
            return urlencode($result);
        }
    }

    /**
     * @see Crypt::tokenValidate();
     */
    public function tokenValidate($token, $options = [])
    {
        $result = [
            'id' => null,
            'data' => null,
            'time' => null,
            'ip' => \Request::ip()
        ];
        if (empty($token)) {
            return false;
        }
        if (is_base64($token)) {
            $token2 = base64_decode($token);
        } else {
            $token2 = urldecode($token);
            if (is_base64($token2)) {
                $token2 = base64_decode($token2);
            }
        }
        $result['time'] = hexdec(substr($token2, 32, 8));
        $temp = explode('!', substr($token2, 40, strlen($token2)));
        $result['id'] = $temp[0] ?? 0;
        $result['token'] = $temp[1] ?? '';
        if (isset($temp[2]) && $temp[2] !== '') {
            $result['data'] = unserialize(base64_decode($temp[2]));
        } else {
            $result['data'] = null;
        }
        $rebuilt = self::tokenCreate($result['id'], $result['token'], $result['data'], ['time' => $result['time'], 'ip' => $result['ip']]);
        if (urldecode($rebuilt) != $token) {
            return false;
        } elseif (empty($options['skip_time_validation'])) {
            // expiration
            if ($this->valid_hours > 0) {
                $hours = (time() - $result['time']) / 60 / 60;
                if ($hours > $this->valid_hours) {
                    return false;
                }
            }
        }
        return $result;
    }

    /**
     * @see Crypt::tokenVerify();
     */
    public function tokenVerify($token, $tokens, $options = [])
    {
        if (empty($token)) {
            throw new UserException(Messages::TOKEN_EXPIRED);
        } else {
            $token_data = $this->tokenValidate($token, $options);
            if ($token_data === false || !in_array($token_data['token'], $tokens)) {
                throw new UserException(Messages::TOKEN_EXPIRED);
            }
            return $token_data;
        }
    }

    /**
     * Hash password
     *
     * @param string $password
     * @return string
     */
    public function passwordHash($password)
    {
        return password_hash($password, $this->password);
    }

    /**
     * Verify password
     *
     * @param string $password
     * @param string $hash
     * @return boolean
     */
    public function passwordVerify($password, $hash)
    {
        return password_verify($password, $hash);
    }

    /**
     * Bearer authorization token create
     *
     * @param string $type
     * @param int|null $user_id
     * @param int|null $tenant_id
     * @param string|null $ip
     * @param string|null $session_id
     * @return string
     */
    public function bearerAuthorizationTokenCreate(string $type = 'REG', ?int $user_id = null, ?int $tenant_id = null, ?string $ip = null, ?string $session_id = null): string
    {
        if ($tenant_id === null) {
            $tenant_id = \Tenant::id() ?? 0;
        }
        if (!$ip) {
            $ip = \Request::ip();
        }
        if ($user_id === null) {
            $user_id = \User::id();
        }
        if ($session_id === null) {
            $session_id = session_id();
            if (empty($session_id)) {
                $session_id = str_pad('', 32, '0', STR_PAD_LEFT);
            }
        }
        $microtime = explode(" ", microtime());
        $id = sprintf(
            '%04x-%08s-%08s-%04s-%08s-%03s',
            $tenant_id,
            dechex(ip2long($ip)),
            substr("00000000" . dechex($microtime[1]), -8),
            substr("0000" . dechex(round($microtime[0] * 65536)), -4),
            str_pad($user_id . '', 8, '0', STR_PAD_LEFT),
            $type
        );
        return $id . '!' . $session_id . '!' . self::hash($id . $session_id . $this->bearer_key);
    }

    /**
     * Bearer authorization token validate
     *
     * @param string $token
     * @return bool
     */
    public function bearerAuthorizationTokenValidate(string $token): bool
    {
        $temp = explode('!', $token);
        return self::hash($temp[0] . ($temp[1] ?? '') . $this->bearer_key) === ($temp[2] ?? '');
    }

    /**
     * Bearer authorization token decode
     *
     * @param string $token
     * @return array
     */
    public function bearerAuthorizationTokenDecode(string $token): array
    {
        $result = [];
        $token_parts = explode('!', $token);
        $parts = explode('-', $token_parts[0]);
        if (is_array($parts) && count($parts) == 6) {
            $result = [
                'tenant_id' => (int) $parts[0],
                'ip' => long2ip(hexdec($parts[1])),
                'unixtime' => hexdec($parts[2]),
                'micro' => hexdec($parts[3]) / 65536,
                'user_id' => (int) $parts[4],
                'type' => $parts[5] ?? 'Unknown',
                // other
                'id' => $token_parts[0],
                'hash' => $token_parts[2] ?? null,
                'valid' => $this->bearerAuthorizationTokenValidate($token),
                'session_id' => $token_parts[1] ?? null,
            ];
        }
        return $result;
    }

    /**
     * Generate password string
     *
     * @param int $length
     * @param string $characters
     * @param array $options
     * 		bool as_array
     * @return string|array
     */
    public function passwordStringGenerate(int $length = 12, $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', array $options = []): string|array
    {
        if ($length < 1) {
            $length = 12;
        }
        $result = [];
        $max = mb_strlen($characters, '8bit') - 1;
        for ($i = 0; $i < $length; $i++) {
            $result[] = $characters[random_int(0, $max)];
        }
        if (!empty($options['as_array'])) {
            return $result;
        }
        return implode('', $result);
    }

    /**
     * Generate password as per policy
     *
     * @param int $length
     * @param array $options
     *		int uppercase
     *		int special
     *		int number
     *		int lowercase - is computed
     *		bool as_array
     */
    public function passwordPolicyGenerate(int $length, array $options = []): string|array
    {
        // settings
        $settings['uppercase'] = $options['uppercase'] ?? 1;
        $settings['special'] = $options['special'] ?? 1;
        $settings['number'] = $options['number'] ?? 1;
        $settings['lowercase'] = $length - $settings['uppercase'] - $settings['special'] - $settings['number'];
        if ($settings['lowercase'] < 1) {
            $settings['lowercase'] = 1;
        }
        // characters
        $characters = [
            'uppercase' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
            'special' => '~!@#$%^&*()_+{}[]\\|,./<>?',
            'number' => '0123456789',
            'lowercase' => 'abcdefghijklmnopqrstuvwxyz',
        ];
        $result = [];
        foreach ($settings as $k => $v) {
            $chars = $this->passwordStringGenerate($v, $characters[$k], [
                'as_array' => true
            ]);
            foreach ($chars as $v2) {
                $result[] = $v2;
            }
        }
        // shufle password
        shuffle($result);
        // return as array
        if (!empty($options['as_array'])) {
            return $result;
        }
        return implode('', $result);
    }

    /**
     * JSON Web Token create (JWT)
     *
     * @param int $id
     * @param string $token
     * @param array|string|null $data
     * @param array $options
     * @return string
     */
    public function jwtCreate(int $id, string $token, array|string|null $data, array $options = []): string
    {
        $header = [
            'alg' => 'HS256',
            'typ' => 'JWT'
        ];
        // if data is not an array we set it as data key
        if (!is_array($data)) {
            $data = ['data' => $data];
            if (!isset($data['data'])) {
                unset($data['data']);
            }
        }
        // generate payload
        $payload = $data;
        $payload['id'] = $id;
        $payload['token'] = $token;
        $payload['iat'] = $data['iat'] ?? time();
        // sort keys
        ksort($payload);
        // concat header with payload
        $encoded = base64_encode_url_safe(json_encode($header)) . '.' . base64_encode_url_safe(json_encode($payload));
        return $encoded . '.' . base64_encode_url_safe(hash_hmac('SHA256', $encoded, $this->token_key, true));
    }

    /**
     * JSON Web Token Validate
     *
     * @param string|null $token
     * @return bool|array
     */
    public function jwtValidate(?string $token): bool|array
    {
        if (empty($token)) {
            return false;
        }
        $temp = explode('.', $token);
        if (empty($temp[2])) {
            return false;
        }
        $result = json_decode(base64_decode_url_safe($temp[1]), true);
        $encoded = $temp[0] . '.' . $temp[1];
        $hash = base64_encode_url_safe(hash_hmac('SHA256', $encoded, $this->token_key, true));
        if ($hash != $temp[2]) {
            return false;
        }
        return $result;
    }

    /**
     * JSON Web Token Verify
     *
     * @param string|null $token
     * @param array $tokens
     * @return array
     */
    public function jwtVerify(?string $token, array $tokens): array
    {
        if (empty($token)) {
            throw new UserException(Messages::TOKEN_EXPIRED);
        } else {
            $token_data = $this->jwtValidate($token);
            if ($token_data === false || !in_array($token_data['token'], $tokens)) {
                throw new UserException(Messages::TOKEN_EXPIRED);
            }
            return $token_data;
        }
    }

    /**
     * Micro Token Create
     *
     * @param int $id
     * @param string $token
     * @return string
     */
    public function microCreate(int $id, string $token)
    {
        $encoded = base64_encode_url_safe($token . '') . '.' . base64_encode_url_safe(pack('Q', $id));
        $hash = base64_encode_url_safe(hash_hmac('sha256', $encoded, $this->token_key, true));
        return $encoded . '.' . substr($hash, 0, 3) . substr($hash, -3);
    }

    /**
     * Micro Token Validate
     *
     * @param string|null $token
     * @return bool|array
     */
    public function microValidate(?string $token): bool|array
    {
        if (empty($token)) {
            return false;
        }
        $temp = explode('.', $token);
        // if no signature we return bool
        if (empty($temp[2])) {
            return false;
        }
        $result = [
            'id' => unpack('Q', base64_decode_url_safe($temp[1]))[1],
            'token' => base64_decode_url_safe($temp[0]),
        ];
        $encoded = $temp[0] . '.' . $temp[1];
        $hash = base64_encode_url_safe(hash_hmac('sha256', $encoded, $this->token_key, true));
        if (substr($hash, 0, 3) !== substr($temp[2], 0, 3) || substr($hash, -3) !== substr($temp[2], -3)) {
            return false;
        }
        return $result;
    }

    /**
     * Micro Token Verify
     *
     * @param string|null $token
     * @param array $tokens
     * @return array
     */
    public function microVerify(?string $token, array $tokens): array
    {
        if (empty($token)) {
            throw new UserException(Messages::TOKEN_EXPIRED);
        } else {
            $token_data = $this->microValidate($token);
            if ($token_data === false || !in_array($token_data['token'], $tokens)) {
                throw new UserException(Messages::TOKEN_EXPIRED);
            }
            return $token_data;
        }
    }

    /**
     * Nano Token Create
     *
     * @param int $id
     * @return string
     */
    public function nanoCreate(int $id): string
    {
        $encoded = base64_encode_url_safe(pack('Q', $id));
        $hash = base64_encode_url_safe(hash_hmac('sha256', $encoded, $this->token_key, true));
        return $encoded . '.' . substr($hash, 0, 2) . substr($hash, -2);
    }

    /**
     * Nano Token Validate
     *
     * @param string|null $token
     * @return bool|array
     */
    public function nanoValidate(?string $token): bool|array
    {
        if (empty($token)) {
            return false;
        }
        $temp = explode('.', $token);
        if (empty($temp[1])) {
            return false;
        }
        $result = [
            'id' => unpack('Q', base64_decode_url_safe($temp[0]))[1],
        ];
        $hash = base64_encode_url_safe(hash_hmac('sha256', $temp[0], $this->token_key, true));
        if (substr($hash, 0, 2) !== substr($temp[1], 0, 2) || substr($hash, -2) !== substr($temp[1], -2)) {
            return false;
        }
        return $result;
    }

    /**
     * Nano Token Verify
     *
     * @param string|null $token
     * @return array
     */
    public function nanoVerify(?string $token): array
    {
        if (empty($token)) {
            throw new UserException(Messages::TOKEN_EXPIRED);
        } else {
            $token_data = $this->nanoValidate($token);
            if ($token_data === false) {
                throw new UserException(Messages::TOKEN_EXPIRED);
            }
            return $token_data;
        }
    }
}
