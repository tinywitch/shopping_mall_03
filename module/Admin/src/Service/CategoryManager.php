<?php
namespace Admin\Service;

use Application\Entity\Category;
use Zend\Filter\StaticFilter;

class CategoryManager 
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
      
    public function addNewCategory($data) 
    {
        if($this->checkCategoryExists($data['name'])) {
            return 0;
        }
        $category = new Category();
        $category->setName($data['name']);
        $category->setAlias($data['alias']);
        $category->setDescription($data['description']);
        $category->setParentId($data['parent_id']);
        $currentDate = date('Y-m-d H:i:s');
        $category->setDateCreated($currentDate);       
            
        // Add the entity to entity manager.
        $this->entityManager->persist($category);
            
        // Apply changes to database.
        $this->entityManager->flush();
        return 1;
    }

    public function updateCategory($category, $data) 
    {
        if ($category->getName() != $data['name']){
            if($this->checkCategoryExists($data['name']))
                return 0;
        }
        
        $category->setName($data['name']);
        $category->setAlias($data['alias']);
        $category->setDescription($data['description']);
        $category->setParentId($data['parent_id']);
        
        // Apply changes to database.
        $this->entityManager->flush();
        return 1;
    }

    public function removeCategory($category) 
        {
            $this->entityManager->remove($category);
                
            $this->entityManager->flush();
        }


    public function checkCategoryExists($data)
    {
        return $this->entityManager->getRepository(Category::class)->
            findByName($data) != [] ? true : false; 
    }

    public function categories_for_select()
    {
        $categories = $this->entityManager->getRepository(Category::class)->findAll();
        $categories_for_select = ['0' => '--Select--'];
        foreach($categories as $cate)
        {
            $categories_for_select[$cate->getID()] = $cate->getName();
        }
        return $categories_for_select;
    }

}