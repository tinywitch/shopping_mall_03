<?php
namespace Application\Service;

use Application\Entity\Product;
use Zend\Filter\StaticFilter;

class ProductManager 
{
    /**
     * Doctrine entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;
    
    // Constructor is used to inject dependencies into the service.
    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }
}
