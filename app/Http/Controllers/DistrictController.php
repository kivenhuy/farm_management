<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\Province;
use Illuminate\Http\Request;

class DistrictController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('district.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $province = Province::all();
        return view('district.create',['province'=>$province]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $district = new District();
        $data = [
            'province_id' =>$request->province_id,
            'district_name' =>$request->district_name,
            'district_code' =>$request->district_code,
            'status'=>$request->status 
        ];
        $district->create($data);
        return redirect()->route("district.index")->with('success','District created successfull');
    }

    /**
     * Display the specified resource.
     */
    public function show(District $district)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(District $district)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, District $district)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(District $district)
    {
        //
    }

    public function filter_by_province($id)
    {
        $province = Province::find($id);
        $district = $province->district()->get();
        return $district;
    }
}
