<?php

namespace App\Http\Controllers;

use App\Models\Commune;
use App\Models\District;
use Illuminate\Http\Request;

class CommuneController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('commune.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $district = District::all();
        return view('commune.create',['district'=>$district]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $commune = new Commune();
        $data = [
            'district_id' =>$request->district_id,
            'commune_name' =>$request->commune_name,
            'commune_code' =>$request->commune_code,
            'status'=>$request->status 
        ];
        $commune->create($data);
        return redirect()->route("country.index")->with('success','Commune created successfull');
    }

    /**
     * Display the specified resource.
     */
    public function show(Commune $commune)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Commune $commune)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Commune $commune)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Commune $commune)
    {
        //
    }

    public function filter_by_district($id)
    {
        $district = District::find($id);
        $commune = $district->commune()->get();
        return $commune;
    }
}
