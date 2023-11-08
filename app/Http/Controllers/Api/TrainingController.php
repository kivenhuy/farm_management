<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Training;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrainingController extends Controller
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
        $training_data = new Training();
        $staff = Auth::user()->staff;
        foreach($request->data_finance as $key => $value);
        {
            $data_training = [
                'farmer_id'=>$request->farmer_id,
                'cultivation_id'=>$request->cultivation_id,
                'staff_id'=>$staff->id,
                'srps_id'=>$request->srps_id,
                'question'=>$key,
                'answer'=>$value,
                'score'=>5
            ];
            $training_data->create($data_training);
        }
       
    }

    /**
     * Display the specified resource.
     */
    public function show(Training $training)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Training $training)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Training $training)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Training $training)
    {
        //
    }
}
