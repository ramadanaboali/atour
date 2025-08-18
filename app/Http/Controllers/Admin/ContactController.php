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
                return $item->closed_at?->format('Y-m-d H:i');
            })
            ->editColumn('status', function ($item) {

                if ($item->status != "closed" && auth()->user()->can('contacts.status')) {
                    $icon = 'check';
                    $color = 'primary';
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
            return '<span class="badge bg-label-success me-1">' . __('contacts.statuses.'.$item->status) . '</span>';
        })

        ->rawColumns(['name','email','phone','created_at','closed_at','status'])
            ->make(true);
    }
}
