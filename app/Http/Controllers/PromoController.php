<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Promo;

class PromoController extends Controller
{
    public function show($id)
    {
        $promo = Promo::findOrFail($id);
        
        // Cek apakah promo aktif
        if ($promo->status !== 'active') {
            abort(404, 'Promo tidak ditemukan atau tidak aktif');
        }
        
        return view('promo.show', compact('promo'));
    }
}