<?php

namespace App\Http\Controllers;

use App\Models\Merchandise;

class MerchandiseController extends Controller
{
    public function index()
    {
        $merchandises = Merchandise::all();
        return view('merchandise', compact('merchandises'));
    }
}
