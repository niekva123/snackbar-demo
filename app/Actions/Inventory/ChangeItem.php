<?php

namespace App\Actions\Inventory;

use App\Http\Resources\ItemResource;
use App\Models\Item;
use App\Models\Snackbar;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Ramsey\Uuid\Uuid;

class ChangeItem
{
    use AsAction;

    public function handle(Item $item, array $data): void
    {
        $item->update($data);
    }

    public function rules(): array
    {
        return [
            'name' => 'string',
            'price' => 'numeric',
        ];
    }

    public function asController(ActionRequest $request, Snackbar $snackbar, Item $item): ItemResource
    {
        $data = $request->only('name', 'price');
        $this->handle($item, $data);
        return new ItemResource($item);
    }
}
