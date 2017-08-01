<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="rates")
 */
class Rate
{
    /**
     * @ORM\ManyToOne(targetEntity="\Application\Entity\User", inversedBy="rates")
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
        $user->addRate($this);
    }

    /**
     * @ORM\ManyToOne(targetEntity="\Application\Entity\Product", inversedBy="rates")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    protected $product;
       
    /*
     * Returns associated product.
     * @return \Application\Entity\Product
     */
    public function getProduct() 
    {
        return $this->product;
    }
      
    /**
     * Sets associated product.
     * @param \Application\Entity\Product $product
     */
    public function setProduct($product) 
    {
        $this->product = $product;
        $product->addRate($this);
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id")
     */
    protected $id;

    /**
     * @ORM\Column(name="rate")
     */
    protected $rate;

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

    public function getRate() 
    {
        return $this->rate;
    }

    public function setRate($rate) 
    {
        $this->rate = $rate;
    }
}
