<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CropInformation;
use App\Models\Cultivations;
use App\Models\FarmLand;
use App\Models\Season;
use App\Models\SeasonMaster;
use App\Models\SRP;
use App\Models\SRPSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CultivationsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $season = SeasonMaster::all();
        $crop_information  = CropInformation::all();
        $farm_land = Auth::user()->staff->farm_land_count;
        return response()->json([
            'result' => true,
            'message' => 'Get Data Crops Successfully',
            'data'=> [
                'season' =>$season,
                'crop_information'=> $crop_information,
                'farm_land'=>$farm_land
            ]
           
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data_log_activities = [];
        $data_log_activities['action'] = 'create';
        $data_log_activities['request'] = $request->all();
        $data_log_activities['lat'] = $request->staff_lat;
        $data_log_activities['lng'] = $request->staff_lng;
        $validator = Validator::make($request->all(), [
            'farm_land_id' => 'required|string',
            'season_id' => 'required|string',
            'crop_master_id' => 'required|string',
            'crop_variety' => 'required|string',
            'sowing_date' => 'required|string',
            'expect_date' => 'required|string',
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
            } catch (\Exception $e) {  
            
            }
            return response()->json([
                'result' => false,
                'message' => $validator->messages(),
            ]);
        }
        $user = Auth::user();
        $crop_photo = [];
        if (!empty($request->all()['photo'])) {
            
            foreach ($request->all()['photo'] as $photo) {                        
                $id = (new UploadsController)->upload_photo($photo,$user->id);
                if (!empty($id)) {
                    array_push($crop_photo, $id);
                }
            }    
        }
        $crops = new Cultivations();
        $data_crops = [
            'farm_land_id'=>$request->farm_land_id,
            'season_id'=>$request->season_id,
            'crop_id'=>$request->crop_master_id,
            'crop_variety'=>$request->crop_variety,
            'sowing_date'=>$request->sowing_date,
            'expect_date'=>$request->expect_date,
            'est_yield'=>$request->est_yield,
            'photo'=>implode(',', $crop_photo), 
        ];
        $sowing_date = Carbon::createFromFormat('d/m/Y', $request->sowing_date);

        $data_each_action_srp = [
            "srp_training" => $sowing_date->addDay(-7)->toDateString('d/m/Y'),
            "srp_pre_plainings" => $sowing_date->addDay(-3)->toDateString('d/m/Y'),
            "srp_land_preparations" => $sowing_date->addDay(-10)->toDateString('d/m/Y'),
            "srp_water_managements" => $sowing_date->addDay(0)->toDateString('d/m/Y'),
            "srp_water_irrigations" => $sowing_date->addDay(-3)->toDateString('d/m/Y'),
            "srp_water_irrigations_1" => $sowing_date->addDay(3)->toDateString('d/m/Y'),
            "srp_water_irrigations_2" => $sowing_date->addDay(10)->toDateString('d/m/Y'),
            "srp_water_irrigations_3" => $sowing_date->addDay(17)->toDateString('d/m/Y'),
            "srp_water_irrigations_4" => $sowing_date->addDay(25)->toDateString('d/m/Y'),
            "srp_water_irrigations_5" => $sowing_date->addDay(36)->toDateString('d/m/Y'),
            "srp_water_irrigations_6" => $sowing_date->addDay(45)->toDateString('d/m/Y'),
            "srp_water_irrigations_7" => $sowing_date->addDay(55)->toDateString('d/m/Y'),
            "srp_water_irrigations_8" => $sowing_date->addDay(65)->toDateString('d/m/Y'),
            "srp_water_irrigations_9" => $sowing_date->addDay(75)->toDateString('d/m/Y'),
            "srp_nutrient_management" => $sowing_date->addDay(55)->toDateString('d/m/Y'),
            "srp_fertilizer_applications_1" => $sowing_date->addDay(10)->toDateString('d/m/Y'),
            "srp_fertilizer_applications_2" => $sowing_date->addDay(25)->toDateString('d/m/Y'),
            "srp_fertilizer_applications_3" => $sowing_date->addDay(35)->toDateString('d/m/Y'),
            "srp_fertilizer_applications_4" => $sowing_date->addDay(45)->toDateString('d/m/Y'),
            "srp_integrated_pest_management" => $sowing_date->addDay(75)->toDateString('d/m/Y'),
            "srp_pesticide_applications_1" => $sowing_date->addDay(7)->toDateString('d/m/Y'),
            "srp_pesticide_applications_2" => $sowing_date->addDay(15)->toDateString('d/m/Y'),
            "srp_pesticide_applications_3" => $sowing_date->addDay(26)->toDateString('d/m/Y'),
            "srp_pesticide_applications_4" => $sowing_date->addDay(35)->toDateString('d/m/Y'),
            "srp_pesticide_applications_5" => $sowing_date->addDay(45)->toDateString('d/m/Y'),
            "srp_pesticide_applications_6" => $sowing_date->addDay(60)->toDateString('d/m/Y'),
            "srp_pesticide_applications_7" => $sowing_date->addDay(75)->toDateString('d/m/Y'),
            "srp_harvests_1" => $sowing_date->addDay(95)->toDateString('d/m/Y'),
            "srp_harvests_2" => $sowing_date->addDay(100)->toDateString('d/m/Y'),
            "srp_health_and_safetys" => $sowing_date->addDay(100)->toDateString('d/m/Y'),
            "srp_labour_rights" => $sowing_date->addDay(100)->toDateString('d/m/Y'),
            "srp_women_empowerments" => $sowing_date->addDay(100)->toDateString('d/m/Y'),
        ];
        try 
        {
            $final_crops = $crops->create($data_crops);
            if($final_crops)
            {
                $srps = new SRP();
                $data_srps = [
                    'farmer_id'=>$request->farmer_id,
                    'staff_id'=>$user->staff->id,
                    'farm_land_id'=>$request->farm_land_id,
                    'season_id'=>$request->season_id,
                    'cultivation_id'=>$final_crops->id,
                    'score'=>0,
                    'sowing_date'=>$request->sowing_date,
                ];
                
                $final_srps = $srps->create($data_srps);
                foreach($data_each_action_srp as $key =>$details)
                {
                    $srp_schedule = new SRPSchedule();
                    $data_srp_schedule = 
                    [
                        'srp_id'=>$final_srps->id,
                        'name_action'=>$key,
                        'date_action'=>$details,
                    ];
                    $final_srp_schedule = $srp_schedule->create($data_srp_schedule);
                }
                $data_log_activities['status_code'] = 200;
                $data_log_activities['status_msg'] = 'Crops Created Successfully';
                $this->create_log((object) $data_log_activities);
                return response()->json([
                    'result' => true,
                    'message' => 'Crops Created Successfully',
                    'data'=> [
                        'data_crops' =>$final_crops,
                    ]
                    
                ]);
            }
        } catch (\Exception $e) {  
            $data_log_activities['status_code'] = 400;
            $data_log_activities['status_msg'] = $e->getMessage();
            $this->create_log((object) $data_log_activities);
            return response()->json([
                'result' => false,
                'message' => $e,
            ]);
        }
        // else
        // {
        //     return response()->json([
        //         'result' => false,
        //         'message' => 'Crops Created Fail'
        //     ]);
        // }
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $crop_data = Cultivations::find($id);
        $farm_land = $crop_data->farm_land()->get();
        $season_master = $crop_data->season()->get();
        $crop_master = $crop_data->crops_master()->get();
        return response()->json([
            'result' => true,
            'message' => 'Get Data Cultivations Successfully',
            'data'=> [
                'cultivation_data' =>$crop_data,
                'farm_land' =>$farm_land,
                'season_master' =>$season_master,
                'crop_master' =>$crop_master,
                'carbon_emission_id'=>$crop_data->carbon_emission?->id,
                'srp_id'=>$crop_data->srp?->id
            ]
           
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cultivations $crops)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $crop_data = Cultivations::find($id);
        $data_log_activities = [];
        $data_log_activities['action'] = 'update';
        $data_log_activities['request'] = $request->all();
        $data_log_activities['lat'] = $request->staff_lat;
        $data_log_activities['lng'] = $request->staff_lng;
        $validator = Validator::make($request->all(), [
            'farm_land_id' => 'required|string',
            'season_id' => 'required|string',
            'crop_master_id' => 'required|string',
            'crop_variety' => 'required|string',
            'sowing_date' => 'required|string',
            'expect_date' => 'required|string',
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
            } catch (\Exception $e) {  
            
            }
            return response()->json([
                'result' => false,
                'message' => $validator->messages(),
            ]);
        }
        $user = Auth::user();
        $crop_photo = [];
        if (!empty($request->all()['photo'])) {
            
            foreach ($request->all()['photo'] as $photo) {                        
                $id = (new UploadsController)->upload_photo($photo,$user->id);
                if (!empty($id)) {
                    array_push($crop_photo, $id);
                }
            }    
        }
        // $crops = new Cultivations();
        $data_crops = [
            'farm_land_id'=>$request->farm_land_id ?? $crop_data->farm_land_id,
            'season_id'=>$request->season_id,
            'crop_id'=>$request->crop_master_id,
            'crop_variety'=>$request->crop_variety,
            'sowing_date'=>$request->sowing_date,
            'expect_date'=>$request->expect_date,
            'est_yield'=>$request->est_yield,
            'photo'=> !empty($crop_photo) ? implode(',', $crop_photo) : $crop_data->photo, 
        ];
        try 
        {
            $final_crops = $crop_data->update($data_crops);
            if($final_crops)
            {
                $data_log_activities['status_code'] = 200;
                $data_log_activities['status_msg'] = 'Crops Created Successfully';
                $this->create_log((object) $data_log_activities);
                return response()->json([
                    'result' => true,
                    'message' => 'Crops Updated Successfully',
                    'data'=> [
                        'data_crops' =>$final_crops,
                    ]
                    
                ]);
            }
        } catch (\Exception $e) {  
            $data_log_activities['status_code'] = 400;
            $data_log_activities['status_msg'] = $e->getMessage();
            $this->create_log((object) $data_log_activities);
            return response()->json([
                'result' => true,
                'message' => 'Farm Updated Fail',
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cultivations $crops)
    {
        //
    }

    public function get_crop_variety($id)
    {
        $crop_information = CropInformation::find($id);
        return response()->json([
            'result' => true,
            'message' => 'Get Crop Variety Successfully',
            'data'=> [
                'crop_variety' =>$crop_information->crop_variety,
            ]
            
        ]);

    }

    public function create_log($data)
    {
        // dd($data);
        $staff = Auth::user()->staff;
        $log_actitvities = new LogActivitiesController();
        $data_log_activities = [
            'staff_id' => $staff->id ?? 0,
            'type' => 357,
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
