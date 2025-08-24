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
                ->get();

        
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

if ($request->has('translations') && is_array($request->translations)) {
    $item->translations()->delete();
    $item->translations()->createMany($request->translations);
}

            if ($request->editimages) {
                Attachment::whereNotIn('id', $request->editimages)->where('model_type','article')->where('model_id', $item->id)->delete();
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
        $data = Article::with(['translations' => function ($q) {
            $q->where('locale', app()->getLocale());
        }])->select('articles.*');

        return FacadesDataTables::of($data)
            ->addIndexColumn()
            ->addColumn('title', function ($item) {
                return $item->translations->first()->title ?? '';
            })
        ->addColumn('photo', function ($item) {
            return '<img src="' . $item->photo . '" height="100px" width="100px">';
        })
        ->editColumn('active', function ($item) {
            return $item->active==1 ? '<button class="btn btn-sm btn-outline-success me-1 waves-effect"><i data-feather="check" ></i></button>':'<button class="btn btn-sm btn-outline-danger me-1 waves-effect"><i data-feather="x" ></i></button>';
        })
       
        ->rawColumns(['photo','active'])
        ->make(true);
    }
}
