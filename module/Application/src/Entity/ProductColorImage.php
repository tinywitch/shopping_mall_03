<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Application\Entity\Image;
use Application\Entity\Product;

/**
 * @ORM\Entity
 * @ORM\Table(name="product_color_images")
 */
class ProductColorImage
{

    /**
     * @ORM\ManyToOne(targetEntity="\Application\Entity\Product", inversedBy="product_color_images")
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
        $product->addProductImage($this);
    }
     /**
     * @ORM\OneToMany(targetEntity="\Application\Entity\Image", mappedBy="productcolorimage")
     * @ORM\JoinColumn(name="id", referencedColumnName="product_color_image_id")
     */
    protected $images;
    public function __construct() 
    {
        $this->images = new ArrayCollection();
    }

    /**
     * Returns products for this category.
     * @return array
     */
    public function getImages() 
    {
        return $this->images;
    }
      
    /**
     * Adds a new product to this category.
     * @param $image
     */
    public function addImage($image) 
    {
        $this->images[] = $image;
    }
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id")
     */
    protected $id;

    /**
     * @ORM\Column(name="color")
     */
    protected $color;

    /**
     * @ORM\Column(name="status")
     */
    protected $status;

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

    public function getColor() 
    {
        return $this->color;
    }

    public function setColor($color) 
    {
        $this->color = $color;
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
