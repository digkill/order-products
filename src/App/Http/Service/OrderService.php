<?php

namespace App\Http\Service;

use App\Http\Entity\Order;
use App\Http\Entity\Product;
use App\Http\Exception\BadRequestException;
use App\Http\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Exception;


class OrderService
{
    private $em;

    public function __construct()
    {
        $this->em = entityManager();
    }

    /**
     * @param array $productIds
     * @return int
     * @throws Exception
     */
    public function create(array $productIds): int
    {
        $productRepository = $this->em->getRepository(Product::class);
        $products = $productRepository->productsByIds($productIds);

        $this->em->beginTransaction();
        try {

            $order = (new Order())
                ->setProducts(new ArrayCollection($products))
                ->setAmount($this->sumPrices($products));


            $this->em->persist($order);
            $this->em->flush();
            $this->em->commit();

        } catch (\Throwable $e) {
            $this->em->rollBack();
            throw new \RuntimeException($e->getMessage(), $e->getCode(), $e);
        }

        return $order->getId();
    }

    /**
     * @param array $products
     * @return float
     */
    public function sumPrices(array &$products): float
    {
        $sum = (float)0;
        foreach ($products as $product) {
            $sum += $product->getPrice();
        }
        return $sum;
    }


    public function pay(int $orderId, float $amount, GatewayInterface $gateway)
    {
        $this->em->beginTransaction();
        try {

            /** @var OrderRepository $orderRepository */
            $orderRepository = $this->em->getRepository(Order::class);

            /** @var Order|null $order */
            $order = $orderRepository->find($orderId);


            if (!$order) {
                throw new BadRequestException('Order not found');
            }

            if ($order->getStatus() !== Order::STATUS_NEW) {
                throw new BadRequestException('Order already paid');
            }

            if ($order->getAmount() !== $amount) {
                throw new BadRequestException('Invalid amount');
            }


            if ($gateway->send() !== GatewayInterface::STATUS_OK) {
                throw new \RuntimeException('Processing error');
            }

            $order->setStatus(Order::STATUS_PAID);

            $this->em->flush();
            $this->em->commit();

        } catch (BadRequestException $e) {
            $this->em->rollback();
            throw new BadRequestException($e->getMessage());
        } catch (\Throwable $t) {
            $this->em->rollback();
            throw new \RuntimeException($t->getMessage(), $t->getCode(), $t);
        }
    }
}