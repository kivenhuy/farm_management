<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FarmCatalogue;
use App\Models\FarmerDetails;
use App\Models\FarmLand;
use App\Models\FarmLandLatLng;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FarmLandController extends Controller
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
        $staff = Auth::user();
        $data_appoarch_road = [];
        $data_land_topolog = [];
        $data_land_gradient = [];
        $data_land_document = [];
        $data_farmer = [];
        $appoarch_road = FarmCatalogue::where('NAME','Approach Road')->first();
        if(isset($appoarch_road))
        {
            $data_appoarch_road = $appoarch_road->catalogue_value()->get();
        }
        $land_topolog = FarmCatalogue::where('NAME','Land Topology')->first();
        if(isset($land_topolog))
        {
            $data_land_topolog = $land_topolog->catalogue_value()->get();
        }
        $land_gradient = FarmCatalogue::where('NAME','Land Gradient')->first();
        if(isset($land_gradient))
        {
            $data_land_gradient = $land_gradient->catalogue_value()->get();
        }
        $land_document = FarmCatalogue::where('NAME','Land Document')->first();
        if(isset($land_document))
        {
            $data_land_document = $land_document->catalogue_value()->get();
        }
        $land_owner_ship = FarmCatalogue::where('NAME','Land Ownership')->first();
        if(isset($land_owner_ship))
        {
            $data_land_owner_ship = $land_owner_ship->catalogue_value()->get();
        }
        $all_farmer = FarmerDetails::where('staff_id',$staff->staff->first()->id)->get();
        return response()->json([
            'result' => true,
            'message' => 'Farmer Created Successfully',
            'data'=>[
                'data_appoarch_road' =>$data_appoarch_road,
                'data_land_topolog' =>$data_land_topolog,
                'data_land_gradient' =>$data_land_gradient,
                'data_land_document' =>$data_land_document,
                'data_land_owner_ship'=>$data_land_owner_ship,
                'all_farmer'=>$all_farmer
            ]
            
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $log_actitvities = new LogActivitiesController();
        $staff = Auth::user()->staff;
        $data_log_activities = [
            'request' =>$request->all(),
            'staff_id' => $staff->id,
            'action' =>'create',
            'type' => 308
        ];
        $data_farm_land_lat_lng = json_decode($request->list_lat_lng);
        // dd($user->farmer_detail()->first()->id);
        $farmer_data = FarmerDetails::find($request->farmer);
        $farm_photo = [];
        $land_document = [];
        if (!empty($request->all()['farm_photo'])) {
            
            foreach ($request->all()['farm_photo'] as $photo) {                        
                $id = (new UploadsController)->upload_photo($photo,$farmer_data->user_id);

                if (!empty($id)) {
                    array_push($farm_photo, $id);
                }
            }    
        }
      
        if (!empty($request->all()['land_document'])) {
            
            foreach ($request->all()['land_document'] as $photo) {                        
                $id = (new UploadsController)->upload_photo($photo,$farmer_data->user_id);

                if (!empty($id)) {
                    array_push($land_document, $id);
                }
            }    
        }

        $data_farm_land = [
            'farmer_id' => $request->farmer,
            'farm_name' => $request->farm_name,
            'total_land_holding' => $request->total_land_holding,
            'lat' => $request->lat,
            'lng' => $request->lng,
            'farm_land_ploting' => $request->farm_land_ploting,
            'actual_area' => $request->actual_area, 
            'farm_photo' =>implode(',', $farm_photo),
            'land_ownership'=> $request->land_ownership, 
            'srp_score'=> $request->srp_score, 
            'carbon_index'=> $request->carbon_index, 
            'approach_road'=> $request->approach_road, 
            'land_topology'=> $request->land_topology, 
            'land_gradient'=> $request->land_gradient, 
            'land_document'=> implode(',', $land_document), 
        ];
        $farm_land = new FarmLand;

        try {
            $final_farm_land = $farm_land->create($data_farm_land);
            if($final_farm_land)
            {
                $farm_land_lat_lng = new FarmLandLatLng;
                foreach($data_farm_land_lat_lng as $key => $lat_lng)
                {
                    $farm_land_lat_lng_data = [
                        'farmer_id'=> $request->farmer,
                        'farm_land_id'=> $final_farm_land->id,
                        'order'=> $key + 1,
                        'lat'=> $lat_lng[0], 
                        'lng'=> $lat_lng[1] 
                    ];
                    $final_farm_land_lat_lng=$farm_land_lat_lng->create($farm_land_lat_lng_data);
                }
            }
            $data_log_activities['status_code'] = 200;
            $data_log_activities['status_msg'] = 'Farm Land Created Successfully';
            $log_actitvities->store_log((object) $data_log_activities);
            return response()->json([
                'result' => true,
                'message' => 'Farm Land Created Successfully',
                'data'=>[
                    'farm_land' =>$final_farm_land,
                    'farm_land_lat_lng' =>$final_farm_land_lat_lng
                ]
            ]);
        } catch (\Exception $e) {  
            $data_log_activities['status_code'] = 400;
            $data_log_activities['status_msg'] = $e->getMessage();
            $log_actitvities->store_log((object) $data_log_activities);
            return response()->json([
                'result' => true,
                'message' => 'Farm Land Failed',
            ]);
        }



        

        
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $farm_land_data = FarmLand::find($id);
        // dd($farm_land_data);
        $farmer_name = FarmerDetails::find($farm_land_data->farmer_id)->full_name;
        $farm_land_data->farmer_name = $farmer_name;
        $farm_land_data->farm_photo = uploaded_asset($farm_land_data->farm_photo);
        return response()->json([
            'result' => true,
            'message' => 'Get Farm Land Successfully',
            'data' =>[
                'farm_land_data'=>$farm_land_data,
                'farm_land_ploting'=>$farm_land_data->farm_land_lat_lng()->get()
            ]
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FarmLand $farmLand)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FarmLand $farmLand)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FarmLand $farmLand)
    {
        //
    }
}
