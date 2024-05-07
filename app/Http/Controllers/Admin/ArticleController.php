<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ArticleRequest;
use App\Models\Article;
use App\Models\Attachment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables as FacadesDataTables;

class ArticleController extends Controller
{
    private $viewIndex  = 'admin.pages.articles.index';
    private $viewEdit   = 'admin.pages.articles.create_edit';
    private $viewShow   = 'admin.pages.articles.show';
    private $route      = 'admin.articles';

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
        $item = Article::findOrFail($id);
        return view($this->viewEdit, get_defined_vars());
    }

    public function show($id): View
    {
        $item = Article::findOrFail($id);
        return view($this->viewShow, get_defined_vars());
    }

    public function destroy($id): RedirectResponse
    {
        $item = Article::findOrFail($id);
        if ($item->delete()) {
            flash(__('articles.messages.deleted'))->success();
        }
        return to_route($this->route . '.index');
    }

    public function store(ArticleRequest $request): RedirectResponse
    {
        if ($this->processForm($request)) {
            flash(__('articles.messages.created'))->success();
        }
        return to_route($this->route . '.index');
    }
    public function select(Request $request): JsonResponse|string
    {
       $data = Article::distinct()
                ->where('active',true)
                ->where(function ($query) use ($request) {
                if ($request->filled('q')) {
                    if(App::isLocale('en')) {
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


    public function update(ArticleRequest $request, $id): RedirectResponse
    {
        $item = Article::findOrFail($id);
        if ($this->processForm($request, $id)) {
            flash(__('articles.messages.updated'))->success();
        }
        return to_route($this->route . '.index');
    }

    protected function processForm($request, $id = null): Article|null
    {
        // dd($request->all());
        $item = $id == null ? new Article() : Article::find($id);
        $data= $request->except(['_token', '_method']);

        $item = $item->fill($data);
        if($request->filled('active')){
            $item->active = 1;
        }else{
            $item->active = 0;
        }
        if ($id == null) {
            $item->created_by = auth()->user()->id;
        }else{
            $item->updated_by = auth()->user()->id;
        }
        if ($item->save()) {

            if ($request->editimages) {
                Attachment::whereNotIn('id', $request->editimages)->where('model_id', $item->id)->delete();
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
                                    'model_type' => 'article',
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
        $data = Article::select('*');
        return FacadesDataTables::of($data)
        ->addIndexColumn()
        ->addColumn('photo', function ($item) {
            return '<img src="' . $item->photo . '" height="100px" width="100px">';
        })
        ->editColumn('active', function ($item) {
            return $item->active==1 ? '<button class="btn btn-sm btn-outline-success me-1 waves-effect"><i data-feather="check" ></i></button>':'<button class="btn btn-sm btn-outline-danger me-1 waves-effect"><i data-feather="x" ></i></button>';
        })
        ->filterColumn('title', function ($query, $keyword) {
                 if(App::isLocale('en')) {
                     return $query->where('title_en', 'like', '%'.$keyword.'%');
                 } else {
                     return $query->where('title_ar', 'like', '%'.$keyword.'%');
                 }
             })
        ->rawColumns(['photo','active'])
        ->make(true);
    }
}
