<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'invoiceId' => $this->invoice_id,
            'invoiceNumber' => $this->invoice_number,
            'poId' => $this->po_id,
            'vendorId' => $this->vendor_id,
            'invoiceDate' => $this->invoice_date,
            'dueDate' => $this->due_date,
            'totalAmount' => $this->total_amount,
            'taxAmount' => $this->tax_amount,
            'discountAmount' => $this->discount_amount,
            'status' => $this->status,
            'order_items' => $this->orderItems,
        ];
    }
}
