<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\LogActivitiesController;
use App\Models\CarbonEmission;
use App\Models\Cultivations;
use Illuminate\Support\Facades\Http;
use App\Models\SaleIntention;
use App\Models\SRPFertilizerApplication;
use App\Models\SRPLandPreparation;
use App\Models\SRPPesticideApplication;
use App\Models\SRPSchedule;
use App\Models\SRPWaterIrrigation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class SaleIntentionController extends Controller
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
       try {
                $sale_intention = new SaleIntention();
                $data_sale = [
                    'farmer_id'=>$request->farmer_id,
                    'farm_land_id'=>$request->farm_land_id,
                    'cultivation_id'=>$request->cultivation_id,
                    'season_id'=>$request->season_id,
                    'variety'=>$request->variety,
                    'sowing_date'=>$request->sowing_date,
                    'quantity'=>$request->quantity,
                    'min_price'=>$request->min_price,
                    'max_price'=>$request->max_price,
                    'date_for_harvest'=>$request->date_for_harvest,
                    'aviable_date'=>$request->aviable_date,
                    'grade'=>$request->grade,
                    'age_of_crop'=>$request->age_of_crop,
                    'quality_check'=>$request->quality_check,
                    'product_id'=>$request->product_id,
                ];
                // return response()->json([
                //     'result' => true,
                //     'message' => 'Sale Intention has been inserted successfully',
                //     'data'=>[
                //         'sale_intention'=>$data_sale,
                //     ]
                // ]);
                $final_data = $sale_intention->create($data_sale);
                return response()->json([
                    'result' => true,
                    'message' => 'Sale Intention has been inserted successfully',
                    'data'=>[
                        'sale_intention'=>$final_data,
                    ]
                ]);
        } 
        catch (\Exception $exception) {
            return response()->json([
                'result' => false,
                'message' => $exception->getMessage(),
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
       $data_sale_intention = SaleIntention::where('product_id',$id)->first();
       $data_carbon_emission = $data_sale_intention->cultivation->carbon_emission;
       if(isset($data_carbon_emission))
       {
            $data_emission = $data_carbon_emission->emission;
            $data_product_loss = $data_carbon_emission->product_loss;
            $data_carbon_stage = $data_carbon_emission->carbon_stage;
       }
       else
       {
            $data_emission = null;
            $data_product_loss = null;
            $data_carbon_stage = null;
       }
       $arr_date_and_cost_land_prepare = [];
       $arr_date_and_cost_water_igiration = [];
       $arr_date_and_cost_fertilize = [];
       $arr_date_and_cost_pesticise = [];
       if($data_sale_intention->farmer->srp_certification == 1)
       {
            $array_schedule = ['srp_water_irrigation','srp_fertilizer_application','srp_pesticide_application','srp_land_preparation'];
            $id_srp = $data_sale_intention->cultivation->srp->id;
            $data_srp_schedule = SRPSchedule::where('srp_id',$id_srp)->where(function($query) use($array_schedule){
                foreach($array_schedule as $keyword) {
                    $query->orWhere('name_action', 'LIKE', "%$keyword%");
                }
            })->get();
          
            foreach($data_srp_schedule as $sub_data_each_srp)
            {
                switch($sub_data_each_srp->name_action)
                {
                    case(str_contains($sub_data_each_srp->name_action,'srp_land_preparation')):
                        $data_each_srp_land_prepare = SRPLandPreparation::where([['srp_id',$id_srp],['question', 'LIKE', "total_cost_of_day"]])->whereDate('created_at',$sub_data_each_srp->date_action)->first();
                        if($data_each_srp_land_prepare)
                        {
                            $arr_date_and_cost_land_prepare[$sub_data_each_srp->date_action] = (int)$data_each_srp_land_prepare->answer;
                        }
                        else
                        {
                            $arr_date_and_cost_land_prepare[$sub_data_each_srp->date_action] = 0;
                        }
                        
                        break;
                    case(str_contains($sub_data_each_srp->name_action,'srp_water_irrigation')):
                        $data_each_srp_land_prepare = SRPWaterIrrigation::where([['srp_id',$id_srp],['question', 'LIKE', "total_cost_of_day"]])->whereDate('created_at',$sub_data_each_srp->date_action)->first();
                        if($data_each_srp_land_prepare)
                        {
                            $arr_date_and_cost_water_igiration[$sub_data_each_srp->date_action] = (int)$data_each_srp_land_prepare->answer;
                        }
                        else
                        {
                            $arr_date_and_cost_water_igiration[$sub_data_each_srp->date_action] = 0;
                        }
                        
                        break;
                    case(str_contains($sub_data_each_srp->name_action,'srp_fertilizer_application')):
                        $data_each_srp_land_prepare = SRPFertilizerApplication::where([['srp_id',$id_srp],['question', 'LIKE', "total_cost_of_day"]])->whereDate('created_at',$sub_data_each_srp->date_action)->first();
                        if($data_each_srp_land_prepare)
                        {
                            $arr_date_and_cost_fertilize[$sub_data_each_srp->date_action] = (int)$data_each_srp_land_prepare->answer;
                        }
                        else
                        {
                            $arr_date_and_cost_fertilize[$sub_data_each_srp->date_action] = 0;
                        }
                       
                        break;
                    case(str_contains($sub_data_each_srp->name_action,'srp_pesticide_application')):
                        $data_each_srp_land_prepare = SRPPesticideApplication::where([['srp_id',$id_srp],['question', 'LIKE', "total_cost_of_day"]])->whereDate('created_at',$sub_data_each_srp->date_action)->first();
                        if($data_each_srp_land_prepare)
                        {
                            $arr_date_and_cost_pesticise[$sub_data_each_srp->date_action] = (int)$data_each_srp_land_prepare->answer;
                        }
                        else
                        {
                            $arr_date_and_cost_pesticise[$sub_data_each_srp->date_action] = 0;
                        }
                        break;
                }
            }
       }
       return response()->json
       ([
        'result' => true,
        'message' => 'Get data Successfully',
        'data'=>
            [
                'data_sale_intention'=>$data_sale_intention,
                'data_farmer'=>$data_sale_intention->farmer,
                'data_farm_land'=>$data_sale_intention->farm_land,
                'data_cultivation'=>$data_sale_intention->cultivation,
                'data_season'=>$data_sale_intention->season,
                'data_emission'=>$data_emission,
                'data_product_loss'=>$data_product_loss,
                'data_carbon_stage'=>$data_carbon_stage,
                'arr_date_and_cost_land_prepare' =>$arr_date_and_cost_land_prepare,
                'arr_date_and_cost_water_igiration' =>$arr_date_and_cost_water_igiration,
                'arr_date_and_cost_fertilize' =>$arr_date_and_cost_fertilize,
                'arr_date_and_cost_pesticise' =>$arr_date_and_cost_pesticise,
            ]
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SaleIntention $saleIntention)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SaleIntention $saleIntention)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SaleIntention $saleIntention)
    {
        //
    }

    public function create_log($data)
    {
        // dd($data);
        $staff = Auth::user()->staff;
        $log_actitvities = new LogActivitiesController();
        $data_log_activities = [
            'staff_id' => $staff?->id,
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
