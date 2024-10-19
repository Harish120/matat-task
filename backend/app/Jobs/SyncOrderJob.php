<?php

namespace App\Jobs;

use App\Services\OrderService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SyncOrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $orders;
    protected $orderService;

    /**
     * Create a new job instance.
     *
     * @param array $orders
     */
    public function __construct(array $orders, OrderService $orderService)
    {
        $this->orders = $orders;
        $this->orderService = $orderService;
    }

    /**
     * Execute the job.
     *
     * @param OrderService $orderService
     */
    public function handle(): void
    {
        // Call sync logic from the OrderService
        foreach ($this->orders as $orderData) {
            try {
                $this->orderService->syncOrder($orderData);
                $this->orderService->syncLineItems($orderData['line_items'], $orderData['id']);
            } catch (\Exception $e) {
                Log::error('Order Sync Failed: ' . $e->getMessage());
            }
        }
    }
}
