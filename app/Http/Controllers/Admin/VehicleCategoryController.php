<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VehicleCategory;
use App\Http\Requests\Admin\StoreVehicleCategoryRequest;
use App\Http\Requests\Admin\UpdateVehicleCategoryRequest;
use Illuminate\Support\Str;

class VehicleCategoryController extends Controller
{
    public function index()
    {
        $categories = VehicleCategory::paginate(10);
        return view('admin.vehicle_categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.vehicle_categories.create');
    }

    public function store(StoreVehicleCategoryRequest $request)
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['name']);
        
        // Ensure slug is unique
        $originalSlug = $data['slug'];
        $count = 1;
        while (VehicleCategory::where('slug', $data['slug'])->exists()) {
            $data['slug'] = $originalSlug . '-' . $count++;
        }

        VehicleCategory::create($data);

        return redirect()->route('admin.vehicle-categories.index')->with('success', 'Kategori berhasil ditambahkan');
    }

    public function edit(VehicleCategory $vehicleCategory)
    {
        return view('admin.vehicle_categories.edit', compact('vehicleCategory'));
    }

    public function update(UpdateVehicleCategoryRequest $request, VehicleCategory $vehicleCategory)
    {
        $data = $request->validated();
        if ($vehicleCategory->name !== $data['name']) {
            $data['slug'] = Str::slug($data['name']);
            // Ensure slug is unique
            $originalSlug = $data['slug'];
            $count = 1;
            while (VehicleCategory::where('slug', $data['slug'])->where('id', '!=', $vehicleCategory->id)->exists()) {
                $data['slug'] = $originalSlug . '-' . $count++;
            }
        }

        $vehicleCategory->update($data);

        return redirect()->route('admin.vehicle-categories.index')->with('success', 'Kategori berhasil diupdate');
    }

    public function destroy(VehicleCategory $vehicleCategory)
    {
        if ($vehicleCategory->vehicles()->exists()) {
            return redirect()->route('admin.vehicle-categories.index')->with('error', 'Kategori tidak dapat dihapus karena masih memiliki kendaraan');
        }

        $vehicleCategory->delete();

        return redirect()->route('admin.vehicle-categories.index')->with('success', 'Kategori berhasil dihapus');
    }
}
