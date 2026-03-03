<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PegawaiController extends Controller
{
    function index() {
        return view('pegawai.index');
    }
}
