<?php

namespace App\Http\Controllers;

use App\Models\Commune;
use App\Models\CropInformation;
use App\Models\Cultivations;
use App\Models\FarmerDetails;
use App\Models\FarmLand;
use App\Models\SeasonMaster;
use App\Models\Staff;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
class ReportController extends Controller
{
    // Farmer Report Page
    public function farmer_report(Request $request)  
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $farmerCode = $request->input('farmer_code');
        $farmerName = $request->input('farmer_name');
        $phoneNumber = $request->input('phone_number');
        $provinceId = $request->input('province_id');
        $staffId = $request->input('staff_id');
        $exportExcel = $request->input('export_excel');

        $farmerDetailQuery = FarmerDetails::orderByDesc('created_at')
            ->withCount(['farm_lands'])
            ->withSum('farm_lands as sum_total_land_holding', 'total_land_holding');

        if (!empty($startDate)) {
            $farmerDetailQuery->where('enrollment_date', '>=', $startDate);
        }

        if (!empty($endDate)) {
            $farmerDetailQuery->where('enrollment_date', '<=', $endDate);
        }

        if (!empty($farmerCode)) {
            $farmerDetailQuery->where('farmer_code', $farmerCode);
        }

        if (!empty($farmerName)) {
            $farmerDetailQuery->Where('full_name', 'like', '%' . $farmerName . '%');
        }

        if (!empty($phoneNumber)) {
            $farmerDetailQuery->where('phone_number', $phoneNumber);
        }

        if (!empty($provinceId)) {
            $farmerDetailQuery->where('province', $provinceId);
        }

        if (!empty($staffId)) {
            $farmerDetailQuery->where('staff_id', $staffId);
        }

        if($exportExcel) {
            $farmerDetails = $farmerDetailQuery->get();
            
            return response()->streamDownload(function () use ($farmerDetails) {
                $this->exportFarmer($farmerDetails);
            }, 'Farmer.csv');
        }

        $farmerDetails = $farmerDetailQuery->paginate()->appends($request->except('page'));

