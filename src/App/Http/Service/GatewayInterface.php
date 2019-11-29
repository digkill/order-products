<?php

namespace App\Http\Service;

interface GatewayInterface
{
    const STATUS_OK = 200;
    public function send(): int;
}