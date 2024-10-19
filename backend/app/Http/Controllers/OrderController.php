<?php

namespace App\Http\Controllers;

use App\Console\Commands\SyncOrders;
use App\Jobs\SyncOrderJob;
use App\Models\Order;
use App\Services\OrderService;
use App\Services\WoocommerceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    protected $woocommerceService;
    protected $orderService;

    public function __construct(WoocommerceService $woocommerceService, OrderService $orderService)
    {
        $this->woocommerceService = $woocommerceService;
        $this->orderService = $orderService;
    }

    /**
     * Display a listing of the orders with support for search, filter, sort, and pagination.
     */
    public function index(Request $request)
    {
        try {
            $orders = $this->orderService->getOrdersWithFilters($request);
            return response()->json($orders);
        } catch (\Exception $e) {
            Log::error('Order Fetch Failed: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch orders'], 500);
        }
    }

    /**
     * Manually trigger sync for new and updated orders.
     */
    public function syncOrders()
    {
        try {
            $fromDate = now()->subDays(30)->toIso8601String();
            $orders = $this->woocommerceService->fetchOrders($fromDate);

            SyncOrders::dispatch($orders);

            return response()->json(['message' => 'Orders sync initiated successfully!']);
        } catch (\Exception $e) {
            Log::error('Order Sync Failed: ' . $e->getMessage());
            return response()->json(['error' => 'Order Sync Failed'], 500);
        }
    }
}
