<?php
namespace App\Services;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use DataTables;
use Spatie\Permission\Models\Role;

class RoleService
{
    private Role $role;

    public function __construct()
    {
        $this->role = new Role();
    }

    public function createItem($request): void
    {
        try {
            $item = $this->role->create($request);
            $this->savePermissions($item, $request);
            flash(__('roles.messages.created'))->success();
        } catch (\Exception $e) {
            flash($e->getMessage())->error();
        }
    }

    public function updateItem($request, $id): void
    {
        $excepts = [
            'admin',
            'vendor',
            'merchant',
        ];
        // try {
            $item = $this->role->findOrFail($id);

            if (in_array($item->name, $excepts)) {
                unset($request['name']);
            }
            $item->update($request);
            $this->savePermissions($item, $request);
            flash(__('roles.messages.updated'))->success();
        // } catch (\Exception $e) {
        //     flash($e->getMessage())->error();
        // }
    }

    public function deleteItem($id): void
    {
        try {
            $item = $this->role->findOrFail($id);
            $item->delete();
            flash(__('roles.messages.deleted'))->success();
        } catch (\Exception $e) {
            flash($e->getMessage())->error();
        }
    }

    public function savePermissions($item, $request): void
    {
        if (isset($request['permissions']) && is_array($request['permissions']) && count($request['permissions']) > 0) {
            $item->syncPermissions($request['permissions']);
        }
    }

    public function getList(Request $request): JsonResponse
    {
        $data = $this->role->select('*');

        return Datatables::of($data)
            ->addIndexColumn()
            ->make(true);
    }

    public function select(Request $request): JsonResponse | string
    {
        $number = 10;
        if ($request->filled('all')) {
            $number = -1;
        }
        $items = $this->role->where(function ($query) use ($request) {
                if ($request->filled('q')) {
                    $query->where('name','LIKE',  "%".$request->q."%");
                }
            })
            ->select(['id', 'name as text'])
            ->take($number)
            ->get();

        if ($request->filled('return_type') && $request->return_type == 'html') {
            return $this->renderHtml($items, $request);
        }

        return response()->json($items);
    }

    public function renderHtml($items,$request)
    {
        $html = '<option value="">'.__('roles.select').'</option>';
        foreach ($items as $item) {
            $selected = $request->selected == $item->id ? 'selected' : '';
            $html .= '<option value="'.$item->id.'" '.$selected.'>'.$item->name.'</option>';
        }
        return $html;
    }
}
