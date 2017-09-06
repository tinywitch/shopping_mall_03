<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\Sale;
/**
 * @ORM\Entity
 * @ORM\Table(name="sale_programs")
 */
class SaleProgram
{
    /**
     * @ORM\OneToMany(targetEntity="\Application\Entity\Sale", mappedBy="sale_program")
     * @ORM\JoinColumn(name="id", referencedColumnName="sale_program_id")
     */
    protected $sales;
    public function __construct() 
    {
        $this->sales = new ArrayCollection();
    }

    /**
     * Returns products for this category.
     * @return array
     */
    public function getSales() 
    {
        return $this->sales;
    }
      
    public function addSale($sale) 
    {
        $this->sales[] = $sale;
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id")
     */
    protected $id;

    /**
     * @ORM\Column(name="date_start")
     */
    protected $date_start;

    /**
     * @ORM\Column(name="date_end")
     */
    protected $date_end;

    /**
     * @ORM\Column(name="date_created")
     */
    protected $date_created;
    
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

    public function getDateStart() 
    {
        return $this->date_start;
    }

    public function setDateStart($date_start) 
    {
        $this->date_start = $date_start;
    }

    public function getDateEnd() 
    {
        return $this->date_end;
    }

    public function setDateEnd($date_end) 
    {
        $this->date_end = $date_end;
    }

    public function getStatus() 
    {
        return $this->status;
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
