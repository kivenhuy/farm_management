<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Commune;
use App\Models\Country;
use App\Models\CropInformation;
use App\Models\District;
use App\Models\FarmCatalogue;
use App\Models\FarmerDetails;
use App\Models\Province;
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
        $farmer_data = FarmerDetails::all();
        foreach ($farmer_data as $details_farmer_data)
        {
            $details_farmer_data->farmer_photo = uploaded_asset($details_farmer_data->farmer_photo);
            $details_farmer_data->country= Country::find($details_farmer_data->country)->country_name;
            $details_farmer_data->province= Province::find($details_farmer_data->province)->province_name;
            $details_farmer_data->district= District::find($details_farmer_data->district)->district_name;
            $details_farmer_data->commune= Commune::find($details_farmer_data->commune)->commune_name;
        }
        return response()->json([
            'result' => true,
            'message' => 'Get All Farmer Successfully',
            'farmer_data' =>$farmer_data
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    public function drop_down_for_register()
    {
        $data_enrollment_place = [];
        $data_identity_proof = [];
        $data_gender = [];
        $identity_proof = FarmCatalogue::where('NAME','Identity Proof')->first();
        if(isset($appoarch_road))
        {
            $data_identity_proof = $identity_proof->catalogue_value()->get();
        }
        $enrollment_place = FarmCatalogue::where('NAME','Enrollment Place')->first();
        if(isset($enrollment_place))
        {
            $data_enrollment_place = $enrollment_place->catalogue_value()->get();
        }
        $gender = FarmCatalogue::where('NAME','Gender')->first();
        if(isset($gender))
        {
            $data_gender = $gender->catalogue_value()->get();
        }
        return response()->json([
            'result' => true,
            'message' => 'Farmer Created Successfully',
            'data_identity_proof' =>$data_identity_proof,
            'data_enrollment_place' =>$data_enrollment_place,
            'data_gender' =>$data_gender
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $farmer_data = FarmerDetails::find($id);
        $farmer_data->farmer_photo = uploaded_asset($farmer_data->farmer_photo);
        $farmer_data->id_proof_photo = uploaded_asset($farmer_data->id_proof_photo);
        $farmer_data->country= Country::find($farmer_data->country)->country_name;
        $farmer_data->province= Province::find($farmer_data->province)->province_name;
        $farmer_data->district= District::find($farmer_data->district)->district_name;
        $farmer_data->commune= Commune::find($farmer_data->commune)->commune_name;
        return response()->json([
            'result' => true,
            'message' => 'Get Farmer Successfully',
            'farmer_data' =>$farmer_data
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update_family_info(Request $request, string $id)
    {
        
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
        $farmer_data = $farmer_details->create($data_farmer_details);
        return response()->json([
            'result' => true,
            'message' => 'Farmer Registration Successfully',
            'farmer_data' =>$farmer_data
        ]);
    }

    public function drop_down_for_family_info()
    {
        $data_education = [];
        $data_marial_status = [];
        $data_gender = [];
        $education = FarmCatalogue::where('NAME','Education')->first();
        if(isset($education))
        {
            $data_education = $education->catalogue_value()->get();
        }
        $marial_status = FarmCatalogue::where('NAME','Marital Status')->first();
        if(isset($marial_status))
        {
            $data_marial_status = $marial_status->catalogue_value()->get();
        }
        return response()->json([
            'result' => true,
            'data_education' =>$data_education,
            'data_marial_status' =>$data_marial_status
        ]);
    }

    public function drop_down_for_asset_info()
    {
        $data_housing_owner = [];
        $data_house_type = [];
        $data_consumer_electronic = [];
        $data_vehicle = [];
        $housing_owner = FarmCatalogue::where('NAME','Housing Ownership')->first();
        if(isset($housing_owner))
        {
            $data_housing_owner = $housing_owner->catalogue_value()->get();
        }
        $house_type = FarmCatalogue::where('NAME','House Type')->first();
        if(isset($house_type))
        {
            $data_house_type = $house_type->catalogue_value()->get();
        }
        $consumer_electronic = FarmCatalogue::where('NAME','Consumer Electronics')->first();
        if(isset($consumer_electronic))
        {
            $data_consumer_electronic = $consumer_electronic->catalogue_value()->get();
        }
        $vehicle = FarmCatalogue::where('NAME','Vehicle')->first();
        if(isset($vehicle))
        {
            $data_vehicle = $vehicle->catalogue_value()->get();
        }
        return response()->json([
            'result' => true,
            'data_housing_owner' =>$data_housing_owner,
            'data_house_type' =>$data_house_type,
            'data_consumer_electronic' =>$data_consumer_electronic,
            'data_vehicle' =>$data_vehicle
        ]);
    }

    public function drop_down_for_bank_info()
    {
        $data_account_type = [];
        $account_type = FarmCatalogue::where('NAME','Account Type')->first();
        if(isset($account_type))
        {
            $data_account_type = $account_type->catalogue_value()->get();
        }
        return response()->json([
            'result' => true,
            'data_account_type' =>$data_account_type
        ]);
    }

    public function drop_down_for_finance_info()
    {
        $data_purpose = [];
        $purpose = FarmCatalogue::where('NAME','Purpose')->first();
        if(isset($purpose))
        {
            $data_purpose = $purpose->catalogue_value()->get();
        }
        return response()->json([
            'result' => true,
            'data_purpose' =>$data_purpose
        ]);
    }

    public function drop_down_for_insurance_info()
    {
        $data_crop = [];
        $data_crop = CropInformation::All();
        return response()->json([
            'result' => true,
            'data_crop' =>$data_crop
        ]);
    }

    public function drop_down_for_animal_husbandry()
    {
        $data_farm_animal = [];
        $data_fodder = [];
        $data_animal_housing = [];
        $data_animal_for_growth = [];
        $farm_animal = FarmCatalogue::where('NAME','Animal Husbandry')->first();
        if(isset($farm_animal))
        {
            $data_farm_animal = $farm_animal->catalogue_value()->get();
        }
        $fodder = FarmCatalogue::where('NAME','Fodder')->first();
        if(isset($fodder))
        {
            $data_fodder = $fodder->catalogue_value()->get();
        }
        $animal_housing = FarmCatalogue::where('NAME','Animal Housing')->first();
        if(isset($animal_housing))
        {
            $data_animal_housing = $animal_housing->catalogue_value()->get();
        }
        $animal_for_growth = FarmCatalogue::where('NAME','Animal for Growth')->first();
        if(isset($animal_for_growth))
        {
            $data_animal_for_growth = $animal_for_growth->catalogue_value()->get();
        }
        return response()->json([
            'result' => true,
            'message' => 'Farmer Created Successfully',
            'data_farm_animal' =>$data_farm_animal,
            'data_fodder' =>$data_fodder,
            'data_animal_housing' =>$data_animal_housing,
            'data_animal_for_growth' =>$data_animal_for_growth
        ]);
    }

    public function drop_down_for_certificate_info()
    {
        $data_enrollment_place = [];
        $data_identity_proof = [];
        $data_gender = [];
        $identity_proof = FarmCatalogue::where('NAME','Identity Proof')->first();
        if(isset($appoarch_road))
        {
            $data_identity_proof = $identity_proof->catalogue_value()->get();
        }
        $enrollment_place = FarmCatalogue::where('NAME','Enrollment Place')->first();
        if(isset($enrollment_place))
        {
            $data_enrollment_place = $enrollment_place->catalogue_value()->get();
        }
        $gender = FarmCatalogue::where('NAME','Gender')->first();
        if(isset($gender))
        {
            $data_gender = $gender->catalogue_value()->get();
        }
        return response()->json([
            'result' => true,
            'message' => 'Farmer Created Successfully',
            'data_identity_proof' =>$data_identity_proof,
            'data_enrollment_place' =>$data_enrollment_place,
            'data_gender' =>$data_gender
        ]);
    }

    public function drop_down_for_farm_equipment()
    {
        $data_farm_equipment = [];
        $farm_equipment = FarmCatalogue::where('NAME','Farm Equipments')->first();
        if(isset($farm_equipment))
        {
            $data_farm_equipment = $farm_equipment->catalogue_value()->get();
        }
        return response()->json([
            'result' => true,
            'data_farm_equipment' =>$data_farm_equipment
        ]);
    }
}
