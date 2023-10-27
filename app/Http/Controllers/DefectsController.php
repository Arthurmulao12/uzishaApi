<?php

namespace App\Http\Controllers;

use App\Models\defects;
use App\Http\Requests\StoredefectsRequest;
use App\Http\Requests\UpdatedefectsRequest;

class DefectsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Http\Requests\StoredefectsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoredefectsRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\defects  $defects
     * @return \Illuminate\Http\Response
     */
    public function show(defects $defects)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\defects  $defects
     * @return \Illuminate\Http\Response
     */
    public function edit(defects $defects)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatedefectsRequest  $request
     * @param  \App\Models\defects  $defects
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatedefectsRequest $request, defects $defects)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\defects  $defects
     * @return \Illuminate\Http\Response
     */
    public function destroy(defects $defects)
    {
        //
    }
}
