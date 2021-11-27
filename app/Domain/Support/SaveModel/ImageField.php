<?php


namespace App\Domain\Support\SaveModel;

use Illuminate\Http\UploadedFile;

use Closure;

class ImageField extends Field
{

    private string $folder = 'images';

    private ?string $disk = null;

    private ?Closure $fileNameClosure = null;

    public function execute()
    {
        if (!$this->value) {

            return $this->value;

        }

        if (!($this->value instanceof UploadedFile)) {

            return $this->value;

        }
        if (!$this->fileNameClosure) {
            return $this->value->store($this->folder, $this->diskName());

        }

        $fileName = ($this->fileNameClosure)($this->value);
       // dd($fileName);
        return $this->value->storeAs($this->folder, $fileName, $this->diskName());
    }

    public function storeToFolder($folder): ImageField
    {
        $this->folder = $folder;

        return $this;
    }

    public function toDisk($disk): ImageField
    {
        $this->disk = $disk;

        return $this;
    }

    private function diskName()
    {
        return $this->disk ?? config('filesystems.default');
    }

    public function fileName(Closure $closure): ImageField
    {
        $this->fileNameClosure = $closure;

        return $this;
    }

    public function useDefaultImageName()
    {

    }
}
