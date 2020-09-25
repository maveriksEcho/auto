<?php


namespace App\Http\Controllers;


use App\Http\Requests\CarBrandSearchRequest;
use App\Models\CarBrand;
use Illuminate\Database\Eloquent\Collection;

class CarBrandController
{
    /**
     * @param CarBrandSearchRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(CarBrandSearchRequest $request)
    {
        /**
         * @var CarBrand[]|Collection $brands
         */
        $brands = CarBrand::where('name', 'LIKE', '%' . $request->get('search') . '%')->get();
        if ($brands->count() > 1) {
            return response()->json([
                'brands' => $brands->pluck('name')->toArray(),
            ]);
        }
        return response()->json([
            'models' => $brands->first()->models()->pluck('name')->toArray(),
        ]);
    }
}
