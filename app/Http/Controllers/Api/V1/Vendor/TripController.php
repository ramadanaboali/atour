<?php

namespace App\Http\Controllers\Api\V1\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\Vendor\TripRequest;
use App\Http\Resources\TripResource;
use App\Models\Attachment;
use App\Models\Trip;
use App\Services\General\StorageService;
use App\Services\Vendor\TripService;
use Illuminate\Support\Facades\Schema;
use function response;

class TripController extends Controller
{
    protected TripService $service;
    protected StorageService $storageService;

    public function __construct(TripService $service,StorageService $storageService)
    {
        $this->storageService = $storageService;
        $this->service = $service;
    }
    public function index(PaginateRequest $request)
    {
        $input = $this->service->inputs($request->all());
        $model = new Trip();
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
        $data = new TripResource($this->service->get($id));
        return response()->apiSuccess($data);
    }

    public function store(TripRequest $request)
    {

        $data = $request->except(['cover']);
        $folder_path = "images/Trip";
        $storedPath = null;
        if ($request->hasFile('cover')) {
            $file = $request->file('cover');
            $storedPath = $this->storageService->storeFile($file, $folder_path);
        }
        $data['cover'] = $storedPath;
        $data['custom_fields'] = json_encode($request->custom_fields);
        $data['vendor_id'] = auth()->user()->id;
        $item = $this->service->store($data);
        if ($request->images) {
            for ($i = 0; $i < count($request->images); $i++) {
                $image = storeFile($request->images[$i], 'files');
                $item->attachments()->
                    save(
                        new Attachment(
                            [
                                'model_id' => $item->id,
                                'attachment' => $image,
                                'title' => 'images',
                                'model_type' => 'trip',
                            ]
                        )
                    );
            }
        }

        return response()->apiSuccess($this->service->store($data));
    }

    public function update(TripRequest $request, Trip $trip)
    {

        $data = $request->except(['cover','_method']);
        if ($request->hasFile('cover')) {
            $folder_path = "images/Trip";
            $storedPath = null;
            $file = $request->file('cover');
            $storedPath = $this->storageService->storeFile($file, $folder_path);
            $data['cover'] = $storedPath;
        }
        return response()->apiSuccess($this->service->update($data,$trip));
    }
    public function delete(Trip $trip)
    {

        return response()->apiSuccess($this->service->delete($trip));
    }

}
