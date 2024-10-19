<?php

namespace App\Console\Commands;

use App\Jobs\SyncOrderJob;
use App\Models\LineItems;
use App\Models\Order;
use App\Services\OrderService;
use App\Services\WoocommerceService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SyncOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync orders from Woocommerce to the local database';

    protected $woocommerceService;
    protected $orderService;

    public function __construct(WoocommerceService $woocommerceService, OrderService $orderService)
    {
        parent::__construct();
        $this->woocommerceService = $woocommerceService;
        $this->orderService = $orderService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $fromDate = Carbon::now()->subDays(400)->toIso8601String();
        $orders = $this->woocommerceService->fetchOrders($fromDate);
        if ($orders) {
            SyncOrderJob::dispatch($orders, $this->orderService);
            $this->info('Orders sync initiated successfully!');
            $this->info('Orders synced successfully!');
        } else {
            $this->error('Failed to fetch orders from Woocommerce.');
        }

        // Call the method to delete old orders
        $this->deleteOldOrders();
    }

    /**
     * Delete orders not modified in the last 3 months.
     */
    private function deleteOldOrders(): void
    {
        $cutoffDate = Carbon::now()->subMonths(3);

        $deletedCount = Order::where('updated_at', '<', $cutoffDate)->delete();

        $this->info("Deleted $deletedCount old orders that haven't been modified in the last 3 months.");
    }
}
