<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreRatingRequest;
use App\Http\Resources\CustomerRatingResource;
use App\Models\CustomerRating;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RatingController extends Controller
{
    /**
     * Submit a new rating
     */
    public function store(StoreRatingRequest $request): JsonResponse
    {

        $customerId = Auth::id();

        // Validate service exists
        if (!$this->validateService($request->service_type, $request->service_id)) {
            return response()->json([
                'success' => false,
                'message' => __('ratings.service_not_found')
            ], 404);
        }

        // Check if customer can rate this transaction
        if ($customerId && !CustomerRating::canCustomerRate($customerId, $request->transaction_id)) {
            return response()->json([
                'success' => false,
                'message' => __('ratings.already_rated')
            ], 409);
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
                'is_verified' => $customerId ? true : false,
                'rated_at' => now(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => __('ratings.rating_submitted'),
                'data' => new CustomerRatingResource($rating)
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => __('ratings.submission_failed'),
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get ratings for a supplier
     */
    public function supplierRatings($supplierId, Request $request): JsonResponse
    {
        $supplier = User::find($supplierId);
        if (!$supplier) {
            return response()->json([
                'success' => false,
                'message' => __('ratings.supplier_not_found')
            ], 404);
        }

        $serviceType = $request->get('service_type');
        $perPage = min($request->get('per_page', 15), 50); // Max 50 per page

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

        return response()->json([
            'success' => true,
            'data' => [
                'supplier' => [
                    'id' => $supplier->id,
                    'name' => $supplier->name,
                    'email' => $supplier->email,
                ],
                'ratings' => CustomerRatingResource::collection($ratings->items()),
                'statistics' => $stats,
                'ratings_by_type' => $ratingsByType,
                'pagination' => [
                    'current_page' => $ratings->currentPage(),
                    'last_page' => $ratings->lastPage(),
                    'per_page' => $ratings->perPage(),
                    'total' => $ratings->total(),
                    'has_more_pages' => $ratings->hasMorePages(),
                ]
            ]
        ]);
    }

    /**
     * Get customer's own ratings
     */
    public function customerRatings(Request $request): JsonResponse
    {
        $customerId = Auth::id();
        
        if (!$customerId) {
            return response()->json([
                'success' => false,
                'message' => __('auth.unauthenticated')
            ], 401);
        }

        $perPage = min($request->get('per_page', 15), 50);
        
        $ratings = CustomerRating::where('customer_id', $customerId)
            ->with(['supplier'])
            ->orderBy('rated_at', 'desc')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => [
                'ratings' => CustomerRatingResource::collection($ratings->items()),
                'pagination' => [
                    'current_page' => $ratings->currentPage(),
                    'last_page' => $ratings->lastPage(),
                    'per_page' => $ratings->perPage(),
                    'total' => $ratings->total(),
                    'has_more_pages' => $ratings->hasMorePages(),
                ]
            ]
        ]);
    }

    /**
     * Get rating statistics for a supplier
     */
    public function supplierStats($supplierId, Request $request): JsonResponse
    {
        $supplier = User::find($supplierId);
        if (!$supplier) {
            return response()->json([
                'success' => false,
                'message' => __('ratings.supplier_not_found')
            ], 404);
        }

        $serviceType = $request->get('service_type');

        $stats = [
            'supplier_id' => $supplierId,
            'supplier_name' => $supplier->name,
            'average_rating' => CustomerRating::getSupplierAverageRating($supplierId, $serviceType),
            'total_ratings' => CustomerRating::getSupplierRatingsCount($supplierId, $serviceType),
            'rating_distribution' => CustomerRating::getSupplierRatingDistribution($supplierId, $serviceType),
            'ratings_by_service_type' => [
                'tour' => [
                    'average' => CustomerRating::getSupplierAverageRating($supplierId, 'tour'),
                    'count' => CustomerRating::getSupplierRatingsCount($supplierId, 'tour'),
                ],
                'event' => [
                    'average' => CustomerRating::getSupplierAverageRating($supplierId, 'event'),
                    'count' => CustomerRating::getSupplierRatingsCount($supplierId, 'event'),
                ],
                'gift' => [
                    'average' => CustomerRating::getSupplierAverageRating($supplierId, 'gift'),
                    'count' => CustomerRating::getSupplierRatingsCount($supplierId, 'gift'),
                ],
            ]
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Check if customer can rate a transaction
     */
    public function canRate(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'transaction_id' => 'required|string',
            'customer_id' => 'nullable|integer|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => __('ratings.validation_failed'),
                'errors' => $validator->errors()
            ], 422);
        }

        $customerId = $request->customer_id ?: Auth::id();
        $canRate = $customerId ? CustomerRating::canCustomerRate($customerId, $request->transaction_id) : true;

        return response()->json([
            'success' => true,
            'data' => [
                'can_rate' => $canRate,
                'message' => $canRate ? __('ratings.can_rate') : __('ratings.already_rated')
            ]
        ]);
    }

    /**
     * Get recent ratings (public endpoint)
     */
    public function recentRatings(Request $request): JsonResponse
    {
        $limit = min($request->get('limit', 10), 50);
        $serviceType = $request->get('service_type');

        $query = CustomerRating::verified()
            ->with(['customer', 'supplier'])
            ->orderBy('rated_at', 'desc')
            ->limit($limit);

        if ($serviceType) {
            $query->where('service_type', $serviceType);
        }

        $ratings = $query->get();

        return response()->json([
            'success' => true,
            'data' => CustomerRatingResource::collection($ratings)
        ]);
    }

    /**
     * Get top rated suppliers
     */
    public function topRatedSuppliers(Request $request): JsonResponse
    {
        $limit = min($request->get('limit', 10), 50);
        $serviceType = $request->get('service_type');

        $query = CustomerRating::verified()
            ->select('supplier_id', DB::raw('AVG(rating) as average_rating'), DB::raw('COUNT(*) as total_ratings'))
            ->with('supplier')
            ->groupBy('supplier_id')
            ->having('total_ratings', '>=', 3) // At least 3 ratings
            ->orderBy('average_rating', 'desc')
            ->orderBy('total_ratings', 'desc')
            ->limit($limit);

        if ($serviceType) {
            $query->where('service_type', $serviceType);
        }

        $suppliers = $query->get()->map(function ($rating) {
            return [
                'supplier_id' => $rating->supplier_id,
                'supplier_name' => $rating->supplier->name,
                'supplier_email' => $rating->supplier->email,
                'average_rating' => round($rating->average_rating, 2),
                'total_ratings' => $rating->total_ratings,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $suppliers
        ]);
    }

    /**
     * Validate if service exists
     */
    private function validateService($serviceType, $serviceId): bool
    {
        switch ($serviceType) {
            case 'tour':
                return \App\Models\Trip::find($serviceId) !== null;
            case 'event':
                return \App\Models\Effectivenes::find($serviceId) !== null;
            case 'gift':
                return \App\Models\Gift::find($serviceId) !== null;
            default:
                return false;
        }
    }
}
