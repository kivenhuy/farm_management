<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SRP;
use App\Models\SRPLandPreparation;
use App\Models\SRPPrePlanting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class SRPController extends Controller
{
    public function srpUploadImage(Request $request)
    {

    }

    public function storePrePlanting(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'farmer_id' => 'required|exists:farmer_details,id',
            'cultivation_id' => 'required|exists:cultivations,id',
            'srps_id' => 'required|exists:srps,id',
            'data_question_answer_group' => 'required|array',
        ]);

        if ($validator->fails()) {
            return $validator->messages();
        }
        
        $staff = Auth::user()->staff;
        $total_score = 0;
        
        foreach($request->data_question_answer_group as $groupData) {
            $collectionCode = SRPPrePlanting::max('collection_code') ?? 0;
            $latestCollectionCode = $collectionCode + 1;

            foreach($groupData as $key => $data) {
                $answer = !empty($data['answer']) ? $data['answer'] : "";
                $score = !empty($data['score']) ? $data['score'] : 0;

                SRPPrePlanting::create([
                    'farmer_id' => $request->farmer_id,
                    'cultivation_id' => $request->cultivation_id,
                    'staff_id'=> $staff->id,
                    'srps_id' => $request->srps_id,
                    'collection_code' => $latestCollectionCode,
                    'question'=> $key,
                    'answer'=> $answer,
                    'score' => $score
                ]);

                $total_score += $score;
            }
        }

        $srp = SRP::find($request->srps_id);
        $srp->score += $total_score;
        $srp->save();

        return response()->json([
            'result' => true,
            'message' => 'SRP Pre-planting Created Successfully',
        ]);
    }

    // ========== Get api ================

    public function getPrePlanting(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'farmer_id' => 'required|exists:farmer_details,id',
            'cultivation_id' => 'required|exists:cultivations,id',
            'srps_id' => 'required|exists:srps,id',
        ]);

        if ($validator->fails()) {
            return $validator->messages();
        }

        $staff = Auth::user()->staff;

        $landPreparations = SRPPrePlanting::where('farmer_id', $request->farmer_id)
            ->where('cultivation_id', $request->cultivation_id)
            ->where('srps_id', $request->srps_id)
            ->where('staff_id', $staff->id)
            ->get()
            ->groupBy('collection_code');

        $dataGroup = [];
        foreach($landPreparations as $landPreparation) {
            $dataGroup[] = $landPreparation;
        }

        return response()->json(['data' => $dataGroup]);
    }

}
