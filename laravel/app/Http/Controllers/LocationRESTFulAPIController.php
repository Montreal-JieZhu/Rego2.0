<?php

namespace App\Http\Controllers;

use DummyFullModelClass;
use App\lain;
use Illuminate\Http\Request;
use DB;

class LocationRESTFulAPIController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @param  \App\lain  $lain
     * @return \Illuminate\Http\Response
     */
    public function index(lain $lain, Request $request) {
        /* option 1 : Means get countries list
         *       2:  Means get provinces list
         */
        $option = $request->query('option');
        if ($option == '1') {
            //$countryList = DB::connection('mysqlLocationDB')->table('countries')->get();
            $countryList = DB::table('countries')->get();
            return response()->json($countryList, 200);
        } else if ($option == '2') {
            $countryId = $request->query('countryId');
            //$provinceList = DB::connection('mysqlLocationDB')->table('provinces')->where('countryCode', $countryCode)->get();
            $provinceList = DB::table('regions')->where('country_id', $countryId)->get();
            return response()->json($provinceList, 200);
        } else if ($option == '3') {
            $countryId = $request->query('countryId');
            $regionId = $request->query('regionId');
            //echo $countryId;
            //echo $regionId;
            $cityList = DB::table('cities')->where([['region_id', '=', $regionId], ['country_id', '=', $countryId],])->select('id', 'name')->get();
            return response()->json($cityList, 200);
        } else if ($option == '4') {
            $countryCode = $request->query('country');
            $countryName = DB::connection('mysqlLocationDB')->table('countries')->where('code', $countryCode)->select('name')->get();
            return response()->json($countryName, 200);
        } else if ($option == '5') {
            $provinceCode = $request->query('provinceCode');
            $provinceName = DB::connection('mysqlLocationDB')->table('provinces')->where('code', $provinceCode)->select('name')->get();
            return response()->json($provinceName, 200);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \App\lain  $lain
     * @return \Illuminate\Http\Response
     */
    public function create(lain $lain) {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\lain  $lain
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, lain $lain) {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\lain  $lain
     * @param  \DummyFullModelClass  $DummyModelVariable
     * @return \Illuminate\Http\Response
     */
    public function show(lain $lain, DummyModelClass $DummyModelVariable) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\lain  $lain
     * @param  \DummyFullModelClass  $DummyModelVariable
     * @return \Illuminate\Http\Response
     */
    public function edit(lain $lain, DummyModelClass $DummyModelVariable) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\lain  $lain
     * @param  \DummyFullModelClass  $DummyModelVariable
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, lain $lain, DummyModelClass $DummyModelVariable) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\lain  $lain
     * @param  \DummyFullModelClass  $DummyModelVariable
     * @return \Illuminate\Http\Response
     */
    public function destroy(lain $lain, DummyModelClass $DummyModelVariable) {
        //
    }

}
