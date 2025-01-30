<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\EffectivenesRequest;
use App\Models\Attachment;
use App\Models\Effectivenes;
use App\Services\General\StorageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables as FacadesDataTables;

class EffectivenesController extends Controller
{
    private $viewIndex  = 'admin.pages.effectivenes.index';
    private $viewEdit   = 'admin.pages.effectivenes.create_edit';
    private $viewShow   = 'admin.pages.effectivenes.show';
    private $route      = 'admin.effectivenes';
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
        $item = Effectivenes::findOrFail($id);
        // dd($item->attachments);
        return view($this->viewEdit, get_defined_vars());
    }

    public function show($id): View
    {
        $item = Effectivenes::findOrFail($id);
        return view($this->viewShow, get_defined_vars());
    }

    public function destroy($id): RedirectResponse
    {
        $item = Effectivenes::findOrFail($id);
        if ($item->delete()) {
            flash(__('effectivenes.messages.deleted'))->success();
        }
        return to_route($this->route . '.index');
    }


    public function update(EffectivenesRequest $request, $id)
    {
        $data = $request->except(['_token', '_method']);
        $effectivenes = Effectivenes::findOrFail($id);
        if ($request->hasFile('cover')) {
            $data['cover'] = $request->file('cover')->store('covers', 'public');
        }
        $effectivenes->update($data);
        if ($request->hasFile('images')) {
            Attachment::where('model_id', $effectivenes->id)->where('model_type', 'effectivenes')->delete();
            $images = $request->file('images');
            foreach ($images as $image) {
                $storedPath = $image->store('effectivenes_images', 'public');
                $attachment = [
                    'model_id' => $effectivenes->id,
                    'model_type' => 'effectivenes',
                    'attachment' => $storedPath,
                    'title' => "effectivenes",
                ];
                Attachment::create($attachment);
            }
        }
        return redirect()->route('admin.effectivenes.index')->with('success', 'Effectivenes updated successfully.');
    }



    public function list(Request $request): JsonResponse
    {
        $data = Effectivenes::with('vendor')->select('effectivenes.*');
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
