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

        $effectivenes->translations()->delete();
        foreach ($request->translations as $tr) {
            $effectivenes->translations()->create($tr);
        }

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
        $data = Effectivenes::with(['vendor','translations' => function ($q) {
            $q->where('locale', app()->getLocale());
        }])->whereHas('vendor')->select('effectivenes.*');
        return FacadesDataTables::of($data)
            ->addIndexColumn()
            ->addColumn('vendor', function ($item) {
                if (auth()->user()->can('suppliers.show') && $item->vendor) {
                    return '<a href="' . route('admin.suppliers.show', $item->vendor?->id) . '">' . $item->vendor?->name . '</a>';
                }
                return $item->vendor ? $item->vendor?->name. ' (P-'.$item->vendor?->code .')' : '--';
            })
        ->addColumn('title', function ($item) {
            return $item->translations->first()->title ?? '';
        })

            ->addColumn('status', function ($item) {
                return $item?->active == 1 ? '<button class="btn btn-sm btn-outline-success me-1 waves-effect"><i data-feather="check" ></i></button>' : '<button class="btn btn-sm btn-outline-danger me-1 waves-effect"><i data-feather="x" ></i></button>';
            })
            ->editColumn('created_at', function ($item) {
                return $item->created_at?->format('Y-m-d H:i') ;
            })

            ->rawColumns(['vendor','status','title'])
            ->make(true);
    }
}
