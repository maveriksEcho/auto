<?php


namespace App\Repository;


use App\Http\Requests\CreateAutoRequest;
use App\Models\Auto;
use App\Models\CarBrand;
use App\Models\CarModel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class AutoRepository
{
    /**
     * @var Auto
     */
    protected $model;
    /**
     * @var Builder
     */
    protected $query;

    /**
     * AutoRepository constructor.
     * @param Auto $model
     */
    public function __construct(Auto $model)
    {
        $this->model = $model;
    }

    /**
     * @return Auto
     */
    public function getModel()
    {
        return $this->model;
    }

    public function getQuery()
    {
        if (!$this->query) {
            $this->query = $this->getModel()->newQuery();
        }
        return $this->query;
    }

    /**
     * @param $search
     * @return $this
     */
    public function search($search)
    {
        $this->getQuery()->where('name', 'LIKE', '%' . $search . '%')
            ->orWhere('number', 'LIKE', '%' . $search . '%')
            ->orWhere('vin', 'LIKE', '%' . $search . '%');
        return $this;
    }

    /**
     * @param $column
     * @param string $direction
     * @return $this
     */
    public function sort($column, $direction = 'asc')
    {
        if (in_array($column, $this->getModel()->getFillable())) {
            $this->getQuery()->orderBy($column, $direction);
        }

        return $this;
    }

    /**
     * @param array $filters
     */
    public function filter(array $filters)
    {
        $this->getQuery()->filter($filters);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllAuto()
    {
        return $this->getQuery()->get();
    }

    /**
     * @param $per_page
     * @param null $page
     * @return LengthAwarePaginator
     */
    public function getAllAutoPagination($per_page = 10, $page = null): LengthAwarePaginator
    {
        return $this->getQuery()->paginate($per_page, ['*'], 'page', $page);
    }

    /**
     * @param $vin
     * @return Auto
     */
    public function getByVin($vin)
    {
        return $this->getModel()->where('vin', $vin)->first();
    }

    /**
     * @param CreateAutoRequest $request
     * @param Collection $response
     * @return Auto
     */
    public function create(CreateAutoRequest $request, Collection $response)
    {
        return $this->getModel()->create([
            'name' => $request->get('name'),
            'number' => $request->get('number'),
            'color' => $request->get('color'),
            'vin' => $request->get('vin'),
            'year' => $response->get('ModelYear'),
            'car_brand_id' => $this->getCarBrandId($response),
            'car_model_id' => $this->getCarModelId($response),
        ]);
    }

    /**
     * @param Collection $response
     * @return int
     */
    private function getCarBrandId(Collection $response): int
    {
        return CarBrand::where('name', $response->get('Make'))->firstOrFail()->getKey();
    }

    /**
     * @param Collection $response
     * @return int
     */
    private function getCarModelId(Collection $response): int
    {
        return CarModel::where('name', $response->get('Model'))->firstOrFail()->getKey();
    }

    /**
     * @param Auto $auto
     * @throws \Exception
     */
    public function destoy(Auto $auto)
    {
        $auto->delete();
    }

    /**
     * @param Auto $auto
     * @param CreateAutoRequest $request
     * @param Collection|null $response
     * @return Auto
     */
    public function update(Auto $auto, CreateAutoRequest $request, Collection $response = null)
    {
        $data = $request->only('name', 'number', 'color', 'vin');
        if (!is_null($response)) {
            $data['year'] = $response->get('ModelYear');
            $data['car_brand_id'] = $this->getCarBrandId($response);
            $data['car_model_id'] = $this->getCarModelId($response);
        }

        $auto->update($data);
        return $auto;
    }
}
