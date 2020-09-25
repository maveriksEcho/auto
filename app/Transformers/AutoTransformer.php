<?php


namespace App\Transformers;


use App\Models\Auto;
use League\Fractal\TransformerAbstract;

class AutoTransformer extends TransformerAbstract
{
    /**
     * @param Auto $auto
     * @return array
     */
    public function transform(Auto $auto)
    {
        return [
            'id' => $auto->id,
            'name' => $auto->name,
            'number' => $auto->number,
            'color' => $auto->color,
            'vin' => $auto->vin,
            'year' => $auto->year,
            'brand' => $auto->brand->name,
            'model' => $auto->model->name,
        ];
    }
}
