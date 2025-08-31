<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeliveryCost;
use App\Models\City;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class DeliveryCostController extends Controller
{
    /**
     * Display delivery costs for a specific supplier
     */
    public function index(Request $request, $supplierId)
    {
        $supplier = User::where('type', User::TYPE_SUPPLIER)->findOrFail($supplierId);
        
        if ($request->ajax()) {
            $deliveryCosts = DeliveryCost::with(['city', 'creator', 'updater'])
                ->where('user_id', $supplierId)
                ->select('delivery_costs.*');

            return DataTables::of($deliveryCosts)
                ->addColumn('city_name', function ($row) {
                    return $row->city->title;
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('Y-m-d H:i:s');
                })
                ->addColumn('cost_formatted', function ($row) {
                    return number_format($row->cost, 2) . ' ' . __('delivery.currency');
                })
                ->addColumn('status', function ($row) {
                    return $row->active 
                        ? '<span class="badge bg-success">' . __('delivery.active') . '</span>'
                        : '<span class="badge bg-danger">' . __('delivery.inactive') . '</span>';
                })
                ->addColumn('actions', function ($row) {
                    $updateUrl = route('admin.delivery-costs.update', [$row->user_id, $row->id]);
                    $deleteUrl = route('admin.delivery-costs.destroy', [$row->user_id, $row->id]);
                    
                    return '
                        <div class="dropdown">
                            <button type="button" class="btn btn-sm dropdown-toggle hide-arrow waves-effect waves-float waves-light" data-bs-toggle="dropdown">
                                <i data-feather="more-vertical" class="font-medium-2"></i>
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item edit-delivery-cost" href="#" 
                                   data-id="' . $row->id . '" 
                                   data-cost="' . $row->cost . '" 
                                   data-notes="' . htmlspecialchars($row->notes ?? '') . '" 
                                   data-active="' . ($row->active ? 1 : 0) . '" 
                                   data-url="' . $updateUrl . '">
                                    <i data-feather="edit-2" class="font-medium-2"></i>
                                    <span>' . __('delivery.actions.edit') . '</span>
                                </a>
                                <a class="dropdown-item delete_item" data-url="' . $deleteUrl . '" href="#">
                                    <i data-feather="trash" class="font-medium-2"></i>
                                    <span>' . __('delivery.actions.delete') . '</span>
                                </a>
                            </div>
                        </div>
                    ';
                })
                ->rawColumns(['status', 'actions','created_at'])
                ->make(true);
        }

        $cities = City::where('active', true)->get();
        
        return view('admin.pages.delivery-costs.index', compact('supplier', 'cities'));
    }

    /**
     * Store a newly created delivery cost
     */
    public function store(Request $request, $supplierId)
    {
        $request->validate([
            'city_id' => 'required|exists:cities,id',
            'cost' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:500',
            'active' => 'boolean'
        ]);

        // Check if delivery cost already exists for this supplier and city
        $existingCost = DeliveryCost::where('user_id', $supplierId)
            ->where('city_id', $request->city_id)
            ->first();

        if ($existingCost) {
            return response()->json([
                'success' => false,
                'message' => __('delivery.messages.already_exists')
            ], 422);
        }

        DeliveryCost::create([
            'user_id' => $supplierId,
            'city_id' => $request->city_id,
            'cost' => $request->cost,
            'notes' => $request->notes,
            'active' => $request->boolean('active', true),
            'created_by' => Auth::id()
        ]);

        return response()->json([
            'success' => true,
            'message' => __('delivery.messages.created')
        ]);
    }

    /**
     * Update delivery cost
     */
    public function update(Request $request, $supplierId, $id)
    {
        $request->validate([
            'cost' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:500',
            'active' => 'boolean'
        ]);

        $deliveryCost = DeliveryCost::where('user_id', $supplierId)->findOrFail($id);
        
        $deliveryCost->update([
            'cost' => $request->cost,
            'notes' => $request->notes,
            'active' => $request->boolean('active', true),
            'updated_by' => Auth::id()
        ]);

        return response()->json([
            'success' => true,
            'message' => __('delivery.messages.updated')
        ]);
    }

    /**
     * Remove delivery cost
     */
    public function destroy($supplierId, $id)
    {
        $deliveryCost = DeliveryCost::where('user_id', $supplierId)->findOrFail($id);
        $deliveryCost->delete();

        flash(__('delivery.messages.deleted'))->success();
        return redirect()->route('admin.delivery-costs.index', $supplierId);
    }
}
