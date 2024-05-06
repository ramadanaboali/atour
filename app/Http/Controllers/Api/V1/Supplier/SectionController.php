<?php

namespace App\Http\Controllers\Api\V1\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\Vendor\SectionRequest;
use App\Models\Section;
use App\Services\General\StorageService;
use App\Services\Vendor\SectionService;
use Illuminate\Support\Facades\Schema;
use function response;

class SectionController extends Controller
{
    protected SectionService $service;
    protected StorageService $storageService;

    public function __construct(SectionService $service,StorageService $storageService)
    {
        $this->storageService = $storageService;
        $this->service = $service;
    }
    public function index(PaginateRequest $request)
    {
        $input = $this->service->inputs($request->all());
        $model = new Section();
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
        return response()->apiSuccess($this->service->get($id));
    }

    public function store(SectionRequest $request)
    {

        $data = $request->except(['image']);
        $folder_path = "images/section";
        $storedPath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $storedPath = $this->storageService->storeFile($file, $folder_path);
        }
        $data['image'] = $storedPath;
        $data['vendor_id'] = auth()->user()->model_id;

        return response()->apiSuccess($this->service->store($data));
    }

    public function update(SectionRequest $request, Section $section)
    {

        $data = $request->except(['image','_method']);
        if ($request->hasFile('image')) {
            $folder_path = "images/section";
            $storedPath = null;
            $file = $request->file('image');
            $storedPath = $this->storageService->storeFile($file, $folder_path);
            $data['image'] = $storedPath;
        }
        return response()->apiSuccess($this->service->update($data,$section));
    }
    public function delete(Section $section)
    {
        return response()->apiSuccess($this->service->delete($section));
    }

}
