<?php

declare(strict_types=1);

namespace App\Http\Requests\Backup;

use App\Rules\PathToZip;
use Illuminate\Foundation\Http\FormRequest;

class DownloadBackupFileRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'fileName' => ['required', new PathToZip()],
        ];
    }

    public function messages()
    {
        return[
            
            'fileName.required' => 'Select a file',
        ];
    }
}
