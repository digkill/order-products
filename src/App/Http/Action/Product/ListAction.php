<?php

namespace App\Http\Action\Product;

use App\Http\Service\ProductService;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\JsonResponse;

class ListAction
{
    public function __invoke(ServerRequestInterface $request)
    {
        $ids = $request->getQueryParams()['ids'] ?? '1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17';
        $ids = explode(',', $ids);
        return new JsonResponse((new ProductService())->list($ids));
    }

}