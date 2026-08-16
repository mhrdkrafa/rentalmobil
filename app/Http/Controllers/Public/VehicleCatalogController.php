<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\VehicleCategory;
use App\Enums\VehicleStatus;
use Illuminate\Http\Request;

class VehicleCatalogController extends Controller
{
    public function index(Request $request)
    {
        $categories = VehicleCategory::all();

        $query = Vehicle::with(['category', 'images' => function($q) {
            $q->orderBy('is_primary', 'desc')->orderBy('sort_order', 'asc');
        }])->where('status', VehicleStatus::Available->value);

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filter by transmission
        if ($request->filled('transmission')) {
            $query->where('transmission', $request->transmission);
        }

        // Filter by min price
        if ($request->filled('min_price')) {
            $query->where('price_per_day', '>=', $request->min_price);
        }

        // Filter by max price
        if ($request->filled('max_price')) {
            $query->where('price_per_day', '<=', $request->max_price);
        }

        // Basic sort (could be extended)
        $query->orderBy('price_per_day', 'asc');

        $vehicles = $query->paginate(12)->withQueryString();

        return view('public.catalog.index', compact('vehicles', 'categories'));
    }

    public function show(Vehicle $vehicle)
    {
        abort_if($vehicle->status->value !== VehicleStatus::Available->value, 404);

        $vehicle->load(['category', 'images' => function($q) {
            $q->orderBy('is_primary', 'desc')->orderBy('sort_order', 'asc');
        }]);

        // Fetch related vehicles (same category, excluding this one)
        $relatedVehicles = Vehicle::with(['category', 'images' => function($q) {
                $q->orderBy('is_primary', 'desc');
            }])
            ->where('category_id', $vehicle->category_id)
            ->where('id', '!=', $vehicle->id)
            ->where('status', VehicleStatus::Available->value)
            ->take(3)
            ->get();

        return view('public.catalog.show', compact('vehicle', 'relatedVehicles'));
    }
}
