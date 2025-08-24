<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactUs;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables as FacadesDataTables;

class ContactController extends Controller
{
    private $viewIndex  = 'admin.pages.contacts.index';
    private $route      = 'admin.contacts';

    public function index(Request $request): View
    {
        return view($this->viewIndex, get_defined_vars());
    }

    public function destroy($id): RedirectResponse
    {
        $item = ContactUs::findOrFail($id);
        if ($item->delete()) {
            flash(__('contacts.messages.deleted'))->success();
        }
        return to_route($this->route . '.index');
    }
    public function status(Request $request, $id)
    {
        $item = ContactUs::findOrFail($id);
        if ($request->status=='closed') {
            $item->closed_at = now();
        }
        $item->status = $request->status;
        $item->notes = $request->notes;
        $item->save();
        flash(__('contacts.messages.updated'))->success();
        return to_route($this->route . '.index');
    }


    public function list(Request $request): JsonResponse
    {
        $data = ContactUs::with('user')->orderBy('id', 'DESC')->select('contact_us.*');
        return FacadesDataTables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function ($item) {
                return $item->user?->name;
            })
            ->addColumn('email', function ($item) {
                return $item->user?->email;
            })
            ->addColumn('phone', function ($item) {
                return $item->user?->phone;
            })
            ->editColumn('created_at', function ($item) {
                return $item->created_at->format('Y-m-d H:i');
            })
            ->editColumn('closed_at', function ($item) {
                return $item->closed_at;
            })
            ->editColumn('status', function ($item) {

                $icon = 'check';
                $color = 'success';
                if ($item->status != "closed" && auth()->user()->can('contacts.status')) {
                    if ($item->status === 'open') {
                        $icon = 'play';
                        $color = 'warning';
                    } elseif ($item->status === 'onprogress') {
                        $icon = 'pause';
                        $color = 'info';
                    }
                    return '
                    <button type="button" class="btn btn-sm btn-outline-' . $color . ' me-1 waves-effect change_status " data-url="' . route('admin.contacts.status', $item->id) . '" >
                        <i data-feather="' . $icon . '" ></i>
                    </button>';
                    }
                        return '<button type="button" class="btn btn-sm btn-outline-' . $color . ' me-1 waves-effect  " >
                            <i data-feather="' . $icon . '" ></i>
                        </button>';

        })

        ->rawColumns(['name','email','phone','created_at','closed_at','status'])
            ->make(true);
    }
}
