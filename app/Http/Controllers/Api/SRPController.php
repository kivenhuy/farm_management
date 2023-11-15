<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NutrientManagement;
use App\Models\SRP;
use App\Models\SRPFarmManagement;
use App\Models\SRPIntegratedPestManagement;
use App\Models\SRPFertilizerApplication;
use App\Models\SRPHarvest;
use App\Models\SRPHealthAndSafety;
use App\Models\SRPLabourRight;
use App\Models\SRPLandPreparation;
use App\Models\SRPPesticideApplication;
use App\Models\SRPPrePlanting;
use App\Models\SRPWaterIrrigation;
use App\Models\SRPWaterManagement;
use App\Models\SRPWomenEmpowerment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class SRPController extends Controller
{
    public function srpUploadImage(Request $request)
    {

    }

    public function storeLandPreparation(Request $request)
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
        
        foreach($request->data_question_answer_group as $groupData) {
            $collectionCode = SRPLandPreparation::max('collection_code') ?? 0;
            $latestCollectionCode = $collectionCode + 1;

            foreach($groupData as $key => $data) {
                $answer = isset($data['answer']) ? $data['answer'] : "";
                $score = isset($data['score']) ? $data['score'] : 0;

                SRPLandPreparation::create([
                    'farmer_id' => $request->farmer_id,
                    'cultivation_id' => $request->cultivation_id,
                    'staff_id'=> $staff->id,
                    'srp_id' => $request->srp_id,
                    'section' => $data['section'],
                    'collection_code' => $latestCollectionCode,
                    'question'=> $key,
                    'answer'=> $answer,
                    'score' => $score
                ]);

            }
        }

        return response()->json([
            'result' => true,
            'message' => 'SRP Farm Management Created Successfully',
        ]);
    }

    public function getLandPreparation(Request $request)
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

        $landPreparationBySections = SRPLandPreparation::where('farmer_id', $request->farmer_id)
            ->where('cultivation_id', $request->cultivation_id)
            ->where('srp_id', $request->srp_id)
            ->where('staff_id', $staff->id)
            ->get()
            ->groupBy('section');

        $resultLandPreparationData = [];
        foreach ($landPreparationBySections as $section => $landPreparationBySection) {
            $dataLandPreparation = [];
            $landPreparationByCollectionCodes = $landPreparationBySection->groupBy('collection_code');
            
            foreach ($landPreparationByCollectionCodes as $landPreparation) {
                array_push($dataLandPreparation, $landPreparation);
            }

            $resultLandPreparationData[$section] = $dataLandPreparation;
        }

        return response()->json(['data'=> $resultLandPreparationData]);
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
                $answer = isset($data['answer']) ? $data['answer'] : "";
                $score = isset($data['score']) ? $data['score'] : 0;

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

    public function storeWaterManagement(Request $request)
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
        $idFirstOfWaterManagement = 0;
        
        foreach($request->data_question_answer_group as $groupData) {
            foreach($groupData as $key => $data) {
                $answer = isset($data['answer']) ? $data['answer'] : "";
                $score = isset($data['score']) ? $data['score'] : 0;
                $srpWaterManagement = SRPWaterManagement::create([
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
            'message' => 'SRP Water Management Created Successfully',
        ]);
    }

    public function storeWaterIrrigation(Request $request)
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
            $collectionCode = SRPWaterIrrigation::max('collection_code') ?? 0;
            $latestCollectionCode = $collectionCode + 1;

            foreach($groupData as $key => $data) {
                $answer = isset($data['answer']) ? $data['answer'] : "";
                $score = isset($data['score']) ? $data['score'] : 0;

                SRPWaterIrrigation::create([
                    'farmer_id' => $request->farmer_id,
                    'cultivation_id' => $request->cultivation_id,
                    'staff_id'=> $staff->id,
                    'srp_id' => $request->srp_id,
                    'section' => $data['section'],
                    'collection_code' => $latestCollectionCode,
                    'question'=> $key,
                    'answer'=> $answer,
                    'score' => $score
                ]);
            }
        }

        return response()->json([
            'result' => true,
            'message' => 'SRP Water Irrigation Created Successfully',
        ]);
    }

    public function storePesticideApplication(Request $request)
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
        
        foreach($request->data_question_answer_group as $groupData) {
            $collectionCode = SRPPesticideApplication::max('collection_code') ?? 0;
            $latestCollectionCode = $collectionCode + 1;

            foreach($groupData as $key => $data) {
                $answer = isset($data['answer']) ? $data['answer'] : "";
                $score = isset($data['score']) ? $data['score'] : 0;

                SRPPesticideApplication::create([
                    'farmer_id' => $request->farmer_id,
                    'cultivation_id' => $request->cultivation_id,
                    'staff_id'=> $staff->id,
                    'srp_id' => $request->srp_id,
                    'section' => $data['section'],
                    'collection_code' => $latestCollectionCode,
                    'question'=> $key,
                    'answer'=> $answer,
                    'score' => $score
                ]);
            }
        }

        return response()->json([
            'result' => true,
            'message' => 'SRP Pesticide Application Created Successfully',
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
        foreach($request->data_question_answer_group as $groupData) {
            foreach($groupData as $key => $data) {
            // dd($groupData);
            // foreach($groupData as $key => $data) {
                $answer = isset($data['answer']) ? $data['answer'] : "";
                $score = isset($data['score']) ? $data['score'] : 0;

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
            }
        }

        $srp = SRP::find($request->srp_id);
        $srp->score += $total_score;
        $srp->save();

        return response()->json([
            'result' => true,
            'message' => 'SRP Pre-planting Created Successfully',
        ]);
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
        foreach($request->data_question_answer_group as $groupData) {
            // dd($groupData);
            foreach($groupData as $key => $data) {
                $answer = isset($data['answer']) ? $data['answer'] : "";
                $score = isset($data['score']) ? $data['score'] : 0;

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
            }
        }

        $srp = SRP::find($request->srp_id);
        $srp->score += $total_score;
        $srp->save();

        return response()->json([
            'result' => true,
            'message' => 'SRP Nutrient Management Created Successfully',
        ]);
    }

    // Integrated Pest Management 
    public function storeIntegratedPestManagement(Request $request)
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
        foreach($request->data_question_answer_group as $groupData) {
            // dd($groupData);
            foreach($groupData as $key => $data) {
                $answer = isset($data['answer']) ? $data['answer'] : "";
                $score = isset($data['score']) ? $data['score'] : 0;

                SRPIntegratedPestManagement::create([
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
            'message' => 'SRP Integrated Pest Management Created Successfully',
        ]);
    }

    // Fertilizer Application
    public function storeFertilizerApplication(Request $request)
    {
        
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
        
        foreach($request->data_question_answer_group as $groupData) {
            $collectionCode = SRPFertilizerApplication::max('collection_code') ?? 0;
            $latestCollectionCode = $collectionCode + 1;

            foreach($groupData as $key => $data) {
                $answer = isset($data['answer']) ? $data['answer'] : "";
                $score = isset($data['score']) ? $data['score'] : 0;

                SRPFertilizerApplication::create([
                    'farmer_id' => $request->farmer_id,
                    'cultivation_id' => $request->cultivation_id,
                    'staff_id'=> $staff->id,
                    'srp_id' => $request->srp_id,
                    'section' => $data['section'],
                    'collection_code' => $latestCollectionCode,
                    'question'=> $key,
                    'answer'=> $answer,
                    'score' => $score
                ]);
            }
        }

        return response()->json([
            'result' => true,
            'message' => 'SRP Fertilizer Application Created Successfully',
        ]);
    }

    // Harvest
    public function storeHarvest(Request $request)
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
            $collectionCode = SRPHarvest::max('collection_code') ?? 0;
            $latestCollectionCode = $collectionCode + 1;

            foreach($groupData as $key => $data) {
                $answer = isset($data['answer']) ? $data['answer'] : "";
                $score = isset($data['score']) ? $data['score'] : 0;

                SRPHarvest::create([
                    'farmer_id' => $request->farmer_id,
                    'cultivation_id' => $request->cultivation_id,
                    'staff_id'=> $staff->id,
                    'srp_id' => $request->srp_id,
                    'section' => $data['section'],
                    'collection_code' => $latestCollectionCode,
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
            'message' => 'SRP Havest Created Successfully',
        ]);
    }

    public function storeLabourRight(Request $request)
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
            $collectionCode = SRPLabourRight::max('collection_code') ?? 0;
            $latestCollectionCode = $collectionCode + 1;

            foreach($groupData as $key => $data) {
                $answer = isset($data['answer']) ? $data['answer'] : "";
                $score = isset($data['score']) ? $data['score'] : 0;

                SRPLabourRight::create([
                    'farmer_id' => $request->farmer_id,
                    'cultivation_id' => $request->cultivation_id,
                    'staff_id'=> $staff->id,
                    'srp_id' => $request->srp_id,
                    'section' => $data['section'],
                    'collection_code' => $latestCollectionCode,
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
            'message' => 'SRP Labour Right Created Successfully',
        ]);
    }

    // Health And Safety
    public function storeHealthAndSafety(Request $request)
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
        foreach($request->data_question_answer_group as $groupData) {
            // dd($groupData);
            foreach($groupData as $key => $data) {
                $answer = isset($data['answer']) ? $data['answer'] : "";
                $score = isset($data['score']) ? $data['score'] : 0;

                SRPHealthAndSafety::create([
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
            'message' => 'SRP Health And Safety Created Successfully',
        ]);
    }

    // Women Empowerment
    public function storeWomenEmpowerment(Request $request)
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
        foreach($request->data_question_answer_group as $groupData) {
            // dd($groupData);
            foreach($groupData as $key => $data) {
                $answer = isset($data['answer']) ? $data['answer'] : "";
                $score = isset($data['score']) ? $data['score'] : 0;

                SRPWomenEmpowerment::create([
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
            'message' => 'SRP Women Empowerment Successfully',
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

    public function getWaterManagement(Request $request)
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

        $waterManagement = SRPWaterManagement::where('farmer_id', $request->farmer_id)
            ->where('cultivation_id', $request->cultivation_id)
            ->where('srp_id', $request->srp_id)
            ->where('staff_id', $staff->id)
            ->get();

        return response()->json(['data'=> $waterManagement]);
    }

    public function getWaterIrrigation(Request $request)
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

        $waterIrrigationBySections = SRPWaterIrrigation::where('farmer_id', $request->farmer_id)
            ->where('cultivation_id', $request->cultivation_id)
            ->where('srp_id', $request->srp_id)
            ->where('staff_id', $staff->id)
            ->get()
            ->groupBy('section');

        $resultData = [];
        foreach ($waterIrrigationBySections as $section => $waterIrrigationBySection) {
            $dataWaterIrrigation = [];
            $waterIrrigationByCollectionCodes = $waterIrrigationBySection->groupBy('collection_code');
            
            foreach ($waterIrrigationByCollectionCodes as $waterIrrigation) {
                array_push($dataWaterIrrigation, $waterIrrigation);
            }

            $resultData[$section] = $dataWaterIrrigation;
        }

        return response()->json(['data'=> $resultData]);
    }

    public function getPesticideApplication(Request $request)
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

        $pesticideApplicationBySections = SRPPesticideApplication::where('farmer_id', $request->farmer_id)
            ->where('cultivation_id', $request->cultivation_id)
            ->where('srp_id', $request->srp_id)
            ->where('staff_id', $staff->id)
            ->get()
            ->groupBy('section');

        $resultData = [];
        foreach ($pesticideApplicationBySections as $section => $pesticideApplicationBySection) {
            $dataPesticideApplication = [];
            $pesticideApplicationByCollectionCodes = $pesticideApplicationBySection->groupBy('collection_code');
            
            foreach ($pesticideApplicationByCollectionCodes as $pesticideApplication) {
                array_push($dataPesticideApplication, $pesticideApplication);
            }

            $resultData[$section] = $dataPesticideApplication;
        }

        return response()->json(['data'=> $resultData]);
    }

    public function getLabourRight(Request $request)
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

        $labourRightBySections = SRPLabourRight::where('farmer_id', $request->farmer_id)
            ->where('cultivation_id', $request->cultivation_id)
            ->where('srp_id', $request->srp_id)
            ->where('staff_id', $staff->id)
            ->get()
            ->groupBy('section');

        $resultData = [];
        foreach ($labourRightBySections as $section => $labourRightBySection) {
            $dataLabourRight = [];
            $labourRightByCollectionCodes = $labourRightBySection->groupBy('collection_code');
            
            foreach ($labourRightByCollectionCodes as $labourRight) {
                array_push($dataLabourRight, $labourRight);
            }

            $resultData[$section] = $dataLabourRight;
        }

        return response()->json(['data'=> $resultData]);
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

    

    

    public function getIntegratedPestManagement(Request $request)
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


    

    public function getHarvest(Request $request)
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

        $harvestBySections = SRPHarvest::where('farmer_id', $request->farmer_id)
            ->where('cultivation_id', $request->cultivation_id)
            ->where('srp_id', $request->srp_id)
            ->where('staff_id', $staff->id)
            ->get()
            ->groupBy('section');

        $resultData = [];
        foreach ($harvestBySections as $section => $harvestBySection) {
            $dataHarvest = [];
            $harvestByCollectionCodes = $harvestBySection->groupBy('collection_code');
            
            foreach ($harvestByCollectionCodes as $harvest) {
                array_push($dataHarvest, $harvest);
            }

            $resultData[$section] = $dataHarvest;
        }

        return response()->json(['data'=> $resultData]);
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

        $fertilizerApplicationBySections = SRPFertilizerApplication::where('farmer_id', $request->farmer_id)
            ->where('cultivation_id', $request->cultivation_id)
            ->where('srp_id', $request->srp_id)
            ->where('staff_id', $staff->id)
            ->get()
            ->groupBy('section');

        $resultData = [];
        foreach ($fertilizerApplicationBySections as $section => $fertilizerApplicationBySection) {
            $datafertilizerApplication = [];
            $fertilizerApplicationByCollectionCodes = $fertilizerApplicationBySection->groupBy('collection_code');
            
            foreach ($fertilizerApplicationByCollectionCodes as $fertilizerApplication) {
                array_push($datafertilizerApplication, $fertilizerApplication);
            }

            $resultData[$section] = $datafertilizerApplication;
        }

        return response()->json(['data'=> $resultData]);
    }

}
