<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="addresses")
 */
class Address
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id")
     */
    protected $id;

    /**
     * @ORM\Column(name="province")
     */
    protected $province;

    /**
     * @ORM\Column(name="district")
     */
    protected $district;

    /**
     * @ORM\Column(name="address")
     */
    protected $address;

    /**
     * @ORM\Column(name="date_created")
     */
    protected $date_created;
    
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

    public function getProvince() 
    {
        return $this->province;
    }

    public function setProvince($province) 
    {
        $this->province = $province;
    }

    public function getDistrict() 
    {
        return $this->district;
    }

    public function setDistrict($district) 
    {
        $this->district = $district;
    }

    public function getAddress() 
    {
        return $this->address;
    }

    public function setAddress($address) 
    {
        $this->address = $address;
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
