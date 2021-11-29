<?php

namespace App\Domain\Support\SaveModel\Fields;

use App\Domain\Support\SaveModel\Traits\FileNameGenerator;

use Illuminate\Http\UploadedFile;

class FileField extends Field
{
    use FileNameGenerator;

    private string $folder = 'files';

    private ?string $disk = 'public';

    private bool $useDefaultFileName = false;

    public function execute()
    {
        if (!$this->value) {
            return $this->value;
        }

        if (!($this->value instanceof UploadedFile)) {
            return $this->value;
        }

        if ($this->useDefaultFileName && ($this->value instanceof UploadedFile)) {

            $fileName = $this->value->getClientOriginalName();

            return $this->value->storeAs($this->folder, $fileName, $this->diskName());
        }

        return $this->value->store($this->folder, $this->diskName());
    }

    public function toDisk($disk): FileField
    {
        $this->disk = $disk;

        return $this;
    }

    private function diskName()
    {
        return $this->disk ?? config('filesystems.default');
    }

    public function useFileOriginalName(): FileField
    {
        $this->useDefaultFileName = true;

        return $this;
    }
}
