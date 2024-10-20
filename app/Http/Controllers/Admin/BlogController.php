<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BlogRequest;
use App\Models\Attachment;
use App\Models\Blog;
use App\Services\General\StorageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables as FacadesDataTables;

class BlogController extends Controller
{
    private $viewIndex  = 'admin.pages.blogs.index';
    private $viewEdit   = 'admin.pages.blogs.create_edit';
    private $viewShow   = 'admin.pages.blogs.show';
    private $route      = 'admin.blogs';
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
        $item = Blog::findOrFail($id);
        return view($this->viewEdit, get_defined_vars());
    }

    public function show($id): View
    {
        $item = Blog::findOrFail($id);
        return view($this->viewShow, get_defined_vars());
    }

    public function destroy($id): RedirectResponse
    {
        $item = Blog::findOrFail($id);
        if ($item->delete()) {
            flash(__('blogs.messages.deleted'))->success();
        }
        return to_route($this->route . '.index');
    }

    public function store(BlogRequest $request): RedirectResponse
    {
        if ($this->processForm($request)) {
            flash(__('blogs.messages.created'))->success();
        }
        return to_route($this->route . '.index');
    }
    public function select(Request $request): JsonResponse|string
    {
        $data = Blog::distinct()
                 ->where('active', true)
                 ->where(function ($query) use ($request) {
                     if ($request->filled('q')) {
                         if (App::isLocale('en')) {
                             return $query->where('title_en', 'like', '%'.$request->q.'%');
                         } else {
                             return $query->where('title_ar', 'like', '%'.$request->q.'%');
                         }
                     }
                 })->select('id', 'title_en', 'title_ar')->get();

        if ($request->filled('pure_select')) {
            $html = '<option value="">'. __('category.select') .'</option>';
            foreach ($data as $row) {
                $html .= '<option value="'.$row->id.'">'.$row->text.'</option>';
            }
            return $html;
        }
        return response()->json($data);
    }


    public function update(BlogRequest $request, $id): RedirectResponse
    {
        $item = Blog::findOrFail($id);
        if ($this->processForm($request, $id)) {
            flash(__('blogs.messages.updated'))->success();
        }
        return to_route($this->route . '.index');
    }

    protected function processForm($request, $id = null): Blog|null
    {
        $item = $id == null ? new Blog() : Blog::find($id);
        $data = $request->except(['_token', '_method']);

        $item = $item->fill($data);
        if ($request->filled('active')) {
            $item->active = 1;
        } else {
            $item->active = 0;
        }
        if ($id == null) {
            $item->created_by = auth()->user()->id;
        } else {
            $item->updated_by = auth()->user()->id;
        }
        if ($item->save()) {


            $folder_path = "blogs";
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $item->cover  = $this->storageService->storeFile($file, $folder_path);
                $item->save();
            }
            if ($request->hasFile('publisher_image')) {
                $file = $request->file('publisher_image');
                $item->publisher_image  = $this->storageService->storeFile($file, $folder_path);
                $item->save();
            }


            if ($request->editimages) {

                Attachment::whereNotIn('id', $request->editimages)->where('model_type', 'blog')->where('model_id', $item->id)->delete();
            }
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
                                    'model_type' => 'blog',
                                ]
                            )
                        );
                }
            }

            return $item;
        }
        return null;
    }

    public function list(Request $request): JsonResponse
    {
        $data = Blog::select('*');
        return FacadesDataTables::of($data)
        ->addIndexColumn()
        ->addColumn('photo', function ($item) {
            return '<img src="' . $item->photo . '" height="100px" width="100px">';
        })
        ->editColumn('active', function ($item) {
            return $item->active == 1 ? '<button class="btn btn-sm btn-outline-success me-1 waves-effect"><i data-feather="check" ></i></button>' : '<button class="btn btn-sm btn-outline-danger me-1 waves-effect"><i data-feather="x" ></i></button>';
        })
        ->filterColumn('title', function ($query, $keyword) {
            if (App::isLocale('en')) {
                return $query->where('title_en', 'like', '%'.$keyword.'%');
            } else {
                return $query->where('title_ar', 'like', '%'.$keyword.'%');
            }
        })
        ->rawColumns(['photo','active'])
        ->make(true);
    }
}
