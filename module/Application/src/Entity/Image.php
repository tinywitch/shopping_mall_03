<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\ProductColorImage;
/**
 * @ORM\Entity
 * @ORM\Table(name="images")
 */
class image
{
   
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id")
     */
    protected $id;

    /**
     * @ORM\Column(name="image")
     */
    protected $image;

    /**
     * @ORM\Column(name="parent_id")
     */
    protected $parent_id = 0;

    /**
     * @ORM\Column(name="date_created")
     */
    protected $date_created;
    
    /**
    * @ORM\Column(name="status")
    */
    protected $status;

    /**
     * @ORM\ManyToOne(targetEntity="\Application\Entity\ProductColorImage", inversedBy="images")
     * @ORM\JoinColumn(name="product_color_image_id", referencedColumnName="id")
     */
    protected $product_color_image;
       
    /*
     * Returns associated category.
     * @return \Application\Entity\ProductColorImage
     */
    public function getProductColorImage() 
    {
        return $this->product_color_image;
    }
      
    /**
     * Sets associated category.
     * @param \Application\Entity\ProductColorImage $product_color_image
     */
    public function setProductColorImage($product_color_image) 
    {
        $this->product_color_image = $product_color_image;
        $product_color_image->addImage($this);
    }
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

    public function getImage() 
    {
        return $this->image;
    }

    public function setImage($image) 
    {
        $this->image = $image;
    }

    public function getStatus() 
    {
        return $this->status;
    }

    public function getParentId() 
    {
        return $this->parent_id;
    }

    public function setParentId($parent_id) 
    {
        $this->parent_id = $parent_id;
    }

    public function setStatus($status) 
    {
        $this->status = $status;
    }

    public function getDateCreated() 
    {
        return $this->date_created;
    }

    public function setDateCreated($date_created) 
    {
        $this->date_created = $date_created;
    }
}
