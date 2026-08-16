<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Enums\VehicleStatus;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        // Get featured vehicles (e.g., 3 latest available)
        $featuredVehicles = Vehicle::with(['category', 'images' => function($query) {
                $query->where('is_primary', true)->orWhere('sort_order', 0);
            }])
            ->where('status', VehicleStatus::Available->value)
            ->latest()
            ->take(3)
            ->get();

        return view('public.home', compact('featuredVehicles'));
    }
}
