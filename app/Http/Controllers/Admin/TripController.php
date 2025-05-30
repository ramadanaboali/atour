<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TripRequest;
use App\Models\Attachment;
use App\Models\Trip;
use App\Models\TripFeature;
use App\Models\TripSubCategory;
use App\Services\General\StorageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables as FacadesDataTables;

class TripController extends Controller
{
    private $viewIndex  = 'admin.pages.trips.index';
    private $viewEdit   = 'admin.pages.trips.create_edit';
    private $viewShow   = 'admin.pages.trips.show';
    private $route      = 'admin.trips';
    protected StorageService $storageService;

    public function __construct(StorageService $storageService)
    {

        $this->storageService = $storageService;

    }
    public function index(Request $request): View
    {
        return view($this->viewIndex, get_defined_vars());
    }

    public function create(): View
    {
        return view($this->viewEdit, get_defined_vars());
    }

    public function edit($id): View
    {
        $item = Trip::findOrFail($id);
        // dd($item->attachments[0]->file);
        return view($this->viewEdit, get_defined_vars());
    }

    public function show($id): View
    {
        $item = Trip::findOrFail($id);
        return view($this->viewShow, get_defined_vars());
    }

    public function destroy($id): RedirectResponse
    {
        $item = Trip::findOrFail($id);
        if ($item->delete()) {
            flash(__('trips.messages.deleted'))->success();
        }
        return to_route($this->route . '.index');
    }




     public function update(TripRequest $request,  $id)
    {
        $data = $request->validated();
        $trip = Trip::findOrFail($id);
        // Format available_times
        $available_times = [];
        foreach ($data['available_times']['from_time'] as $index => $from_time) {
            $available_times[] = [
                'from_time' => $from_time,
                'to_time' => $data['available_times']['to_time'][$index]
            ];
        }
        $data['available_times'] = $available_times;

        // Format available_days
        $data['available_days'] = array_values($data['available_days']);

        // Handle file upload
        if ($request->hasFile('cover')) {
            $data['cover'] = $request->file('cover')->store('covers', 'public');
        }

        // Update trip
        $trip->update($data);

        // Sync relationships
        $trip->requirements()->sync($data['requirement_ids']);
        $trip->subCategory()->sync($data['sub_category_ids']);
        $trip->features()->sync($data['featur_ids']);

        // Handle images
        if ($request->hasFile('images')) {
            // Delete existing attachments
            Attachment::where('model_id', $trip->id)->where('model_type', 'trip')->delete();

            // Save new images
            $images = $request->file('images');
            foreach ($images as $image) {
                $storedPath = $image->store('trip_images', 'public');
                $attachment = [
                    'model_id' => $trip->id,
                    'model_type' => 'trip',
                    'attachment' => $storedPath,
                    'title' => "trip",
                ];
                Attachment::create($attachment);
            }
        }

        // Redirect
        return redirect()->route('admin.trips.index')->with('success', 'Trip updated successfully.');
    }



    public function list(Request $request): JsonResponse
    {
        $data = Trip::with('vendor')->select('trips.*');
        return FacadesDataTables::of($data)
            ->addIndexColumn()
            ->addColumn('vendor', function ($item) {
                return $item->vendor?->name ;
            })
            ->addColumn('status', function ($item) {
                return $item?->active == 1 ? '<button class="btn btn-sm btn-outline-success me-1 waves-effect"><i data-feather="check" ></i></button>' : '<button class="btn btn-sm btn-outline-danger me-1 waves-effect"><i data-feather="x" ></i></button>';
            })
            ->editColumn('created_at', function ($item) {
                return $item->created_at?->format('Y-m-d H:i') ;
            })
             ->orderColumn('title', function ($query, $order) {
                 if (App::isLocale('en')) {
                     return $query->orderby('title_en', $order);
                 } else {
                     return $query->orderby('title_ar', $order);
                 }
             })
             ->filterColumn('title', function ($query, $keyword) {
                 if (App::isLocale('en')) {
                     return $query->where('title_en', 'like', '%'.$keyword.'%');
                 } else {
                     return $query->where('title_ar', 'like', '%'.$keyword.'%');
                 }
             })
             ->filterColumn('description', function ($query, $keyword) {
                 if (App::isLocale('en')) {
                     return $query->where('description_en', 'like', '%'.$keyword.'%');
                 } else {
                     return $query->where('description_ar', 'like', '%'.$keyword.'%');
                 }
             })
            ->rawColumns(['vendor','status'])
            ->make(true);
    }
}
