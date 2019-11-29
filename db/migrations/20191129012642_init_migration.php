<?php

use App\Http\Entity\Order;
use Phinx\Migration\AbstractMigration;

class InitMigration extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    addCustomColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Any other destructive changes will result in an error when trying to
     * rollback the migration.
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $orders = $this->table('orders');
        $orders->addColumn('status', 'integer', ['limit' => 1, 'default' => Order::STATUS_NEW])
            ->addColumn('amount', 'decimal')
            ->create();

        $orders = $this->table('products');
        $orders->addColumn('name', 'string', ['limit' => 255])
            ->addColumn('price', 'decimal')
            ->create();

        $ordersProducts = $this->table('orders_products');
        $ordersProducts->addColumn('order_id', 'integer', ['limit' => 11])
            ->addColumn('product_id', 'integer', ['limit' => 11])
            ->addForeignKey('order_id', 'orders', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE'])
            ->addForeignKey('product_id', 'products', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE'])
            ->create();
    }
}
