<?php declare(strict_types=1);

namespace App\Models\Traits;

use App\Models\BaseModel;
use Illuminate\Http\UploadedFile;
use Ramsey\Uuid\Uuid;

/**
 * @mixin BaseModel
 */
trait HasPhoto
{
    public function setPhoto(UploadedFile $file): void
    {
        $oldPhotoPath = $this->getPhotoPath();
        if ($oldPhotoPath) {
            @unlink($oldPhotoPath);
        }

        $field = $this->getPhotoField();
        $this->{$field} = Uuid::uuid1()->toString() . '.' . $file->getClientOriginalExtension();
        $file->storeAs($this->getPhotoDir(), $this->{$field});
    }

    public function getPhotoPath(): ?string
    {
        $field = $this->getPhotoField();
        if ($this->{$field} === null) {
            return null;
        }
        $path = $this->getPhotoDir() . '/' . $this->{$field};

        return file_exists($path) ? $path : null;
    }

    abstract public function getPhotoField(): string;

    public function getPhotoDir(): string
    {
        return storage_path('app/public/' . ltrim($this->getDirName(), '/'));
    }

    abstract public function getDirName(): string;
}
