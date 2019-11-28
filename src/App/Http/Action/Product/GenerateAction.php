<?php

namespace App\Http\Action\Product;

use App\Http\Service\ProductService;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

class GenerateAction
{
    public function __invoke(ServerRequestInterface $request)
    {
        return new JsonResponse((new ProductService())->generate());
    }

}