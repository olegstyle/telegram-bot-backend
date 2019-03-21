<?php declare(strict_types=1);

namespace App\Models;

use App\Models\Traits\HasUser;
use Illuminate\Database\Eloquent\Builder;

/**
 * Fields
 * @property string $title
 * @property string $message
 * @property string|null $photo_path -- TODO
 * @property bool $active
 *
 * Scopes
 * @method static Builder|static whereActive()
 */
class Post extends BaseModel
{
    use HasUser;

    protected $casts = ['active' => 'bool'];

    public function scopeWhereActive(Builder $builder): Builder
    {
        return $builder->where('active', true);
    }
}
