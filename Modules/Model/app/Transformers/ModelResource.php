<?php

namespace Modules\Model\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ModelResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return
        [
            'id' => $this->id,
            'name' => $this->name,
            'date_of_birth' => $this->date_of_birth,
            'height' => $this->height,
            'shoe_size' => $this->shoe_size,
            'picture' => $this->picture,
            'category' => $this->whenLoaded('category')
        ];
    }
}
