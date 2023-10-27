<?php

namespace App\Http\Controllers;

use App\Models\spots;
use App\Http\Requests\StorespotsRequest;
use App\Http\Requests\UpdatespotsRequest;

class SpotsController extends Controller
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
     * @param  \App\Http\Requests\StorespotsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorespotsRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\spots  $spots
     * @return \Illuminate\Http\Response
     */
    public function show(spots $spots)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\spots  $spots
     * @return \Illuminate\Http\Response
     */
    public function edit(spots $spots)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatespotsRequest  $request
     * @param  \App\Models\spots  $spots
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatespotsRequest $request, spots $spots)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\spots  $spots
     * @return \Illuminate\Http\Response
     */
    public function destroy(spots $spots)
    {
        //
    }
}
