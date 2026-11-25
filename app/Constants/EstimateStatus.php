<?php

namespace App\Constants;

use App\Support\UiKit\BadgeVariant;
use App\Support\UiKit\HasStatusBadge;

/**
 * Answer of the client to an estimate (estimates.status, the values of Response::DEVIS_*), shown
 * with x-admin.status-badge.
 */
enum EstimateStatus: int implements HasStatusBadge
{
    case Pending = Response::DEVIS_EN_ATTENTE;
    case Accepted = Response::DEVIS_ACCEPTE;
    case Refused = Response::DEVIS_NON_ACCEPTE;

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'En attente',
            self::Accepted => 'Accepté',
            self::Refused => 'Refusé',
        };
    }

    public function badgeVariant(): BadgeVariant
    {
        return match ($this) {
            self::Pending => BadgeVariant::WARNING,
            self::Accepted => BadgeVariant::SUCCESS,
            self::Refused => BadgeVariant::NEUTRAL,
        };
    }
}
