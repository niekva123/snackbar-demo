<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Business\Order\Domain\Service\OrderService;
use App\Http\Resources\OrderItemResource;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

class OrderController extends JsonAPIController
{
    public function __construct(
        private readonly OrderService $orderService,
    ) {}

    public function getOrder(string $orderUuid): OrderResource
    {
        return new OrderResource(Order::with('orderItems')->findOrFail($orderUuid));
    }

    public function createOrder(string $snackbarUuid): OrderResource
    {
        $orderUuid = $this->orderService->createOrder(Uuid::fromString($snackbarUuid));

        return new OrderResource(Order::with('orderItems')->findOrFail($orderUuid->toString()));
    }

    public function addOrderItem(string $orderUuid, Request $request): Response|OrderItemResource
    {
        $data = json_decode($request->getContent(), true);
        $validator = Validator::make($data, [
            'item_uuid' => 'required|string',
            'amount' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return $this->failedResponse('Validation failed', 400, $validator->messages()->toArray());
        }
        $orderItemUuid = $this->orderService->addOrderItem(
            Uuid::fromString($orderUuid),
            Uuid::fromString($data['item_uuid']),
            (int) $data['amount'],
        );

        return new OrderItemResource(OrderItem::findOrFail($orderItemUuid->toString()));
    }

    public function changeOrderItemAmount(string $orderUuid, string $orderItemUuid, Request $request): Response|OrderItemResource
    {
        $data = json_decode($request->getContent(), true);
        $validator = Validator::make($data, [
            'amount' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return $this->failedResponse('Validation failed', 400, $validator->messages()->toArray());
        }
        $this->orderService->changeOrderItemAmount(
            Uuid::fromString($orderUuid),
            Uuid::fromString($orderItemUuid),
            (int) $data['amount'],
        );

        return new OrderItemResource(OrderItem::findOrFail($orderItemUuid));
    }

    public function removeOrderItem(string $orderUuid, string $orderItemUuid): OrderResource
    {
        $this->orderService->removeOrderItem(
            Uuid::fromString($orderUuid),
            Uuid::fromString($orderItemUuid),
        );
        return new OrderResource(Order::with('orderItems')->findOrFail($orderUuid));
    }
}
