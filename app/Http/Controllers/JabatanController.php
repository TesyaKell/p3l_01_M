<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class JabatanController extends Controller
{
    public function setRole($role)
    {
        session(['selected_role' => $role]);

        // Redirect ke halaman login sesuai role
        return redirect()->route('login.' . $role);
    }
}
