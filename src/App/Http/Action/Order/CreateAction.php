<?php

namespace App\Http\Action\Order;

use App\Http\Exception\BadRequestException;
use App\Http\Service\OrderService;
use App\Http\Service\ProductService;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

class CreateAction
{

    public function __invoke(ServerRequestInterface $request)
    {
        $products = $request->getAttribute('products');

        $products = explode(',',$products);

        if (!is_array($products)) {
            throw new BadRequestException('Продукты должны быть представлены в массиве');
        }

        $orderService = new OrderService();
        $orderId = $orderService->create($products);

        return new JsonResponse(['result' => [
            'orderId' => $orderId,
        ]]);


    }


}