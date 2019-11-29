<?php

namespace App\Http\Action\Order;

use App\Http\Exception\BadRequestException;
use App\Http\Service\OrderService;
use App\Http\Service\YandexGateway;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

class PayAction
{
public function __invoke(ServerRequestInterface $request)
{
    $orderId = $request->getAttribute('orderId');
    $amount = $request->getAttribute('amount');

    if (is_null($orderId)) {
        throw new BadRequestException('Не передали id заказа');
    }

    if (is_null($amount)) {
        throw new BadRequestException('Нет стоимости');
    }

    $yandexGateway = new YandexGateway();

    $orderService = new OrderService();
    $orderService->pay($orderId, $amount, $yandexGateway);

    return new JsonResponse([
        'result' => 'Заказ оплачен'
    ]);
}
}