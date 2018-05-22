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
            $countryName = DB::connection('mysql3')->select('SELECT * FROM countries');
            //return response()->json(array($countryName), 200);
            return response()->json($countryName, 200);
        } else if ($option == '2') {
            $countryCode = $request->query('country');
            //echo $countryCode;
            $provinceName = DB::connection('mysql3')->select('SELECT * FROM superexpress.provinces where countryCode=?', [$countryCode]);
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
