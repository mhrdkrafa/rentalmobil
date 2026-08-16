<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\VehicleImage;
use App\Http\Requests\Admin\StoreVehicleImageRequest;
use Illuminate\Support\Facades\Storage;

class VehicleImageController extends Controller
{
    public function index(Vehicle $vehicle)
    {
        $images = $vehicle->images()->orderBy('sort_order')->get();
        return view('admin.vehicles.images', compact('vehicle', 'images'));
    }

    public function store(StoreVehicleImageRequest $request, Vehicle $vehicle)
    {
        if ($request->hasFile('images')) {
            $currentCount = $vehicle->images()->count();
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('vehicles', 'public');
                VehicleImage::create([
                    'vehicle_id' => $vehicle->id,
                    'image_path' => $path,
                    'is_primary' => ($currentCount === 0 && $index === 0), // Primary if it's the first ever
                    'sort_order' => $currentCount + $index,
                ]);
            }
        }

        return redirect()->route('admin.vehicles.images.index', $vehicle)->with('success', 'Gambar berhasil diupload');
    }

    public function destroy(VehicleImage $image)
    {
        Storage::disk('public')->delete($image->image_path);
        
        $vehicle = $image->vehicle;
        $wasPrimary = $image->is_primary;
        
        $image->delete();

        // If the deleted image was primary, set another one to primary if exists
        if ($wasPrimary) {
            $newPrimary = $vehicle->images()->first();
            if ($newPrimary) {
                $newPrimary->update(['is_primary' => true]);
            }
        }

        return back()->with('success', 'Gambar berhasil dihapus');
    }

    public function setPrimary(VehicleImage $image)
    {
        $vehicle = $image->vehicle;
        
        // Remove primary from all other images of this vehicle
        $vehicle->images()->update(['is_primary' => false]);
        
        // Set this one as primary
        $image->update(['is_primary' => true]);

        return back()->with('success', 'Gambar utama berhasil diubah');
    }
}
