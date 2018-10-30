<?php

namespace SendinBlue;

class Base32
{
    const ALPHABET = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';

    // PHP 5.6 prevents using isset on constant arrays
    private static $CCS = [
        'A' => 0, 'B' => 1, 'C' => 2, 'D' => 3,
        'E' => 4, 'F' => 5, 'G' => 6, 'H' => 7,
        'I' => 8, 'J' => 9, 'K' => 10, 'L' => 11,
        'M' => 12, 'N' => 13, 'O' => 14, 'P' => 15,
        'Q' => 16, 'R' => 17, 'S' => 18, 'T' => 19,
        'U' => 20, 'V' => 21, 'W' => 22, 'X' => 23,
        'Y' => 24, 'Z' => 25, '2' => 26, '3' => 27,
        '4' => 28, '5' => 29, '6' => 30, '7' => 31,
    ];

    /**
     * @param string $string
     * @param bool   $padding
     *
     * @return string
     */
    public static function encode($string, $padding = true)
    {
        $encoded = '';
        $bitLeftCount = $carry = 0;

        for ($i = 0, $length = \strlen($string); $i < $length; ++$i) {
            $ascii = \ord($string[$i]);

            $bitLeftCount += 3;
            $encoded .= self::ALPHABET[$carry << 8 - $bitLeftCount | $ascii >> $bitLeftCount];
            $carry = $ascii & 2 ** $bitLeftCount - 1;

            if ($bitLeftCount >= 5) {
                $bitLeftCount -= 5;
                $encoded .= self::ALPHABET[$carry >> $bitLeftCount];
                $carry &= 2 ** $bitLeftCount - 1;
            }
        }

        if ($bitLeftCount) {
            $encoded .= self::ALPHABET[$carry << 5 - $bitLeftCount];
        }

        if ($padding) {
            $encoded .= str_repeat('=', [0, 6, 4, 3, 1][$length % 5]);
        }

        return $encoded;
    }

    /**
     * @param string $string
     *
     * @return string
     */
    public static function decode($string)
    {
        $decoded = '';
        $bitCount = $carry = 0;

        for ($i = 0, $length = \strlen($string); $i < $length; ++$i) {
            $character = $string[$i];

            if ('=' === $character) {
                for (; $i < $length; ++$i) {
                    if ('=' !== $string[$i]) {
                        throw new \UnexpectedValueException('“=” character found outside padding');
                    }
                }

                if ($length % 4) {
                    throw new \RangeException('Invalid padding');
                }

                return $decoded;
            }

            if (!isset(self::$CCS[$character])) {
                throw new \OutOfBoundsException(sprintf('“%s” is not a character from the base 32 alphabet', $character));
            }

            $codePoint = self::$CCS[$character];

            if ($bitCount >= 3) {
                $bitCount -= 3;
                $decoded .= \chr($carry << 5 - $bitCount | $codePoint >> $bitCount);
                $carry = $codePoint & 2 ** $bitCount - 1;
            } else {
                $bitCount += 5;
                $carry = $carry << 5 | $codePoint & 2 ** $bitCount - 1;
            }
        }

        return $decoded;
    }
}
