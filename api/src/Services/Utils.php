<?php

namespace App\Services;

use App\Exception\InvalidJsonFormatException;

/**
 * Class Utils.
 *
 * @author Wings <Eternity.mr8@gmail.com>
 */
class Utils
{
    /**
     * @throws InvalidJsonFormatException
     */
    public static function parseJson(string $json): array
    {
        $data = json_decode($json, true);
        if (null === $data) {
            throw new InvalidJsonFormatException();
        }

        return $data;
    }

    /**
     * @return string
     *
     * @throws \Exception
     */
    public static function generateRandomString(int $strength = 16)
    {
        $randomString = '';
        $zeroAsciiCode = ord(0);
        $zAsciiCode = ord('z');
        for ($i = 0; $i < $strength; ++$i) {
            $randomString .= chr(random_int($zeroAsciiCode, $zAsciiCode));
        }

        return $randomString;
    }
}
