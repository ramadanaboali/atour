<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClientRequest;
use App\Models\BookingEffectivene;
use App\Models\BookingGift;
use App\Models\BookingTrip;
use App\Models\Client;
use App\Models\Order;
use App\Models\User;
use App\Services\OneSignalService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Facades\DataTables as FacadesDataTables;

class ClientController extends Controller
{
    private $viewIndex  = 'admin.pages.clients.index';
    private $viewEdit   = 'admin.pages.clients.create_edit';
    private $viewShow   = 'admin.pages.clients.show';
    private $route      = 'admin.clients';

    public function index(Request $request): View
    {

        return view($this->viewIndex, get_defined_vars());
    }
    public function newClients(Request $request): View
    {
        $status = "pendding";
        return view($this->viewIndex, get_defined_vars());
    }
    public function status($id)
    {
        $item = Client::findOrFail($id);
        if ($item->active == 0) {
            $item->active = 1;
            $item->status = "accepted";

        } else {
            $item->active = 0;
        }
        $item->save();

        flash(__('clients.messages.updated'))->success();

        return back();
    }
    public function currentClients(Request $request): View
    {

        $status = "accepted";
        return view($this->viewIndex, get_defined_vars());
    }

    public function create(): View
    {
        return view($this->viewEdit, get_defined_vars());
    }

    public function edit($id): View
    {
        $item = Client::findOrFail($id);
        return view($this->viewEdit, get_defined_vars());
    }

    public function show($id): View
    {
        $item = Client::findOrFail($id);
        $completedOrders = Order::where("user_id", $item->id)->where('status', Order::STATUS_COMPLEALED)->get();
        $pendingOrders = Order::where("user_id", $item->id)->where('status', Order::STATUS_PENDING)->get();
        $status = [Order::STATUS_COMPLEALED,Order::STATUS_PENDING,Order::STATUS_ACCEPTED,Order::STATUS_REJECTED,Order::STATUS_ONPROGRESS,Order::STATUS_COMPLEALED,Order::STATUS_CANCELED,Order::STATUS_WITHDRWAL];

        return view($this->viewShow, get_defined_vars());
    }

    public function destroy($id): RedirectResponse
    {
        $item = Client::findOrFail($id);
        if ($item->delete()) {
            flash(__('clients.messages.deleted'))->success();
        }
        return to_route($this->route . '.index');
    }

    public function store(ClientRequest $request): RedirectResponse
    {
        if ($this->processForm($request)) {
            flash(__('clients.messages.created'))->success();
        }
        return to_route($this->route . '.index');
    }
    public function select(Request $request): JsonResponse|string
    {
        $data = Client::distinct()
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


    public function update(ClientRequest $request, $id): RedirectResponse
    {
        $item = Client::findOrFail($id);
        if ($this->processForm($request, $id)) {
            flash(__('clients.messages.updated'))->success();
        }
        return to_route($this->route . '.index');
    }

    protected function processForm($request, $id = null): Client|null
    {
        $item = $id == null ? new Client() : Client::find($id);
        $data = $request->except(['_token', '_method']);
        $item = $item->fill($data);
        if ($request->filled('active')) {
            $item->active = 1;
        } else {
            $item->active = 0;
        }
        $item->type = User::TYPE_CLIENT;
        if ($request->filled('email')) {
            $item->temperory_email = $request->email;
        }
        if ($request->filled('phone')) {
            $item->temperory_phone = $request->phone;
        }

        if ($item->save()) {

            if ($request->filled('password')) {
                $item->password = Hash::make($request->password);
                $item->save();
            }

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $fileName = time() . rand(0, 999999999) . '.' . $image->getClientOriginalExtension();
                $item->image->move(public_path('storage/users'), $fileName);
                $item->image = $fileName;
                $item->save();
            }
            return $item;
        }
        return null;
    }

    public function list(Request $request): JsonResponse
    {
        $data = Client::whereNotNull('email')->where(function ($query) use ($request) {
            if ($request->filled('name')) {
                $query->where('name', 'like', '%'. $request->name .'%');
            }
            if ($request->filled('email')) {
                $query->where('email', $request->email);
            }
            if ($request->filled('phone')) {
                $query->where('phone', $request->phone);
            }
            if ($request->filled('birthdate')) {
                $query->where('birthdate', $request->birthdate);
            }
            if ($request->filled('city_id')) {
                $query->where('city_id', $request->city_id);
            }
            if ($request->filled('active')) {
                $query->where('active', $request->active);
            }
            if ($request->filled('joining_date')) {
                $query->where('joining_date_from', $request->joining_date);
            }
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
        })->where('type', User::TYPE_CLIENT)->select('users.*');
        return FacadesDataTables::of($data)
            ->addIndexColumn()
            ->addColumn('photo', function ($item) {
                return $item->photo ? '<img src="' . $item->photo . '" height="100px" width="100px">' : '';
            })
            ->addColumn('joining_date', function ($item) {
                return $item->created_at?->format('Y-m-d H:i');
            })
            ->addColumn('order_count', function ($item) {
                return 0;
            })
            ->editColumn('code', function ($item) {
                return $item->code ? 'C-'.$item->code : '';
            })
            ->editColumn('active', function ($item) {
                return $item->active == 1 ? '<button class="btn btn-sm btn-outline-success me-1 waves-effect " ><i data-feather="check" ></i></button>' : '<button class="btn btn-sm btn-outline-danger me-1 waves-effect " ><i data-feather="x" ></i></button>';
            })

        ->rawColumns(['photo','active','joining_date'])
        ->make(true);
    }


    public function orders(Request $request): JsonResponse
    {


        $bookingGift = BookingGift::with(['user', 'vendor'])
            ->where('user_id', $request->user_id)
            ->select('id', 'user_id', 'vendor_id','admin_value', 'status', 'total', 'created_at', DB::raw("'BookingGift' as source"))
            ->get();

        $bookingTrip = BookingTrip::with(['user', 'vendor'])
            ->where('user_id', $request->user_id)
            ->select('id', 'user_id', 'vendor_id','admin_value', 'status', 'total', 'created_at', DB::raw("'BookingTrip' as source"))
            ->get();

        $bookingEffectivene = BookingEffectivene::with(['user', 'vendor'])
            ->where('user_id', $request->user_id)
            ->select('id', 'user_id', 'vendor_id','admin_value', 'status', 'total', 'created_at', DB::raw("'BookingEffectivene' as source"))
            ->get();

        $data = $bookingGift->merge($bookingTrip)->merge($bookingEffectivene);
        $data = $data->sortByDesc('created_at')->values();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('client', function ($item) {
                return $item->user?->name;
            })
            ->addColumn('vendor', function ($item) {
                return $item->vendor?->name . '(P' . $item->vendor?->code . ')';
            })
            ->addColumn('status', function ($item) {
                return __('admin.orders_statuses.' . $item->status);
            })
            ->editColumn('source', function ($item) {
                return __('admin.' . $item->source);
            })
            ->editColumn('created_at', function ($item) {
                return $item->created_at?->format('Y-m-d H:i');
            })
            ->rawColumns(['source', 'created_at', 'client', 'vendor'])
            ->make(true);
    }

    /**
     * Send notification to individual client
     */
    public function sendNotification(Request $request, $id): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
        ]);

        $user = User::findOrFail($id);

        try {
            OneSignalService::sendToUser($user->id, $request->title, $request->message);
            
            return response()->json([
                'success' => true,
                'message' => __('clients.notification_sent_successfully')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('admin.error_occurred') . ': ' . $e->getMessage()
            ], 500);
        }
    }
}
