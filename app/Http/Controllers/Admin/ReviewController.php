<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\SiteReview;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = SiteReview::with('site')->latest()->paginate(15);
        return view('admin.reviews.index', compact('reviews'));
    }

    public function approve(SiteReview $review)
    {
        $review->update(['approved' => true]);

        if ($review->approved && $review->user) {
            $review->user->updateReputation();
        }

        return redirect()->back()->with('success', 'Avis approuvé.');
    }

    public function destroy(SiteReview $review)
    {
        $review->delete();
        return redirect()->back()->with('success', 'Avis supprimé.');
    }
}
