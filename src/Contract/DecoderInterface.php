<?php

namespace App\Contract;

interface DecoderInterface
{
    /**
     * @param string $data
     * @return string
     */
    public function encode(string $data): string;

    /**
     * @param string $data
     * @return string
     */
    public function decode(string $data): string;

    /**
     * @param string $data
     * @return bool
     */
    public function isEncoded(string $data): bool;
}
