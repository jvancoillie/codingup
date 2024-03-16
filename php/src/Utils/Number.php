<?php

namespace App\Utils;

class Number
{
    public static function collatzGenerator($value): \Generator
    {
        yield $value;
        while (1 != $value) {
            if (0 == $value % 2) {
                $value /= 2;
            } else {
                $value = 3 * $value + 1;
            }
            yield $value;
        }
    }

    public static function nextCollatz(int $current): float|int
    {
        if (0 == $current % 2) {
            $next = $current / 2;
        } else {
            $next = 3 * $current + 1;
        }

        return $next;
    }

    public static function collatz(int $seed): int
    {
        $length = 1;
        $next = self::nextCollatz($seed);
        while ($next > 1) {
            $next = self::nextCollatz($next);
            ++$length;
        }

        return $length + 1;
    }

    /**
     * Sum of the simplest arithmetic progression, consisting of the first n natural numbers.
     *
     * @see https://fr.wikipedia.org/wiki/Somme_(arithm%C3%A9tique)
     */
    public static function summation($n): float|int
    {
        return $n * ($n + 1) / 2;
    }

    /**
     * Check to see if a given integer is prime.
     *
     * @param int $number the number to check to see if it is prime
     *
     * @return bool true if the number is prime, false if not
     */
    public static function isPrime(int $number): bool
    {
        $n = abs($number);
        $i = 2;
        while ($i <= sqrt($n)) {
            if (0 == $n % $i) {
                return false;
            }
            ++$i;
        }

        return true;
    }
}
