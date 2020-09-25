<?php

namespace App\Models;

use App\Filters\AutoFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Auto
 *
 * @property int $id
 * @property string $name
 * @property string $number
 * @property string $color
 * @property string $vin
 * @property int $year
 * @property int $car_brand_id
 * @property int $car_model_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\CarBrand|null $brand
 * @property-read \App\Models\CarModel|null $model
 * @method static \Illuminate\Database\Eloquent\Builder|Auto newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Auto newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Auto query()
 * @method static \Illuminate\Database\Eloquent\Builder|Auto whereCarBrandId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Auto whereCarModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Auto whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Auto whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Auto whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Auto whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Auto whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Auto whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Auto whereVin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Auto whereYear($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|Auto filter($filters)
 */
class Auto extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'number',
        'color',
        'vin',
        'year',
        'car_brand_id',
        'car_model_id',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function brand()
    {
        return $this->belongsTo(CarBrand::class, 'car_brand_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function model()
    {
        return $this->belongsTo(CarModel::class, 'car_model_id');
    }

    /**
     * @param $query
     * @param array $filters
     */
    public function scopeFilter($query, array $filters)
    {
        (new AutoFilter($query))->apply($filters);
    }
}
