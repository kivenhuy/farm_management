<?php

namespace App\Http\Controllers;

use App\Models\CropVariety;
use App\Models\District;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AjaxOptionsController extends Controller
{
    public function getProvinces(Request $request)
    {
        $validator = Validator::make($request->all(), ['country_id' => 'required|numeric|exists:countries,id']);

        if ($validator->fails()) {
            return response()->json([]);
        }

        $provinces = Province::where('country_id', $request->input('country_id'))->get();

        return response()->json($provinces->pluck('province_name', 'id')->toArray());
    }

    public function getDistricts(Request $request)
    {
        $validator = Validator::make($request->all(), ['province_id' => 'required|numeric|exists:provinces,id']);

        if ($validator->fails()) {
            return response()->json([]);
        }

        $provinces = District::where('province_id', $request->input('province_id'))->get();

        return response()->json($provinces->pluck('district_name', 'id')->toArray());
    }

    public function getVarieties(Request $request)
    {
        $validator = Validator::make($request->all(), ['crop_information_id' => 'required|numeric|exists:crop_informations,id']);

        if ($validator->fails()) {
            return response()->json([]);
        }

        $cropVarieties = CropVariety::where('crop_id', $request->input('crop_information_id'))->get();

        return response()->json($cropVarieties->pluck('name', 'id')->toArray());
    }
}
