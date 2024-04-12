<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\NotificationRequest;
use App\Models\ApiNotification;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables as FacadesDataTables;
use App\Notifications\SendPushNotification;
use Illuminate\Support\Facades\Notification;
class NotificationController extends Controller
{
    private $viewIndex  = 'admin.pages.notifications.index';
    private $viewEdit   = 'admin.pages.notifications.create_edit';
    private $viewShow   = 'admin.pages.notifications.show';
    private $route      = 'admin.notifications';

    public function index(Request $request): View
    {
        return view($this->viewIndex, get_defined_vars());
    }

    public function create(): View
    {
         $clients = User::distinct()
            ->select('id', 'name AS text')
            ->get();

        $companies = User::distinct()
           ->select('id', 'name AS text')
           ->get();

        return view($this->viewEdit, get_defined_vars());
    }

    public function edit($id): View
    {
        $item = ApiNotification::findOrFail($id);
        return view($this->viewEdit, get_defined_vars());
    }

    public function show($id): View
    {
        $item = ApiNotification::findOrFail($id);
        return view($this->viewShow, get_defined_vars());
    }

    public function destroy($id): RedirectResponse
    {
        $item = ApiNotification::findOrFail($id);
        if ($item->delete()) {
            flash(__('notifications.messages.deleted'))->success();
        }
        return to_route($this->route . '.index');
    }

    public function store(NotificationRequest $request): RedirectResponse
    {
        if ($this->processForm($request)) {
            flash(__('notifications.messages.created'))->success();
        }
        return to_route($this->route . '.index');
    }



    protected function processForm($request, $id = null)
    {
        if ($request->company_id) {
            if (in_array("all", $request->company_id)) {
                $company_id = User::distinct()
                    ->pluck('id')
                    ->toArray();
            } else {
                $company_id = $request->company_id;
            }
            foreach($company_id as $user_id) {
                $notifications = [
                    'user_id'=>$user_id,
                    'text'=>$request->text,
                    'model_id'=>null,
                    'day'=>date('Y-m-d'),
                    'time'=>date('H:i'),
                ];
                ApiNotification::create($notifications);
            }
            $fcmTokens = User::whereIn('id', $company_id)->pluck('fcm_token')->toArray();
            Notification::send(null, new SendPushNotification($request->text, $fcmTokens));
        }

        if ($request->client_id) {
            if(in_array("all", $request->client_id)) {
                $client_id = User::distinct()
                ->pluck('id')
                ->toArray();
            } else {
                $client_id = $request->client_id;
            }
            foreach($client_id as $user_id) {
                $notifications = [
                    'user_id'=>$user_id,
                    'text'=>$request->text,
                    'model_id'=>null,
                    'day'=>date('Y-m-d'),
                    'time'=>date('H:i'),
                ];
                ApiNotification::create($notifications);
            }
            $fcmTokens = User::whereIn('id', $client_id)->pluck('fcm_token')->toArray();
            Notification::send(null, new SendPushNotification($request->text, $fcmTokens));
        }

        return true;
    }

    public function list(Request $request): JsonResponse
    {
        $data = ApiNotification::with('user')->select('*');
        return FacadesDataTables::of($data)
            ->addIndexColumn()
            ->addColumn('user', function ($item) {
                return $item->user?->name;
            })
            ->rawColumns(['user'])
            ->make(true);
    }
}
