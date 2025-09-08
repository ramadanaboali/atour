<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\BookingGift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AccountantController extends Controller
{
    /**
     * Display the accountants dashboard
     */
    public function dashboard()
    {
        // Get current month and year
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $lastMonth = Carbon::now()->subMonth();
        
        // Total Revenue (from completed orders)
        $totalRevenue = Order::where('status', 'completed')
            ->sum('total');
            
        $monthlyRevenue = Order::where('status', 'completed')
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->sum('total');
            
        $lastMonthRevenue = Order::where('status', 'completed')
            ->whereMonth('created_at', $lastMonth->month)
            ->whereYear('created_at', $lastMonth->year)
            ->sum('total');
            
        // Revenue growth percentage
        $revenueGrowth = $lastMonthRevenue > 0 
            ? (($monthlyRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100 
            : 0;
        
        // Total Commissions (platform fee from orders)
        $totalCommissions = Order::where('status', 'completed')
            ->sum('total');
            
        $monthlyCommissions = Order::where('status', 'completed')
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->sum('total');
        
        // Pending Payouts to Suppliers
        $pendingPayouts = Order::where('status', 'completed')
            ->sum('total');
            
        // Total Transactions
        $totalTransactions = BookingGift::count();
        $monthlyTransactions = BookingGift::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();
        
        // Active Clients and Suppliers
        $activeClients = User::where('type', User::TYPE_CLIENT)
            ->where('status', 'active')
            ->count();
            
        $activeSuppliers = User::where('type', User::TYPE_SUPPLIER)
            ->where('status', 'active')
            ->count();
        
        // Recent Orders for quick overview
        $recentOrders = Order::with(['client', 'supplier'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        // Monthly Revenue Chart Data (last 12 months)
        $monthlyRevenueChart = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $revenue = Order::where('status', 'completed')
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->sum('total');
            $monthlyRevenueChart[] = [
                'month' => $date->format('M Y'),
                'revenue' => $revenue
            ];
        }
        
        // Top Performing Suppliers
        $topSuppliers = User::where('type', User::TYPE_SUPPLIER)
            ->limit(5)
            ->get();
        
        // Payment Methods Distribution
        $paymentMethods = BookingGift::select('payment_way', DB::raw('count(*) as count'))
            ->groupBy('payment_way')
            ->get();
        
        return view('admin.pages.accountants.dashboard', compact(
            'totalRevenue',
            'monthlyRevenue',
            'revenueGrowth',
            'totalCommissions',
            'monthlyCommissions',
            'pendingPayouts',
            'totalTransactions',
            'monthlyTransactions',
            'activeClients',
            'activeSuppliers',
            'recentOrders',
            'monthlyRevenueChart',
            'topSuppliers',
            'paymentMethods'
        ));
    }
    
    /**
     * Display revenue management page
     */
    public function revenue(Request $request)
    {
        $query = Order::where('status', 'completed');
        
        // Apply filters
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }
        
        $orders = $query->with(['client', 'supplier'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        $totalRevenue = $query->sum('total');
        $totalCommissions = $query->sum('total');
        
        $suppliers = User::where('type', User::TYPE_SUPPLIER)->get();
        
        return view('admin.pages.accountants.revenue', compact(
            'orders',
            'totalRevenue',
            'totalCommissions',
            'suppliers'
        ));
    }
    
    /**
     * Display expenses management page
     */
    public function expenses()
    {
        // This would typically connect to an expenses table
        // For now, we'll show operational costs and refunds
        
        $refunds = Order::where('status', 'refunded')
            ->with(['client', 'supplier'])
            ->orderBy('updated_at', 'desc')
            ->paginate(20);
            
        $totalRefunds = Order::where('status', 'refunded')
            ->sum('total');
            
        $monthlyRefunds = Order::where('status', 'refunded')
            ->whereMonth('updated_at', Carbon::now()->month)
            ->sum('total');
        
        return view('admin.pages.accountants.expenses', compact(
            'refunds',
            'totalRefunds',
            'monthlyRefunds'
        ));
    }
    
    /**
     * Display transactions page
     */
    public function transactions(Request $request)
    {
        $query = BookingGift::query();
        
        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('payment_way')) {
            $query->where('payment_way', $request->payment_way);
        }
        
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $transactions = $query->with(['user', 'order'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('admin.pages.accountants.transactions', compact('transactions'));
    }
    
    /**
     * Display commissions page
     */
    public function commissions()
    {
        $commissions = Order::where('status', 'completed')
            ->where('total', '>', 0)
            ->with(['client', 'supplier'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        $totalCommissions = Order::where('status', 'completed')
            ->sum('total');
            
        $monthlyCommissions = Order::where('status', 'completed')
            ->whereMonth('created_at', Carbon::now()->month)
            ->sum('total');
        
        return view('admin.pages.accountants.commissions', compact(
            'commissions',
            'totalCommissions',
            'monthlyCommissions'
        ));
    }
    
    /**
     * Display supplier payouts page
     */
    public function payouts()
    {
        $pendingPayouts = Order::where('status', 'completed')
            ->with(['supplier', 'client'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        $completedPayouts = Order::with(['supplier', 'client'])
            ->orderBy('payout_date', 'desc')
            ->limit(10)
            ->get();
            
        $totalPendingAmount = Order::where('status', 'completed')
            ->sum('total');
        
        return view('admin.pages.accountants.payouts', compact(
            'pendingPayouts',
            'completedPayouts',
            'totalPendingAmount'
        ));
    }
    
    /**
     * Display financial reports page
     */
    public function reports()
    {
        // Generate comprehensive financial reports
        $currentYear = Carbon::now()->year;
        
        // Yearly summary
        $yearlyRevenue = Order::where('status', 'completed')
            ->whereYear('created_at', $currentYear)
            ->sum('total');
            
        $yearlyCommissions = Order::where('status', 'completed')
            ->whereYear('created_at', $currentYear)
            ->sum('total');
            
        $yearlyOrders = Order::where('status', 'completed')
            ->whereYear('created_at', $currentYear)
            ->count();
        
        // Monthly breakdown
        $monthlyBreakdown = [];
        for ($month = 1; $month <= 12; $month++) {
            $monthlyBreakdown[] = [
                'month' => Carbon::create($currentYear, $month, 1)->format('F'),
                'revenue' => Order::where('status', 'completed')
                    ->whereYear('created_at', $currentYear)
                    ->whereMonth('created_at', $month)
                    ->sum('total'),
                'orders' => Order::where('status', 'completed')
                    ->whereYear('created_at', $currentYear)
                    ->whereMonth('created_at', $month)
                    ->count(),
                'commissions' => Order::where('status', 'completed')
                    ->whereYear('created_at', $currentYear)
                    ->whereMonth('created_at', $month)
                    ->sum('total')
            ];
        }
        
        return view('admin.pages.accountants.reports', compact(
            'yearlyRevenue',
            'yearlyCommissions',
            'yearlyOrders',
            'monthlyBreakdown'
        ));
    }
    
    /**
     * Display tax management page
     */
    public function taxes()
    {
        // Tax calculations and management
        $currentYear = Carbon::now()->year;
        
        $taxableRevenue = Order::where('status', 'completed')
            ->whereYear('created_at', $currentYear)
            ->sum('total');
            
        // Assuming 15% tax rate (this should be configurable)
        $taxRate = 0.15;
        $estimatedTax = $taxableRevenue * $taxRate;
        
        $quarterlyBreakdown = [];
        for ($quarter = 1; $quarter <= 4; $quarter++) {
            $startMonth = ($quarter - 1) * 3 + 1;
            $endMonth = $quarter * 3;
            
            $quarterRevenue = Order::where('status', 'completed')
                ->whereYear('created_at', $currentYear)
                ->whereBetween(DB::raw('MONTH(created_at)'), [$startMonth, $endMonth])
                ->sum('total');
                
            $quarterlyBreakdown[] = [
                'quarter' => "Q{$quarter}",
                'revenue' => $quarterRevenue,
                'tax' => $quarterRevenue * $taxRate
            ];
        }
        
        return view('admin.pages.accountants.taxes', compact(
            'taxableRevenue',
            'estimatedTax',
            'taxRate',
            'quarterlyBreakdown'
        ));
    }
}
