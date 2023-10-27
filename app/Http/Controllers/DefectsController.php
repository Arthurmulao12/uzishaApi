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
    public function index($enterpriseId)
    {
        return defects::where('enterprise_id','=',$enterpriseId)->get();
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
        return defects::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\defects  $defects
     * @return \Illuminate\Http\Response
     */
    public function show(defects $defects)
    {
        return defects::find($defects);
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
        return $this->show(defects::find($defects)->update($request->all()));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\defects  $defects
     * @return \Illuminate\Http\Response
     */
    public function destroy(defects $defects)
    {
        return $defects->delete();
    }
}
