<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleRequest;
use App\Services\RoleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Response;
use DataTables;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    private $route = 'admin.roles';
    private $service;
    private Role $role;

    public function __construct()
    {
        $this->service = new RoleService();
        $this->role = new Role();
    }

    public function index(Request $request): View
    {
        $seo['title'] = __('roles.plural');
        return view('admin.pages.roles.index', get_defined_vars());
    }

    public function create(): View
    {
        $seo['title'] = __('roles.actions.create');
        $permissionsGroups = Permission::get()->groupBy('group');
        $itemPermissions = [];
        return view('admin.pages.roles.create_edit', get_defined_vars());
    }

    public function edit($id): View
    {
        $item = $this->role->findOrFail($id);
        $seo['title'] = $item->name;
        $permissionsGroups = Permission::get()->groupBy('group');
        $itemPermissions = $item->permissions->pluck('id')->toArray();
        return view('admin.pages.roles.create_edit', get_defined_vars());
    }

    public function store(RoleRequest $request) : RedirectResponse
    {
        $this->service->createItem($request->validated());
        return to_route($this->route.'.index');
    }

    public function update(RoleRequest $request, $id) : RedirectResponse
    {
        $this->service->updateItem($request->validated(),$id);
        return to_route($this->route.'.index');
    }

    public function destroy($id) : RedirectResponse
    {
        $this->service->deleteItem($id);
        return to_route($this->route.'.index');
    }

    public function list(Request $request) : JsonResponse
    {
        return $this->service->getList($request);
    }

    public function select(Request $request) : JsonResponse | string
    {
        return $this->service->select($request);
    }
}
