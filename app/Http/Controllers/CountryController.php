<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('country.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('country.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $country = new Country();
        $data = [
            'country_name' =>$request->country_name,
            'country_code' =>$request->country_code,
            'status'=>$request->status 
        ];
        $country->create($data);
        return redirect()->route("country.index")->with('success','Country created successfull');
    }

    /**
     * Display the specified resource.
     */
    public function show(Country $country)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Country $country)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Country $country)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Country $country)
    {
        //
    }

    public function dtajax(Request $request)
    {
            $country = Country::all()->sortDesc();
            dd($country);
            $out =  DataTables::of($country)->make(true);
            $data = $out->getData();
            dd($data);
            // for($i=0; $i < count($data->data); $i++) {
            //     // dd($data->data[$i]->id);
            //     $output = '';
            //     $output .= ' <a href="'.url(route('rfq_request_user.show',['id'=>$data->data[$i]->id])).'" class="btn btn-info btn-xs" data-toggle="tooltip" title="Show Details" style="display:inline;padding:2px 5px 3px 5px;"><i class="fa fa-eye"></i></a>';
            //     $data->data[$i]->product_name = Product::find($data->data[$i]->product_id)?->name;
            //     $data->data[$i]->seller_name = User::find($data->data[$i]->seller_id)?->name;
            //     $data->data[$i]->buyer_name = User::find($data->data[$i]->buyer_id)?->name;
            //     if(!empty($data->data[$i]->product_name) && !empty($data->data[$i]->seller_name) && !empty($data->data[$i]->buyer_name) )
            //     {
            //         $data->data[$i]->action = (string)$output;
            //     }
            //     else
            //     {
            //         $data->data[$i]->action = '';
            //     }
            //  }
            // $out->setData($data);
            return $out;
    }
}
