<?php

namespace App\Console\Commands;

use App\Models\CarBrand;
use App\Models\CarModel;
use App\Services\AutoService;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;

class AutoUpdateData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:update-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update auto data';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle(): void
    {
        /**
         * @var AutoService $service
         */
        $service = app(AutoService::class);
        $response = $service->getAllCarBrands();

        $bar = $this->output->createProgressBar($response->count());

        $bar->start();

        if ($response->isEmpty()) {
            $this->error('Bad response');
            return;
        }

        $response->each(function (array $item) use ($service, $bar) {
            $brandName = Arr::get($item, 'Make_Name');
            $brand = CarBrand::firstOrCreate([
                'name' => $brandName,
            ]);
            $models = $service->getAllCarModelsByBrand($brandName);

            if ($models->isEmpty()) {
                return;
            }

            foreach ($models as $model) {
                $modelName = Arr::get($model, 'Model_Name');
                if ($modelName) {
                    CarModel::firstOrCreate([
                        'name' => $modelName,
                        'car_brand_id' => $brand->id,
                    ]);
                }
            }

            $bar->advance();
        });
        $bar->finish();

        $this->info('Success update');
    }
}
