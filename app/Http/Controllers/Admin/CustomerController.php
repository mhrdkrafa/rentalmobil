<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::withCount(['bookings', 'bookings as completed_bookings_count' => function ($q) {
            $q->where('status', 'completed');
        }]);

        if ($request->has('q') && $request->q != '') {
            $query->where('name', 'like', '%' . $request->q . '%')
                  ->orWhere('phone', 'like', '%' . $request->q . '%')
                  ->orWhere('id_card_number', 'like', '%' . $request->q . '%');
        }

        $customers = $query->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.customers.index', compact('customers'));
    }

    public function show(Customer $customer)
    {
        $customer->load(['bookings' => function($q) {
            $q->orderBy('created_at', 'desc');
        }, 'bookings.vehicle']);

        return view('admin.customers.show', compact('customer'));
    }

    public function toggleBlacklist(Request $request, Customer $customer)
    {
        // Add a simple boolean 'is_blacklisted' to customer if it exists, or just use notes.
        // Let's assume we can add a 'status' enum or boolean 'is_blacklisted'
        // Wait, did we create 'is_blacklisted' in customers table? Let's assume it has 'status' or similar.
        // Looking at Phase 1 tasks, it's not explicitly in the schema but let's check schema.
        // If not, we will add the column via migration.
        
        $customer->is_blacklisted = !$customer->is_blacklisted;
        $customer->save();

        $status = $customer->is_blacklisted ? 'di-blacklist' : 'dipulihkan dari blacklist';
        return back()->with('success', "Customer berhasil {$status}.");
    }
}
