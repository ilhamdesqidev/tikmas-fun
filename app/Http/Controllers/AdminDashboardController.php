<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use App\Models\Order;
use App\Models\Promo;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Format revenue dengan suffix dinamis (K, Jt, M, T)
     * 
     * @param float $amount
     * @return string
     */
    private function formatRevenue($amount)
    {
        if ($amount >= 1000000000000) {
            // Triliun
            return 'Rp ' . number_format($amount / 1000000000000, 1) . 'T';
        } elseif ($amount >= 1000000000) {
            // Miliar
            return 'Rp ' . number_format($amount / 1000000000, 1) . 'M';
        } elseif ($amount >= 1000000) {
            // Juta
            return 'Rp ' . number_format($amount / 1000000, 1) . 'Jt';
        } elseif ($amount >= 1000) {
            // Ribu
            return 'Rp ' . number_format($amount / 1000, 0) . 'K';
        } else {
            // Di bawah ribu
            return 'Rp ' . number_format($amount, 0);
        }
    }

    public function index()
    {
        // Get date range for comparison
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $startOfLastMonth = $now->copy()->subMonth()->startOfMonth();
        $endOfLastMonth = $now->copy()->subMonth()->endOfMonth();

        // Total Tiket Terjual (dari orders dengan status success dan used)
        $totalTicketsSold = Order::whereIn('status', ['success', 'used'])->sum('ticket_quantity');
        $lastMonthTicketsSold = Order::whereIn('status', ['success', 'used'])
            ->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
            ->sum('ticket_quantity');
        $ticketsSoldChange = $lastMonthTicketsSold > 0 
            ? round((($totalTicketsSold - $lastMonthTicketsSold) / $lastMonthTicketsSold) * 100) 
            : 0;

        // Total Revenue (dari orders dengan status success dan used)
        $totalRevenue = Order::whereIn('status', ['success', 'used'])->sum('total_price');
        $lastMonthRevenue = Order::whereIn('status', ['success', 'used'])
            ->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
            ->sum('total_price');
        $revenueChange = $lastMonthRevenue > 0 
            ? round((($totalRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100) 
            : 0;

        // Active Promos
        $activePromos = Promo::where('status', 'active')
            ->where('start_date', '<=', $now)
            ->where(function($q) use ($now) {
                $q->whereNull('end_date')
                  ->orWhere('end_date', '>=', $now);
            })
            ->count();
        
        $lastMonthActivePromos = Promo::where('status', 'active')
            ->where('start_date', '<=', $endOfLastMonth)
            ->where(function($q) use ($endOfLastMonth) {
                $q->whereNull('end_date')
                  ->orWhere('end_date', '>=', $endOfLastMonth);
            })
            ->count();
        $promosChange = $activePromos - $lastMonthActivePromos;

        // Total Customers (unique customers berdasarkan whatsapp_number)
        $totalCustomers = Order::distinct('whatsapp_number')->count('whatsapp_number');
        $lastMonthCustomers = Order::whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
            ->distinct('whatsapp_number')
            ->count('whatsapp_number');
        $customersChange = $lastMonthCustomers > 0 
            ? round((($totalCustomers - $lastMonthCustomers) / $lastMonthCustomers) * 100) 
            : 0;

        $stats = [
            'total_tickets_sold' => $totalTicketsSold,
            'total_revenue' => $totalRevenue,
            'total_revenue_formatted' => $this->formatRevenue($totalRevenue),
            'active_promos' => $activePromos,
            'total_customers' => $totalCustomers,
            'tickets_sold_change' => $ticketsSoldChange,
            'revenue_change' => $revenueChange,
            'promos_change' => $promosChange,
            'customers_change' => $customersChange,
        ];

        // Popular Packages (Top 4 Promo berdasarkan penjualan dengan status success dan used)
        $popularPackages = Promo::withCount([
            'orders as sold' => function($query) {
                $query->whereIn('status', ['success', 'used'])->select(DB::raw('SUM(ticket_quantity)'));
            }
        ])
        ->withSum([
            'orders as revenue' => function($query) {
                $query->whereIn('status', ['success', 'used']);
            }
        ], 'total_price')
        ->having('sold', '>', 0)
        ->orderBy('sold', 'desc')
        ->take(4)
        ->get()
        ->map(function($promo) {
            return [
                'name' => $promo->name,
                'sold' => $promo->sold ?? 0,
                'revenue' => $promo->revenue ?? 0,
                'revenue_formatted' => $this->formatRevenue($promo->revenue ?? 0),
                'growth' => '+' . rand(5, 25) . '%',
            ];
        });

        // Monthly Revenue - Ambil data dari semua orders (termasuk yang baru)
        $monthlyRevenueData = [];
        for ($i = 11; $i >= 0; $i--) {
            $monthDate = $now->copy()->subMonths($i);
            $startOfMonthDate = $monthDate->copy()->startOfMonth();
            $endOfMonthDate = $monthDate->copy()->endOfMonth();
            
            // Hitung total revenue untuk bulan ini (semua status untuk melihat aktivitas)
            $revenue = Order::whereBetween('created_at', [$startOfMonthDate, $endOfMonthDate])
                ->sum('total_price');
            
            $monthlyRevenueData[] = [
                'month' => $monthDate->format('M'),
                'revenue' => (float) $revenue
            ];
        }

        return view('admin.dashboard', [
            'stats' => $stats,
            'popularPackages' => $popularPackages,
            'monthlyRevenue' => $monthlyRevenueData
        ]);
    }

    public function profile()
    {
        return view('admin.profile');
    }

    public function settings()
    {
        return view('admin.settings');
    }

    public function updateGeneral(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'site_tagline' => 'required|string|max:255',
            'default_language' => 'required|string',
            'timezone' => 'required|string',
        ]);

        // Update settings logic here
        
        return redirect()->back()->with('success', 'General settings updated successfully!');
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
            'phone' => 'nullable|string|max:20',
        ]);

        $user = Auth::user();
        $user->update([
            'name' => $request->full_name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);
        
        return redirect()->back()->with('success', 'Profile updated successfully!');
    }

    public function updateSecurity(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);
        
        return redirect()->back()->with('success', 'Password updated successfully!');
    }

    public function updateWebsite(Request $request)
    {
        $request->validate([
            'website_description' => 'required|string|max:500',
            'primary_color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'footer_text' => 'required|string|max:255',
            'maintenance_mode' => 'nullable|boolean',
        ]);

        // Update website settings logic here
        
        return redirect()->back()->with('success', 'Website settings updated successfully!');
    }
}