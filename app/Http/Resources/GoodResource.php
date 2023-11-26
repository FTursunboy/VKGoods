<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GoodResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'availability' => $this->availability,
            'category' => new CategoryResource($this->category),
            'description' => $this->description,
            'id' => $this->id,
            'owner_id' => $this->owner_id,
            'price' => PriceResource::make($this->price),
            'title' => $this->title,
            'date' => $this->date,
            'is_owner' => $this->is_owner,
            'is_adult' => $this->is_adult,
            'thumb_photo' => $this->thumb_photo,
            'item_rating' => new ReviewResource($this->review)
        ];
    }
}
