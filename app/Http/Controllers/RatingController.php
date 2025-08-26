<?php

namespace App\Http\Controllers;

use App\Models\CustomerRating;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RatingController extends Controller
{
    /**
     * Show rating form for a specific transaction
     */
    public function create(Request $request)
    {
        $transactionId = $request->get('transaction_id');
        $serviceType = $request->get('service_type');
        $serviceId = $request->get('service_id');
        $supplierId = $request->get('supplier_id');

        // Validate required parameters
        if (!$transactionId || !$serviceType || !$serviceId || !$supplierId) {
            return redirect()->back()->with('error', __('ratings.invalid_parameters'));
        }

        // Check if customer can rate this transaction
        if (Auth::check() && !CustomerRating::canCustomerRate(Auth::id(), $transactionId)) {
            return redirect()->back()->with('error', __('ratings.already_rated'));
        }

        // Get service details
        $service = $this->getServiceDetails($serviceType, $serviceId);
        $supplier = User::find($supplierId);

        if (!$service || !$supplier) {
            return redirect()->back()->with('error', __('ratings.service_not_found'));
        }

        return view('ratings.create', compact(
            'transactionId',
            'serviceType',
            'serviceId',
            'supplierId',
            'service',
            'supplier'
        ));
    }

    /**
     * Store a new rating
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'transaction_id' => 'required|string|max:255',
            'service_type' => 'required|in:tour,event,gift',
            'service_id' => 'required|integer|exists:' . $this->getServiceTable($request->service_type) . ',id',
            'supplier_id' => 'required|integer|exists:users,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'customer_name' => 'nullable|string|max:255',
            'customer_email' => 'nullable|email|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', __('ratings.validation_failed'));
        }

        $customerId = Auth::id();

        // Check if customer can rate this transaction
        if ($customerId && !CustomerRating::canCustomerRate($customerId, $request->transaction_id)) {
            return redirect()->back()->with('error', __('ratings.already_rated'));
        }

        try {
            DB::beginTransaction();

            $rating = CustomerRating::create([
                'customer_id' => $customerId,
                'supplier_id' => $request->supplier_id,
                'transaction_id' => $request->transaction_id,
                'service_type' => $request->service_type,
                'service_id' => $request->service_id,
                'rating' => $request->rating,
                'comment' => $request->comment,
                'customer_name' => $request->customer_name ?: (Auth::user()->name ?? null),
                'customer_email' => $request->customer_email ?: (Auth::user()->email ?? null),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'is_verified' => $customerId ? true : false, // Auto-verify for logged-in users
                'rated_at' => now(),
            ]);

            DB::commit();

            return redirect()->back()->with('success', __('ratings.rating_submitted'));

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', __('ratings.submission_failed'));
        }
    }

    /**
     * Show ratings for a supplier
     */
    public function supplierRatings($supplierId, Request $request)
    {
        $supplier = User::find($supplierId);
        if (!$supplier) {
            abort(404);
        }

        $serviceType = $request->get('service_type');
        $perPage = $request->get('per_page', 15);

        $ratingsQuery = CustomerRating::verified()
            ->where('supplier_id', $supplierId)
            ->with(['customer', 'supplier'])
            ->orderBy('rated_at', 'desc');

        if ($serviceType) {
            $ratingsQuery->where('service_type', $serviceType);
        }

        $ratings = $ratingsQuery->paginate($perPage);

        // Get statistics
        $stats = [
            'average_rating' => CustomerRating::getSupplierAverageRating($supplierId, $serviceType),
            'total_ratings' => CustomerRating::getSupplierRatingsCount($supplierId, $serviceType),
            'rating_distribution' => CustomerRating::getSupplierRatingDistribution($supplierId, $serviceType),
        ];

        // Get ratings by service type
        $ratingsByType = [
            'tour' => CustomerRating::getSupplierAverageRating($supplierId, 'tour'),
            'event' => CustomerRating::getSupplierAverageRating($supplierId, 'event'),
            'gift' => CustomerRating::getSupplierAverageRating($supplierId, 'gift'),
        ];

        return view('ratings.supplier', compact(
            'supplier',
            'ratings',
            'stats',
            'ratingsByType',
            'serviceType'
        ));
    }

    /**
     * Show customer's ratings
     */
    public function customerRatings()
    {
        $customerId = Auth::id();
        
        $ratings = CustomerRating::where('customer_id', $customerId)
            ->with(['supplier'])
            ->orderBy('rated_at', 'desc')
            ->paginate(15);

        return view('ratings.customer', compact('ratings'));
    }

    /**
     * Admin: Show all ratings
     */
    public function adminIndex(Request $request)
    {
        $this->authorize('admin');

        $query = CustomerRating::with(['customer', 'supplier'])
            ->orderBy('rated_at', 'desc');

        // Apply filters
        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        if ($request->filled('service_type')) {
            $query->where('service_type', $request->service_type);
        }

        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        if ($request->filled('is_verified')) {
            $query->where('is_verified', $request->is_verified);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('rated_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('rated_at', '<=', $request->date_to);
        }

        $ratings = $query->paginate(20);

        // Get suppliers for filter dropdown
        $suppliers = User::whereHas('customerRatings')->get();

        return view('admin.ratings.index', compact('ratings', 'suppliers'));
    }

    /**
     * Admin: Toggle rating verification
     */
    public function toggleVerification($id)
    {
        $this->authorize('admin');

        $rating = CustomerRating::findOrFail($id);
        $rating->is_verified = !$rating->is_verified;
        $rating->save();

        return redirect()->back()->with('success', __('ratings.verification_updated'));
    }

    /**
     * Admin: Delete rating
     */
    public function destroy($id)
    {
        $this->authorize('admin');

        $rating = CustomerRating::findOrFail($id);
        $rating->delete();

        return redirect()->back()->with('success', __('ratings.rating_deleted'));
    }

    /**
     * Get service details based on type and ID
     */
    private function getServiceDetails($serviceType, $serviceId)
    {
        switch ($serviceType) {
            case 'tour':
                return \App\Models\Trip::find($serviceId);
            case 'event':
                return \App\Models\Event::find($serviceId);
            case 'gift':
                return \App\Models\Gift::find($serviceId);
            default:
                return null;
        }
    }

    /**
     * Get service table name based on type
     */
    private function getServiceTable($serviceType)
    {
        switch ($serviceType) {
            case 'tour':
                return 'trips';
            case 'event':
                return 'events';
            case 'gift':
                return 'gifts';
            default:
                return 'trips';
        }
    }
}
