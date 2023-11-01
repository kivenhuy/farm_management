<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Commune;
use App\Models\Country;
use App\Models\District;
use App\Models\FarmerCountable;
use App\Models\FarmerDetails;
use App\Models\LogActivities;
use Yajra\DataTables\DataTables;
use App\Models\Province;
use App\Models\Staff;
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
    public function __construct()
    {
        $log_actitvities = new LogActivities();
    }



    public function index()
    {
        return view('farmer.index');
        // $farmer_data = FarmerDetails::all();
        // foreach ($farmer_data as $details_farmer_data)
        // {
        //     $details_farmer_data->farmer_photo = uploaded_asset($details_farmer_data->farmer_photo);
        //     $details_farmer_data->country= Country::find($details_farmer_data->country)->country_name;
        //     $details_farmer_data->province= Province::find($details_farmer_data->province)->province_name;
        //     $details_farmer_data->district= District::find($details_farmer_data->district)->district_name;
        //     $details_farmer_data->commune= Commune::find($details_farmer_data->commune)->commune_name;
        // }
        
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
        $farmerDetail = FarmerDetails::find($id);
        
        return view('farmer.show',['farmerDetail'=> $farmerDetail]);
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
    public function farmer_location(Request $request)
    {
        $farmer_data = FarmerDetails::all();
        // foreach ($farmer_data as $each_farmer)
        // {
        //     $farm_land_data = $each_farmer->farm_lands()->get();
        //     $cultivation_crop = $each_farmer->cultivation_crop()->get();
        //     dd($cultivation_crop);
        // }
        // dd($farmer_data);
        return view('farmer.farmer_location',['farmers_data'=>$farmer_data]);
    }

    public function dtajax(Request $request)
    {
        $farmer = FarmerDetails::all()->sortDesc();
        $out =  DataTables::of($farmer)->make(true);
        $data = $out->getData();
        for($i=0; $i < count($data->data); $i++) {
            $output = '';
            $output .= ' <a href="'.url(route('farmer.show',['id'=>$data->data[$i]->id])).'" class="btn btn-primary btn-xs"  data-toggle="tooltip" title="Show Details" style="display:inline;padding:2px 5px 3px 5px;"><i class="fa fa-eye"></i></a>';
             
            $data->data[$i]->action = (string)$output;

            $staff = Staff::find($data->data[$i]->staff_id);
            $data->data[$i]->staff_name = $staff?->name;
        }
        $out->setData($data);
        // dd($out);
        return $out;
        
    }
    
    // public function registration(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'phone_number' => 'required|string|unique:users,phone_number',
    //         'username' => 'string|unique:users,username',
    //         'password' => 'required|string|min:5',
    //     ]);
    //     if ($validator->fails()) {
    //         return response()->json([
    //             'result' => false,
    //             'message' => $validator->messages(),
    //         ]);
    //     }
    //     $user = New User();
    //     $farmer_details = New FarmerDetails();

    //     if($request->email == null)
    //     {
    //         $email = "";
    //     }
    //     $user_data = [
    //         'name' =>$request->full_name,
    //         'user_type'=>'farmer',
    //         'username'=>$request->full_name,
    //         'email'=>$email,
    //         'password'=>Hash::make($request->password),
    //         'phone_number'=>$request->phone_number,
    //         'email_verified_at' => ''
    //     ];

    //     $user = new User(); 
    //     $user->name = $request->full_name; 
    //     $user->user_type = "farmer"; 
    //     $user->username = $request->full_name; 
    //     $user->email = $email; 
    //     $user->password = Hash::make($request->password); 
    //     $user->phone_number = $request->phone_number; 
    //     $user->email_verified_at = ""; 
    //     $user->save();

    //     // $user->create($user_data);
    //     if (!empty($request->all()['farmer_photo'])) {
    //         $farmer_photo = [];
    //         foreach ($request->all()['farmer_photo'] as $photo) {                        
    //             $id = (new UploadsController)->upload_photo($photo,$user->id);

    //             if (!empty($id)) {
    //                 array_push($farmer_photo, $id);
    //             }
    //         }    
    //     }

    //     if (!empty($request->all()['id_proof_photo'])) {
    //         $id_proof_photo = [];
    //         foreach ($request->all()['id_proof_photo'] as $photo) {                        
    //             $id = (new UploadsController)->upload_photo($photo,$user->id);

    //             if (!empty($id)) {
    //                 array_push($id_proof_photo, $id);
    //             }
    //         }    
    //     }

    //     $ldate = date('Ymd');
    //     $current_timestamp = Carbon::now()->timestamp; 
    //     $farmer_code = 'Farmer-'.$ldate.'-'.$current_timestamp;
    //     $data_farmer_details =[
    //         'user_id'=>$user->id,
    //         'enrollment_date' =>$request->enrollment_date,
    //         'enrollment_place'=>$request->enrollment_place,
    //         'full_name'=>$request->full_name,
    //         'phone_number'=>$request->phone_number,
    //         'identity_proof'=>$request->identity_proof,
    //         'country'=>$request->country,
    //         'province'=>$request->province,
    //         'district'=>$request->district,
    //         'commune'=>$request->commune,
    //         'village'=>$request->village,
    //         'lng'=>$request->lng,
    //         'lat'=>$request->lat,
    //         'proof_no'=>$request->proof_no,
    //         'gender'=>$request->gender,
    //         'farmer_code'=>$farmer_code,
    //         'dob'=>$request->dob,
    //         'farmer_photo'=>implode(',', $farmer_photo),
    //         'id_proof_photo'=>implode(',', $id_proof_photo),
    //     ];
    //     $farmer_data = $farmer_details->create($data_farmer_details);
    //     return response()->json([
    //         'result' => true,
    //         'message' => 'Farmer Registration Successfully',
    //         'farmer_data' =>$farmer_data
    //     ]);
    // }

    public function importCSV(Request $request)
    {
        $faker = \Faker\Factory::create();

        $filePath = $request->csvFile->path(); // csvFile is request name input
        if ($file = fopen($filePath, "r")) {
            while(($row = fgetcsv($file, 1000, ",")) !== FALSE) {     
                if ($row[0] == "First Name") {
                    continue;
                }

                $staff = Staff::where('id', '!=', 1)->has('farmer_details','<', 200)->first();
                if (empty($staff)) {
                    $staff = Staff::find(3);
                }

                $fullName = $row[0] . ' ' . $row[1];

                $phoneNumber = str_replace('o', '0', $row[2]);
                $phoneNumber = preg_replace("([^0-9])",'', $phoneNumber);

                if(empty($phone_number)) {
                    $phoneNumber = str_replace('+', '0', fake()->unique()->e164PhoneNumber());
                }

                $province = Province::where('province_name', $row[4])->first();
                if (empty($province)) {
                    $province = Province::where('province_name', 'like', '%'.$row[2]. '%')->first();
                }

                $dicstrict = District::where('district_name', $row[5])->first();
                if (empty($province)) {
                    $dicstrict = District::where('district_name', 'like', '%'.$row[3]. '%')->first();
                }

                $commune = Commune::where('commune_name', $row[6])->first();
                if (empty($commune)) {
                    $commune = Commune::where('commune_name', 'like', '%'.$row[6]. '%')->first();
                }

                $village = $row[7];

                $countable = FarmerCountable::find(1);
                $farmer_code = 'FA'.date('Y').date('m').date('d').$countable->count_number;
                $countable->update(['count_number'=>$countable->count_number +=1]);

                FarmerDetails::create([
                    'user_id' => 3,
                    'staff_id' => $staff->id,
                    'full_name' => $fullName,
                    'phone_number' => $phoneNumber,
                    'country' => 1,
                    'province' => $province->id ?? 0,
                    'district' => $dicstrict->id ?? 0,
                    'commune' => $commune->id ?? 0,
                    'village' => $village,
                    // auto generate field
                    'enrollment_date' => now()->toDateString(),
                    'gender' => 'Male',
                    'farmer_code' => $farmer_code,
                ]);
            }
            fclose($file);
        }

        return back()->with(['success' => 'Import farmer succesfully']);
    }
}
