<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected function validator(array $data)
    {
        
    }

    public function index()
    {
        return view('staff.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('staff.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone_number' => 'required|string|unique:users,phone_number',
            'password' => 'required|string|min:6|confirmed',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'result' => false,
                'message' => $validator->messages(),
            ]);
        }


        $user = new User(); 
        $user->name = $request->first_name." ".$request->last_name; 
        $user->user_type = "staff"; 
        $user->username = $request->first_name.$request->last_name; 
        $user->email = $request->email; 
        $user->password = Hash::make($request->password); 
        $user->phone_number = $request->phone_number; 
        $user->email_verified_at = ""; 
        $user->save();
        $staff = new Staff();
        $data_staff = [
            'user_id'=>$user->id,
            'first_name'=>$request->first_name,
            'last_name'=>$request->last_name,
            'gender'=>$request->gender,
            'email'=>$request->email,
            'phone_number'=>$request->phone_number,
            'status'=>$request->status,
        ];
        $staff->create($data_staff);
        return redirect()->route("staff.index")->with('success','Staff created successfull');
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
        $staff = Staff::all();
        
        $out =  DataTables::of($staff)->make(true);
        // dd($out);
        $data = $out->getData();
        $out->setData($data);
        return $out;
    }
}