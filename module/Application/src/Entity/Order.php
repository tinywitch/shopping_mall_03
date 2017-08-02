<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Application\Entity\OrderItem;
/**
 * @ORM\Entity
 * @ORM\Table(name="orders")
 */
class Order
{
    /**
     * @ORM\OneToMany(targetEntity="\Application\Entity\OrderItem", mappedBy="orders")
     * @ORM\JoinColumn(name="id", referencedColumnName="order_id")
     */
    protected $order_items;
    public function __construct() 
    {
        $this->order_items = new ArrayCollection();
    }

    /**
     * Returns orderitems for this order.
     * @return array
     */
    public function getOrderItems() 
    {
        return $this->order_items;
    }
      
    /**
     * Adds a new orderitem to this order.
     * @param $orderitem
     */
    public function addOrderItem($order_item) 
    {
        $this->order_items[] = $order_item;
    }
    /**
     * @ORM\ManyToOne(targetEntity="\Application\Entity\User", inversedBy="orders")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;
    /*
     * Returns associated user.
     * @return \Application\Entity\User
     */
    public function getUser() 
    {
        return $this->user;
    }
      
    /**
     * Sets associated user.
     * @param \Application\Entity\User $user
     */
    public function setUser($user) 
    {
        $this->user = $user;
        $user->addOrder($this);
    }
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id")
     */
    protected $id;

    /**
     * @ORM\Column(name="status")
     */
    protected $status;

    /**
     * @ORM\Column(name="name")
     */
    protected $name;

    /**
     * @ORM\Column(name="phone")
     */
    protected $phone;

    /**
     * @ORM\Column(name="address")
     */
    protected $address;

    /**
     * @ORM\Column(name="cost")
     */
    protected $cost;

    /**
     * @ORM\Column(name="completed_at")
     */
    protected $completed_at;

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

    public function getStatus() 
    {
        return $this->status;
    }

    public function setStatus($status) 
    {
        $this->status = $status;
    }

    public function getName() 
    {
        return $this->name;
    }

    public function setName($name) 
    {
        $this->name = $name;
    }

    public function getPhone() 
    {
        return $this->phone;
    }

    public function setPhone($phone) 
    {
        $this->phone = $phone;
    }

    public function getAddress() 
    {
        return $this->address;
    }

    public function setAddress($address) 
    {
        $this->address = $address;
    }

    public function getCost() 
    {
        return $this->cost;
    }

    public function setCost($cost) 
    {
        $this->cost = $cost;
    }

    public function getCompleted_at() 
    {
        return $this->completed_at;
    }

    public function setCompleted_at($completed_at) 
    {
        $this->completed_at = $completed_at;
    }
}
