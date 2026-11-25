<?php

namespace App\Constants;

use App\Support\UiKit\BadgeVariant;
use App\Support\UiKit\HasStatusBadge;

/**
 * Payment status of an invoice (invoices.status), shown with x-admin.status-badge.
 */
enum InvoiceStatus: string implements HasStatusBadge
{
    case Paid = 'paid';
    case NonPaid = 'non-paid';

    public function label(): string
    {
        return match ($this) {
            self::Paid => 'Payée',
            self::NonPaid => 'À régler',
        };
    }

    public function badgeVariant(): BadgeVariant
    {
        return match ($this) {
            self::Paid => BadgeVariant::SUCCESS,
            self::NonPaid => BadgeVariant::WARNING,
        };
    }
}
