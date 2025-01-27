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
        // dd($item->steps_list);
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


    public function update(TripRequest $request, $id): RedirectResponse
    {
        $trip = Trip::findOrFail($id);

        $folder_path = "images/trips";
        $storedPath = null;
        if ($request->hasFile('cover')) {
            $file = $request->file('cover');
            $storedPath = $this->storageService->storeFile($file, $folder_path);
        }
        $data = [
            'cover' => $storedPath ?? $trip->cover,
            'title_ar' => $request->title_ar ?? $trip->title_ar,
            'title_en' => $request->title_en ?? $trip->title_en,
            'description_en' => $request->description_en ?? $trip->description_en,
            'description_ar' => $request->description_ar ?? $trip->description_ar,
            'price' => $request->price ?? $trip->price,
            'start_point_en' => $request->start_point_en ?? $trip->start_point_en,
            'start_point_ar' => $request->start_point_ar ?? $trip->start_point_ar,
            'end_point_en' => $request->end_point_en ?? $trip->end_point_en,
            'end_point_ar' => $request->end_point_ar ?? $trip->end_point_ar,
            'program_time_en' => $request->program_time_en ?? $trip->program_time_en,
            'program_time_ar' => $request->program_time_ar ?? $trip->program_time_ar,
            'people' => $request->people ?? $trip->people,
            'steps_list' => $request->steps_list ?? $trip->steps_list,
            'start_long' => $request->start_long ?? $trip->start_long,
            'start_lat' => $request->start_lat ?? $trip->start_lat,
            'end_long' => $request->end_long ?? $trip->end_long,
            'end_lat' => $request->end_lat ?? $trip->end_lat,
            'free_cancelation' => $request->free_cancelation ?? $trip->free_cancelation,
            'available_days' => $request->available_days ?? $trip->available_days,
            'available_times' => $request->available_times ?? $trip->available_times,
            'pay_later' => $request->pay_later ?? $trip->pay_later,
            'city_id' => $request->city_id ?? $trip->city_id,
            'updated_by' => auth()->user()->id,
        ];
        $item = $trip->update($data);

        if ($item) {
            if ($request->filled('sub_category_ids')) {
                TripSubCategory::where('trip_id', $trip->id)->delete();
            }
            foreach ($request->sub_category_ids as $sub_category_id) {
                $feature = [
                    'trip_id' => $trip->id,
                    'sub_category_id' => $sub_category_id,
                ];
                TripSubCategory::create($feature);
            }
            if ($request->filled('featurs')) {
                TripFeature::where('trip_id', $trip->id)->delete();
            }
            foreach ($request->featurs as $feature_data) {
                $feature = [
                    'trip_id' => $trip->id,
                    'title_ar' => $feature_data['title_ar'] ?? null,
                    'title_en' => $feature_data['title_en'] ?? null,
                    'description_en' => $feature_data['description_en'] ?? null,
                    'description_ar' => $feature_data['description_ar'] ?? null,
                ];
                TripFeature::create($feature);
            }
            $images = $request->file('images');
            if ($request->filled('images')) {
                Attachment::where('model_id', $trip->id)->where('model_type', 'trip')->delete();
            }
            foreach ($images as $image) {
                $storedPath = $this->storageService->storeFile($image, $folder_path);
                $attachment = [
                    'model_id' => $trip->id,
                    'model_type' => 'trip',
                    'attachment' => $storedPath,
                    'title' => "trip",
                ];
                Attachment::create($attachment);
            }
        }

        flash(__('trips.messages.created'))->success();

        return to_route($this->route . '.index');
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
