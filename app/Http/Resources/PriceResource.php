<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
class PriceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        // Access the properties on the related Price model
        return [
            'amount' => $this->amount,
            'currency' => json_decode($this->currency),
            'text' => $this->text,
        ];
    }
}

