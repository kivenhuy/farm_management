<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
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
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_at' => null,
            'data' =>[
                'user' => [
                    'id' => $user->id,
                    'type' => $user->user_type,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone_number,
                ]
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

    public function dashboard()
    {
        return response()->json([
            'result' => true,
            'message' => 'dashboard page',
        ]);
    }
}
