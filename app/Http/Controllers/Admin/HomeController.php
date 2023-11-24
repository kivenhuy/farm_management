<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Commune;
use App\Models\Country;
use App\Models\FarmerDetails;
use App\Models\FarmLand;
use App\Models\Staff;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function dashboard()
    {
        $staffs = Staff::withCount('farmer_details')->where('status', 'active')->get();
        $farmerCount = FarmerDetails::where('status', 'active')->count();
        $totalLandHolding = FarmLand::where('status', 'active')->sum('total_land_holding');
        $totalFarmlands = FarmLand::where('status', 'active')->count();

        $staffsFormat = [];
        foreach ($staffs as $staff) {
            if ($staff->farmer_details_count > 0) {
                $staffsFormat[] = [
                    'name' => $staff->first_name,
                    'data' => [$staff->farmer_details_count],
                ];
            }
        }

        $comunessData = [];
        foreach (Commune::withCount('farmer_details')->get() as $commune) {
            if (empty($commune->farmer_details_count)) {
                continue;
            }
            $comunessData[] = [
                'name' => $commune->commune_name,
                'data' => [$commune->farmer_details_count],
            ];
        }

        // Farm area by commune chart
        $communeCategory = Commune::has('farmer_details')->pluck('commune_name')->toArray();
        $communes = Commune::has('farmer_details')->get();
        $communeByFarmAreas = [];
        if ($communes->count()) {
            foreach ($communes as $commune) {
                $farmerDetailIds = FarmerDetails::where('commune', $commune->id)->pluck('id')->toArray();
                $totalLandHoldingByCommune = FarmLand::whereIn('farmer_id', $farmerDetailIds)->sum('total_land_holding');
                $actualAreaByCommune = FarmLand::whereIn('farmer_id', $farmerDetailIds)->sum('actual_area');
                
                $communeByFarmAreas[] = [
                    'name' => $commune->commune_name,
                    'total_land_holding' => $totalLandHoldingByCommune,
                    'actual_area' => $actualAreaByCommune,
                ];
            }
        }

        return view('admin.dashboard', compact('staffsFormat', 'comunessData', 'staffs', 'farmerCount', 'totalLandHolding', 'totalFarmlands', 'communeCategory', 'communeByFarmAreas'));
    }
}
