<?php

namespace App\Support;

use NumberFormatter;

/**
 * An amount in French words, as Moroccan invoices state it ("Arrêtée la présente facture à la
 * somme de …"): dirhams, then centimes when there are any.
 */
final class AmountInWords
{
    public static function dirhams(float|int|string|null $amount): string
    {
        $cents = (int) round(((float) $amount) * 100);
        $dirhams = intdiv($cents, 100);
        $centimes = $cents % 100;
        $words = new NumberFormatter('fr', NumberFormatter::SPELLOUT);

        $text = $words->format($dirhams).' '.($dirhams > 1 ? 'dirhams' : 'dirham');

        if ($centimes > 0) {
            $text .= ' et '.$words->format($centimes).' '.($centimes > 1 ? 'centimes' : 'centime');
        }

        return $text;
    }
}
