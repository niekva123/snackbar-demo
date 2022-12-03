<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * App\Models\OrderItem
 *
 * @property string $uuid
 * @property string $order_uuid
 * @property string $item_uuid
 * @property int $amount
 * @property int $price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem whereItemUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem whereOrderUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem wherePrice($value)
 * @mixin \Eloquent
 */
class OrderItem extends Model
{
    use HasFactory;

    public $primaryKey = 'uuid';

    public function item(): HasOne
    {
        return $this->hasOne(Item::class, 'item_uuid');
    }

    public function order(): HasOne
    {
        return $this->hasOne(Order::class, 'order_uuid');
    }
}
