<?php

namespace App\Services;

class EgyptianPhoneNormalizer
{
    /**
     * Normalize any Egyptian or international phone number into a clean
     * international digit string suitable for WhatsApp (e.g. 201012345678).
     */
    public static function normalize(?string $phone): ?string
    {
        if (blank($phone)) {
            return null;
        }

        // Remove all non-digit characters
        $digits = preg_replace('/\D+/', '', (string) $phone);

        if (empty($digits)) {
            return null;
        }

        // Handle 0020 prefix -> 20
        if (str_starts_with($digits, '0020')) {
            $digits = substr($digits, 2);
        }

        // Handle 010, 011, 012, 015 (11 digits local format)
        if (preg_match('/^0(10|11|12|15)\d{8}$/', $digits)) {
            return '20'.substr($digits, 1);
        }

        // Handle 2010, 2011, 2012, 2015 (12 digits with Egypt country code)
        if (preg_match('/^20(10|11|12|15)\d{8}$/', $digits)) {
            return $digits;
        }

        // Handle 10, 11, 12, 15 without leading zero (10 digits)
        if (preg_match('/^(10|11|12|15)\d{8}$/', $digits)) {
            return '20'.$digits;
        }

        // If other digits provided with at least 8 digits, return sanitized digits
        if (strlen($digits) >= 8) {
            return $digits;
        }

        return null;
    }

    /**
     * Check if a given phone number is a valid Egyptian mobile number.
     */
    public static function isValidEgyptianMobile(?string $phone): bool
    {
        $normalized = self::normalize($phone);

        if (! $normalized) {
            return false;
        }

        return (bool) preg_match('/^20(10|11|12|15)\d{8}$/', $normalized);
    }

    /**
     * Format an Egyptian phone number for human-friendly display.
     * e.g. "010 1234 5678" or "+20 101 234 5678"
     */
    public static function formatDisplay(?string $phone, bool $withCountryCode = true): string
    {
        $normalized = self::normalize($phone);

        if (! $normalized) {
            return $phone ?? '—';
        }

        if (preg_match('/^20(10|11|12|15)(\d{4})(\d{4})$/', $normalized, $matches)) {
            $prefix = $matches[1];
            $part1 = $matches[2];
            $part2 = $matches[3];

            if ($withCountryCode) {
                return "+20 {$prefix} {$part1} {$part2}";
            }

            return "0{$prefix} {$part1} {$part2}";
        }

        return $phone ?? '—';
    }

    /**
     * Identify telecom network operator name for an Egyptian mobile.
     */
    public static function getOperator(?string $phone): ?string
    {
        $normalized = self::normalize($phone);

        if (! $normalized || ! preg_match('/^20(10|11|12|15)/', $normalized, $matches)) {
            return null;
        }

        return match ($matches[1]) {
            '10' => 'Vodafone',
            '11' => 'Etisalat',
            '12' => 'Orange',
            '15' => 'WE',
            default => 'Egyptian Mobile',
        };
    }
}
