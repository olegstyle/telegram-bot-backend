<?php declare(strict_types=1);

namespace App\Models;

use App\Models\Traits\HasPhoto;
use App\Models\Traits\HasUser;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Fields
 * @property string $title
 * @property string $message
 * @property string|null $photo_path
 * @property bool $active
 *
 * Scopes
 * @method static Builder|static whereActive()
 */
class Post extends BaseModel
{
    use HasUser,
        HasPhoto,
        SoftDeletes;

    protected $table = 'posts';

    protected $casts = ['active' => 'bool'];

    public function scopeWhereActive(Builder $builder): Builder
    {
        return $builder->where('active', true);
    }

    public function getPhotoField(): string
    {
        return 'photo_path';
    }

    public function getDirName(): string
    {
        return 'posts/' . sha1((string) $this->user->id);
    }
}
