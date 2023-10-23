<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SeasonMasterRequest;
use App\Models\Models\Season;
use App\Models\SeasonMaster;
use Illuminate\Http\Request;

class SeasonMasterController extends Controller
{
    public function index(Request $request)
    {
        $seasonCode = $request->input('season_code');
        $fromPefiod = $request->input('from_period');
        $toPefiod = $request->input('to_period');
        $status = $request->input('status');

        $seasonMasterQuery = SeasonMaster::with(['season'])->orderByDesc('id');

        if ($seasonCode) {
            $seasonMasterQuery->where('season_code', $seasonCode);
        }

        if ($fromPefiod) {  
            $seasonMasterQuery->where('from_period', '>=', $fromPefiod);
        }

        if ($toPefiod) {  
            $seasonMasterQuery->where('to_period', '<=', $toPefiod);
        }

        if ($status) {
            $seasonMasterQuery->where('status', $status);
        }

        $seasonMasters = $seasonMasterQuery->paginate()->appends($request->except('page'));
        return view('admin.season_master.index', compact('seasonMasters', 'seasonCode', 'fromPefiod', 'toPefiod', 'status'));
    }

    public function create()
    {
        $seasonMaster = new SeasonMaster();

        return $this->edit($seasonMaster);
    }

    public function edit(SeasonMaster $seasonMaster)
    {
        return view('admin.season_master.form', compact('seasonMaster'));
    }

    public function show(SeasonMaster $seasonMaster)
    {
        return redirect()->route('season-masters.edit', ['season_master' => $seasonMaster]);
    }


    public function store(SeasonMasterRequest $seasonMasterRequest)
    {
        return $this->createOrUpdate($seasonMasterRequest, new SeasonMaster());
    }


    public function update(SeasonMasterRequest $seasonMasterRequest, SeasonMaster $seasonMaster)
    {
        return $this->createOrUpdate($seasonMasterRequest, $seasonMaster);
    }

    private function createOrUpdate(SeasonMasterRequest $seasonMasterRequest, SeasonMaster $seasonMaster)
    {
        $isNewSeasonMaster = empty($seasonMaster->id);
        $seasonMaster->season_code = $seasonMasterRequest->season_code;
        $seasonMaster->from_period = $seasonMasterRequest->from_period;
        $seasonMaster->to_period = $seasonMasterRequest->to_period;
        $seasonMaster->status = $seasonMasterRequest->status;

        $seasonMaster->save();

        return redirect()->route('season-masters.edit', ['season_master' => $seasonMaster])->with([
            'success' => $isNewSeasonMaster ? 'Season Master has been created!' : 'Season Master has been updated!',
        ]);
    }

    public function destroy(SeasonMaster $seasonMaster)
    {
        $seasonMaster->delete();

        return redirect()->route('season-masters.index')->with('success', 'The season master has been deleted!');
    }

}
