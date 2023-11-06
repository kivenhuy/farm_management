<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CropInformation;
use App\Models\Cultivations;
use App\Models\FarmerDetails;
use App\Models\FarmLand;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone_number' => 'required|string',
            'password' => 'required|string|min:5',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'result' => false,
                'message' => $validator->messages(),
            ]);
        }

        $credential = [
            'phone_number' => $request->input('phone_number'),
            'password' => $request->input('password'),
        ];

        if (auth()->attempt($credential)) {
            $user = User::where('phone_number',  $request->input('phone_number'))->first();
            if ($user) {
                return $this->loginSuccess($user);
            } 
            return response()->json([
                'result' => false,
                'message' => 'User is not exist',
            ]);
        } 
        
        return response()->json([
            'result' => false,
            'message' => 'The credentials did not match',
        ]);
    }

    public function loginSuccess($user, $token = null)
    {

        if (!$token) {
            $token = $user->createToken('Farm-angel API Token')->plainTextToken;
        }
        
        return response()->json([
            'result' => true,
            'message' => 'Successfully logged in',
            'data' =>[
                'user' => [
                    'id' => $user->id,
                    'type' => $user->user_type,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone_number,
                ],
                'access_token' => $token,
                'token_type' => 'Bearer',
                'expires_at' => null,
            ]
        ]);
    }

    public function logout(Request $request)
    {
        $user = request()->user();
        $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();

        return response()->json([
            'result' => true,
            'message' => 'Successfully logged out',
        ]);
    }

    public function dashboard(Request $request)
    {
        $request->validate([
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric',
            'nearby_km' => 'nullable|numeric',
        ]);
        $farmerDetail = Auth::user()->staff->farmer_details->take(5);
        $totalFarmer = Auth::user()->staff->farmer_details->count();
        $totalHectares = FarmLand::whereIn('farmer_id', $farmerDetail->pluck('id'))->sum('total_land_holding');
        $totalPlot = FarmLand::whereIn('farmer_id', $farmerDetail->pluck('id'))->sum('actual_area');

        $nearbyPlot = [];
        if ($request->lat && $request->lng) {
            $nearbyKm   = $request->nearby_km ?? 3;
            foreach (Farmland::get() as $farmLand) {
                $distance = $this->distance($request->lat, $request->lng, $farmLand->lat, $farmLand->lng);
                if ($distance > $nearbyKm) {
                    continue;
                }

                array_push($nearbyPlot, $farmLand);
            }
        }

        $farmer_list = FarmerDetails::with('farm_lands:id,farm_name,actual_area,farmer_id')
            ->select('id','full_name', 'farmer_code','phone_number', 'farmer_photo')
            ->latest()
            ->take(5)
            ->get();

        $totalExpectedYield = Cultivations::sum('est_yield');

        return response()->json([
            'result' => true,
            'message' => 'dashboard page',
            'data' => [
                'total_farmmer' => $totalFarmer,
                'total_hectares' => $totalHectares,
                'total_plot' => $totalPlot,
                'nearby_plot' => $nearbyPlot,
                'farmer_list' => $farmerDetail,
                'totalExpectedYield' => $totalExpectedYield,
            ]
        ]);
    }

    public function distance($lat1, $lon1, $lat2, $lon2) {
        if (($lat1 == $lat2) && ($lon1 == $lon2)) {
          return 0;
        }
        else {
          $theta = $lon1 - $lon2;
          $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
          $dist = acos($dist);
          $dist = rad2deg($dist);
          $miles = $dist * 60 * 1.1515;
      
          return ($miles * 1.609344); // return KM
        }
    }
}
