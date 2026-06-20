<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Order;
use App\Models\PurchaseOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class FinanceDashboardController extends Controller
{
    /**
     * Halaman utama Dashboard Finance (Laporan Laba Rugi).
     */
    public function index(Request $request): View
    {
        // ── Period Filter ──────────────────────────────────────
        $period = $request->query('period', 'this_month');
        $dateRange = $this->resolveDateRange($period, $request);

        $startDate = $dateRange['start'];
        $endDate   = $dateRange['end'];

        // ── KPI Widgets ────────────────────────────────────────
        $totalRevenue          = $this->getTotalRevenue($startDate, $endDate);
        $totalCOGS             = $this->getTotalCOGS($startDate, $endDate);
        $totalOperationalCost  = $this->getTotalOperationalCost($startDate, $endDate);
        $netProfit             = $totalRevenue - $totalCOGS - $totalOperationalCost;
        $profitMargin          = $totalRevenue > 0 ? ($netProfit / $totalRevenue) * 100 : 0;

        // ── Sales Trend Chart ──────────────────────────────────
        $chartMode  = $request->query('chart_mode', 'daily'); // daily, monthly, hourly
        $chartData  = $this->getChartData($chartMode, $startDate, $endDate);

        // ── Top Products ───────────────────────────────────────
        $topProducts = $this->getTopProducts($startDate, $endDate);

        // ── Expense Breakdown ──────────────────────────────────
        $expenseBreakdown = $this->getExpenseBreakdown($startDate, $endDate);

        // ── Recent Orders ──────────────────────────────────────
        $recentOrders = Order::with('cashier')
            ->whereBetween('created_at', [$startDate, $endDate->copy()->endOfDay()])
            ->latest()
            ->take(5)
            ->get();

        // ── Order Count & Average ──────────────────────────────
        $orderCount   = Order::whereBetween('created_at', [$startDate, $endDate->copy()->endOfDay()])->count();
        $averageOrder = $orderCount > 0 ? $totalRevenue / $orderCount : 0;

        return view('finance.dashboard', compact(
            'totalRevenue',
            'totalCOGS',
            'totalOperationalCost',
            'netProfit',
            'profitMargin',
            'chartData',
            'chartMode',
            'topProducts',
            'expenseBreakdown',
            'recentOrders',
            'orderCount',
            'averageOrder',
            'period',
            'startDate',
            'endDate',
        ));
    }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
    // PRIVATE HELPERS
    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

    /**
     * Resolve date range from period filter.
     */
    private function resolveDateRange(string $period, Request $request): array
    {
        return match ($period) {
            'today'      => ['start' => Carbon::today(),         'end' => Carbon::today()],
            'yesterday'  => ['start' => Carbon::yesterday(),     'end' => Carbon::yesterday()],
            'this_week'  => ['start' => Carbon::now()->startOfWeek(), 'end' => Carbon::now()->endOfWeek()],
            'this_month' => ['start' => Carbon::now()->startOfMonth(), 'end' => Carbon::now()->endOfMonth()],
            'last_month' => ['start' => Carbon::now()->subMonth()->startOfMonth(), 'end' => Carbon::now()->subMonth()->endOfMonth()],
            'this_year'  => ['start' => Carbon::now()->startOfYear(), 'end' => Carbon::now()->endOfYear()],
            'custom'     => [
                'start' => Carbon::parse($request->query('start_date', Carbon::now()->startOfMonth()->toDateString())),
                'end'   => Carbon::parse($request->query('end_date', Carbon::now()->toDateString())),
            ],
            default      => ['start' => Carbon::now()->startOfMonth(), 'end' => Carbon::now()->endOfMonth()],
        };
    }

    /**
     * Total Pendapatan: SUM total_pay from orders.
     */
    private function getTotalRevenue(Carbon $start, Carbon $end): float
    {
        return (float) Order::whereBetween('created_at', [$start, $end->copy()->endOfDay()])
            ->sum('total_pay');
    }

    /**
     * Total HPP: SUM total_amount from completed purchase orders.
     */
    private function getTotalCOGS(Carbon $start, Carbon $end): float
    {
        return (float) PurchaseOrder::where('status', 'completed')
            ->whereBetween('order_date', [$start->toDateString(), $end->toDateString()])
            ->sum('total_amount');
    }

    /**
     * Total Pengeluaran Operasional: SUM amount from expenses.
     */
    private function getTotalOperationalCost(Carbon $start, Carbon $end): float
    {
        return (float) Expense::whereBetween('date', [$start->toDateString(), $end->toDateString()])
            ->sum('amount');
    }

    /**
     * Chart data: tren penjualan berdasarkan mode (daily/monthly/hourly).
     */
    private function getChartData(string $mode, Carbon $start, Carbon $end): array
    {
        $query = Order::whereBetween('created_at', [$start, $end->copy()->endOfDay()]);

        return match ($mode) {
            'daily' => $query
                ->select(
                    DB::raw('DATE(created_at) as label'),
                    DB::raw('SUM(total_pay) as total'),
                    DB::raw('COUNT(*) as count')
                )
                ->groupBy('label')
                ->orderBy('label')
                ->get()
                ->toArray(),

            'monthly' => $query
                ->select(
                    DB::raw("DATE_FORMAT(created_at, '%Y-%m') as label"),
                    DB::raw('SUM(total_pay) as total'),
                    DB::raw('COUNT(*) as count')
                )
                ->groupBy('label')
                ->orderBy('label')
                ->get()
                ->toArray(),

            'hourly' => $query
                ->select(
                    DB::raw('HOUR(created_at) as label'),
                    DB::raw('SUM(total_pay) as total'),
                    DB::raw('COUNT(*) as count')
                )
                ->groupBy('label')
                ->orderBy('label')
                ->get()
                ->map(function ($item) {
                    $hour = (int) $item['label'];
                    $item['label'] = sprintf('%02d:00', $hour);
                    return $item;
                })
                ->toArray(),

            default => [],
        };
    }

    /**
     * Top 5 best-selling products.
     */
    private function getTopProducts(Carbon $start, Carbon $end): array
    {
        return DB::table('order_product')
            ->join('orders', 'orders.id', '=', 'order_product.order_id')
            ->join('products', 'products.id', '=', 'order_product.product_id')
            ->whereBetween('orders.created_at', [$start, $end->copy()->endOfDay()])
            ->select(
                'products.name',
                DB::raw('SUM(order_product.quantity) as total_qty'),
                DB::raw('SUM(order_product.quantity * order_product.price) as total_revenue')
            )
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get()
            ->toArray();
    }

    /**
     * Expense breakdown by category.
     */
    private function getExpenseBreakdown(Carbon $start, Carbon $end): array
    {
        return Expense::whereBetween('date', [$start->toDateString(), $end->toDateString()])
            ->select('category', DB::raw('SUM(amount) as total'))
            ->groupBy('category')
            ->orderByDesc('total')
            ->get()
            ->toArray();
    }
}
