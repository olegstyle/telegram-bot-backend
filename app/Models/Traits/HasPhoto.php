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
        $oldPhotoPath = $this->getPhotoFullPath();
        if ($oldPhotoPath) {
            @unlink($oldPhotoPath);
        }

        $field = $this->getPhotoField();
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->{$field} = Uuid::uuid1()->toString() . '.' . $file->getClientOriginalExtension();
        $file->storeAs($this->getPhotoStorageDir(), $this->{$field});
    }

    public function getPhotoHttpPath(): ?string
    {
        $fullPath = $this->getPhotoFullPath();
        if (!$fullPath) {
            return null;
        }

        return config('app.url') . '/storage/' . ltrim($this->getDirName(), '/') . '/' . $this->{$this->getPhotoField()};
    }

    public function getPhotoFullPath(): ?string
    {
        $field = $this->getPhotoField();
        if ($this->{$field} === null) {
            return null;
        }
        $path = $this->getPhotoFullDir() . '/' . $this->{$field};

        return file_exists($path) ? $path : null;
    }

    abstract public function getPhotoField(): string;

    public function getPhotoFullDir(): string
    {
        return storage_path('app/' . ltrim($this->getPhotoStorageDir(), '/'));
    }

    public function getPhotoStorageDir(): string
    {
        return 'public/' . ltrim($this->getDirName(), '/');
    }

    abstract public function getDirName(): string;
}
