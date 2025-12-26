<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfilController extends Controller
{
    public function index()
    {
        $title = "Profil";
        return view('profil.index', compact('title'));
    }
}
