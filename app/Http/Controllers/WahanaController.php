<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Facility;

class WahanaController extends Controller
{
    /**
     * Tampilkan halaman wahana.
     */
    public function index()
    {
        $facilities = Facility::all();
        return view('wahana', compact('facilities'));
    }

    /**
     * Tampilkan detail wahana.
     */
    public function show($id)
    {
        $facility = Facility::findOrFail($id);
        return view('showwahana', compact('facility'));
    }
}