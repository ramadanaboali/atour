<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\GiftRequest;
use App\Models\Attachment;
use App\Models\Gift;
use App\Services\General\StorageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables as FacadesDataTables;

class GiftController extends Controller
{
    private $viewIndex  = 'admin.pages.gifts.index';
    private $viewEdit   = 'admin.pages.gifts.create_edit';
    private $viewShow   = 'admin.pages.gifts.show';
    private $route      = 'admin.gifts';
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
        $item = Gift::findOrFail($id);
        // dd($item->attachments);
        return view($this->viewEdit, get_defined_vars());
    }

    public function show($id): View
    {
        $item = Gift::findOrFail($id);
        return view($this->viewShow, get_defined_vars());
    }

    public function destroy($id): RedirectResponse
    {
        $item = Gift::findOrFail($id);
        if ($item->bookings->count()) {
            flash(__('gifts.messages.has_bookings'))->error();
            return to_route($this->route . '.index');
        }
        if ($item->delete()) {
            flash(__('gifts.messages.deleted'))->success();
        }
        return to_route($this->route . '.index');
    }

    public function update(GiftRequest $request, $id)
    {
        $data = $request->except(['_token', '_method']);
        $gift = Gift::findOrFail($id);
        if ($request->hasFile('cover')) {
            $data['cover'] = $request->file('cover')->store('covers', 'public');
        }
        $gift->update($data);
        $gift->translations()->delete();
        foreach ($request->translations as $tr) {
            $gift->translations()->create($tr);
        }
        $gift->subCategory()->sync($data['sub_category_ids']);
        if ($request->hasFile('images')) {
            Attachment::where('model_id', $gift->id)->where('model_type', 'gift')->delete();
            $images = $request->file('images');
            foreach ($images as $image) {
                $storedPath = $image->store('gift_images', 'public');
                $attachment = [
                    'model_id' => $gift->id,
                    'model_type' => 'gift',
                    'attachment' => $storedPath,
                    'title' => "gift",
                ];
                Attachment::create($attachment);
            }
        }
        flash(__('gifts.messages.updated'))->success();
        return redirect()->route('admin.gifts.index');
    }

    public function list(Request $request): JsonResponse
    {
        $data = Gift::with(['vendor','translations' => function ($q) {
            $q->where('locale', app()->getLocale());
        }])->whereHas('vendor')->select('gifts.*');
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
