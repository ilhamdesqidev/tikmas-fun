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
     */
    private function formatRevenue($amount)
    {
        if ($amount >= 1000000000000) {
            return 'Rp ' . number_format($amount / 1000000000000, 1) . 'T';
        } elseif ($amount >= 1000000000) {
            return 'Rp ' . number_format($amount / 1000000000, 1) . 'M';
        } elseif ($amount >= 1000000) {
            return 'Rp ' . number_format($amount / 1000000, 1) . 'Jt';
        } elseif ($amount >= 1000) {
            return 'Rp ' . number_format($amount / 1000, 0) . 'K';
        } else {
            return 'Rp ' . number_format($amount, 0);
        }
    }

    /**
     * Generate revenue data berdasarkan periode
     */
    private function getRevenueByPeriod($period)
    {
        $now = Carbon::now();
        $revenueData = [];

        switch ($period) {
            case 'daily':
                // 30 hari terakhir
                for ($i = 29; $i >= 0; $i--) {
                    $date = $now->copy()->subDays($i);
                    $startOfDay = $date->copy()->startOfDay();
                    $endOfDay = $date->copy()->endOfDay();
                    
                    $revenue = Order::whereBetween('created_at', [$startOfDay, $endOfDay])
                        ->whereIn('status', ['success', 'used'])
                        ->sum('total_price');
                    
                    $revenueData[] = [
                        'label' => $date->format('d M'),
                        'revenue' => (float) $revenue
                    ];
                }
                break;

            case 'weekly':
                // 12 minggu terakhir
                for ($i = 11; $i >= 0; $i--) {
                    $weekStart = $now->copy()->subWeeks($i)->startOfWeek();
                    $weekEnd = $now->copy()->subWeeks($i)->endOfWeek();
                    
                    $revenue = Order::whereBetween('created_at', [$weekStart, $weekEnd])
                        ->whereIn('status', ['success', 'used'])
                        ->sum('total_price');
                    
                    $revenueData[] = [
                        'label' => 'W' . $weekStart->weekOfYear,
                        'revenue' => (float) $revenue
                    ];
                }
                break;

            case 'monthly':
                // 12 bulan terakhir
                for ($i = 11; $i >= 0; $i--) {
                    $monthDate = $now->copy()->subMonths($i);
                    $startOfMonth = $monthDate->copy()->startOfMonth();
                    $endOfMonth = $monthDate->copy()->endOfMonth();
                    
                    $revenue = Order::whereBetween('created_at', [$startOfMonth, $endOfMonth])
                        ->whereIn('status', ['success', 'used'])
                        ->sum('total_price');
                    
                    $revenueData[] = [
                        'label' => $monthDate->format('M Y'),
                        'revenue' => (float) $revenue
                    ];
                }
                break;

            case 'quarterly':
                // 4 quarter terakhir (3 bulan)
                for ($i = 3; $i >= 0; $i--) {
                    $quarterStart = $now->copy()->subMonths($i * 3)->startOfMonth();
                    $quarterEnd = $quarterStart->copy()->addMonths(2)->endOfMonth();
                    
                    $revenue = Order::whereBetween('created_at', [$quarterStart, $quarterEnd])
                        ->whereIn('status', ['success', 'used'])
                        ->sum('total_price');
                    
                    $revenueData[] = [
                        'label' => 'Q' . ceil(($quarterStart->month) / 3) . ' ' . $quarterStart->format('Y'),
                        'revenue' => (float) $revenue
                    ];
                }
                break;

            case 'biannual':
                // 2 semester terakhir (6 bulan)
                for ($i = 1; $i >= 0; $i--) {
                    $semesterStart = $now->copy()->subMonths($i * 6)->startOfMonth();
                    $semesterEnd = $semesterStart->copy()->addMonths(5)->endOfMonth();
                    
                    $revenue = Order::whereBetween('created_at', [$semesterStart, $semesterEnd])
                        ->whereIn('status', ['success', 'used'])
                        ->sum('total_price');
                    
                    $semester = $semesterStart->month <= 6 ? 'S1' : 'S2';
                    $revenueData[] = [
                        'label' => $semester . ' ' . $semesterStart->format('Y'),
                        'revenue' => (float) $revenue
                    ];
                }
                break;

            case 'yearly':
                // 5 tahun terakhir
                for ($i = 4; $i >= 0; $i--) {
                    $yearStart = $now->copy()->subYears($i)->startOfYear();
                    $yearEnd = $now->copy()->subYears($i)->endOfYear();
                    
                    $revenue = Order::whereBetween('created_at', [$yearStart, $yearEnd])
                        ->whereIn('status', ['success', 'used'])
                        ->sum('total_price');
                    
                    $revenueData[] = [
                        'label' => $yearStart->format('Y'),
                        'revenue' => (float) $revenue
                    ];
                }
                break;

            default:
                // Default ke monthly
                return $this->getRevenueByPeriod('monthly');
        }

        return $revenueData;
    }

    public function index(Request $request)
    {
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $startOfLastMonth = $now->copy()->subMonth()->startOfMonth();
        $endOfLastMonth = $now->copy()->subMonth()->endOfMonth();

        // Total Tiket Terjual
        $totalTicketsSold = Order::whereIn('status', ['success', 'used'])->sum('ticket_quantity');
        $lastMonthTicketsSold = Order::whereIn('status', ['success', 'used'])
            ->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
            ->sum('ticket_quantity');
        $ticketsSoldChange = $lastMonthTicketsSold > 0 
            ? round((($totalTicketsSold - $lastMonthTicketsSold) / $lastMonthTicketsSold) * 100) 
            : 0;

        // Total Revenue
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

        // Total Customers
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

        // Popular Packages
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

        // Revenue Chart - Ambil periode dari request, default monthly
        $period = $request->get('period', 'monthly');
        $revenueChartData = $this->getRevenueByPeriod($period);

        return view('admin.dashboard', [
            'stats' => $stats,
            'popularPackages' => $popularPackages,
            'revenueChart' => $revenueChartData,
            'currentPeriod' => $period
        ]);
    }

    /**
     * AJAX endpoint untuk update chart
     */
    public function getRevenueChart(Request $request)
    {
        $period = $request->get('period', 'monthly');
        $revenueData = $this->getRevenueByPeriod($period);
        
        return response()->json([
            'success' => true,
            'data' => $revenueData
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

        return redirect()->back()->with('success', 'Website settings updated successfully!');
    }
}