<?php

namespace App\Services;

use App\Models\LineItems;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderService
{
    /**
     * Get orders with filters, search, sort, and pagination.
     *
     * @param Request $request
     * @return mixed
     */
    public function getOrdersWithFilters(Request $request)
    {
        $query = Order::query();

        // Decode the stringifies filter object
        if ($request->has('filter')) {
            $filters = json_decode($request->input('filter'), true);
            if (is_array($filters)) {
                foreach ($filters as $column => $value) {
                    if (in_array($column, ['status', 'customer_id', 'total', 'date_created'])) {
                        $query->where($column, $value);
                    }
                }
            }
        }

        // Search functionality
        if ($request->has('query')) {
            $search = $request->input('query');
            $query->where(function ($q) use ($search) {
                $q->where('number', 'like', '%' . $search . '%')
                    ->orWhere('customer_note', 'like', '%' . $search . '%')
                    ->orWhere('total', 'like', '%' . $search . '%')
                    ->orWhere('status', 'like', '%' . $search . '%');
            });
        }

        // Sorting logic
        $sortBy = $request->input('sort', 'date_created');
        $direction = $request->input('direction', 'desc');
        $query->orderBy($sortBy, $direction);

        // Paginate the result, default 10 per page
        $perPage = $request->input('per_page', 10);
        if($perPage && $perPage > 0) {
            return $query->paginate($perPage);
        } else {
            return $query->get();
        }
    }

    /**
     * Sync an individual order.
     *
     * @param array $orderData
     * @return mixed
     */
    public function syncOrder(array $orderData): mixed
    {
        return Order::updateOrCreate(
            ['number' => $orderData['number']],
            [
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
     * Sync line items of the order.
     * @param array $lineItems
     * @param int $orderId
     * @return void
     */
    public function syncLineItems(array $lineItems, int $orderId): void
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
}
