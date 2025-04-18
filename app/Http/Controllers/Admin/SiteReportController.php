<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteReport;

class SiteReportController extends Controller
{
    public function index()
    {
        $reports = SiteReport::with('site', 'user')->latest()->paginate(20);
        return view('admin.reports.index', compact('reports'));
    }

    public function resolve(SiteReport $report)
    {
        $report->resolved = true;
        $report->save();

        return redirect()->route('admin.reports.index')->with('success', 'Report marked as resolved.');
    }
}

