<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NutrientManagement;
use App\Models\SRP;
use App\Models\SRPFarmManagement;
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

    public function storeFarmManagement(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'farmer_id' => 'required|exists:farmer_details,id',
            'cultivation_id' => 'required|exists:cultivations,id',
            'srp_id' => 'required|exists:srps,id',
            'data_question_answer_group' => 'required|array',
        ]);

        if ($validator->fails()) {
            return $validator->messages();
        }
        
        $staff = Auth::user()->staff;
        $total_score = 0;
        
        foreach($request->data_question_answer_group as $groupData) {
            foreach($groupData as $key => $data) {
                $answer = !empty($data['answer']) ? $data['answer'] : "";
                $score = !empty($data['score']) ? $data['score'] : 0;

                SRPFarmManagement::create([
                    'farmer_id' => $request->farmer_id,
                    'cultivation_id' => $request->cultivation_id,
                    'staff_id'=> $staff->id,
                    'srp_id' => $request->srp_id,
                    'question'=> $key,
                    'answer'=> $answer,
                    'score' => $score
                ]);

                $total_score += $score;
            }
        }

        $srp = SRP::find($request->srp_id);
        $srp->score += $total_score;
        $srp->save();

        return response()->json([
            'result' => true,
            'message' => 'SRP Farm Management Created Successfully',
        ]);
    }

    public function storePrePlanting(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'farmer_id' => 'required|exists:farmer_details,id',
            'cultivation_id' => 'required|exists:cultivations,id',
            'srp_id' => 'required|exists:srps,id',
            'data_question_answer_group' => 'required|array',
        ]);

        if ($validator->fails()) {
            return $validator->messages();
        }
        
        $staff = Auth::user()->staff;
        $total_score = 0;
        foreach($request->data_question_answer_group as $key => $groupData) {
            // dd($groupData);
            // foreach($groupData as $key => $data) {
                $answer = !empty($groupData['answer']) ? $groupData['answer'] : "";
                $score = !empty($groupData['score']) ? $groupData['score'] : 0;

                SRPPrePlanting::create([
                    'farmer_id' => $request->farmer_id,
                    'cultivation_id' => $request->cultivation_id,
                    'staff_id'=> $staff->id,
                    'srp_id' => $request->srp_id,
                    'question'=> $key,
                    'answer'=> $answer,
                    'score' => $score
                ]);
                $total_score += $score;
            // }
        }

        $srp = SRP::find($request->srp_id);
        $srp->score += $total_score;
        $srp->save();

        return response()->json([
            'result' => true,
            'message' => 'SRP Pre-planting Created Successfully',
        ]);
    }

    // ========== Get api ================

    public function getFarmManagement(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'farmer_id' => 'required|exists:farmer_details,id',
            'cultivation_id' => 'required|exists:cultivations,id',
            'srp_id' => 'required|exists:srps,id',
        ]);

        if ($validator->fails()) {
            return $validator->messages();
        }

        $staff = Auth::user()->staff;

        $landPreparations = SRPFarmManagement::where('farmer_id', $request->farmer_id)
            ->where('cultivation_id', $request->cultivation_id)
            ->where('srp_id', $request->srp_id)
            ->where('staff_id', $staff->id)
            ->get();

        return response()->json(['data' => $landPreparations]);
    }

    public function getPrePlanting(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'farmer_id' => 'required|exists:farmer_details,id',
            'cultivation_id' => 'required|exists:cultivations,id',
            'srp_id' => 'required|exists:srps,id',
        ]);

        if ($validator->fails()) {
            return $validator->messages();
        }

        $staff = Auth::user()->staff;

        $landPreparations = SRPPrePlanting::where('farmer_id', $request->farmer_id)
            ->where('cultivation_id', $request->cultivation_id)
            ->where('srp_id', $request->srp_id)
            ->where('staff_id', $staff->id)
            ->get(['question','answer','score']);
            // ->groupBy('collection_code');

        $dataGroup = [];
        foreach($landPreparations as $landPreparation) {
            $dataGroup[] = $landPreparation;
        }

        return response()->json(['data' => $dataGroup]);
    }



    // Nutrient Managemet 
    public function storeNutrientManagement(Request $request)
    {
        // dd($request);
        $validator = Validator::make($request->all(), [
            'farmer_id' => 'required|exists:farmer_details,id',
            'cultivation_id' => 'required|exists:cultivations,id',
            'srp_id' => 'required|exists:srps,id',
            'data_question_answer_group' => 'required|array',
        ]);

        $id_water_management = 0;
        if ($validator->fails()) {
            return $validator->messages();
        }
        
        $staff = Auth::user()->staff;
        $total_score = 0;
        foreach($request->data_question_answer_group as $key => $groupData) {
            // dd($groupData);
            // foreach($groupData as $key => $data) {
                $answer = !empty($groupData['answer']) ? $groupData['answer'] : "";
                $score = !empty($groupData['score']) ? $groupData['score'] : 0;

                NutrientManagement::create([
                    'farmer_id' => $request->farmer_id,
                    'cultivation_id' => $request->cultivation_id,
                    'staff_id'=> $staff->id,
                    'srp_id' => $request->srp_id,
                    'question'=> $key,
                    'answer'=> $answer,
                    'score' => $score
                ]);
               
                $total_score += $score;
            // }
        }

        $srp = SRP::find($request->srp_id);
        $srp->score += $total_score;
        $srp->save();

        return response()->json([
            'result' => true,
            'message' => 'SRP Nutrient Management Created Successfully',
        ]);
    }


    public function getNutrientManagement(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'farmer_id' => 'required|exists:farmer_details,id',
            'cultivation_id' => 'required|exists:cultivations,id',
            'srp_id' => 'required|exists:srps,id',
        ]);

        if ($validator->fails()) {
            return $validator->messages();
        }

        $staff = Auth::user()->staff;

        $landPreparations = NutrientManagement::where('farmer_id', $request->farmer_id)
            ->where('cultivation_id', $request->cultivation_id)
            ->where('srp_id', $request->srp_id)
            ->where('staff_id', $staff->id)
            ->get(['question','answer','score']);
            // ->groupBy('collection_code');

        $dataGroup = [];
        foreach($landPreparations as $landPreparation) {
            $dataGroup[] = $landPreparation;
        }

        return response()->json(['data' => $dataGroup]);
    }

    // Fertilizer Application
    public function storeFertilizerApplication(Request $request)
    {
        // dd($request);
        $validator = Validator::make($request->all(), [
            'farmer_id' => 'required|exists:farmer_details,id',
            'cultivation_id' => 'required|exists:cultivations,id',
            'srp_id' => 'required|exists:srps,id',
            'data_question_answer_group' => 'required|array',
        ]);

        $id_water_management = 0;
        if ($validator->fails()) {
            return $validator->messages();
        }
        
        $staff = Auth::user()->staff;
        $total_score = 0;
        foreach($request->data_question_answer_group as $key => $groupData) {
            // dd($groupData);
            // foreach($groupData as $key => $data) {
                $answer = !empty($groupData['answer']) ? $groupData['answer'] : "";
                $score = !empty($groupData['score']) ? $groupData['score'] : 0;

                NutrientManagement::create([
                    'farmer_id' => $request->farmer_id,
                    'cultivation_id' => $request->cultivation_id,
                    'staff_id'=> $staff->id,
                    'srp_id' => $request->srp_id,
                    'question'=> $key,
                    'answer'=> $answer,
                    'score' => $score
                ]);
               
                $total_score += $score;
            // }
        }

        $srp = SRP::find($request->srp_id);
        $srp->score += $total_score;
        $srp->save();

        return response()->json([
            'result' => true,
            'message' => 'SRP Nutrient Management Created Successfully',
        ]);
    }


    public function getFertilizerApplication(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'farmer_id' => 'required|exists:farmer_details,id',
            'cultivation_id' => 'required|exists:cultivations,id',
            'srp_id' => 'required|exists:srps,id',
        ]);

        if ($validator->fails()) {
            return $validator->messages();
        }

        $staff = Auth::user()->staff;

        $landPreparations = NutrientManagement::where('farmer_id', $request->farmer_id)
            ->where('cultivation_id', $request->cultivation_id)
            ->where('srp_id', $request->srp_id)
            ->where('staff_id', $staff->id)
            ->get(['question','answer','score']);
            // ->groupBy('collection_code');

        $dataGroup = [];
        foreach($landPreparations as $landPreparation) {
            $dataGroup[] = $landPreparation;
        }

        return response()->json(['data' => $dataGroup]);
    }

}
