<?php

namespace App\Console\Commands;

use App\Models\LineItems;
use App\Models\Order;
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

    public function __construct(WoocommerceService $woocommerceService)
    {
        parent::__construct();
        $this->woocommerceService = $woocommerceService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $fromDate = Carbon::now()->subDays(30)->toIso8601String();
        $orders = $this->woocommerceService->fetchOrders($fromDate);
        if ($orders) {
            foreach ($orders as $orderData) {
                try {
                    $this->syncOrder($orderData);
                    $this->syncLineItems($orderData['line_items'], $orderData['id']);
                } catch (\Exception $e) {
                    Log::error('Order Sync Failed: ' . $e->getMessage());
                    $this->error('Order Sync Failed for order ID: ' . $orderData['id']);
                }
            }
            $this->info('Orders synced successfully!');
        } else {
            $this->error('Failed to fetch orders from Woocommerce.');
        }

        // Call the method to delete old orders
        $this->deleteOldOrders();
    }

    /**
     * Sync individual order
     * @param array $orderData
     * @return void
     */
    private function syncOrder(array $orderData): void
    {
        Order::updateOrCreate(
            ['id' => $orderData['id']],
            [
                'number' => $orderData['number'],
                'order_key' => $orderData['order_key'],
                'status' => $orderData['status'],
                'date_created' => $orderData['date_created'],
                'total' => $orderData['total'],
                'customer_id' => $orderData['customer_id'],
                'customer_note' => $orderData['customer_note'],
                'billing' => json_encode($orderData['billing']),
                'shipping' => json_encode($orderData['shipping']),
            ]
        );
    }

    /**
     * Sync line items of the order
     * @param array $lineItems
     * @param int $orderId
     * @return void
     */
    private function syncLineItems(array $lineItems, int $orderId): void
    {
        foreach ($lineItems as $lineItemData) {
            LineItems::updateOrCreate(
                ['id' => $lineItemData['id']],
                [
                    'order_id' => $orderId,
                    'name' => $lineItemData['name'],
                    'product_id' => $lineItemData['product_id'],
                    'variation_id' => $lineItemData['variation_id'],
                    'quantity' => $lineItemData['quantity'],
                    'tax_class' => $lineItemData['tax_class'],
                    'subtotal' => $lineItemData['subtotal'],
                    'subtotal_tax' => $lineItemData['subtotal_tax'],
                    'total' => $lineItemData['total'],
                    'total_tax' => $lineItemData['total_tax'],
                    'taxes' => json_encode($lineItemData['taxes']),
                    'meta_data' => json_encode($lineItemData['meta_data']),
                    'sku' => $lineItemData['sku'],
                    'price' => $lineItemData['price'],
                ]
            );
        }
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
