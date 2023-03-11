<?php

declare(strict_types=1);

namespace App\Http\Requests\Backup;

use App\Rules\PathToZip;
use App\Rules\StoreToDisk;
use Illuminate\Foundation\Http\FormRequest;

class DownloadBackupFileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'fileName' => ['required', new PathToZip()],
            'diskName' => ['nullable', new StoreToDisk()],
        ];
    }

    public function messages()
    {
        return [

            'fileName.required' => 'Select a file',
        ];
    }
}
