<?php

namespace App\Support\UiKit;

/**
 * A status shown with x-admin.status-badge: a label plus one badge variant. Implement it on
 * your status enums; REFERENCE.md ("Status badges") gives the mapping rules.
 *
 *     enum InvoiceStatus: string implements HasStatusBadge
 *     {
 *         case DRAFT = 'DRAFT';
 *         case PAID = 'PAID';
 *
 *         public function label(): string { return match ($this) { self::DRAFT => 'Brouillon', self::PAID => 'Réglée' }; }
 *
 *         public function badgeVariant(): BadgeVariant { return match ($this) { self::DRAFT => BadgeVariant::NEUTRAL, self::PAID => BadgeVariant::SUCCESS }; }
 *     }
 */
interface HasStatusBadge
{
    public function label(): string;

    public function badgeVariant(): BadgeVariant;
}
