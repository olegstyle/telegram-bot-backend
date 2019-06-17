<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * Fields
 * @property int $id
 * @property Carbon created_at
 * @property Carbon updated_at
 *
 * Scopes
 * @method static Builder|static whereId(int|string $id)
 * @method static Builder|static whereIds(array $id)
 * @method static Builder|static orderDescByCreatedAt()
 * @method static Builder|static orderDescById()
 *
 * @mixin Builder
 */
abstract class BaseModel extends Model
{
    /**
     * @param Builder $builder
     * @param int|string $id Some models have id as integer and other could have uuid
     * @return Builder
     */
    public function scopeWhereId(Builder $builder, $id): Builder
    {
        return $builder->where($this->getKeyName(), $id);
    }

    public function scopeWhereIds(Builder $builder, array $ids): Builder
    {
        return $builder->whereIn($this->getKeyName(), $ids);
    }

    public function scopeOrderDescByCreatedAt(Builder $builder): Builder
    {
        return $builder->orderByDesc('created_at');
    }

    public function scopeOrderDescById(Builder $builder): Builder
    {
        return $builder->orderByDesc('id');
    }


    public static function getTableName(): string
    {
        return app(static::class)->getTable();
    }

    public static function getModelKeyName(): string
    {
        return app(static::class)->getKeyName();
    }
}
