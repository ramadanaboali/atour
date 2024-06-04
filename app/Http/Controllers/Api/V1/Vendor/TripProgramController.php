<?php

namespace App\Http\Controllers\Api\V1\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\Vendor\TripProgramRequest;
use App\Models\TripProgram;
use App\Services\General\StorageService;
use App\Services\Vendor\TripProgramService;
use Illuminate\Support\Facades\Schema;
use function response;

class TripProgramController extends Controller
{
    protected TripProgramService $service;
    protected StorageService $storageService;

    public function __construct(TripProgramService $service,StorageService $storageService)
    {
        $this->storageService = $storageService;
        $this->service = $service;
    }
    public function index(PaginateRequest $request)
    {
        $input = $this->service->inputs($request->all());
        $model = new TripProgram();
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

    public function store(TripProgramRequest $request)
    {

        $data = $request->except(['image']);
        $folder_path = "images/TripProgram";
        $storedPath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $storedPath = $this->storageService->storeFile($file, $folder_path);
        }
        $data['image'] = $storedPath;

        return response()->apiSuccess($this->service->store($data));
    }

    public function update(TripProgramRequest $request, TripProgram $trip_program)
    {

        $data = $request->except(['image','_method']);
        if ($request->hasFile('image')) {
            $folder_path = "images/TripProgram";
            $storedPath = null;
            $file = $request->file('image');
            $storedPath = $this->storageService->storeFile($file, $folder_path);
            $data['image'] = $storedPath;
        }
        return response()->apiSuccess($this->service->update($data,$trip_program));
    }
    public function delete(TripProgram $trip_program)
    {

        return response()->apiSuccess($this->service->delete($trip_program));
    }

}
