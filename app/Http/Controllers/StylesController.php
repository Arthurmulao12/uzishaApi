<?php

namespace App\Http\Controllers;

use App\Models\styles;
use App\Http\Requests\StorestylesRequest;
use App\Http\Requests\UpdatestylesRequest;
use Illuminate\Http\Request;

class StylesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($enterpriseId)
    {
        return styles::where('enterprise_id','=',$enterpriseId)->get();
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
     * @param  \App\Http\Requests\StorestylesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorestylesRequest $request)
    {
        return styles::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\styles  $styles
     * @return \Illuminate\Http\Response
     */
    public function show(styles $styles)
    {
        return styles::find($styles);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\styles  $styles
     * @return \Illuminate\Http\Response
     */
    public function edit(styles $styles)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatestylesRequest  $request
     * @param  \App\Models\styles  $styles
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatestylesRequest $request, styles $styles)
    {
        return $this->show(styles::find($styles)->update($request->all()));
    }

      /**
     * update 2
     */
    public function update2(Request $request,$id)
    {
        $color=styles::find($id);
        $color->update($request->all());

        return $this->show($color);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\styles  $styles
     * @return \Illuminate\Http\Response
     */
    public function destroy(styles $styles)
    {
        return $styles->delete();
    }

    /**
     * Delete
     */
    public function destroy2($id)
    {
        $get=styles::find($id);
        return $get->delete();
    }
}
