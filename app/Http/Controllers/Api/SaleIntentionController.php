<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Models\SaleIntention;
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
        $format = 'd/m/Y';;
        $heromarketUrl = env('HEROMARKET_URL');
        $signupApiUrl = $heromarketUrl . '/api/v2/auction/product_store_seller';
        $data_auction_products = [
            'name'=>$request->ST25,
            'added_by'=>$request->ST25,
            'category_id'=>$request->ST25,
            'brand_id'=>0,
            'barcode'=>"",
            'starting_bid'=>Carbon::createFromFormat($format, $request->date_for_harvest)->timestamp,
            'photos'=>$request->photo,
            'thumbnail_img'=>$request->photo,
            'description'=>"",
            'video_provider'=>"",
            'video_link'=>"",
            'shipping_type'=>"",
            'meta_title'=>"",
            'meta_description'=>"",
            'sku'=>"9081241",
            'est_shipping_days'=>3,
            'tags'=>[],
            'date_for_harvest'=>Carbon::createFromFormat($format, $request->date_for_harvest)->timestamp,
            'aviable_date'=>Carbon::createFromFormat($format, $request->aviable_date)->timestamp,
       ];
       try {
        // dd($signupApiUrl);
            $response = Http::withToken('54|MRuVmHTkbtpSh6vW9ziiwTOtzHBCEIr90pt5xRds')->post($signupApiUrl, $data_auction_products);
            dd(json_decode($response));

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
