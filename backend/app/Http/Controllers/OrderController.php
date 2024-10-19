<?php

namespace App\Http\Controllers;

use App\Console\Commands\SyncOrders;
use App\Models\Order;
use App\Services\WoocommerceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    protected $woocommerceService;

    public function __construct(WoocommerceService $woocommerceService)
    {
        $this->woocommerceService = $woocommerceService;
    }

    /**
     * Display a listing of the orders with support for search, filter, sort, and pagination.
     */
    public function index(Request $request)
    {
        $query = Order::query();

        // Apply filters, search, and sorting if provided
        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }
        if ($request->has('customer_id')) {
            $query->where('customer_id', $request->input('customer_id'));
        }
        if ($request->has('sort')) {
            $query->orderBy($request->input('sort'), $request->input('direction', 'asc'));
        }

        // Paginate the result
        $orders = $query->paginate(10);

        return response()->json($orders);
    }

    /**
     * Manually trigger sync for new and updated orders.
     */
    public function syncOrders()
    {
        try {
            $fromDate = now()->subDays(30)->toIso8601String();
            $orders = $this->woocommerceService->fetchOrders($fromDate);

            foreach ($orders as $orderData) {
                // Reuse the sync logic from SyncOrders command
                (new SyncOrders($this->woocommerceService))->syncOrder($orderData);
            }

            return response()->json(['message' => 'Orders synced successfully!']);
        } catch (\Exception $e) {
            Log::error('Order Sync Failed: ' . $e->getMessage());
            return response()->json(['error' => 'Order Sync Failed'], 500);
        }
    }
}
