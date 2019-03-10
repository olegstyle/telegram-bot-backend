<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * Class BaseModel
 * @package App\Models
 *
 * @author Oleh Borysenko <oleg.borisenko@morefromit.com>
 *
 * Fields
 * @property int $h_id
 * @property Carbon h_created_at
 * @property Carbon h_updated_at
 *
 * Appends
 * @property-read int|string $id
 *
 * Scopes
 * @method static Builder|static whereId(int|string $id)
 * @method static Builder|static whereIds(array $id)
 * @method static Builder|static orderDescByCreatedAt()
 *
 * @mixin Builder
 */
abstract class BaseModel extends Model
{
    protected $primaryKey = 'h_id';

    const CREATED_AT = 'h_created_at';

    const UPDATED_AT = 'h_updated_at';

    protected $appends = ['id'];

    /**
     * This is useful for some packages where ->id is hardcoded
     *
     * @return int|string Some models have id as integer and other could have uuid
     */
    public function getIdAttribute()
    {
        return $this->{$this->getKeyName()} ?? 0;
    }

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
        return $builder->orderByDesc('h_created_at');
    }

    public function getForeignKey(): string
    {
        return 'e_' . Str::snake(class_basename($this));
    }

    public static function getModelForeignKey(): string
    {
        return app(static::class)->getForeignKey();
    }

    public static function getTableName(): string
    {
        return app(static::class)->getTable();
    }
}
