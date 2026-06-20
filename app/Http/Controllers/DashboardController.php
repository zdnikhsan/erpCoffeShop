<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Halaman Utama Dashboard (Owner & Manager).
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // Kasir dialihkan otomatis langsung ke POS
        if ($user->hasRole('cashier')) {
            return redirect()->route('pos.index');
        }

        // Hanya Owner & Manager yang boleh mengakses dashboard
        if (!$user->hasAnyRole(['owner', 'manager'])) {
            abort(403, 'Akses ditolak. Anda tidak memiliki wewenang.');
        }

        $timezone = 'Asia/Jakarta';

        // Tentukan batas waktu hari ini & kemarin dalam UTC berdasarkan Waktu Jakarta
        $todayStart = Carbon::today($timezone)->setTimezone('UTC');
        $todayEnd   = Carbon::today($timezone)->endOfDay()->setTimezone('UTC');
        
        $yesterdayStart = Carbon::yesterday($timezone)->setTimezone('UTC');
        $yesterdayEnd   = Carbon::yesterday($timezone)->endOfDay()->setTimezone('UTC');

        // ────────────────────────────────────────────────────────
        // 1. WIDGET RINGKASAN HARIAN (Real-time KPI)
        // ────────────────────────────────────────────────────────
        
        // Omset Hari Ini vs Kemarin
        $omsetToday     = (float) Order::whereBetween('created_at', [$todayStart, $todayEnd])->sum('total_pay');
        $omsetYesterday = (float) Order::whereBetween('created_at', [$yesterdayStart, $yesterdayEnd])->sum('total_pay');
        $omsetChange    = $omsetYesterday > 0 ? (($omsetToday - $omsetYesterday) / $omsetYesterday) * 100 : 0;

        // Jumlah Transaksi Hari Ini vs Kemarin
        $transaksiToday     = Order::whereBetween('created_at', [$todayStart, $todayEnd])->count();
        $transaksiYesterday = Order::whereBetween('created_at', [$yesterdayStart, $yesterdayEnd])->count();
        $transaksiChange    = $transaksiYesterday > 0 ? (($transaksiToday - $transaksiYesterday) / $transaksiYesterday) * 100 : 0;

        // Rata-rata Keranjang (Basket Size) Hari Ini vs Kemarin
        $avgBasketToday     = $transaksiToday > 0 ? $omsetToday / $transaksiToday : 0;
        $avgBasketYesterday = $transaksiYesterday > 0 ? $omsetYesterday / $transaksiYesterday : 0;
        $avgBasketChange    = $avgBasketYesterday > 0 ? (($avgBasketToday - $avgBasketYesterday) / $avgBasketYesterday) * 100 : 0;

        // Jumlah Bahan Baku dengan Stok Kritis
        $stokKritisCount = Ingredient::whereColumn('stock', '<=', 'safety_stock')->count();

        // ────────────────────────────────────────────────────────
        // 2. ANALYTICS GRAFIK TREN PENJUALAN
        // ────────────────────────────────────────────────────────
        
        // Tarik order hari ini untuk diolah berdasarkan jam lokal (GMT+7)
        $ordersToday = Order::whereBetween('created_at', [$todayStart, $todayEnd])->get();
        $hourlySales = [];
        foreach ($ordersToday as $order) {
            $localHour = (int) $order->created_at->setTimezone($timezone)->format('G');
            if (!isset($hourlySales[$localHour])) {
                $hourlySales[$localHour] = 0;
            }
            $hourlySales[$localHour] += (float) $order->total_pay;
        }

        $hourlyChartData = [];
        for ($h = 8; $h <= 22; $h++) {
            $hourlyChartData[sprintf('%02d:00', $h)] = (float) ($hourlySales[$h] ?? 0);
        }

        // Tren Mingguan (7 Hari Terakhir)
        $sevenDaysAgoStart = Carbon::today($timezone)->subDays(6)->setTimezone('UTC');
        $ordersWeekly      = Order::whereBetween('created_at', [$sevenDaysAgoStart, $todayEnd])->get();

        $weeklySales = [];
        foreach ($ordersWeekly as $order) {
            $localDate = $order->created_at->setTimezone($timezone)->toDateString();
            if (!isset($weeklySales[$localDate])) {
                $weeklySales[$localDate] = 0;
            }
            $weeklySales[$localDate] += (float) $order->total_pay;
        }

        $weeklyChartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date    = Carbon::today($timezone)->subDays($i);
            $dateStr = $date->toDateString();
            $label   = $date->format('d/m');
            $weeklyChartData[$label] = (float) ($weeklySales[$dateStr] ?? 0);
        }

        // ────────────────────────────────────────────────────────
        // 3. RINGKASAN DATA (Top Lists)
        // ────────────────────────────────────────────────────────
        
        // 5 Produk Terlaris
        $topProducts = DB::table('order_product')
            ->join('products', 'products.id', '=', 'order_product.product_id')
            ->select('products.name', DB::raw('SUM(order_product.quantity) as total_qty'))
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        // Live Order Monitor (5 transaksi terakhir hari ini)
        $liveOrders = Order::with('cashier')
            ->whereBetween('created_at', [$todayStart, $todayEnd])
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'omsetToday',
            'transaksiToday',
            'avgBasketToday',
            'omsetYesterday',
            'transaksiYesterday',
            'avgBasketYesterday',
            'omsetChange',
            'transaksiChange',
            'avgBasketChange',
            'stokKritisCount',
            'hourlyChartData',
            'weeklyChartData',
            'topProducts',
            'liveOrders'
        ));
    }
}
