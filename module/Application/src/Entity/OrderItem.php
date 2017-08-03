<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="order_items")
 */
class OrderItem
{
    /**
     * @ORM\ManyToOne(targetEntity="\Application\Entity\Product_image", inversedBy="order_items")
     * @ORM\JoinColumn(name="product_image_id", referencedColumnName="id")
     */
    protected $product_image;
    /*
     * Returns associated product_image.
     * @return \Application\Entity\Product_image
     */
    public function getProductImage() 
    {
        return $this->product_image;
    }
      
    /**
     * Sets associated product_image.
     * @param \Application\Entity\Product_image $product_image
     */
    public function setProductImage($product_image) 
    {
        $this->product_image = $product_image;
        $product_image->addOrderItem($this);
    }

    /**
     * @ORM\ManyToOne(targetEntity="\Application\Entity\Order", inversedBy="order_items")
     * @ORM\JoinColumn(name="product_image_id", referencedColumnName="id")
     */
    protected $order;
    /*
     * Returns associated order.
     * @return \Application\Entity\Order
     */
    public function getOder() 
    {
        return $this->order;
    }
      
    /**
     * Sets associated order.
     * @param \Application\Entity\Order $order
     */
    public function setOder($order) 
    {
        $this->order = $order;
        $order->addOrderItem($this);
    }
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id")
     */
    protected $id;

    /**
     * @ORM\Column(name="quantity")
     */
    protected $quantity;

    /**
     * @ORM\Column(name="status")
     */
    protected $status;

    /**
     * @ORM\Column(name="cost")
     */
    protected $cost;

    // Returns ID of this post.
    public function getId() 
    {
        return $this->id;
    }

    // Sets ID of this post.
    public function setId($id) 
    {
        $this->id = $id;
    }

    public function getQuantity() 
    {
        return $this->quantity;
    }

    public function setQuantity($quantity) 
    {
        $this->quantity = $quantity;
    }

    public function getStatus() 
    {
        return $this->status;
    }

    public function setStatus($status) 
    {
        $this->status = $status;
    }

    public function getCost() 
    {
        return $this->cost;
    }

    public function setCost($cost) 
    {
        $this->cost = $cost;
    }
}
