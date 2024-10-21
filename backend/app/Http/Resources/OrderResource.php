<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'number' => $this->number,
            'status' => $this->status,
            'date_created' => $this->date_created,
            'total' => $this->total,
            'customer_note' => $this->customer_note ?? 'N/A',
            'billing' => $this->billing,
            'shipping' => $this->shipping,
            'line_items' => LineItemsResource::collection($this->lineItems),
            'line_items_count' => $this->lineItems->count()
        ];
    }
}
