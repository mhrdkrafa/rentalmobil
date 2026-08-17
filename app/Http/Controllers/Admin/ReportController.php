<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        
        $bookings = Booking::with(['customer', 'vehicle'])
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        $totalRevenue = $bookings->filter(function($b) { return in_array($b->status->value, ['completed', 'active', 'confirmed']); })->sum('total_price');
        $totalBookings = $bookings->count();
        $completedBookings = $bookings->filter(function($b) { return $b->status->value === 'completed'; })->count();
        
        return view('admin.reports.index', compact(
            'bookings', 
            'startDate', 
            'endDate', 
            'totalRevenue', 
            'totalBookings', 
            'completedBookings'
        ));
    }
    
    public function exportPdf(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        
        $bookings = Booking::with(['customer', 'vehicle'])
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        $totalRevenue = $bookings->filter(function($b) { return in_array($b->status->value, ['completed', 'active', 'confirmed']); })->sum('total_price');
        
        $pdf = Pdf::loadView('admin.reports.pdf', compact('bookings', 'startDate', 'endDate', 'totalRevenue'));
        $pdf->setPaper('A4', 'landscape');
        
        return $pdf->download('laporan-penyewaan-' . date('Ymd', strtotime($startDate)) . '-' . date('Ymd', strtotime($endDate)) . '.pdf');
    }
}
