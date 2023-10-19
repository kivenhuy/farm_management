<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SeasonMaster;
use Illuminate\Http\Request;

class SeasonMasterController extends Controller
{
    public function index(Request $request)
    {
        $seasonMasterQuery = SeasonMaster::with(['season'])->orderByDesc('id');

        $seasonMasters = $seasonMasterQuery->paginate()->appends($request->except('page'));

        return view('admin.season_master.index', compact('seasonMasters'));
    }
}
