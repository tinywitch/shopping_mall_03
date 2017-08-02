<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="comments")
 */
class Comment
{
    /**
     * @ORM\ManyToOne(targetEntity="\Application\Entity\User", inversedBy="notifications")
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
        $user->addComment($this);
    }
    /**
     * @ORM\ManyToOne(targetEntity="\Application\Entity\Product", inversedBy="comments")
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
        $product->addComment($this);
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id")
     */
    protected $id;

    /**
     * @ORM\Column(name="content")
     */
    protected $content;

    /**
     * @ORM\Column(name="status")
     */
    protected $status;

    // Returns ID of this product.
    public function getId() 
    {
        return $this->id;
    }

    // Sets ID of this product.
    public function setId($id) 
    {
        $this->id = $id;
    }

    public function getContent() 
    {
        return $this->content;
    }

    public function setContent($content) 
    {
        $this->content = $content;
    }

    public function getStatus() 
    {
        return $this->status;
    }

    public function setStatus($status) 
    {
        $this->status = $status;
    }
}
