<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class installApi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'install:api';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This Command Custom for install api for application ';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // $this->recurseCopy("orders", 'Order');

        return 0;
    }

    public function recurseCopy(
        string $model_name,
        string $model
    ): void {

        copy('app/Http/Controllers/Api/V1/Vendor/TripController.php', 'app/Http/Controllers/Api/V1/Vendor/'.$model.'Controller.php');

        $file_content = file_get_contents('app/Http/Controllers/Api/V1/Vendor/'.$model.'Controller.php');
        $str = str_replace("trips", $model_name, $file_content);
        file_put_contents('app/Http/Controllers/Api/V1/Vendor/'.$model.'Controller.php', $str);

        $file_content = file_get_contents('app/Http/Controllers/Api/V1/Vendor/'.$model.'Controller.php');
        $str = str_replace("Trip", $model, $file_content);
        file_put_contents('app/Http/Controllers/Api/V1/Vendor/'.$model.'Controller.php', $str);

        copy('app/Http/Requests/Vendor/TripRequest.php', 'app/Http/Requests/Vendor/'.$model.'Request.php');
        $file_content = file_get_contents('app/Http/Requests/Vendor/'.$model.'Request.php');
        $str = str_replace("Trip", $model, $file_content);
        file_put_contents('app/Http/Requests/Vendor/'.$model.'Request.php', $str);

        copy('app/Services/Vendor/TripService.php', 'app/Services/Vendor/'.$model.'Service.php');
        $file_content = file_get_contents('app/Services/Vendor/'.$model.'Service.php');
        $str = str_replace("Trip", $model, $file_content);
        file_put_contents('app/Services/Vendor/'.$model.'Service.php', $str);

        copy('app/Repositories/Vendor/TripRepository.php', 'app/Repositories/Vendor/'.$model.'Repository.php');
        $file_content = file_get_contents('app/Repositories/Vendor/'.$model.'Repository.php');
        $str = str_replace("Trip", $model, $file_content);
        file_put_contents('app/Repositories/Vendor/'.$model.'Repository.php', $str);


        $file_content = file_get_contents('routes/vendor.php');
        $routes = "
        //addnewrouteheredontdeletemeplease

            Route::get('".$model_name."', [".$model."Controller::class, 'index']);
            Route::post('".$model_name."', [".$model."Controller::class, 'store']);
            Route::get('".$model_name."/{".$model_name."}', [".$model."Controller::class, 'show']);
            Route::put('".$model_name."/{".$model_name."}', [".$model."Controller::class, 'update']);

        ";
        $str = str_replace("//addnewrouteheredontdeletemeplease", $routes, $file_content);
        file_put_contents('routes/vendor.php', $str);


    }

}
