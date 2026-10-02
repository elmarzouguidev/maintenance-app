<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

class CompanyLogoDataUri
{
    public static function fromPublicDisk(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        $disk = Storage::disk('public');

        if (! $disk->exists($path)) {
            return null;
        }

        $mimeType = $disk->mimeType($path) ?: 'image/jpeg';

        return 'data:'.$mimeType.';base64,'.base64_encode($disk->get($path));
    }
}
