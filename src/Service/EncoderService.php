<?php

//TODO
//implement some actual encrypt method, such as openssl-encrypt or sodium_crypto_secretbox
//https://www.php.net/manual/de/function.openssl-encrypt.php
//https://www.php.net/manual/de/function.sodium-crypto-secretbox.php

namespace App\Service;

use App\Contract\DecoderInterface;
class EncoderService implements DecoderInterface
{

    /**
     * @var string
     */
    private $encodeKey;

    public function __construct(string $encodeKey)
    {
        $this->encodeKey = $encodeKey;
    }

    /**
     *
     * @param string $data
     * @return string
     */
    public function encode(string $data): string
    {
        return implode('|', [$this->encodeKey, base64_encode($data)]);
    }

    /**
     * @param string $data
     * @return string
     */
    public function decode(string $data): string
    {
        if (!self::isEncoded($data)) {
            return $data;
        }

        $data = explode('|', $data);
        return base64_decode($data[1]);
    }

    /**
     * @param $data
     * @return bool
     */
    public function isEncoded($data): bool
    {
        if (strpos($data, $this->encodeKey) === false) return false;
        return true;
    }
}
