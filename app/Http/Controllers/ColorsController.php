<?php

namespace App\Http\Controllers;

use App\Models\colors;
use App\Http\Requests\StorecolorsRequest;
use App\Http\Requests\UpdatecolorsRequest;

class ColorsController extends Controller
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
     * @param  \App\Http\Requests\StorecolorsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorecolorsRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\colors  $colors
     * @return \Illuminate\Http\Response
     */
    public function show(colors $colors)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\colors  $colors
     * @return \Illuminate\Http\Response
     */
    public function edit(colors $colors)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatecolorsRequest  $request
     * @param  \App\Models\colors  $colors
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatecolorsRequest $request, colors $colors)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\colors  $colors
     * @return \Illuminate\Http\Response
     */
    public function destroy(colors $colors)
    {
        //
    }
}
