<?php

// app/Http/Controllers/Admin/SiteClaimAdminController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteClaim;
use Illuminate\Http\Request;

class SiteClaimAdminController extends Controller
{
    public function index()
    {
        $claims = SiteClaim::with('user', 'site')->latest()->paginate(20);
        return view('admin.claims.index', compact('claims'));
    }

    public function show($id)
    {

        $claim = SiteClaim::where('id', $id)->firstOrFail();

        return view('admin.claims.show', compact('claim'));
    }

    public function approve(SiteClaim $claim)
    {
        if ($claim->status !== 'pending') {
            return back()->with('error', 'Already processed.');
        }

        // Assign ownership
        $claim->site->update(['user_id' => $claim->user_id]);

        $claim->update([
            'status' => 'approved',
        ]);

        return back()->with('success', 'Site claim approved.');
    }

    public function reject(Request $request, SiteClaim $claim)
    {
        if ($claim->status !== 'pending') {
            return back()->with('error', 'Already processed.');
        }

        $claim->update([
            'status' => 'rejected',
            'admin_note' => $request->input('admin_note'),
        ]);

        return back()->with('success', 'Site claim rejected.');
    }
}
