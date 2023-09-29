<?php

namespace App\Http\Controllers;

use App\Models\PointOfSale;
use App\Http\Requests\StorePointOfSaleRequest;
use App\Http\Requests\UpdatePointOfSaleRequest;
use Prophecy\Doubler\Generator\Node\ReturnTypeNode;

class PointOfSaleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return PointOfSale::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePointOfSaleRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePointOfSaleRequest $request)
    {
       return PointOfSale::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PointOfSale  $pointOfSale
     * @return \Illuminate\Http\Response
     */
    public function show(PointOfSale $pointOfSale)
    {
        return PointOfSale::find($pointOfSale);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PointOfSale  $pointOfSale
     * @return \Illuminate\Http\Response
     */
    public function edit(PointOfSale $pointOfSale)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePointOfSaleRequest  $request
     * @param  \App\Models\PointOfSale  $pointOfSale
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePointOfSaleRequest $request, PointOfSale $pointOfSale)
    {
        return $pointOfSale->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PointOfSale  $pointOfSale
     * @return \Illuminate\Http\Response
     */
    public function destroy(PointOfSale $pointOfSale)
    {
        return PointOfSale::destroy($pointOfSale);
    }
}
