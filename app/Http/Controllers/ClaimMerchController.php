<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClaimMerch;
use App\Models\Merchandise;
use Carbon\Carbon;

class ClaimMerchController extends Controller
{
    public function index()
    {
        $claimMerchList = ClaimMerch::with(['merchandise', 'pembeli'])->get();
        return view('claimMerc', compact('claimMerchList'));
    }
}
