<?php

class OtpModel
{
    private const SESSION_KEY = 'password_reset';
    private const EXPIRY_SECONDS = 600;

    public function generateOtp()
    {
        return (string) random_int(100000, 999999);
    }

    public function storeOtp($email, $otp)
    {
        $_SESSION[self::SESSION_KEY] = [
            'email' => $email,
            'otp' => (string) $otp,
            'expires_at' => time() + self::EXPIRY_SECONDS,
            'verified' => false,
        ];
    }

    public function getResetData()
    {
        return $_SESSION[self::SESSION_KEY] ?? null;
    }

    public function hasActiveOtp()
    {
        $data = $this->getResetData();
        return $data !== null && !$this->isExpired();
    }

    public function isExpired()
    {
        $data = $this->getResetData();
        if ($data === null) {
            return true;
        }

        return (int) ($data['expires_at'] ?? 0) < time();
    }

    public function getRemainingSeconds()
    {
        $data = $this->getResetData();
        if ($data === null) {
            return 0;
        }

        return max(0, (int) ($data['expires_at'] ?? 0) - time());
    }

    public function verifyOtp($inputOtp)
    {
        if ($this->isExpired()) {
            $this->clear();
            return false;
        }

        $data = $this->getResetData();
        $storedOtp = (string) ($data['otp'] ?? '');
        $inputOtp = trim((string) $inputOtp);

        if ($storedOtp === '' || !hash_equals($storedOtp, $inputOtp)) {
            return false;
        }

        $_SESSION[self::SESSION_KEY]['verified'] = true;
        unset($_SESSION[self::SESSION_KEY]['otp']);

        return true;
    }

    public function isVerified()
    {
        $data = $this->getResetData();
        return $data !== null && !$this->isExpired() && !empty($data['verified']);
    }

    public function getEmail()
    {
        $data = $this->getResetData();
        return $data['email'] ?? null;
    }

    public function clear()
    {
        unset($_SESSION[self::SESSION_KEY]);
    }
}
