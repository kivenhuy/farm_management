<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CropInformation;
use App\Models\Crops;
use App\Models\Season;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CropsController extends Controller
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
        $season = Season::all();
        $crop_information  = CropInformation::all();
        return response()->json([
            'result' => true,
            'message' => 'Get Data Crops Successfully',
            'data'=> [
                'season' =>$season,
                'crop_information'=> $crop_information
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
        $crops = new Crops();
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
        try 
        {
            $final_crops = $crops->create($data_crops);
            if($final_crops)
            {
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
                'result' => true,
                'message' => 'Farm Land Failed',
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
        $crop_data = Crops::find($id);
        $farm_land = $crop_data->farm_land()->get();
        $season_master = $crop_data->season()->get();
        $crop_master = $crop_data->crops_master()->get();
        return response()->json([
            'result' => true,
            'message' => 'Get Data Crops Successfully',
            'data'=> [
                'crop_data' =>$crop_data,
                'farm_land' =>$farm_land,
                'season_master' =>$season_master,
                'crop_master' =>$crop_master
            ]
           
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Crops $crops)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Crops $crops)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Crops $crops)
    {
        //
    }

    public function create_log($data)
    {
        // dd($data);
        $staff = Auth::user()->staff;
        $log_actitvities = new LogActivitiesController();
        $data_log_activities = [
            'staff_id' => $staff->id,
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
