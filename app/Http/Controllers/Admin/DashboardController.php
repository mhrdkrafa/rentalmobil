<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Cards data
        $totalBookings = Booking::count();
        $totalRevenue = Booking::whereIn('status', ['completed', 'active'])->sum('total_price');
        $totalCustomers = Customer::count();
        $totalVehicles = Vehicle::count();

        // Recent Bookings
        $recentBookings = Booking::with(['customer', 'vehicle'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Chart Data: Revenue Last 6 Months
        $months = collect();
        for ($i = 5; $i >= 0; $i--) {
            $months->push(Carbon::now()->subMonths($i)->format('M Y'));
        }

        $revenueData = [];
        for ($i = 5; $i >= 0; $i--) {
            $monthStart = Carbon::now()->subMonths($i)->startOfMonth();
            $monthEnd = Carbon::now()->subMonths($i)->endOfMonth();
            
            $revenue = Booking::whereIn('status', ['completed', 'active'])
                ->whereBetween('created_at', [$monthStart, $monthEnd])
                ->sum('total_price');
                
            $revenueData[] = $revenue;
        }

        // Chart Data: Bookings by Status
        $statusCounts = Booking::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();
            
        $statusData = [
            'pending' => $statusCounts['pending'] ?? 0,
            'confirmed' => $statusCounts['confirmed'] ?? 0,
            'active' => $statusCounts['active'] ?? 0,
            'completed' => $statusCounts['completed'] ?? 0,
            'cancelled' => $statusCounts['cancelled'] ?? 0,
        ];

        return view('admin.dashboard', compact(
            'totalBookings', 
            'totalRevenue', 
            'totalCustomers', 
            'totalVehicles',
            'recentBookings',
            'months',
            'revenueData',
            'statusData'
        ));
    }
}
