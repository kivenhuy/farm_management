<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FarmerDetails;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class FarmersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    
    public function registration(Request $request)
    {
        $user = New User();
        $farmer_details = New FarmerDetails();

        if($request->email == null)
        {
            $email = "";
        }
        $user_data = [
            'name' =>$request->full_name,
            'user_type'=>'farmer',
            'username'=>$request->full_name,
            'email'=>$email,
            'password'=>Hash::make($request->password),
            'phone_number'=>$request->phone_number,
            'email_verified_at' => ''
        ];

        // $user->create($user_data);
        if (!empty($request->all()['farmer_photo'])) {
            $photo_ids = [];
            foreach ($request->all()['farmer_photo'] as $photo) {                        
                $id = (new UploadsController)->upload_photo($photo);

                if (!empty($id)) {
                    array_push($photo_ids, $id);
                }
            }    
        }

        if (!empty($request->all()['id_proof_photo'])) {
            $photo_ids = [];
            foreach ($request->all()['id_proof_photo'] as $photo) {                        
                $id = (new UploadsController)->upload_photo($photo);

                if (!empty($id)) {
                    array_push($photo_ids, $id);
                }
            }    
        }
    }
}
