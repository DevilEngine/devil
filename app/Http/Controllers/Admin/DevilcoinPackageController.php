<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\DevilcoinPackage;

class DevilcoinPackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $packs = DevilcoinPackage::orderBy('amount')->get();
        return view('admin.devilcoin.index', compact('packs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.devilcoin.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'amount' => 'required|integer|min:1',
            'usd_price' => 'required|numeric|min:1',
        ]);
    
        $data['active'] = $request->has('active');
    
        DevilcoinPackage::create($data);
    
        return redirect()
            ->route('devilcoin-packages.index')
            ->with('success', 'Package created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $package = DevilcoinPackage::whereId($id)->firstOrFail();
        return view('admin.devilcoin.edit')->with(compact('package'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, \App\Models\DevilcoinPackage $devilcoinPackage)
    {
        $data = $request->validate([
            'amount' => 'required|integer|min:1',
            'usd_price' => 'required|numeric|min:0.000001',
        ]);
    
        $data['active'] = $request->has('active');
    
        $devilcoinPackage->update($data);
    
        return redirect()
            ->route('devilcoin-packages.index')
            ->with('success', 'Package updated successfully.');
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DevilcoinPackage::whereId($id)->delete();

        return redirect()->back()->with('success','Package removed with success !');
    }
}
