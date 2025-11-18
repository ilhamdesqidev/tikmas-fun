<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use App\Models\VoucherClaim;
use App\Models\Order;
use App\Models\Promo;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{
    /**
     * Format revenue dengan suffix dinamis
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

    public function index(Request $request)
    {
        try {
            $now = Carbon::now();
            $startOfMonth = $now->copy()->startOfMonth();
            $startOfLastMonth = $now->copy()->subMonth()->startOfMonth();
            $endOfLastMonth = $now->copy()->subMonth()->endOfMonth();

            // ===== VOUCHER STATISTICS =====
            $totalVouchers = Voucher::count();
            $activeVouchers = Voucher::where('status', 'aktif')->count();
            $totalClaims = VoucherClaim::count();
            $claimsThisMonth = VoucherClaim::whereMonth('created_at', $now->month)
                                          ->whereYear('created_at', $now->year)
                                          ->count();
            
            // Top 5 most claimed vouchers
            $topVouchers = Voucher::withCount('claims')
                                 ->orderBy('claims_count', 'desc')
                                 ->take(5)
                                 ->get();
            
            // Recent claims
            $recentClaims = VoucherClaim::with('voucher')
                                       ->latest()
                                       ->take(10)
                                       ->get();

            // ===== PROMO STATISTICS =====
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
                ];
            });

            // Get current period for promo chart
            $currentPeriod = $request->get('period', 'monthly');
            
            return view('admin.reports.index', compact(
                'totalVouchers',
                'activeVouchers',
                'totalClaims',
                'claimsThisMonth',
                'topVouchers',
                'recentClaims',
                'stats',
                'popularPackages',
                'currentPeriod'
            ));
            
        } catch (\Exception $e) {
            Log::error('Error loading reports: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            // Return with safe defaults
            return view('admin.reports.index', [
                'totalVouchers' => 0,
                'activeVouchers' => 0,
                'totalClaims' => 0,
                'claimsThisMonth' => 0,
                'topVouchers' => collect([]),
                'recentClaims' => collect([]),
                'stats' => [
                    'total_tickets_sold' => 0,
                    'total_revenue' => 0,
                    'total_revenue_formatted' => 'Rp 0',
                    'active_promos' => 0,
                    'total_customers' => 0,
                    'tickets_sold_change' => 0,
                    'revenue_change' => 0,
                    'promos_change' => 0,
                    'customers_change' => 0,
                ],
                'popularPackages' => collect([]),
                'currentPeriod' => 'monthly'
            ])->with('error', 'Terjadi kesalahan saat memuat data: ' . $e->getMessage());
        }
    }
    
    public function getChartData(Request $request)
    {
        try {
            $period = $request->get('period', 'daily');
            
            $data = match($period) {
                'daily' => $this->getLast30DaysData(),
                'weekly' => $this->getWeeklyData(),
                'monthly' => $this->getMonthlyData(),
                default => $this->getLast30DaysData()
            };
            
            return response()->json($data);
            
        } catch (\Exception $e) {
            Log::error('Error loading chart data: ' . $e->getMessage());
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    private function getLast30DaysData()
    {
        $startDate = Carbon::now()->subDays(29)->startOfDay();
        $endDate = Carbon::now()->endOfDay();
        
        $claims = VoucherClaim::whereBetween('created_at', [$startDate, $endDate])
                             ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
                             ->groupBy('date')
                             ->orderBy('date')
                             ->get()
                             ->pluck('count', 'date');
        
        $labels = [];
        $data = [];
        
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $dateStr = $date->format('Y-m-d');
            $labels[] = $date->format('d M');
            $data[] = $claims->get($dateStr, 0);
        }
        
        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Jumlah Klaim',
                    'data' => $data,
                    'borderColor' => 'rgb(59, 130, 246)',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'tension' => 0.4,
                    'fill' => true
                ]
            ],
            'total' => array_sum($data)
        ];
    }
    
    private function getWeeklyData()
    {
        $now = Carbon::now();
        $startDate = $now->copy()->subWeeks(11)->startOfWeek();
        $endDate = $now->copy()->endOfWeek();
        
        $claims = VoucherClaim::whereBetween('created_at', [$startDate, $endDate])
                             ->select(
                                 DB::raw('YEAR(created_at) as year'),
                                 DB::raw('WEEK(created_at, 1) as week'),
                                 DB::raw('COUNT(*) as count')
                             )
                             ->groupBy('year', 'week')
                             ->orderBy('year')
                             ->orderBy('week')
                             ->get()
                             ->mapWithKeys(function($item) {
                                 return ["{$item->year}-{$item->week}" => $item->count];
                             });
        
        $labels = [];
        $data = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $weekStart = $now->copy()->subWeeks($i)->startOfWeek();
            $weekNumber = $weekStart->week;
            $year = $weekStart->year;
            $key = "{$year}-{$weekNumber}";
            
            $labels[] = 'W' . $weekNumber;
            $data[] = $claims->get($key, 0);
        }
        
        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Jumlah Klaim',
                    'data' => $data,
                    'borderColor' => 'rgb(139, 92, 246)',
                    'backgroundColor' => 'rgba(139, 92, 246, 0.1)',
                    'tension' => 0.4,
                    'fill' => true
                ]
            ],
            'total' => array_sum($data)
        ];
    }
    
    private function getMonthlyData()
    {
        $startDate = Carbon::now()->subMonths(11)->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();
        
        $claims = VoucherClaim::whereBetween('created_at', [$startDate, $endDate])
                             ->select(
                                 DB::raw('YEAR(created_at) as year'),
                                 DB::raw('MONTH(created_at) as month'),
                                 DB::raw('COUNT(*) as count')
                             )
                             ->groupBy('year', 'month')
                             ->orderBy('year')
                             ->orderBy('month')
                             ->get()
                             ->mapWithKeys(function($item) {
                                 return ["{$item->year}-{$item->month}" => $item->count];
                             });
        
        $labels = [];
        $data = [];
        
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addMonth()) {
            $key = $date->format('Y-n');
            $labels[] = $date->format('M Y');
            $data[] = $claims->get($key, 0);
        }
        
        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Jumlah Klaim',
                    'data' => $data,
                    'borderColor' => 'rgb(16, 185, 129)',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                    'tension' => 0.4,
                    'fill' => true
                ]
            ],
            'total' => array_sum($data)
        ];
    }
}