<?php

namespace App\Http\Controllers;

use App\Models\reasons;
use App\Http\Requests\StorereasonsRequest;
use App\Http\Requests\UpdatereasonsRequest;

class ReasonsController extends Controller
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
     * @param  \App\Http\Requests\StorereasonsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorereasonsRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\reasons  $reasons
     * @return \Illuminate\Http\Response
     */
    public function show(reasons $reasons)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\reasons  $reasons
     * @return \Illuminate\Http\Response
     */
    public function edit(reasons $reasons)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatereasonsRequest  $request
     * @param  \App\Models\reasons  $reasons
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatereasonsRequest $request, reasons $reasons)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\reasons  $reasons
     * @return \Illuminate\Http\Response
     */
    public function destroy(reasons $reasons)
    {
        //
    }
}
