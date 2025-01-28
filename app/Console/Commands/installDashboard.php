<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class installDashboard extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'install:dashboard';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This Command Custom for install dashboard for application ';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->recurseCopy("gifts", 'Gift');


        return 0;
    }

    public function recurseCopy(
        string $model_name,
        string $model
    ): void {
        $copy = 'resources/views/admin/pages/onboardings';
        $directory = opendir($copy);
        if (is_dir('resources/views/admin/pages/'.$model_name) === false) {
            mkdir('resources/views/admin/pages/'.$model_name);
        }
        while (($file = readdir($directory)) !== false) {
            if ($file === '.' || $file === '..') {
                continue;
            }
            if (is_dir("$copy/$file") === true) {
                $this->recurseCopy("$copy/$file", "resources/views/admin/pages/$model_name/$file");
            } else {
                copy("$copy/$file", "resources/views/admin/pages/$model_name/$file");
            }
            $file_content = file_get_contents("resources/views/admin/pages/$model_name/$file");
            $str = str_replace("onboardings", $model_name, $file_content);
            file_put_contents("resources/views/admin/pages/$model_name/$file", $str);
        }
        copy('app/Http/Controllers/Admin/OnboardingController.php', 'app/Http/Controllers/Admin/'.$model.'Controller.php');

        $file_content = file_get_contents('app/Http/Controllers/Admin/'.$model.'Controller.php');
        $str = str_replace("onboardings", $model_name, $file_content);
        file_put_contents('app/Http/Controllers/Admin/'.$model.'Controller.php', $str);

        $file_content = file_get_contents('app/Http/Controllers/Admin/'.$model.'Controller.php');
        $str = str_replace("Onboarding", $model, $file_content);
        file_put_contents('app/Http/Controllers/Admin/'.$model.'Controller.php', $str);

        copy('app/Http/Requests/OnboardingRequest.php', 'app/Http/Requests/'.$model.'Request.php');
        $file_content = file_get_contents('app/Http/Requests/'.$model.'Request.php');
        $str = str_replace("Onboarding", $model, $file_content);
        file_put_contents('app/Http/Requests/'.$model.'Request.php', $str);

        copy('lang/en/onboardings.php', 'lang/en/'.$model_name.'.php');
        copy('lang/ar/onboardings.php', 'lang/ar/'.$model_name.'.php');

        $file_content = file_get_contents('routes/admin.php');
        $routes = "
        //addnewrouteheredontdeletemeplease

            Route::get('".$model_name."/select', [App\Http\Controllers\Admin\\".$model."Controller::class, 'select'])->name('".$model_name.".select');
            Route::delete('".$model_name."/bulk', [App\Http\Controllers\Admin\\".$model."Controller::class, 'deleteBulk'])->name('".$model_name.".deleteBulk')->middleware('adminPermission:".$model_name.".delete');
            Route::get('".$model_name."/list', [App\Http\Controllers\Admin\\".$model."Controller::class, 'list'])->name('".$model_name.".list')->middleware('adminPermission:".$model_name.".view');
            Route::post('".$model_name."', [App\Http\Controllers\Admin\\".$model."Controller::class, 'store'])->name('".$model_name.".store')->middleware('adminPermission:".$model_name.".create');
            Route::delete('".$model_name."/{id}', [App\Http\Controllers\Admin\\".$model."Controller::class, 'destroy'])->name('".$model_name.".destroy')->middleware('adminPermission:".$model_name.".delete');
            Route::get('".$model_name."', [App\Http\Controllers\Admin\\".$model."Controller::class, 'index'])->name('".$model_name.".index')->middleware('adminPermission:".$model_name.".view');
            Route::get('".$model_name."/create', [App\Http\Controllers\Admin\\".$model."Controller::class, 'create'])->name('".$model_name.".create')->middleware('adminPermission:".$model_name.".create');
            Route::match(['PUT', 'PATCH'], '".$model_name."/{id}', [App\Http\Controllers\Admin\\".$model."Controller::class, 'update'])->name('".$model_name.".update')->middleware('adminPermission:".$model_name.".edit');
            Route::get('".$model_name."/{id}/edit', [App\Http\Controllers\Admin\\".$model."Controller::class, 'edit'])->name('".$model_name.".edit')->middleware('adminPermission:".$model_name.".edit');


        ";
        $str = str_replace("//addnewrouteheredontdeletemeplease", $routes, $file_content);
        file_put_contents('routes/admin.php', $str);

        $file_content = file_get_contents('resources\views\admin\partials\sidebar.blade.php');
        $routes = "
        {{--addnewrouteheredontdeletemeplease--}}

           @can('".$model_name.".view')
                <li>
                    <a class='d-flex align-items-center' href='{{ route('admin.".$model_name.".index') }} '>
                        <i data-feather='key'></i>
                        <span class='menu-item text-truncate' data-i18n='List'>{{ __('admin.".$model_name."') }}</span>
                    </a>
                </li>
            @endcan

        ";
        $str = str_replace("{{--addnewrouteheredontdeletemeplease--}}", $routes, $file_content);
        file_put_contents('resources\views\admin\partials\sidebar.blade.php', $str);

        closedir($directory);
    }

}
