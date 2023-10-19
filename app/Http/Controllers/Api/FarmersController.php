<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FarmerDetails;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
        $validator = Validator::make($request->all(), [
            'phone_number' => 'required|string|unique:users,phone_number',
            'username' => 'string|unique:users,username',
            'password' => 'required|string|min:5',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'result' => false,
                'message' => $validator->messages(),
            ]);
        }
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

        $user = new User(); 
        $user->name = $request->full_name; 
        $user->user_type = "farmer"; 
        $user->username = $request->full_name; 
        $user->email = $email; 
        $user->password = Hash::make($request->password); 
        $user->phone_number = $request->phone_number; 
        $user->email_verified_at = ""; 
        $user->save();

        // $user->create($user_data);
        if (!empty($request->all()['farmer_photo'])) {
            $farmer_photo = [];
            foreach ($request->all()['farmer_photo'] as $photo) {                        
                $id = (new UploadsController)->upload_photo($photo,$user->id);

                if (!empty($id)) {
                    array_push($farmer_photo, $id);
                }
            }    
        }

        if (!empty($request->all()['id_proof_photo'])) {
            $id_proof_photo = [];
            foreach ($request->all()['id_proof_photo'] as $photo) {                        
                $id = (new UploadsController)->upload_photo($photo,$user->id);

                if (!empty($id)) {
                    array_push($id_proof_photo, $id);
                }
            }    
        }

        $ldate = date('Ymd');
        $current_timestamp = Carbon::now()->timestamp; 
        $farmer_code = 'Farmer-'.$ldate.'-'.$current_timestamp;
        $data_farmer_details =[
            'user_id'=>$user->id,
            'enrollment_date' =>$request->enrollment_date,
            'enrollment_place'=>$request->enrollment_place,
            'full_name'=>$request->full_name,
            'phone_number'=>$request->phone_number,
            'identity_proof'=>$request->identity_proof,
            'country'=>$request->country,
            'province'=>$request->province,
            'district'=>$request->district,
            'commune'=>$request->commune,
            'village'=>$request->village,
            'lng'=>$request->lng,
            'lat'=>$request->lat,
            'proof_no'=>$request->proof_no,
            'gender'=>$request->gender,
            'farmer_code'=>$farmer_code,
            'dob'=>$request->dob,
            'farmer_photo'=>implode(',', $farmer_photo),
            'id_proof_photo'=>implode(',', $id_proof_photo),
        ];
        $farmer_details->create($data_farmer_details);
    }
}
