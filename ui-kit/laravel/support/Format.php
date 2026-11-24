<?php

namespace App\Support\UiKit;

use Carbon\CarbonInterface;
use DateTimeInterface;
use Illuminate\Support\Carbon;

/**
 * One shared display format for amounts, dates, mileage and durations (x-admin.amount,
 * x-admin.date). Display only: input values and submitted formats never go through it.
 *
 * Defaults are French (the kit's source language). Change them once, for example in
 * AppServiceProvider::boot(): Format::$currency = 'EUR'; Format::$locale = 'en';
 * Format::$dateTimeFormat = 'm/d/Y \a\t H:i';
 */
class Format
{
    /** No-break space: amounts never wrap between groups or before the currency. */
    public const SPACE = "\u{00A0}";

    public const FALLBACK = '—';

    public static string $currency = 'MAD';

    public static string $locale = 'fr';

    public static string $decimalSeparator = ',';

    public static string $thousandsSeparator = self::SPACE;

    public static string $dateFormat = 'd/m/Y';

    public static string $dateTimeFormat = 'd/m/Y \à H:i';

    public static string $longDateFormat = 'j F Y';

    /**
     * "1 234,56 MAD" from an amount in major units. A null currency uses the default; an empty
     * string shows no currency.
     */
    public static function money(int|float|string|null $amount, ?string $currency = null, string $fallback = self::FALLBACK): string
    {
        if ($amount === null || $amount === '' || ! is_numeric($amount)) {
            return $fallback;
        }

        $currency ??= self::$currency;
        $formatted = number_format((float) $amount, 2, self::$decimalSeparator, self::$thousandsSeparator);

        return $currency !== '' ? $formatted.self::SPACE.$currency : $formatted;
    }

    /**
     * Same as money(), from integer minor units (cents).
     */
    public static function moneyMinor(?int $amountMinor, ?string $currency = null, string $fallback = self::FALLBACK): string
    {
        return $amountMinor === null ? $fallback : self::money($amountMinor / 100, $currency, $fallback);
    }

    /**
     * "03/11/2026".
     */
    public static function date(DateTimeInterface|string|null $value, string $fallback = self::FALLBACK): string
    {
        return self::carbon($value)?->format(self::$dateFormat) ?? $fallback;
    }

    /**
     * "03/11/2026 à 09:30".
     */
    public static function dateTime(DateTimeInterface|string|null $value, string $fallback = self::FALLBACK): string
    {
        return self::carbon($value)?->format(self::$dateTimeFormat) ?? $fallback;
    }

    /**
     * "3 novembre 2026" (month names through Carbon's translatedFormat in Format::$locale).
     */
    public static function longDate(DateTimeInterface|string|null $value, string $fallback = self::FALLBACK): string
    {
        return self::carbon($value)?->locale(self::$locale)->translatedFormat(self::$longDateFormat) ?? $fallback;
    }

    /**
     * "24 500 km" from an odometer reading (no-break spaces).
     */
    public static function mileage(int|string|null $kilometres, string $fallback = self::FALLBACK): string
    {
        if ($kilometres === null || $kilometres === '' || ! is_numeric($kilometres)) {
            return $fallback;
        }

        return number_format((int) $kilometres, 0, self::$decimalSeparator, self::$thousandsSeparator).self::SPACE.'km';
    }

    /**
     * "2 jours et 3 h", "3 h 20 min", "45 min" from a number of minutes (French wording).
     */
    public static function duration(int $minutes): string
    {
        $minutes = max($minutes, 0);
        $days = intdiv($minutes, 1440);
        $hours = intdiv($minutes % 1440, 60);
        $rest = $minutes % 60;

        if ($days > 0) {
            $duration = $days.' '.($days === 1 ? 'jour' : 'jours');

            if ($hours > 0) {
                return $duration.' et '.$hours.' h';
            }

            return $rest > 0 ? $duration.' et '.$rest.' min' : $duration;
        }

        if ($hours > 0) {
            return $hours.' h'.($rest > 0 ? ' '.$rest.' min' : '');
        }

        return $rest.' min';
    }

    private static function carbon(DateTimeInterface|string|null $value): ?CarbonInterface
    {
        if ($value === null || $value === '') {
            return null;
        }

        return $value instanceof DateTimeInterface ? Carbon::instance($value) : Carbon::parse($value);
    }
}
