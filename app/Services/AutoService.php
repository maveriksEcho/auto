<?php


namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class AutoService
{
    /**
     * @var Client
     */
    private $client;

    /**
     * AutoService constructor.
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @param $vin
     * @return Collection
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getByVinCode($vin)
    {
        try{
            $response = $this->client->post('vehicles/DecodeVINValuesBatch/' . $vin, [
                'query' => [
                    'format' => 'json',
                ],
            ]);
        }catch (\Exception $exception){
            return collect();
        }
        $response = $this->getResult($response);
        return collect($response->first());
    }

    /**
     * @return Collection
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getAllCarBrands()
    {
        $response = $this->client->get('vehicles/getallmakes', [
            'query' => [
                'format' => 'json',
            ],
        ]);

        return $this->getResult($response);
    }

    /**
     * @param string $brand
     * @return Collection
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getAllCarModelsByBrand(string $brand)
    {
        $response = $this->client->get('vehicles/GetModelsForMake/' . $brand, [
            'query' => [
                'format' => 'json',
            ],
        ]);
        return $this->getResult($response);
    }

    /**
     * @param $response
     * @return Collection
     */
    private function getResult($response)
    {
        $response = json_decode($response->getBody()->getContents(), true);

        return collect(Arr::get($response, 'Results', []));
    }
}
