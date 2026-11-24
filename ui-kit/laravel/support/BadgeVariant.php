<?php

namespace App\Support\UiKit;

/**
 * Visual variant of a status badge (x-admin.status-badge). Presentation only: the component
 * maps each case to full class names, never to string-built utilities. There is no accent
 * variant: the accent colour is reserved for actions, focus and selection.
 */
enum BadgeVariant: string
{
    case NEUTRAL = 'neutral';
    case SUCCESS = 'success';
    case WARNING = 'warning';
    case DANGER = 'danger';
    case INFO = 'info';
}
