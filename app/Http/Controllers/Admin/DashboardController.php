<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        // Data statistik
        $stats = [
            'total_tickets_sold' => 1234,
            'total_revenue' => 45200000,
            'active_promos' => 8,
            'total_customers' => 567,
            'tickets_sold_change' => '+12.5',
            'revenue_change' => '+8.3',
            'promos_change' => '+2',
            'customers_change' => '+15.7',
        ];

        // Pesanan terbaru
        $recentOrders = [
            [
                'id' => 'TKT-001',
                'customer_name' => 'John Doe',
                'package' => 'Family Weekend Package',
                'amount' => 375000,
                'status' => 'completed',
                'date' => '2024-12-10 14:30',
            ],
            [
                'id' => 'TKT-002',
                'customer_name' => 'Jane Smith',
                'package' => 'Student Discount',
                'amount' => 50000,
                'status' => 'pending',
                'date' => '2024-12-10 13:15',
            ],
            [
                'id' => 'TKT-003',
                'customer_name' => 'Bob Johnson',
                'package' => 'VIP All-Inclusive',
                'amount' => 950000,
                'status' => 'completed',
                'date' => '2024-12-10 11:45',
            ],
            [
                'id' => 'TKT-004',
                'customer_name' => 'Alice Brown',
                'package' => 'Family Weekend Package',
                'amount' => 375000,
                'status' => 'processing',
                'date' => '2024-12-10 10:20',
            ],
            [
                'id' => 'TKT-005',
                'customer_name' => 'Charlie Wilson',
                'package' => 'Student Discount',
                'amount' => 50000,
                'status' => 'completed',
                'date' => '2024-12-10 09:30',
            ],
        ];

        // Paket populer
        $popularPackages = [
            [
                'name' => 'Family Weekend Package',
                'sold' => 125,
                'revenue' => 46875000,
                'growth' => '+15%',
            ],
            [
                'name' => 'Student Discount',
                'sold' => 89,
                'revenue' => 4450000,
                'growth' => '+8%',
            ],
            [
                'name' => 'VIP All-Inclusive',
                'sold' => 23,
                'revenue' => 21850000,
                'growth' => '+25%',
            ],
            [
                'name' => 'Group Package',
                'sold' => 45,
                'revenue' => 11250000,
                'growth' => '+5%',
            ],
        ];

        // Revenue bulanan
        $monthlyRevenue = [
            ['month' => 'Jan', 'revenue' => 35000000],
            ['month' => 'Feb', 'revenue' => 42000000],
            ['month' => 'Mar', 'revenue' => 38000000],
            ['month' => 'Apr', 'revenue' => 47000000],
            ['month' => 'May', 'revenue' => 52000000],
            ['month' => 'Jun', 'revenue' => 48000000],
            ['month' => 'Jul', 'revenue' => 55000000],
            ['month' => 'Aug', 'revenue' => 58000000],
            ['month' => 'Sep', 'revenue' => 51000000],
            ['month' => 'Oct', 'revenue' => 46000000],
            ['month' => 'Nov', 'revenue' => 49000000],
            ['month' => 'Dec', 'revenue' => 45200000],
        ];

        return view('admin.dashboard.index', compact(
            'stats', 
            'recentOrders', 
            'popularPackages', 
            'monthlyRevenue'
        ));
    }
}