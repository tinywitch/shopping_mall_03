<?php
namespace Admin\Service;

use Application\Entity\Product;

use Zend\Filter\StaticFilter;

// The UserManager service is responsible for adding new posts.
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


    
    // This method adds a new product.
    public function addNewProduct($data)
    {
        // Create new Product entity.

        $product = new Product();
        
        $product->setName($data['name']);
        $product->setAlias($data['alias']);
        $product->setPrice($data['price']);
        $product->setIntro($data['intro']);
        $product->setImage($data['image']);
        $product->setPrice($data['price']);
        $product->setPopular_level($data['popular_level']);
        $product->setDescription($data['description']);
        $product->setStatus($data['status']);
        $product->setRate_avg($data['rate_avg']);
        $product->setRate_count($data['rate_count']);
        $product->setSale($data['sale']);
        $product->setCategory($data['category']);
        $product->setStore($data['store']);
        $product->setDate_created($data['date_created']);
        // Add the entity to entity manager.

        $this->entityManager->persist($product);

        // Apply changes to database.
        $this->entityManager->flush();
    }
    public function updateProduct($product, $data) 
    {
        $product->setName($data['name']);
        $product->setAlias($data['alias']);
        $product->setPrice($data['price']);
        $product->setIntro($data['intro']);
        $product->setImage($data['image']);
        $product->setPrice($data['price']);
        $product->setPopular_level($data['popular_level']);
        $product->setDescription($data['description']);
        $product->setStatus($data['status']);
        $product->setSale($data['sale']);
        $product->setCategory($data['category']);
        $product->setStore($data['store']);
        // Apply changes to database.
        $this->entityManager->flush();
    }
    public function removeProduct($product)
    {   
        
        $productkeys = $product->getKeywords();
            foreach ($productkeys as $productkey) {
              $this->entityManager->remove($productkey);
        }
        $this->entityManager->remove($product);   
        $this->entityManager->flush();

    }
    
}
