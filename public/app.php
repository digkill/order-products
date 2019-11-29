<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Tools\Setup;
use Framework\Http\Router\ActionResolver;
use Symfony\Component\Dotenv\Dotenv;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Diactoros\ServerRequestFactory;
use Zend\HttpHandlerRunner\Emitter\SapiEmitter;

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

(new Dotenv(true))->load(__DIR__ . '/../.env');

function entityManager()
{
    $config = Setup::createAnnotationMetadataConfiguration([__DIR__ . '/../src/App/Http/Entity'], true, null, null, false);
    try {
        return $entityManager = EntityManager::create([
            'driver' => 'pdo_mysql',
            'host' => getenv('MYSQL_HOST'),
            'user' => getenv('MYSQL_USER'),
            'password' => getenv('MYSQL_PASSWORD'),
            'dbname' => getenv('MYSQL_DATABASE'),
        ], $config);
    } catch (ORMException $e) {
        var_dump($e->getMessage());
    }
}

$aura = new Aura\Router\RouterContainer();
$routes = $aura->getMap();

$routes->get('home', '/', App\Http\Action\HomeAction::class);
$routes->get('product.list', '/product/list', App\Http\Action\Product\ListAction::class);
$routes->get('product.generate', '/product/generate', App\Http\Action\Product\GenerateAction::class);
$routes->get('order.create', '/order/create/products/{products}', App\Http\Action\Order\CreateAction::class);
$routes->get('order.pay', '/order/pay/order/{orderId}/amount/{amount}', App\Http\Action\Order\PayAction::class);
$routes->post('order.create-post', '/order/create/products/{products}', App\Http\Action\Order\CreateAction::class);
$routes->post('order.pay-post', '/order/create/order/{orderId}/amount/{amount}', App\Http\Action\Order\PayAction::class);

$matcher = $aura->getMatcher();
$request = ServerRequestFactory::fromGlobals();

$resolver = new ActionResolver();

try {
    $route = $matcher->match($request);
    foreach ($route->attributes as $key => $val) {
        $request = $request->withAttribute($key, $val);
    }

    $callable = $resolver->resolve($route->handler);
    $response = $callable($request);
} catch (\Exception $e) {
    $response = new JsonResponse(['error' => $e->getMessage()], 404);
}
### Postprocessing
$response = $response->withHeader('X-Developer', 'Edifanoff');

### Send
$emitter = new SapiEmitter();
$emitter->emit($response);
