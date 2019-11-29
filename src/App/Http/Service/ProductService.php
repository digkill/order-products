<?php

namespace App\Http\Service;

use App\Http\Entity\Product;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

class ProductService
{

    public function list($ids = null): array
    {
        $productRepository = entityManager()->getRepository(Product::class);

        if ($ids) {
            $result = $productRepository->productsByIds($ids);
        } else {
            $result = $productRepository->productsAll();
        }

        $data = [];
        foreach ($result as $item) {
            $data[] = $item->asArray();
        }
        return $data;
    }

    public function generate($count = 20): bool
    {

        $em = entityManager();

        for ($i = 0; $i < $count; $i++) {
            $product = new Product();
            $product->setName('SKU-' . random_int(100000, 999999));
            $product->setPrice(number_format(random_int(100, 1000), 2, '.', ''));
            try {
                $em->persist($product);
                return true;
            } catch (ORMException $e) {
                var_dump($e->getMessage());
            }
        }
        try {
            return $em->flush();
        } catch (OptimisticLockException $e) {
            var_dump($e->getMessage());
        } catch (ORMException $e) {
            var_dump($e->getMessage());
        }
    }
}