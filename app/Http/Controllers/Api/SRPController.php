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
        foreach($request->data_question_answer_group as $key => $answerData) {
            $answer = !empty($answerData['answer']) ? $answerData['answer'] : "";
            $score = !empty($answerData['score']) ? $answerData['score'] : 0;
            dd($answerData);

            SRPPrePlanting::create([
                'farmer_id' => $request->farmer_id,
                'cultivation_id' => $request->cultivation_id,
                'staff_id'=> $staff->id,
                'srps_id' => $request->srps_id,
                'question' => $key,
                'answer' => $answer,
                'score' => $score,
            ]);
        }

        $srp = SRP::find($request->srps_id);
        $srp->score += $total_score;
        $srp->save();

        return response()->json([
            'result' => true,
            'message' => 'SRP Pre-planting Created Successfully',
        ]);
    }
}
