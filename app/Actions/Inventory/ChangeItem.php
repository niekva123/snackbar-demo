<?php

namespace App\Actions\Inventory;

use App\Http\Resources\ItemResource;
use App\Models\Item;
use App\Models\Snackbar;
use Illuminate\Validation\Validator;
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
        $this->handle($item, $request->validated());
        return new ItemResource($item);
    }
}
