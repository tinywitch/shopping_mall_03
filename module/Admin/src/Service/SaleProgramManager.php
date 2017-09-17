<?php
namespace Admin\Service;

use Application\Entity\SaleProgram;
use Zend\Filter\StaticFilter;

class SaleProgramManager 
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

    public function addNewSaleProgram($data) 
    {
        $saleProgram = new SaleProgram();
        $saleProgram->setName($data['name']);
        $saleProgram->setDateStart($data['date_start']);
        $saleProgram->setDateEnd($data['date_end']);
        $currentDate = date('Y-m-d H:i:s');
        $saleProgram->setDateCreated($currentDate);       
            
        // Add the entity to entity manager.
        $this->entityManager->persist($saleProgram);
            
        // Apply changes to database.
        $this->entityManager->flush();
        
        return 1;
    }
}
