<?php


namespace App\Filters;


use Illuminate\Database\Eloquent\Builder;

class AutoFilter
{
    /**
     * @var Builder
     */
    public $query;

    /**
     * AutoFilter constructor.
     * @param Builder $query
     */
    public function __construct(Builder $query)
    {
        $this->query = $query;
    }

    /**
     * @param array $filters
     */
    public function apply(array $filters)
    {
        foreach ($filters as $name => $value) {
            if (method_exists($this, $name)) {
                $this->{$name}($value);
            }
        }
    }

    /**
     * @param $brand
     */
    public function brand($brand)
    {
        $this->query->whereHas('brand', function ($query) use ($brand) {
            $query->where('name', $brand);
        });
    }

    /**
     * @param $model
     */
    public function model($model)
    {
        $this->query->whereHas('model', function ($query) use ($model) {
            $query->where('name', $model);
        });
    }

    /**
     * @param $year
     */
    public function year($year)
    {
        $this->query->where('year', $year);
    }
}