        return view('report.farmer_report_index', compact('farmerDetails', 'farmerCode', 'farmerName', 'startDate', 'endDate',  'phoneNumber', 'provinceId', 'staffId'));
    }

    private function exportFarmer($farmerDetails)
    {      
        $file = fopen('php://output', 'w');
        fputs($file, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF))); // Fix for Excel

        fputcsv($file, [
            'Enrollment Date',
            'Farmer Code',
            'Farmer Name',
            'Phone Number',
            'Field Officer',
            'Gender',
            'Province',
            'District',
            'Commune',
            'Total No of Plots',
            'Total land holding(HA)',
            'Status',
        ]);

        foreach ($farmerDetails as $farmerDetail) {
            fputcsv($file, [
                $farmerDetail->enrollment_date,
                $farmerDetail->farmer_code,
                $farmerDetail->full_name,
                $farmerDetail->phone_number,
                $farmerDetail->staff->name,
                $farmerDetail->gender,
                $farmerDetail->provinceRelation?->province_name,
                $farmerDetail->districtRelation?->district_name,
                $farmerDetail->communeRelation?->commune_name,
                $farmerDetail->sum_total_land_holding,
                ucwords($farmerDetail->status)
            ]);
        }

        fclose($file);
    } 

    public function farmer_report_ajax(Request $request)
    {
        $farmer = FarmerDetails::all()->sortDesc();
        $out =  DataTables::of($farmer)->make(true);
        $data = $out->getData();
        for($i=0; $i < count($data->data); $i++) {
            $output = '';
            $output .= ' <a href="'.url(route('farmer.show',['id'=>$data->data[$i]->id])).'" class="btn btn-primary btn-xs"  data-toggle="tooltip" title="Show Details" style="display:inline;padding:2px 5px 3px 5px;"><i class="fa fa-eye"></i></a>';
            $data->data[$i]->action = (string)$output;

            $staff = Staff::find($data->data[$i]->staff_id);
            $commune = Commune::find($data->data[$i]->commune);
            $data->data[$i]->staff_name = $staff?->name;
            $data->data[$i]->commune = $commune?->name;
        }
        $out->setData($data);
        // dd($out);
        return $out;
        
    }


    public function farmland_report()
    {
        return view('report.farmland_report');
    }

    public function farmland_report_ajax(Request $request)
    {
        $farmland = FarmLand::all()->sortDesc();
        $out =  DataTables::of($farmland)->make(true);
        $data = $out->getData();
        for($i=0; $i < count($data->data); $i++) {
            $output = '';
            $output .= ' <a href="'.url(route('farmer_report.singel_farmland_location',['id'=>$data->data[$i]->id])).'" class="btn btn-primary btn-xs"  data-toggle="tooltip" title="Show Details" style="display:inline;padding:2px 5px 3px 5px;"><i class="fa fa-map-marker"></i></a>';
            $data->data[$i]->action = (string)$output;

            $farmer_details = FarmerDetails::find($data->data[$i]->farmer_id, ['staff_id', 'farmer_code','full_name','phone_number']);
            $staff = Staff::find($farmer_details->staff_id);
            $data->data[$i]->staff_name = $staff?->name;
            $data->data[$i]->farmer_details = $farmer_details;
            $data->data[$i]->actual_area = round($data->data[$i]->actual_area/10000,2);
        }
        $out->setData($data);
        // dd($out);
        return $out;
        
    }


    public function singel_farmland_location($id)
    {
        $farm_land_data = FarmLand::find($id);
        $plot_data = [];
        $data_farmer = FarmerDetails::select(['full_name','farmer_code','farmer_photo'])->find($farm_land_data->farmer_id);
        $cultivation_data = $farm_land_data->cultivation()->first();
        if(isset($cultivation_data))
        {
            $season_data = SeasonMaster::find($cultivation_data->season_id);
            $crop_information = CropInformation::find($cultivation_data->crop_id);
            $farm_land_data->crop_name = $crop_information->name;
            $farm_land_data->season_period_from = $season_data->from_period;
            $farm_land_data->season_period_to = $season_data->to_period;
            $farm_land_data->est_yeild = $cultivation_data->est_yield;
            $farm_land_data->harvest_date = $cultivation_data->expect_date;
        }
        else
        {
            $farm_land_data->crop_name = 'N/A';
            $farm_land_data->season_period_from = 'N/A';
            $farm_land_data->season_period_to = 'N/A';
            $farm_land_data->est_yeild = 'N/A';
            $farm_land_data->harvest_date ='N/A';
        }
        $farm_land_data->farmer_name = $data_farmer->full_name;
        $farm_land_data->farmer_code = $data_farmer->farmer_code;
        $farm_land_data->farmer_photo = uploaded_asset($data_farmer->farmer_photo);
        $data_ploting = $farm_land_data->farm_land_lat_lng()->get();
        foreach($data_ploting as $each_data_ploting)
        {
            if($each_data_ploting->order == 1)
            {
                $farm_land_data->lat = $each_data_ploting->lat;
                $farm_land_data->lng = $each_data_ploting->lng;
            }
            $subplot = [
                'lat'=>$each_data_ploting->lat,
                'lng'=>$each_data_ploting->lng
            ];
            array_push($plot_data,$subplot);
            
        }
        if(count($data_ploting)>0)
        {
            $subplot_final = [
                'lat'=>$data_ploting[0]->lat,
                'lng'=>$data_ploting[0]->lng
            ];
            array_push($plot_data,$subplot_final);
        }
        
        $farm_land_data->plot_data = $plot_data;
        // dd($farm_land_data);
        return view('farm_land.single_farmland_loaction',['farm_land_data'=>$farm_land_data]);
    }

    public function cultivation_report()  
    {
        return view('report.cultivation_report');
    }

    public function cultivation_report_ajax(Request $request)
    {
        $cultivation = Cultivations::all()->sortDesc();
        $out =  DataTables::of($cultivation)->make(true);
        $data = $out->getData();
        for($i=0; $i < count($data->data); $i++) {
            // $farmer_details = FarmerDetails::find($data->data[$i]->farmer_id, ['staff_id', 'farmer_code','full_name','phone_number']);
            // $staff = Staff::find($farmer_details->staff_id);
            $farmer_data = Cultivations::find($data->data[$i]->id)->farm_land->farmer_details;
            $farm_land = Cultivations::find($data->data[$i]->id)->farm_land;
            $staff = Staff::find($farmer_data->staff_id);
            $season = SeasonMaster::find($data->data[$i]->season_id)->season_name;
            $data->data[$i]->season = $season;
            $data->data[$i]->staff_name = $staff?->name;
            $data->data[$i]->farmer_data = $farmer_data;
            $data->data[$i]->farm_land = $farm_land;
        }
        $out->setData($data);
        // dd($out);
        return $out;
        
    }

}
