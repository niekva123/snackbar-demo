<?php

namespace App\Http\Resources;

use App\Business\Value\Price;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'price' => (new Price($this->price))->getPriceInEuro(),
            'snackbar_uuid' => $this->snackbar_uuid,
        ];
    }
}
