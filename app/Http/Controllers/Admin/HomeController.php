<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Commune;
use App\Models\Country;
use App\Models\Staff;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function dashboard()
    {
        $staffs = Staff::withCount('farmer_details')->get();
        $staffsFormat = [];
        foreach ($staffs as $staff) {
            if ($staff->farmer_details_count > 0) {
                $staffsFormat[] = [
                    'name' => $staff->first_name,
                    'data' => [$staff->farmer_details_count],
                ];
            }
        }

        $commune = Country::get();
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

        return view('admin.dashboard', compact('staffsFormat', 'comunessData'));
    }
}
