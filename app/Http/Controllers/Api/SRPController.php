<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SRP;
use App\Models\SRPLandPreparation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SRPController extends Controller
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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
    }

    /**
     * Display the specified resource.
     */
    public function show(SRP $sRP)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SRP $sRP)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SRP $sRP)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SRP $sRP)
    {
        //
    }


    public function store_land_preparation(Request $request)
    {
        $data_log_activities = [];
        $data_log_activities['action'] = 'create';
        $data_log_activities['lat'] = $request->staff_lat;
        $data_log_activities['lng'] = $request->staff_lng;
        $data_log_activities['request'] = $request->all();
        $landpreparation = new SRPLandPreparation();
        $staff = Auth::user()->staff;
        $total_score = 0;
        // foreach($request->data_srp as $key => $value)
        // {
        //     $data_training = [
        //         'farmer_id'=>$request->farmer_id,
        //         'cultivation_id'=>$request->cultivation_id,
        //         'staff_id'=>$staff->id,
        //         'srps_id'=>$request->srps_id,
        //         'question'=>$key,
        //         'answer'=>$value,
        //         'score'=>$score,
        //     ];
        //     try {
        //         $training_data->create($data_training);
        //         $total_score += $score;
        //     } 
        //     catch (\Exception $e) {  
        //         $data_log_activities['status_code'] = 400;
        //         $data_log_activities['status_msg'] = $e->getMessage();
        //         $this->create_log((object) $data_log_activities);
        //         return response()->json([
        //             'result' => true,
        //             'message' => 'Training SRP Created Failed',
        //         ]);
        //     }
        // }
        // dd($total_score);
        $srp_data = SRP::find($request->srps_id);
        $srp_data->score = $total_score;
        $srp_data->save();
        $data_log_activities['status_code'] = 200;
        $data_log_activities['status_msg'] = 'SRP Land Preparation Created Successfully';
        $this->create_log((object) $data_log_activities);
        return response()->json([
            'result' => true,
            'message' => 'SRP Land Preparation',
        ]);
       
    }

    public function create_log($data)
    {
        // dd($data);
        $staff = Auth::user()->staff;
        $log_actitvities = new LogActivitiesController();
        $data_log_activities = [
            'staff_id' => $staff->id,
            'type' => 500,
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
