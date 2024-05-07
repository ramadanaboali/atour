<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Route;

class installPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'install:permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This Command Custom for install permissions for application that used spatie\laravel-permessions';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->installRoutesPermissions();
        $this->assignPermissionsToAdmin();

        return 0;
    }

    private function installRoutesPermissions(): void
    {
        $adminpermissions = Permission::where('guard_name', 'web')->where('model_type', 'admin')->get()->pluck('name')->toArray();
        $generalpermissions = Permission::where('guard_name', 'web')->where('model_type', 'general')->get()->pluck('name')->toArray();
        $supplierpermissions = Permission::where('guard_name', 'web')->where('model_type', 'supplier')->get()->pluck('name')->toArray();
        $routes = Route::getRoutes();
        $arr = [];
        app()->setLocale('ar');
        $tempPermissions = [];
        foreach ($routes as $route) {
            $middleware = $route->middleware();
            if (is_array($middleware)) {
                foreach ($middleware as $middleware) {
                    if (strpos($middleware, 'adminPermission:') > -1) {
                        $permission = explode(':', $middleware);
                        if (!in_array($permission[1], $tempPermissions)) {
                            array_push($tempPermissions, $permission[1]);
                            $group = explode('.', $permission[1]);
                            $arr[] = [
                                'name' => $permission[1],
                                'model_type' => 'admin',
                                'group_display_name' => __('permissions.'.($group[0] ?? null)),
                                'display_name' => __('permissions.'.($group[1] ?? null)),
                                'group' => $group[0] ?? null,
                                'guard_name' => 'web',
                                'created_at' => now(),
                                'updated_at' => now(),
                            ];
                        }
                    }
                    if (strpos($middleware, 'supplierPermission:') > -1) {
                        $permission = explode(':', $middleware);
                        if (!in_array($permission[1], $tempPermissions)) {
                            array_push($tempPermissions, $permission[1]);
                            $group = explode('.', $permission[1]);
                            $arr[] = [
                                'name' => $permission[1],
                                'model_type' => 'supplier',
                                'group_display_name' => __('permissions.'.($group[0] ?? null)),
                                'display_name' => __('permissions.'.($group[1] ?? null)),
                                'group' => $group[0] ?? null,
                                'guard_name' => 'web',
                                'created_at' => now(),
                                'updated_at' => now(),
                            ];
                        }
                    }
                    if (strpos($middleware, 'generalPermission:') > -1) {
                        $permission = explode(':', $middleware);
                        if (!in_array($permission[1], $tempPermissions)) {
                            array_push($tempPermissions, $permission[1]);
                            $group = explode('.', $permission[1]);
                            $arr[] = [
                                'name' => $permission[1],
                                'model_type' => 'general',
                                'group_display_name' => __('permissions.'.($group[0] ?? null)),
                                'display_name' => __('permissions.'.($group[1] ?? null)),
                                'group' => $group[0] ?? null,
                                'guard_name' => 'web',
                                'created_at' => now(),
                                'updated_at' => now(),
                            ];
                        }
                    }
                }
            }
        }
        if (count($arr) > 0) {
            foreach ($arr as $row) {
                Permission::updateOrCreate(
                    ['name' => $row['name'],
                    'model_type' => $row['model_type'],
                    'guard_name' => $row['guard_name']],
                    $row
                );
            }
            $this->info('Routes Permissions installed');
        }
    }



    private function assignPermissionsToAdmin(): void
    {
        $user = User::where('email', config('admin.email'))->first();
        $role = Role::where('name', 'admin')->where('model_type', 'admin')->first();

        if(!$role) {
            $role = Role::Create([
                'name' => 'admin',
                'model_type' => 'admin',
                'guard_name' => 'web',
                'can_edit' => 0,
                'display_name' => 'سوبر ادمن'
            ]);
        }
        if ($user && $role) {
            $user->syncRoles($role->id);
            $role->syncPermissions(Permission::whereIn('model_type', ['admin','general'])->get());
            Artisan::call('cache:clear');
            $this->info("All Permissions assigned to user {$user->email}");
        }
        $supplierRole = Role::firstOrCreate(['name' => 'supplier'], ['name' => 'supplier','model_type' => 'supplier','can_edit' => 0,'guard_name' => 'web','display_name' => ' ادمن']);
        if ($supplierRole) {
            $supplierPermissions = Permission::whereIn('model_type', ['supplier','general'])->get();
            $supplierRole->syncPermissions($supplierPermissions);
        }
    }
}
