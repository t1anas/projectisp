<?php

namespace App\Http\Controllers;

use App\Models\AgendaNoc;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    function index() {
        return view('admin');
    }

    function noc() {
        $agenda = AgendaNoc::with('pelanggan')
            ->latest()
            ->get();

        return view('noc', compact('agenda'));
    }

    function cs() {
        return view('cs');
    }

    function admin() {
        return view('admin');
    }
}