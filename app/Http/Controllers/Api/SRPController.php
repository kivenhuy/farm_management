<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SRP;
use App\Models\SRPPrePlanting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
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

    public function storePrePlanting(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'farmer_id' => 'required|exists:farmer_details,id',
            'cultivation_id' => 'required|exists:cultivations,id',
            'srps_id' => 'required|exists:srps,id',
            'data_finance' => 'required|array',
        ]);

        if ($validator->fails()) {
            return $validator->messages();
        }
        
        $staff = Auth::user()->staff;
        $total_score = 0;
        foreach($request->data_finance as $key => $answer) {
            $score = !empty($answer['score']) ? $answer['score'] : 0;
            SRPPrePlanting::create([
                'farmer_id' => $request->farmer_id,
                'cultivation_id' => $request->cultivation_id,
                'staff_id'=> $staff->id,
                'srps_id' => $request->srps_id,
                'question' => $key,
                'answer' => !empty($answer['answer']) ? $answer['answer'] : "",
                'score' => $score,
            ]);

            $total_score += $score;
        }
        $srp = SRP::find($request->srps_id);
        $srp->score += $total_score;
        $srp->save();

        return response()->json([
            'result' => true,
            'message' => 'Training SRP Created Successfully',
        ]);
    }
}
