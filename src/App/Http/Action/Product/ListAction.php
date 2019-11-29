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
        $ids = $request->getQueryParams()['ids'] ?? null;

        if ($ids) {
            $ids = explode(',', $ids);
        }

        return new JsonResponse((new ProductService())->list($ids));
    }

}