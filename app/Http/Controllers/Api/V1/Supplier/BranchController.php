<?php

namespace App\Http\Controllers\Api\V1\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\Vendor\BranchRequest;
use App\Models\Branch;
use App\Services\Vendor\BranchService;
use App\Services\General\StorageService;
use Illuminate\Support\Facades\Schema;
use function response;

class BranchController extends Controller
{
    protected BranchService $service;
    protected StorageService $storageService;
    public function __construct(BranchService $service,StorageService $storageService)
    {
        $this->service = $service;
        $this->storageService = $storageService;

    }
    public function index(PaginateRequest $request)
    {
        $input = $this->service->inputs($request->all());
        $model = new Branch();
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

    public function store(BranchRequest $request)
    {
        $data = $request->except(['image','images','officialHours']);
        $folder_path = "images/branch";
        $storedPath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $storedPath = $this->storageService->storeFile($file, $folder_path);
        }
        $data['image'] = $storedPath;
        $data['vendor_id'] = auth()->user()->model_id;
        $branch=$this->service->store($data);

        if ($branch && $request->images) {
            $this->service->addImages($request->images,$branch->id);
        }
        if ($branch && $request->officialHours) {
            $this->service->officialHours($request->officialHours,$branch->id);
        }
        return response()->apiSuccess($branch);
    }

    public function update(BranchRequest $request, Branch $branch)
    {

        $data = $request->except(['image','images','officialHours']);
        $folder_path = "images/branch";
        $storedPath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $storedPath = $this->storageService->storeFile($file, $folder_path);
        }
        $data['image'] = $storedPath;

        if ($branch && $request->images) {
            $branch->images()->delete();
            $this->service->addImages($request->images, $branch->id);
        }
        if ($branch && $request->officialHours) {
            $branch->officialHours()->delete();
            $this->service->officialHours($request->officialHours, $branch->id);
        }

        return response()->apiSuccess($this->service->update($data,$branch));
    }
    public function delete(Branch $branch)
    {
        return response()->apiSuccess($this->service->delete($branch));
    }

}
