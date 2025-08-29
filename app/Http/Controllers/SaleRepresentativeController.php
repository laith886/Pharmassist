<?php

namespace App\Http\Controllers;

use App\Models\SaleRepresentative;
use Illuminate\Http\Request;

class SaleRepresentativeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       $representatives = SaleRepresentative::all();
    return response()->json($representatives);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(SaleRepresentative $saleRepresentative)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SaleRepresentative $saleRepresentative)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SaleRepresentative $saleRepresentative)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SaleRepresentative $saleRepresentative)
    {
        //
    }
}
