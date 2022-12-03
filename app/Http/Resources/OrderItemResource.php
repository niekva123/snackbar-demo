<?php

namespace App\Http\Resources;

use App\Business\Value\Price;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
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
            'name' => $this->item()->firstOrFail()->name,
            'price' => (new Price($this->price))->getPriceInEuro(),
            'amount' => $this->amount,
        ];
    }
}
