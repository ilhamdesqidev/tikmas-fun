<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VoucherClaim;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        try {
            // Summary statistics
            $totalVouchers = Voucher::count();
            $activeVouchers = Voucher::where('status', 'aktif')->count();
            $totalClaims = VoucherClaim::count();
            $claimsThisMonth = VoucherClaim::whereMonth('created_at', Carbon::now()->month)
                                          ->whereYear('created_at', Carbon::now()->year)
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
            
            return view('admin.reports.index', compact(
                'totalVouchers',
                'activeVouchers',
                'totalClaims',
                'claimsThisMonth',
                'topVouchers',
                'recentClaims'
            ));
        } catch (\Exception $e) {
            Log::error('Error loading reports: ' . $e->getMessage());
            
            // Return view with default values if error occurs
            return view('admin.reports.index', [
                'totalVouchers' => 0,
                'activeVouchers' => 0,
                'totalClaims' => 0,
                'claimsThisMonth' => 0,
                'topVouchers' => collect([]),
                'recentClaims' => collect([])
            ]);
        }
    }
    
    public function getChartData(Request $request)
    {
        $period = $request->get('period', '30days'); // 30days, monthly, yearly
        
        $data = match($period) {
            '30days' => $this->getLast30DaysData(),
            'monthly' => $this->getMonthlyData(),
            'yearly' => $this->getYearlyData(),
            default => $this->getLast30DaysData()
        };
        
        return response()->json($data);
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
    
    private function getYearlyData()
    {
        $startYear = Carbon::now()->subYears(4)->year;
        $endYear = Carbon::now()->year;
        
        $claims = VoucherClaim::whereYear('created_at', '>=', $startYear)
                             ->select(
                                 DB::raw('YEAR(created_at) as year'),
                                 DB::raw('COUNT(*) as count')
                             )
                             ->groupBy('year')
                             ->orderBy('year')
                             ->get()
                             ->pluck('count', 'year');
        
        $labels = [];
        $data = [];
        
        for ($year = $startYear; $year <= $endYear; $year++) {
            $labels[] = (string)$year;
            $data[] = $claims->get($year, 0);
        }
        
        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Jumlah Klaim',
                    'data' => $data,
                    'borderColor' => 'rgb(245, 158, 11)',
                    'backgroundColor' => 'rgba(245, 158, 11, 0.1)',
                    'tension' => 0.4,
                    'fill' => true
                ]
            ],
            'total' => array_sum($data)
        ];
    }
}