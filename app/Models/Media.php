<?php

declare(strict_types=1);

namespace App\Models;

use App\Casts\ExtentionCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\MediaCollections\Models\Media as BaseMedia;

class Media extends BaseMedia
{
    use HasFactory;
}
