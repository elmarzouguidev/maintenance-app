<?php

/*
 * Tests of App\Support\UiKit\Format. Copy to tests/Unit/UiKit/FormatTest.php in your project
 * (INSTALL.md). Plain Pest: no database and no application boot are needed.
 */

use App\Support\UiKit\BadgeVariant;
use App\Support\UiKit\Format;

afterEach(function (): void {
    Format::$currency = 'MAD';
    Format::$locale = 'fr';
});

it('formats amounts with two decimals, no-break spaces and the currency', function (): void {
    expect(Format::money(1234.5))->toBe("1\u{00A0}234,50\u{00A0}MAD")
        ->and(Format::money('980.4', 'EUR'))->toBe("980,40\u{00A0}EUR")
        ->and(Format::money(15, ''))->toBe('15,00')
        ->and(Format::moneyMinor(1842505))->toBe("18\u{00A0}425,05\u{00A0}MAD");
});

it('shows a dash for a missing or invalid amount', function (): void {
    expect(Format::money(null))->toBe('—')
        ->and(Format::money(''))->toBe('—')
        ->and(Format::money('abc'))->toBe('—')
        ->and(Format::moneyMinor(null))->toBe('—');
});

it('uses the default currency set once for the application', function (): void {
    Format::$currency = 'EUR';

    expect(Format::money(10))->toBe("10,00\u{00A0}EUR");
});

it('formats dates, date-times and long dates', function (): void {
    expect(Format::date('2026-11-03'))->toBe('03/11/2026')
        ->and(Format::dateTime('2026-11-03 09:30'))->toBe('03/11/2026 à 09:30')
        ->and(Format::longDate('2026-11-03'))->toBe('3 novembre 2026')
        ->and(Format::date(null))->toBe('—')
        ->and(Format::date('', 'Non renseignée'))->toBe('Non renseignée');
});

it('formats mileage and durations', function (): void {
    expect(Format::mileage(24500))->toBe("24\u{00A0}500\u{00A0}km")
        ->and(Format::mileage(null))->toBe('—')
        ->and(Format::duration(45))->toBe('45 min')
        ->and(Format::duration(200))->toBe('3 h 20 min')
        ->and(Format::duration(1440))->toBe('1 jour')
        ->and(Format::duration(4380))->toBe('3 jours et 1 h')
        ->and(Format::duration(-5))->toBe('0 min');
});

it('lists the five badge variants', function (): void {
    expect(array_map(fn (BadgeVariant $variant): string => $variant->value, BadgeVariant::cases()))
        ->toBe(['neutral', 'success', 'warning', 'danger', 'info']);
});
