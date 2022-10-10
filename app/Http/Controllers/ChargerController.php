<?php

namespace App\Http\Controllers;

use App\Models\Charger;
use App\Http\Requests\StoreChargerRequest;
use App\Http\Requests\UpdateChargerRequest;

class ChargerController extends Controller
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
     * @param  \App\Http\Requests\StoreChargerRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreChargerRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Charger  $charger
     * @return \Illuminate\Http\Response
     */
    public function show(Charger $charger)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Charger  $charger
     * @return \Illuminate\Http\Response
     */
    public function edit(Charger $charger)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateChargerRequest  $request
     * @param  \App\Models\Charger  $charger
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateChargerRequest $request, Charger $charger)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Charger  $charger
     * @return \Illuminate\Http\Response
     */
    public function destroy(Charger $charger)
    {
        //
    }
}
