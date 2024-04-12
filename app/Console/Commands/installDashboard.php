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
        $this->recurseCopy("cities",'City');

        return 0;
    }

    public function recurseCopy(
        string $model_name,
        string $model
    ): void {
        $directory = opendir('resources/views/admin/pages/categories');
        if (is_dir('resources/views/admin/pages/cities') === false) {
            mkdir('resources/views/admin/pages/cities');
        }
        while (($file = readdir($directory)) !== false) {
            if ($file === '.' || $file === '..') {
                continue;
            }
            if (is_dir("resources/views/admin/pages/categories/$file") === true) {
                recurseCopy("resources/views/admin/pages/categories/$file", "resources/views/admin/pages/cities/$file");
            } else {
                copy("resources/views/admin/pages/categories/$file", "resources/views/admin/pages/cities/$file");
            }
            $file_content = file_get_contents("resources/views/admin/pages/cities/$file");
            $str = str_replace("categories", $model_name, $file_content);
            file_put_contents("resources/views/admin/pages/cities/$file", $str);
        }
        copy('app/Http/Controllers/Admin/CategoryController.php', 'app/Http/Controllers/Admin/'.$model.'Controller.php');

        $file_content = file_get_contents('app/Http/Controllers/Admin/'.$model.'Controller.php');
        $str = str_replace("categories", $model_name, $file_content);
        file_put_contents('app/Http/Controllers/Admin/'.$model.'Controller.php', $str);

        $file_content = file_get_contents('app/Http/Controllers/Admin/'.$model.'Controller.php');
        $str = str_replace("Category", $model, $file_content);
        file_put_contents('app/Http/Controllers/Admin/'.$model.'Controller.php', $str);

        copy('app/Http/Requests/CategoryRequest.php', 'app/Http/Requests/'.$model.'Request.php');
        $file_content = file_get_contents('app/Http/Requests/'.$model.'Request.php');
        $str = str_replace("Category", $model, $file_content);
        file_put_contents('app/Http/Requests/'.$model.'Request.php', $str);

        copy('lang/en/categories.php', 'lang/en/'.$model_name.'.php');
        copy('lang/ar/categories.php', 'lang/ar/'.$model_name.'.php');

        $file_content = file_get_contents('routes/admin.php');
        $routes = "
        //addnewrouteheredontdeletemeplease

            Route::get('".$model_name."/select', [App\Http\Controllers\Admin\\".$model."Controller::class, 'select'])->name('".$model_name.".select');
            Route::delete('".$model_name."/bulk', [App\Http\Controllers\Admin\\".$model."Controller::class, 'deleteBulk'])->name('".$model_name.".deleteBulk')->middleware('permission:".$model_name.".delete');
            Route::get('".$model_name."/list', [App\Http\Controllers\Admin\\".$model."Controller::class, 'list'])->name('".$model_name.".list')->middleware('permission:".$model_name.".view');
            Route::post('".$model_name."', [App\Http\Controllers\Admin\\".$model."Controller::class, 'store'])->name('".$model_name.".store')->middleware('permission:".$model_name.".create');
            Route::delete('".$model_name."/{id}', [App\Http\Controllers\Admin\\".$model."Controller::class, 'destroy'])->name('".$model_name.".destroy')->middleware('permission:".$model_name.".delete');
            Route::get('".$model_name."', [App\Http\Controllers\Admin\\".$model."Controller::class, 'index'])->name('".$model_name.".index')->middleware('permission:".$model_name.".view');
            Route::get('".$model_name."/create', [App\Http\Controllers\Admin\\".$model."Controller::class, 'create'])->name('".$model_name.".create')->middleware('permission:".$model_name.".create');
            Route::match(['PUT', 'PATCH'], '".$model_name."/{id}', [App\Http\Controllers\Admin\\".$model."Controller::class, 'update'])->name('".$model_name.".update')->middleware('permission:".$model_name.".edit');
            Route::get('".$model_name."/{id}/edit', [App\Http\Controllers\Admin\\".$model."Controller::class, 'edit'])->name('".$model_name.".edit')->middleware('permission:".$model_name.".edit');


        ";
        $str = str_replace("//addnewrouteheredontdeletemeplease", $routes, $file_content);
        file_put_contents('routes/admin.php',$str);

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
        file_put_contents('resources\views\admin\partials\sidebar.blade.php',$str);

        closedir($directory);
    }

}
