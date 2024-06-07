<?php

namespace App\Http\Controllers\Api\V1\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\Vendor\ServiceRequest;
use App\Models\Service;
use App\Services\General\StorageService;
use App\Services\Vendor\ServiceService;
use Illuminate\Support\Facades\Schema;
use function response;

class ServiceController extends Controller
{
    protected ServiceService $service;
    protected StorageService $storageService;

    public function __construct(ServiceService $service,StorageService $storageService)
    {
        $this->storageService = $storageService;
        $this->service = $service;
    }
    public function index(PaginateRequest $request)
    {
        $input = $this->service->inputs($request->all());
        $model = new Service();
        $columns = Schema::getColumnListing($model->getTable());

        if (count($input["columns"]) < 1 || (count($input["columns"]) != count($input["column_values"])) || (count($input["columns"]) != count($input["operand"]))) {
            $wheres = [];
        } else {
            $wheres = $this->service->whereOptions($input, $columns);
        }
        $data = $this->service->Paginate($input, $wheres);

        return response()->apiSuccess($data);

    }

    public function show($id){
        $data = $this->service->get($id);
        return response()->apiSuccess($data);
    }

    public function store(ServiceRequest $request)
    {

        $data = $request->except(['cover']);
        $folder_path = "images/Service";
        $storedPath = null;
        if ($request->hasFile('cover')) {
            $file = $request->file('cover');
            $storedPath = $this->storageService->storeFile($file, $folder_path);
        }
        $data['cover'] = $storedPath;
        $data['vendor_id'] = auth()->user()->id;

        return response()->apiSuccess($this->service->store($data));
    }

    public function update(ServiceRequest $request, Service $trip)
    {

        $data = $request->except(['cover','_method']);
        if ($request->hasFile('cover')) {
            $folder_path = "images/Service";
            $storedPath = null;
            $file = $request->file('cover');
            $storedPath = $this->storageService->storeFile($file, $folder_path);
            $data['cover'] = $storedPath;
        }
        return response()->apiSuccess($this->service->update($data,$trip));
    }
    public function delete(Service $trip)
    {

        return response()->apiSuccess($this->service->delete($trip));
    }

}
