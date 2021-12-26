<?php

namespace App\Domain\Support\SaveModel\Fields;

use Illuminate\Http\UploadedFile;
use Closure;
use Illuminate\Support\Facades\Storage;

class ImageField extends Field
{

    private string $folder = 'images';

    private ?string $disk = 'public';

    private ?Closure $fileNameClosure = null;

    private bool $deleteImageOnUpdate = false;

    private bool $useDefaultFileName = false;

    private bool $useMediaLibrary = false;

    public function execute()
    {
        if (!$this->value) {
            return $this->value;
        }

        if (!($this->value instanceof UploadedFile)) {
            return $this->value;
        }

        if ($this->useMediaLibrary) {

             dd($this->storeUsingMediaLibrary());
          
            // dd('Oui  its use HasMedia from imageField');
        }
        if ($this->useDefaultFileName && ($this->value instanceof UploadedFile)) {

            $fileName = $this->value->getClientOriginalName();

            return $this->value->storeAs($this->folder, $fileName, $this->diskName());
        }

        $this->deleteOldFileIfNecessary();

        if (!$this->fileNameClosure) {
            return $this->value->store($this->folder, $this->diskName());
        }

        $fileName = ($this->fileNameClosure)($this->value);

        return $this->value->storeAs($this->folder, $fileName, $this->diskName());
    }

    public function storeUsingMediaLibrary()
    {
   
       return $this->model->addMediaFromRequest('photo')
            ->toMediaCollection('images');
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

    private function deleteOldFileIfNecessary(): void
    {
        $fileName = $this->model->getRawOriginal($this->column);

        if ($this->deleteImageOnUpdate && $this->isUpdate() && $fileName) {
            Storage::disk($this->diskName())->delete($fileName);
        }
    }

    public function useFileOriginalName(): ImageField
    {
        $this->useDefaultFileName = true;

        return $this;
    }
    public function DeletePreviousImage(): ImageField
    {
        $this->deleteImageOnUpdate = true;

        return $this;
    }

    public function useMediaLibrary()
    {
        $this->useMediaLibrary = true;
        return $this;
    }
}
