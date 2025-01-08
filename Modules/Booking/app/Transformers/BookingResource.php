<?php

namespace Modules\Booking\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {

        return
        [
            'id' => $this->id,
            'customer_name' => $this->customer_name,
            'booking_date' => $this->booking_date,
            'model' => $this->whenLoaded('model'),
        ];
    }
}
