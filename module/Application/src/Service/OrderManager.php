<?php
namespace Application\Service;

use Application\Entity\Order;
use Application\Entity\ProductMaster;
use Application\Entity\OrderItem;
use Zend\Filter\StaticFilter;


class OrderManager 
{
    /**
     * Doctrine entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $color = [
        ProductMaster::WHITE => 'White',
        ProductMaster::BLACK => 'Black',
        ProductMaster::YELLOW => 'Yellow',
        ProductMaster::RED => 'Red',
        ProductMaster::GREEN => 'Green',
        ProductMaster::PURPLE => 'Purple',
        ProductMaster::ORANGE => 'Orange',
        ProductMaster::BLUE => 'Blue',
        ProductMaster::GREY => 'Grey',
        ];

    private $entityManager;
    
    // Constructor is used to inject dependencies into the service.
    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function addNewOrder($data, $cart)
    {
        $arrItems = $cart->items;
        $data['status'] = 1;
        $order = new Order();
        
        $order->setName($data['full_name']);
        $order->setPhone($data['phone_number']);
        //$order->setAddress($data['address']);
        $order->setNumberOfItems($cart->totalItem);
        $order->setCost($cart->totalPrice);
        $order->setStatus(Order::STATUS_PENDING);
        $date_created = date('Y-m-d H:i:s');
        $order->setDateCreated($date_created);
        $order->setUser($this->entityManager
            ->getRepository('Application\Entity\User')->find($data['user_id']));
        $this->entityManager->persist($order);
        foreach ($arrItems as $item) {

            $arr = explode("_",$item->id);
            $color_id = array_search($arr[1], $color);
            $orderItem = new OrderItem();
            $product = $this->entityManager->
                getRepository('Application\Entity\ProductMaster')->findOneBy([
                    'product_id' => $arr[0],
                    'color_id' => $color_id,
                    'size_id' => $arr[2]
                    ],[]
                    );

            $orderItem->setProduct($product);
            $orderItem->setOrder($order);
            $orderItem->setQuantity($item->quantity);
            $orderItem->setCost($item->price);
            $orderItem->setStatus($data['status']);
            $orderItem->setDateCreated($date_created);

            $this->entityManager->persist($orderItem);
            $this->entityManager->flush();
        }
        // Add the entity to entity manager.  
    }
    public function addNewOrderItems($arrItems)
    {
        $count = count($arrItems);
        foreach ($arrItems as $item) {
            $order = new OrderItem();
            
            $order->setName($data['full_name']);
            $order->setPhone($data['phone']);
            $order->setAddress($data['address']);
        }
    }
   
}
