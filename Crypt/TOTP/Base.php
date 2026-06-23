<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\Crypt\TOTP;

/**
 * TOTP: Time-Based One-Time Password Algorithm
 *
 * Implements RFC 6238
 */
#[\AllowDynamicProperties]
class Base
{
    /**
     * TOTP Secret
     *
     * @var string|null
     */
    protected ?string $totp_secret;

    /**
     * TOTP digits
     *
     * @var int
     */
    protected int $totp_digits = 6;

    /**
     * TOTP time interval
     *
     * @var int
     */
    protected int $totp_time_interval = 30;

    /**
     * Constructing
     *
     * @param string $crypt_link
     * @param array $options
     */
    public function __construct(string $crypt_link, array $options = [])
    {
        $this->crypt_link = $crypt_link;
        $this->totp_secret = base32_encode($options['totp_secret'] ?? sha1('totp'));
    }

    /**
     * Set settings
     *
     * @param string|null $secret
     * @param int $digits - default 6
     * @param mixed $time_interval - default 60
     * @return Base
     */
    public function setSettings(string|null $secret = null, int $digits = 6, $time_interval = 30): static
    {
        if ($secret != null) {
            $this->totp_secret = base32_encode($secret);
        }
        $this->totp_digits = $digits;
        $this->totp_time_interval = $time_interval;
        return $this;
    }

    /**
     * Generate interval
     *
     * @param int $interval
     * @return string
     */
    protected function generateInterval(int $interval): string
    {
        $key = base32_decode($this->totp_secret);
        $binary = pack('N*', 0) . pack('N*', $interval);
        $hash = hash_hmac('sha1', $binary, $key, true);
        $offset = ord($hash[19]) & 0xf;
        $code = (
            (ord($hash[$offset]) & 0x7f) << 24 |
            (ord($hash[$offset + 1]) & 0xff) << 16 |
            (ord($hash[$offset + 2]) & 0xff) << 8 |
            (ord($hash[$offset + 3]) & 0xff)
        );
        return str_pad($code % pow(10, $this->totp_digits), $this->totp_digits, '0', STR_PAD_LEFT);
    }

    /**
     * Validate
     *
     * @param string $code
     * @param int $window
     * @return bool
     */
    public function validate(string $code, int $window = 1): bool
    {
        $interval = floor(time() / $this->totp_time_interval);
        for ($i = -$window; $i <= $window; $i++) {
            $generated = $this->generateInterval($interval + $i);
            if (hash_equals($generated, $code)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Get deep link URL
     *
     * @param string $username
     * @param string $issuer
     * @return string
     */
    public function getDeepLinkUrl(string $username, string $issuer): string
    {
        $username = rawurlencode($username);
        $issuer = rawurlencode($issuer);
        return "otpauth://totp/{$issuer}:{$username}?secret={$this->totp_secret}&issuer={$issuer}&digits={$this->totp_digits}&period={$this->totp_time_interval}";
    }

    /**
     * Get MFA code
     *
     * @return string
     */
    public function getMFACode(): string
    {
        $interval = floor(time() / $this->totp_time_interval);
        return $this->generateInterval($interval);
    }

    /**
     * Get MFA expires
     *
     * @return float|int
     */
    public function getMFAExpires(): int
    {
        $interval = floor(time() / $this->totp_time_interval);
        return ($interval * $this->totp_time_interval + $this->totp_time_interval) - time();
    }
}
