<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\LogActivitiesController;
use Illuminate\Support\Facades\Http;
use App\Models\SaleIntention;
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
        dd(uploaded_asset(106));
        $farmer_photo = [];
        $staff = Auth::user()->staff;
        if (!empty($request->all()['photo'])) {
            foreach ($request->all()['photo'] as $photo) {                        
                $id = (new UploadsController)->upload_photo($photo,$staff->id);

                if (!empty($id)) {
                    array_push($farmer_photo, $id);
                }
            }    
        }
        $format = 'd/m/Y';
        $heromarketUrl = env('HEROMARKET_URL');
        $signupApiUrl = $heromarketUrl . '/api/v2/auction/product_store_seller';
        $data_auction_products = [
            'name'=>$request->variety,
            'added_by'=>'seller',
            'category_id'=>1,
            'brand_id'=>0,
            'barcode'=>"",
            'starting_bid'=>$request->min_price,
            'photo'=>$request->all()['photo'],
            'description'=>"",
            'video_provider'=>"",
            'video_link'=>"",
            'shipping_type'=>"",
            'meta_title'=>"",
            'meta_description'=>"",
            'sku'=>"9081241",
            'est_shipping_days'=>3,
            'unit'=>'KG',
            'auction_date_range'=>"upstream",
            'date_for_harvest'=>Carbon::createFromFormat($format, $request->date_for_harvest)->timestamp,
            'aviable_date'=>Carbon::createFromFormat($format, $request->aviable_date)->timestamp,
       ];
    //    dd($data_auction_products);
       try {
        // dd($signupApiUrl);
            $response = Http::withToken('54|MRuVmHTkbtpSh6vW9ziiwTOtzHBCEIr90pt5xRds')->post($signupApiUrl, $data_auction_products);
            if(json_decode($response)->data)
            {
                $sale_intention = new SaleIntention();
                $data_sale = [
                    'farmer_id'=>$request->farmer_id,
                    'farm_land_id'=>$request->farmer_id,
                    'crop_id'=>$request->crop_id,
                    'season_id'=>$request->season_id,
                    'variety'=>$request->variety,
                    'sowing_date'=>$request->sowing_date,
                    'photo'=>implode(',', $farmer_photo),
                    'quantity'=>$request->quantity,
                    'min_price'=>$request->min_price,
                    'max_price'=>$request->max_price,
                    'date_for_harvest'=>$request->date_for_harvest,
                    'aviable_date'=>$request->aviable_date,
                    'grade'=>$request->grade,
                    'age_of_crop'=>$request->age_of_crop,
                    'quality_check'=>$request->quality_check,
                ];
                $sale_intention->create($data_sale);
                return response()->json([
                    'result' => true,
                    'message' => 'Sale Intention has been inserted successfully',
                    'data'=>[
                        'sale_intention'=>$sale_intention,
                    ]
                ]);
            }

        } 
        catch (\Exception $exception) {
            \Log::error($exception->getMessage());
            $data_log_activities['status_code'] = 400;
            $data_log_activities['status_msg'] = $exception->getMessage();
            $this->create_log((object) $data_log_activities);
        }
       
    //    dd($response);
    }

    /**
     * Display the specified resource.
     */
    public function show(SaleIntention $saleIntention)
    {
        //
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
