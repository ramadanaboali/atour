<?php

namespace App\Http\Controllers\Api\V1\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\Vendor\UserRequest;
use App\Models\User;
use App\Services\Vendor\UserService;
use App\Services\General\StorageService;
use Illuminate\Support\Facades\Schema;
use function response;

class UserController extends Controller
{
    protected UserService $service;
        protected StorageService $storageService;

    public function __construct(UserService $service,StorageService $storageService)
    {
        $this->service = $service;

        $this->storageService = $storageService;

    }
    public function index(PaginateRequest $request)
    {
        $input = $this->service->inputs($request->all());
        $model = new User();
        $columns = Schema::getColumnListing($model->getTable());

        if (count($input["columns"]) < 1 || (count($input["columns"]) != count($input["column_values"])) || (count($input["columns"]) != count($input["operand"]))) {
            $wheres = [];
        } else {
            $wheres = $this->service->whereOptions($input, $columns);

        }
        $data = $this->service->Paginate($input, $wheres);
        //$meta = $this->service->Meta($data,$input);
        return response()->apiSuccess($data);
    }

    public function show($id){
        return response()->apiSuccess($this->service->get($id));
    }

    public function store(UserRequest $request)
    {
        $data = $request->all();
        $folder_path = "images/user";
        $storedPath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $storedPath = $this->storageService->storeFile($file, $folder_path);
        }
        $data['image'] = $storedPath;
        $data['type'] = 'admin';
        $data['model_id'] = auth()->user()->model_id;
        return response()->apiSuccess($this->service->createUser($data));
    }

    public function update(UserRequest $request, User $user)
    {
        $data = $request->all();
        return response()->apiSuccess($this->service->updateUser($data,$user));
    }
    public function delete(User $user)
    {
        return response()->apiSuccess($this->service->delete($user));
    }

}
