<?php
declare(strict_types=1);

namespace App\Actions\Inventory;

use App\Http\Resources\ItemResource;
use App\Models\Snackbar;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Lorisleiva\Actions\Concerns\AsAction;

class GetInventory
{
    use AsAction;

    public function asController(Snackbar $snackbar): ResourceCollection
    {
        return ItemResource::collection($snackbar->items()->get());
    }
}
