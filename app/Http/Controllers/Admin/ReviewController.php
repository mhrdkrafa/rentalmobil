<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with(['customer', 'vehicle', 'booking'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        return view('admin.reviews.index', compact('reviews'));
    }

    public function togglePublish(Review $review)
    {
        $review->is_published = !$review->is_published;
        $review->save();

        $status = $review->is_published ? 'dipublikasikan' : 'disembunyikan';
        return back()->with('success', "Review berhasil {$status}.");
    }
}
