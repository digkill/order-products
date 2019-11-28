<?php

namespace App\Http\Action;

use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;

class HomeAction
{

    public function __invoke(ServerRequestInterface $request)
    {
        $params = $request->getQueryParams();
        return new HtmlResponse('Home Action');
    }

}