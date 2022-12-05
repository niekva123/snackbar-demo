<?php

namespace App\Actions\Inventory;

use App\Http\Resources\ItemResource;
use App\Models\Snackbar;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Ramsey\Uuid\Uuid;

class CreateItem
{
    use AsAction;

    public function handle(Snackbar $snackbar, array $data): ItemResource
    {
        $item = $snackbar->items()->create($data);

        return new ItemResource($item);
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'price' => 'required|numeric',
        ];
    }

    public function asController(ActionRequest $request, Snackbar $snackbar): ItemResource
    {
        $data = $request->only('name', 'price');
        return $this->handle($snackbar, $data);
    }
}
