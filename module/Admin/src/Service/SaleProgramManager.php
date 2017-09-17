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
        $saleProgram->setStatus($this->get_status_depend_on_time(
            $data['date_start'], $data['date_end']));
        $currentDate = date('Y-m-d H:i:s');
        $saleProgram->setDateCreated($currentDate);       
            
        // Add the entity to entity manager.
        $this->entityManager->persist($saleProgram);
            
        // Apply changes to database.
        $this->entityManager->flush();
        
        return 1;
    }

    public function updateSaleProgram($saleProgram, $data)
    {
        $saleProgram->setName($data['name']);
        $saleProgram->setDateStart($data['date_start']);
        $saleProgram->setDateEnd($data['date_end']);
        $saleProgram->setStatus($this->get_status_depend_on_time(
            $data['date_start'], $data['date_end']));

        $this->entityManager->flush();

        return 1;
    }

    public function cancelSaleProgram($saleProgram)
    {
        $saleProgram->setStatus(SaleProgram::CANCEL);
        $this->entityManager->flush();
    }

    private function get_status_depend_on_time($date_start, $date_end)
    {
        $currentDate = date('d-m-Y');
        $date_start = str_replace('/', '-', $date_start);
        $date_start = date('d-m-Y', strtotime($date_start));
        $date_end = str_replace('/', '-', $date_end);
        $date_end = date('d-m-Y', strtotime($date_end));
        if ($currentDate < $date_start)
            return SaleProgram::PENDING;
        elseif ($currentDate <= $date_end)
            return SaleProgram::ACTIVE;
        if ($currentDate > $date_end)
            return SaleProgram::DONE;
    }
}
