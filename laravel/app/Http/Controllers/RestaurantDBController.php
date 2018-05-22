<?php

namespace App\Http\Controllers;

use DummyFullModelClass;
use App\lain;
use Illuminate\Http\Request;
use DB;

class RestaurantDBController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @param  \App\lain  $lain
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, lain $lain) {
        //
        $restaurants = null;
        $option = $request->query('option');
        if ($option == '1') {//option = 1, means city
            $city = $request->query('name');
            $restaurants = DB::table('restaurants')->where('city', $city)->get();
        } else if ($option == '2') {// means province
            $province = $request->query('name');
            $restaurants = DB::table('restaurants')->where('province', $province)->get();
        } else if ($option == '3') {// means province
            $country = $request->query('name');
            $restaurants = DB::table('restaurants')->where('country', $country)->get();
        } else if ($option == '4') {
            $restaurants = DB::table('restaurants')->get();
        }
        return response()->json($restaurants, 200);
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
