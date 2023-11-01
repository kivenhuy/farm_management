<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AnimalHusbandry;
use App\Models\AssetInfo;
use App\Models\BankInfo;
use App\Models\CertificateInformation;
use App\Models\Commune;
use App\Models\Country;
use App\Models\CropInformation;
use App\Models\District;
use App\Models\FamilyInfo;
use App\Models\FarmCatalogue;
use App\Models\FarmEquipment;
use App\Models\FarmerCountable;
use App\Models\FarmerDetails;
use App\Models\FinanceInfo;
use App\Models\InsuranceInfo;
use App\Models\Province;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class FarmersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // private $log_actitvities;
    // public function __construct($log_actitvities)
    // {
    //     $this->;
    // }

    public function index()
    {
        $user_login = Auth::user();
        if(!($user_login->staff))
        {
            return response()->json([
                'result' => true,
                'message' => 'Staff Not Exist Try Again',
            ]);
        }
        $staff_data = $user_login->staff->first();
        $farmer_data = FarmerDetails::where('staff_id',$staff_data->id)->get();
        foreach ($farmer_data as $details_farmer_data)
        {
            $details_farmer_data->farmer_photo = uploaded_asset($details_farmer_data->farmer_photo);
            $details_farmer_data->country = "N/A";
            $details_farmer_data->province= "N/A";
            $details_farmer_data->district= "N/A";
            $details_farmer_data->commune = "N/A";
            if(Country::find($details_farmer_data->country))
            {
                $details_farmer_data->country = Country::find($details_farmer_data->country)->country_name;
            }
            if(Province::find($details_farmer_data->province))
            {
                $details_farmer_data->province= Province::find($details_farmer_data->province)->province_name;
            }
            if( District::find($details_farmer_data->district))
            {
                $details_farmer_data->district= District::find($details_farmer_data->district)->district_name;
            }
            if(Commune::find($details_farmer_data->commune))
            {
                $details_farmer_data->commune= Commune::find($details_farmer_data->commune)->commune_name;
            }
        }
        return response()->json([
            'result' => true,
            'message' => 'Get All Farmer Successfully',
            'data' =>[
                'farmer_data'=>$farmer_data
            ]
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
        $identity_proof = FarmCatalogue::where('NAME','Id Proof')->first();
        if(isset($identity_proof))
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
            'data'=>[
                'data_identity_proof' =>$data_identity_proof,
                'data_enrollment_place' =>$data_enrollment_place,
                'data_gender' =>$data_gender
            ]
            
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $farmer_data = FarmerDetails::with([
            'countryRelation',
            'provinceRelation',
            'districtRelation',
            'communeRelation',
        ])->find($id);
        $farmer_data->farmer_photo = uploaded_asset($farmer_data->farmer_photo);
        
        $farmer_data->total_area    = $farmer_data->farm_lands->sum('actual_area');

        return response()->json([
            'result' => true,
            'message' => 'Get Farmer Successfully',
            'data' =>[
                'farmer_data'=>$farmer_data
            ]
        ]);
    }



    /**
     * Update the specified resource in storage.
     */
    // Personal Infomartion
    public function update_personal_info(Request $request)
    {
        $data_log_activities = [];
        $data_log_activities['action'] = 'update';
        $data_log_activities['lat'] = $request->staff_lat;
        $data_log_activities['lng'] = $request->staff_lng;
        $data_log_activities['request'] = $request->all();
        $farmer_details = FarmerDetails::find($request->farmer_id);
        if (!$farmer_details) {
            $data_log_activities['status_code'] = 400;
            $data_log_activities['status_msg'] = "Farmer Not Exists";
            try {
                $this->create_log((object) $data_log_activities);
                // $log_actitvities->store_log();
            } catch (\Exception $e) {  
            
            }
            return response()->json([
                'result' => false,
                'message' =>"Farmer Not Exists",
            ]);
        }
        $staff = Auth::user()->staff;
        $validator = Validator::make($request->all(), [
            'phone_number' => 'required|string|unique:users,phone_number',
            'full_name' => '|nullable|string',
            // 'password' => 'required|string|min:5',
        ]);
        if ($validator->fails()) {
            $str_validation = "";
            foreach ($validator->messages()->messages() as $key => $data)
            {
                $str_validation .= $data[0].",";
            }
            $data_log_activities['status_code'] = 400;
            $data_log_activities['status_msg'] = $str_validation;
            try {
                $this->create_log((object) $data_log_activities);
                // $log_actitvities->store_log();
            } catch (\Exception $e) {  
            
            }
            return response()->json([
                'result' => false,
                'message' => $validator->messages(),
            ]);
        }
        $email = "";
        if($request->email != "")
        {
            $email = $request->email;
        }
        $password = "";
        if($request->password != "")
        {
            $password = Hash::make($request->password); 
        }
        $user_data = User::find($farmer_details->user_id);
        $user_data->name = $request->full_name;
        $user_data->username = $request->full_name; 
        $user_data->email = $email; 
        $user_data->password =  $password; 
        $user_data->phone_number = $request->phone_number; 
        $user_data->email_verified_at = ""; 
        // dd($password);
        $user_data->save();
        $farmer_photo = [];
        if (!empty($request->all()['farmer_photo'])) {
            foreach ($request->all()['farmer_photo'] as $photo) {                        
                $id = (new UploadsController)->upload_photo($photo,$user_data->id);

                if (!empty($id)) {
                    array_push($farmer_photo, $id);
                }
            }    
        }
        $id_proof_photo = [];
        if (!empty($request->all()['id_proof_photo'])) {
            
            foreach ($request->all()['id_proof_photo'] as $photo) {                        
                $id = (new UploadsController)->upload_photo($photo,$user_data->id);

                if (!empty($id)) {
                    array_push($id_proof_photo, $id);
                }
            }    
        }
        
        $data_farmer_details =[
            'staff_id'=>$farmer_details->staff_id,
            'user_id'=>$farmer_details->user_id,
            'enrollment_date' =>$farmer_details->enrollment_date,
            'enrollment_place'=>$farmer_details->enrollment_place,
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
            'farmer_code'=>$farmer_details->farmer_code,
            'dob'=>$request->dob,
            'is_online'=>$request->is_online,
            'farmer_photo'=>implode(',', $farmer_photo),
            'id_proof_photo'=>implode(',', $id_proof_photo),
        ];
       
        try {
            $farmer_data = $farmer_details->update($data_farmer_details);
            $data_log_activities['status_code'] = 200;
            $data_log_activities['status_msg'] = 'Farmer Update Successfully';
            $this->create_log((object) $data_log_activities);
            return response()->json([
                'result' => true,
                'message' => 'Farmer Update Successfully',
                'data' =>[
                    'farmer_data'=>$farmer_data
                ]
            ]);
        } catch (\Exception $e) {  
            $data_log_activities['status_code'] = 400;
            $data_log_activities['status_msg'] = $e->getMessage();
            $this->create_log((object) $data_log_activities);
            return response()->json([
                'result' => true,
                'message' => 'Farmer Update Failed',
            ]);
        }
    }

    // Family Info
    public function update_family_info(Request $request, string $id)
    {
        $data_log_activities = [];
        $data_log_activities['action'] = 'edit';
        $data_log_activities['request'] = $request->all();
        $data_family = $request->data_family;
        $farmer_data = FarmerDetails::find($id);
        if(!isset($farmer_data))
        {
            $data_log_activities['status_code'] = 404;
            $data_log_activities['status_msg'] = 'Farmer Not Exists';
            $this->create_log((object) $data_log_activities);
            return response()->json([
                'result' => false,
                'message' => 'Farmer Not Exists',
            ]);
        }
        // $family_info = new FamilyInfo();
        $data_family_info = [
            'education'=>$data_family['education'],
            'marial_status'=>$data_family['marial_status'],
            'parent_name'=>$data_family['parent_name'],
            'spouse_name'=>$data_family['spouse_name'],
            'no_of_family'=>$data_family['no_of_family'],
            'total_child_under_18'=>json_encode($data_family['total_child_under_18']),
            'total_child_under_18_going_school'=>$data_family['total_child_under_18_going_school']
        ];
        try {
            $family_info = FamilyInfo::updateOrCreate(['farmer_id'=>$farmer_data->id], $data_family_info );
            if(isset($family_info))
            {
                $data_log_activities['status_code'] = 200;
                $data_log_activities['status_msg'] = 'Update Family Information Successfully';
                $this->create_log((object) $data_log_activities);
                return response()->json([
                    'result' => true,
                    'message' => 'Update Family Information Successfully',
                    'data'=>[
                        'family_info'=>$family_info
                    ]
                    
                ]);
            }
        } 
        catch (\Exception $e) {  
            $data_log_activities['status_code'] = 400;
            $data_log_activities['status_msg'] = $e->getMessage();
            $this->create_log((object) $data_log_activities);
            return response()->json([
                'result' => true,
                'message' => 'Update Family Information Failed',
            ]);
        }
    }

    // Asset Info
    public function update_asset_info(Request $request, string $id)
    {
        $data_log_activities = [];
        $data_log_activities['action'] = 'edit';
        $data_log_activities['request'] = $request->all();
        $data_asset = $request->data_asset;
        $farmer_data = FarmerDetails::find($id);
        if(!isset($farmer_data))
        {
            $data_log_activities['status_code'] = 404;
            $data_log_activities['status_msg'] = 'Farmer Not Exists';
            $this->create_log((object) $data_log_activities);
            return response()->json([
                'result' => false,
                'message' => 'Farmer Not Exists',
            ]);
        }
        // $family_info = new FamilyInfo();
        $data_asset_info = [
            'housing_ownership'=>$data_asset['housing_ownership'],
            'house_type'=>$data_asset['house_type'],
            'consumer_electronic'=>$data_asset['consumer_electronic'],
            'vehicle'=>$data_asset['vehicle']
        ];
        try {
            $asset_info = AssetInfo::updateOrCreate(['farmer_id'=>$farmer_data->id], $data_asset_info );
            if(isset($asset_info))
            {
                $data_log_activities['status_code'] = 200;
                $data_log_activities['status_msg'] = 'Update Asset Information Successfully';
                $this->create_log((object) $data_log_activities);
                return response()->json([
                    'result' => true,
                    'message' => 'Update Asset Information Successfully',
                    'data'=>[
                        'asset_info'=>$asset_info
                    ]
                    
                ]);
            }
        } 
        catch (\Exception $e) { 
            $data_log_activities['status_code'] = 400;
            $data_log_activities['status_msg'] = $e->getMessage();
            $this->create_log((object) $data_log_activities);
            return response()->json([
                'result' => false,
                'message' => 'Update Asset Information Failed',
            ]);
        }
    }

    //Bank Info 
    public function update_bank_info(Request $request, string $id)
    {
        $data_bank = $request->data_bank;
        $farmer_data = FarmerDetails::find($id);
        $all_bank_update = [];
        if(!isset($farmer_data))
        {
            return response()->json([
                'result' => false,
                'message' => 'Farmer Not Exists',
            ]);
        }
        foreach($data_bank as $sub_data_bank)
        {
            $data_bank_info = [
                'farmer_id'=>$farmer_data->id,
                'accout_type'=>$sub_data_bank['accout_type'],
                'accout_no'=>$sub_data_bank['accout_no'],
                'bank_name'=>$sub_data_bank['bank_name'],
                'branch_details'=>$sub_data_bank['branch_details'],
                'sort_code'=>$sub_data_bank['sort_code']
            ];
            $bank_info = BankInfo::updateOrCreate(['id'=>$sub_data_bank['id']], $data_bank_info );
            array_push($all_bank_update,$bank_info);  
        }
        
        
        if(count($all_bank_update)>0)
        {
            return response()->json([
                'result' => true,
                'message' => 'Update Bank Information Successfully',
                'data'=>[
                    'all_bank_update'=>$all_bank_update
                ]
                
            ]);
        }
        else
        {
            return response()->json([
                'result' => false,
                'message' => 'Update Insurance Information Failed',
                'data'=>[
                ]
            ]);
        }
    }

    // Insurance Info
    public function update_insurance_info(Request $request, string $id)
    {
        $farmer_data = FarmerDetails::find($id);
        if(!isset($farmer_data))
        {
            return response()->json([
                'result' => false,
                'message' => 'Farmer Not Exists',
            ]);
        }
        $all_insurance_update = [];
        if(count($request->data_insurance) == 0)
        {
            return response()->json([
                'result' => false,
                'message' => 'No Data Update',
            ]);
        }
        foreach($request->data_insurance as $sub_data_insurance)
        {
            $life_insurance = "";
            $provider_life_insurance = "";
            $life_insurance_amount = 0;
            $life_insurance_enrolled_date = "";
            $life_insurance_end_date = "";
            $health_insurance = "";
            $provider_health_insurance = "";
            $health_insurance_amount = 0;
            $health_insurance_enrolled_date = "";
            $health_insurance_end_date = "";
            $crop_insurance = "";
            $provider_crop_insurance = "";
            $crop_insured = 0;
            $no_of_area_insured = 0;
            $crop_insurance_enrolled_date = "";
            $crop_insurance_end_date = "";
            $social_insurance = "";
            $provider_social_insurance = "";
            $social_insurance_enrolled_date = "";
            $social_insurance_end_date = "";
            $other_insurance = $request->other_insurance;
            if($sub_data_insurance['life_insurance'] == "yes")
            {
                $life_insurance = $sub_data_insurance['life_insurance'];
                $provider_life_insurance = $sub_data_insurance['provider_life_insurance'];
                $life_insurance_amount = $sub_data_insurance['life_insurance_amount'];
                $life_insurance_enrolled_date = $sub_data_insurance['life_insurance_enrolled_date'];
                $life_insurance_end_date = $sub_data_insurance['life_insurance_end_date'];
            }
            if($sub_data_insurance['health_insurance'] == "yes")
            {
                $health_insurance = $sub_data_insurance['health_insurance'];
                $provider_health_insurance = $sub_data_insurance['provider_health_insurance'];
                $health_insurance_amount = $sub_data_insurance['health_insurance_amount'];
                $health_insurance_enrolled_date = $sub_data_insurance['health_insurance_enrolled_date'];
                $health_insurance_end_date = $sub_data_insurance['health_insurance_end_date'];
            }
            if($sub_data_insurance['crop_insurance'] == "yes")
            {
                $crop_insurance = $sub_data_insurance['crop_insurance'];
                $provider_crop_insurance = $sub_data_insurance['provider_crop_insurance'];
                $crop_insured = $sub_data_insurance['crop_insured'];
                $no_of_area_insured = $sub_data_insurance['no_of_area_insured'];
                $crop_insurance_enrolled_date = $sub_data_insurance['crop_insurance_enrolled_date'];
                $crop_insurance_end_date = $sub_data_insurance['crop_insurance_end_date'];
            }
            if($sub_data_insurance['social_insurance'] == "yes")
            {
                $social_insurance = $sub_data_insurance['social_insurance'];
                $provider_social_insurance = $sub_data_insurance['provider_social_insurance'];
                $social_insurance_enrolled_date = $sub_data_insurance['social_insurance_enrolled_date'];
                $life_insurance_enrolled_date = $sub_data_insurance['life_insurance_enrolled_date'];
                $social_insurance_end_date = $sub_data_insurance['social_insurance_end_date'];
            }
            $data_insurance_info = [
                'farmer_id'=>$farmer_data->id,
                'life_insurance'=>$life_insurance,
                'provider_life_insurance'=>$provider_life_insurance,
                'life_insurance_amount'=>$life_insurance_amount,
                'life_insurance_enrolled_date'=>$life_insurance_enrolled_date,
                'life_insurance_end_date'=>$life_insurance_end_date,
                'health_insurance'=>$health_insurance,
                'provider_health_insurance'=>$provider_health_insurance,
                'health_insurance_amount'=>$health_insurance_amount,
                'health_insurance_enrolled_date'=>$health_insurance_enrolled_date,
                'health_insurance_end_date'=>$health_insurance_end_date,
                'crop_insurance'=>$crop_insurance,
                'provider_crop_insurance'=>$provider_crop_insurance,
                'crop_insured'=>$crop_insured,
                'no_of_area_insured'=>$no_of_area_insured,
                'crop_insurance_enrolled_date'=>$crop_insurance_enrolled_date,
                'crop_insurance_end_date'=>$crop_insurance_end_date,
                'social_insurance'=>$social_insurance,
                'provider_social_insurance'=>$provider_social_insurance,
                'social_insurance_enrolled_date'=>$social_insurance_enrolled_date,
                'social_insurance_end_date'=>$social_insurance_end_date,
                'other_insurance'=>$other_insurance
            ];
            // if($sub_data_insurance['id'] == "")
            // {
            //     $creat_insurance = new InsuranceInfo();
            //     $insurance_info = $creat_insurance->create($data_insurance_info);
            //     array_push($all_insurance_update,$insurance_info);  
            // }
            // else
            // {
            //     $creat_insurance = InsuranceInfo::find($sub_data_insurance['id']);
            //     $insurance_info = $creat_insurance->create($data_insurance_info);
            //     array_push($all_insurance_update,$insurance_info);  
            // }
            $insurance_info = BankInfo::updateOrCreate(['id'=>$sub_data_insurance['id']], $data_insurance_info );
            array_push($all_insurance_update,$insurance_info);  
        }
        if(count($all_insurance_update)>0)
        {
            return response()->json([
                'result' => true,
                'message' => 'Update Insurance Information Successfully',
                'data'=>[
                    'all_insurance_update'=>$all_insurance_update
                ]
                
            ]);
        }
        else
        {
            return response()->json([
                'result' => false,
                'message' => 'Update Insurance Information Failed',
                'data'=>[
                ]
            ]);
        }
    }

    // Finance Info
    public function update_finance_info(Request $request, string $id)
    {
        $data_finance = $request->data_finance;
        $farmer_data = FarmerDetails::find($id);
        if(!isset($farmer_data))
        {
            return response()->json([
                'result' => false,
                'message' => 'Farmer Not Exists',
            ]);
        }
        $data_finance_info = [
            'loan_taken_last_year'=>$data_finance['loan_taken_last_year'],
            'loan_taken_from'=>$data_finance['loan_taken_from'],
            'loan_amount'=>$data_finance['loan_amount'],
            'purpose'=>$data_finance['purpose'],
            'loan_interest'=>$data_finance['loan_interest'],
            'interest_period'=>$data_finance['interest_period'],
            'security'=>$data_finance['security'],
            'loan_repayment_amount'=>$data_finance['loan_repayment_amount'],
            'loan_repayment_date'=>$data_finance['loan_repayment_date']
        ];
        $finance_info = FinanceInfo::updateOrCreate(['farmer_id'=>$farmer_data->id], $data_finance_info );
        if(isset($finance_info))
        {
            return response()->json([
                'result' => true,
                'message' => 'Update Finance Information Successfully',
                'data'=>[
                    'finance_info'=>$finance_info
                ]
                
            ]);
        }
        else
        {
            return response()->json([
                'result' => false,
                'message' => 'Update Finance Information Failed',
            ]);
        }

    }

    // Farm Equipment 
    public function update_farm_equipment(Request $request, string $id)
    {
        $data_farm_equipment = $request->data_farm_equipment;
        $farmer_data = FarmerDetails::find($id);
        $all_farm_equipment = [];
        if(!isset($farmer_data))
        {
            return response()->json([
                'result' => false,
                'message' => 'Farmer Not Exists',
            ]);
        }
        foreach($data_farm_equipment as $sub_data_farm_equipment)
        {
            $data_create_farm_equipment = [
                'farmer_id'=>$farmer_data->id,
                'farm_equipment_items'=>$sub_data_farm_equipment['farm_equipment_items'],
                'farm_equipment_items_count'=>$sub_data_farm_equipment['farm_equipment_items_count'],
                'year_of_manufacture'=>$sub_data_farm_equipment['year_of_manufacture'],
                'year_of_purchase'=>$sub_data_farm_equipment['year_of_purchase']
            ];
            $farm_equipment = FarmEquipment::updateOrCreate(['id'=>$sub_data_farm_equipment['id']], $data_create_farm_equipment);
            array_push($all_farm_equipment,$farm_equipment);  
        }
        
        
        if(count($all_farm_equipment)>0)
        {
            return response()->json([
                'result' => true,
                'message' => 'Update Farm Equipment Successfully',
                'data'=>[
                    'all_farm_equipment'=>$all_farm_equipment
                ]
                
            ]);
        }
        else
        {
            return response()->json([
                'result' => false,
                'message' => 'Update Farm Equipment Failed',
                'data'=>[
                ]
            ]);
        }
    }

    // Animal Husbandry
    public function update_animal_husbandry(Request $request, string $id)
    {
        $data_animal_husbandry = $request->data_animal_husbandry;
        $farmer_data = FarmerDetails::find($id);
        $all_animal_husbandry = [];
        if(!isset($farmer_data))
        {
            return response()->json([
                'result' => false,
                'message' => 'Farmer Not Exists',
            ]);
        }
        foreach($data_animal_husbandry as $sub_data_animal_husbandry)
        {
            $data_create_animal_husbandry = [
                'farmer_id'=>$farmer_data->id,
                'farm_animal'=>$sub_data_animal_husbandry['farm_animal'],
                'animal_count'=>$sub_data_animal_husbandry['animal_count'],
                'fodder'=>$sub_data_animal_husbandry['fodder'],
                'animal_housing'=>$sub_data_animal_husbandry['animal_housing'],
                'revenue'=>$sub_data_animal_husbandry['revenue'],
                'breed_name'=>$sub_data_animal_husbandry['breed_name'],
                'animal_for_growth'=>$sub_data_animal_husbandry['animal_for_growth']
            ];
            $animal_husbandry = AnimalHusbandry::updateOrCreate(['id'=>$sub_data_animal_husbandry['id']], $data_create_animal_husbandry);
            array_push($all_animal_husbandry,$animal_husbandry);  
        }
        
        
        if(count($all_animal_husbandry)>0)
        {
            return response()->json([
                'result' => true,
                'message' => 'Update Animal Husbandry Successfully',
                'data'=>[
                    'all_animal_husbandry'=>$all_animal_husbandry
                ]
                
            ]);
        }
        else
        {
            return response()->json([
                'result' => false,
                'message' => 'Update Animal Husbandry Failed',
                'data'=>[
                ]
            ]);
        }
    }

    // Certification
    public function update_certificate(Request $request, string $id)
    {
        $data_certificate = $request->data_certificate;
        $farmer_data = FarmerDetails::find($id);
        if(!isset($farmer_data))
        {
            return response()->json([
                'result' => false,
                'message' => 'Farmer Not Exists',
            ]);
        }
        // $family_info = new FamilyInfo();
        $data_certitifcate_info = [
            'is_certified_farmer'=>$data_certificate['is_certified_farmer'],
            'certification_type'=>$data_certificate['certification_type'],
            'year_of_ics'=>$data_certificate['year_of_ics']
        ];
        $certitifcate_info = CertificateInformation::updateOrCreate(['farmer_id'=>$farmer_data->id], $data_certitifcate_info );
        if(isset($certitifcate_info))
        {
            return response()->json([
                'result' => true,
                'message' => 'Update Certificate Information Successfully',
                'data'=>[
                    'certitifcate_info'=>$certitifcate_info
                ]
                
            ]);
        }
        else
        {
            return response()->json([
                'result' => false,
                'message' => 'Update Family Information Failed',
            ]);
        }
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
        $data_log_activities = [];
        $data_log_activities['action'] = 'create';
        $data_log_activities['lat'] = $request->staff_lat;
        $data_log_activities['lng'] = $request->staff_lng;
        $data_log_activities['request'] = $request->all();
        $staff = Auth::user()->staff;
        $validator = Validator::make($request->all(), [
            'phone_number' => 'required|string|unique:users,phone_number',
            'full_name' => '|nullable|string',
            // 'password' => 'required|string|min:5',
        ]);
        if ($validator->fails()) {
            $str_validation = "";
            foreach ($validator->messages()->messages() as $key => $data)
            {
                $str_validation .= $data[0].",";
            }
            $data_log_activities['status_code'] = 400;
            $data_log_activities['status_msg'] = $str_validation;
            try {
                $this->create_log((object) $data_log_activities);
                // $log_actitvities->store_log();
            } catch (\Exception $e) {  
            
            }
            return response()->json([
                'result' => false,
                'message' => $validator->messages(),
            ]);
        }
        
        $user = New User();
        $farmer_details = New FarmerDetails();
        $countable = FarmerCountable::find(1);
        $farmer_code = 'FA'.date('Y').date('m').date('d').$countable->count_number;
        $email = "";
        if($request->email != "")
        {
            $email = $request->email;
        }
        $password = "";
        if($request->password != "")
        {
            $password = Hash::make($request->password); 
        }
        $user = new User(); 
        $user->name = $request->full_name; 
        $user->user_type = "farmer"; 
        $user->username = $request->full_name; 
        $user->email = $email; 
        $user->password =  $password; 
        $user->phone_number = $request->phone_number; 
        $user->email_verified_at = ""; 
        // dd($password);
        $user->save();
        $farmer_photo = [];
        if (!empty($request->all()['farmer_photo'])) {
            foreach ($request->all()['farmer_photo'] as $photo) {                        
                $id = (new UploadsController)->upload_photo($photo,$user->id);

                if (!empty($id)) {
                    array_push($farmer_photo, $id);
                }
            }    
        }
        $id_proof_photo = [];
        if (!empty($request->all()['id_proof_photo'])) {
            
            foreach ($request->all()['id_proof_photo'] as $photo) {                        
                $id = (new UploadsController)->upload_photo($photo,$user->id);

                if (!empty($id)) {
                    array_push($id_proof_photo, $id);
                }
            }    
        }
        
        $data_farmer_details =[
            'staff_id'=>$staff->id,
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
            'is_online'=>$request->is_online,
            'farmer_photo'=>implode(',', $farmer_photo),
            'id_proof_photo'=>implode(',', $id_proof_photo),
        ];
       
        try {
            $farmer_data = $farmer_details->create($data_farmer_details);
            $data_log_activities['status_code'] = 200;
            $data_log_activities['status_msg'] = 'Farmer Registration Successfully';
            $this->create_log((object) $data_log_activities);
            $countable->update(['count_number'=>$countable->count_number +=1]);
            return response()->json([
                'result' => true,
                'message' => 'Farmer Registration Successfully',
                'data' =>[
                    'farmer_data'=>$farmer_data
                ]
            ]);
        } catch (\Exception $e) {  
            $data_log_activities['status_code'] = 400;
            $data_log_activities['status_msg'] = $e->getMessage();
            $this->create_log((object) $data_log_activities);
            // $log_actitvities->store_log((object) $data_log_activities);
            User::find($user->id)->delete();
            return response()->json([
                'result' => true,
                'message' => 'Farmer Registration Failed',
            ]);
        }
        
    }

    public function get_data_for_family_info($id)
    {
        if(!isset($id))
        {
            return response()->json([
                'result' => false,
                'message' => 'Missing Parameter',
            ]);
        }
        $farmer_data = FarmerDetails::find($id);
        if(!isset($farmer_data))
        {
            return response()->json([
                'result' => false,
                'message' => 'Farmer Not Exists',
            ]);
        }
        if ( $farmer_data->family_info ) {
            $family_info = $farmer_data->family_info->first();
        } else {
            $family_info = [];
        }
       
        
        $data_education = [];
        $data_marial_status = [];
        $data_gender = [];
        $education = FarmCatalogue::where('NAME','Education Status')->first();
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
            'message' =>'Get Data Successfully',
            'data' => [
                'data_education' =>$data_education,
                'data_marial_status' =>$data_marial_status,
                'family_info' =>$family_info
            ]
            
        ]);
    }

    public function get_data_for_asset_info($id)
    {
        if(!isset($id))
        {
            return response()->json([
                'result' => false,
                'message' => 'Missing Parameter',
            ]);
        }
        $farmer_data = FarmerDetails::find($id);
        if(!isset($farmer_data))
        {
            return response()->json([
                'result' => false,
                'message' => 'Farmer Not Exists',
            ]);
        }
        if ( $farmer_data->asset_info ) {
            $asset_info = $farmer_data->asset_info->first();
        } else {
            $asset_info = [];
        }
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
            'message' =>'Get Data Successfully',
            'data' => [
                'data_housing_owner' =>$data_housing_owner,
                'data_house_type' =>$data_house_type,
                'data_consumer_electronic' =>$data_consumer_electronic,
                'data_vehicle' =>$data_vehicle,
                'asset_info'=>$asset_info
            ]
            
        ]);
    }

    public function get_data_for_bank_info($id)
    {
        if(!isset($id))
        {
            return response()->json([
                'result' => false,
                'message' => 'Missing Parameter',
            ]);
        }
        $farmer_data = FarmerDetails::find($id);
        if(!isset($farmer_data))
        {
            return response()->json([
                'result' => false,
                'message' => 'Farmer Not Exists',
            ]);
        }
        if ($farmer_data->bank_info ) {
            $bank_info = $farmer_data->bank_info()->get();
        } else {
            $bank_info = [];
        }
        $data_account_type = [];
        $account_type = FarmCatalogue::where('NAME','Account Type')->first();
        if(isset($account_type))
        {
            $data_account_type = $account_type->catalogue_value()->get();
        }
        return response()->json([
            'result' => true,
            'message' =>'Get Data Successfully',
            'data' => [
                'data_account_type' =>$data_account_type,
                'bank_info'=>$bank_info
            ]
           
        ]);
    }

    public function get_data_for_finance_info($id)
    {
        if(!isset($id))
        {
            return response()->json([
                'result' => false,
                'message' => 'Missing Parameter',
            ]);
        }
        $farmer_data = FarmerDetails::find($id);
        if(!isset($farmer_data))
        {
            return response()->json([
                'result' => false,
                'message' => 'Farmer Not Exists',
            ]);
        }
        if ($farmer_data->finance_info ) {
            $finance_info = $farmer_data->finance_info()->first();
        } else {
            $finance_info = [];
        }
        $data_purpose = [];
        $purpose = FarmCatalogue::where('NAME','Purpose')->first();
        if(isset($purpose))
        {
            $data_purpose = $purpose->catalogue_value()->get();
        }
        return response()->json([
            'result' => true,
            'message' =>'Get Data Successfully',
            'data' => [
                'data_purpose' =>$data_purpose,
                'finance_info' =>$finance_info
            ]

        ]);
    }

    public function get_data_for_insurance_info($id)
    {
        $data_crop = [];
        $farmer_data = FarmerDetails::find($id);
        if(!isset($farmer_data))
        {
            return response()->json([
                'result' => false,
                'message' => 'Farmer Not Exists',
            ]);
        }
        if ($farmer_data->insurance_info ) {
            $insurance_info = $farmer_data->insurance_info()->get();
        } else {
            $insurance_info = [];
        }
        $data_crop = CropInformation::All();
        return response()->json([
            'result' => true,
            'message' =>'Get Data Successfully',
            'data' => [
                'data_crop' =>$data_crop,
                'insurance_info'=>$insurance_info
            ]
        ]);
    }

    public function get_data_for_animal_husbandry($id)
    {
        $data_farm_animal = [];
        $data_fodder = [];
        $data_animal_housing = [];
        $data_animal_for_growth = [];
        $farmer_data = FarmerDetails::find($id);
        if(!isset($farmer_data))
        {
            return response()->json([
                'result' => false,
                'message' => 'Farmer Not Exists',
            ]);
        }
        if ($farmer_data->animal_husbandry ) {
            $animal_husbandry = $farmer_data->animal_husbandry()->get();
        } else {
            $animal_husbandry = [];
        }
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
            'message' =>'Get Data Successfully',
            'data' => [
                'data_farm_animal' =>$data_farm_animal,
                'data_fodder' =>$data_fodder,
                'data_animal_housing' =>$data_animal_housing,
                'data_animal_for_growth' =>$data_animal_for_growth,
                'animal_husbandry'=>$animal_husbandry
            ]
           
        ]);
    }

    public function get_data_for_certificate_info($id)
    {
        $data_enrollment_place = [];
        $data_identity_proof = [];
        $data_gender = [];
        $farmer_data = FarmerDetails::find($id);
        if(!isset($farmer_data))
        {
            return response()->json([
                'result' => false,
                'message' => 'Farmer Not Exists',
            ]);
        }
        if ($farmer_data->certificate_info ) {
            $certificate_info = $farmer_data->certificate_info()->get();
        } else {
            $certificate_info = [];
        }
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
            'message' =>'Get Data Successfully',
            'data' => [
                'data_identity_proof' =>$data_identity_proof,
                'data_enrollment_place' =>$data_enrollment_place,
                'data_gender' =>$data_gender,
                'certificate_info'=>$certificate_info
            ]
            
        ]);
    }

    public function get_data_for_farm_equipment($id)
    {
        $data_farm_equipment = [];
        $farmer_data = FarmerDetails::find($id);
        if(!isset($farmer_data))
        {
            return response()->json([
                'result' => false,
                'message' => 'Farmer Not Exists',
            ]);
        }
        if ($farmer_data->farm_equipment ) {
            $farm_equipment_info = $farmer_data->farm_equipment()->get();
        } else {
            $farm_equipment_info = [];
        }
        $farm_equipment = FarmCatalogue::where('NAME','Farm Equipments')->first();
        if(isset($farm_equipment))
        {
            $data_farm_equipment = $farm_equipment->catalogue_value()->get();
        }
        return response()->json([
            'result' => true,
            'message' =>'Get Data Successfully',
            'data' => [
                'data_farm_equipment' =>$data_farm_equipment,
                'farm_equipment'=>$farm_equipment_info
            ]
            
        ]);
    }

    public function create_log($data)
    {
        // dd($data);
        $staff = Auth::user()->staff;
        $log_actitvities = new LogActivitiesController();
        $data_log_activities = [
            'staff_id' => $staff->id,
            'type' => 308,
            'action'=>$data->action,
            'request'=>$data->request,
            'status_code'=>$data->status_code,
            'status_msg'=>$data->status_msg,
            'lat'=>$data->lat,
            'lng'=>$data->lng
        ];
        $log_actitvities->store_log((object) $data_log_activities);
    }
}
