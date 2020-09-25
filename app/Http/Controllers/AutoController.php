<?php

namespace App\Http\Controllers;

use App\Export\AutoExport;
use App\Http\Requests\CreateAutoRequest;
use App\Models\Auto;
use App\Repository\AutoRepository;
use App\Services\AutoService;
use App\Transformers\AutoTransformer;
use Illuminate\Http\Request;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

class AutoController extends Controller
{
    /**
     * @var AutoTransformer
     */
    protected $transformer;
    /**
     * @var AutoService
     */
    protected $service;
    /**
     * @var AutoRepository
     */
    protected $repository;

    /**
     * AutoController constructor.
     * @param AutoTransformer $transformer
     * @param AutoService $service
     * @param AutoRepository $repository
     */
    public function __construct(AutoTransformer $transformer, AutoService $service, AutoRepository $repository)
    {
        $this->transformer = $transformer;
        $this->service = $service;
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return AutoExport
     */
    public function index(Request $request)
    {
        if ($request->has('search')) {
            $this->repository->search($request->get('search'));
        }
        if ($request->has('sort')) {
            $this->repository->sort($request->get('sort'), $request->get('direction', 'asc'));
        }

        $filters = $request->get('filters');
        if ($filters && is_array($filters)) {
            $this->repository->filter($filters);
        }

        if ($request->has('excel')) {
            $data = $this->repository->getAllAuto()->transformWith($this->transformer)->toArray();
            return new AutoExport(collect($data));
        }

        $paginator = $this->repository->getAllAutoPagination($request->get('per_page'), $request->get('page'));
        $data = $paginator->getCollection();

        return $data->transformWith($this->transformer)
            ->paginateWith(new IlluminatePaginatorAdapter($paginator))
            ->toArray();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateAutoRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function store(CreateAutoRequest $request)
    {
        $response = $this->service->getByVinCode($request->get('vin'));

        if ($response->isEmpty()) {
            return response()->json([], 404);
        }

        $auto = $this->repository->getByVin($request->get('vin'));

        if ($auto) {
            return response()->json(['Already create'], 403);
        }

        $auto = $this->repository->create($request, $response);

        return response()->json(fractal($auto, $this->transformer)->toArray(), 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CreateAutoRequest $request
     * @param Auto $auto
     * @return \Illuminate\Http\JsonResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function update(CreateAutoRequest $request, Auto $auto)
    {
        $response = null;
        $vin = $request->get('vin');

        if ($vin && $vin !== $auto->vin) {
            $response = $this->service->getByVinCode($request->get('vin'));
        }

        $auto = $this->repository->update($auto, $request, $response);

        return response()->json(fractal($auto, $this->transformer)->toArray());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Auto $auto
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Auto $auto)
    {
        $this->repository->destoy($auto);
        return response()->json([]);
    }
}
