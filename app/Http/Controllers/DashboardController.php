<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Promo;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil promo untuk dashboard (exclude draft dan inactive)
        $promos = Promo::forDashboard()
                      ->withCount(['successfulOrders'])
                      ->limit(12)
                      ->get();

        return view('dashboard', compact('promos'));
    }
}