<?php
namespace Application\Repository;

use Doctrine\ORM\EntityRepository;
use Application\Entity\Product;
use Application\Entity\Category;

/**
 * This is the custom repository class for Post entity.
 */
class ProductRepository extends EntityRepository
{
    /**
    * Retrieves all published posts in descending date order.
    * @return Query
    */
    public function findProductsByCategory($categoryId)
   {
        $entityManager = $this->getEntityManager();
        
        $queryBuilder = $entityManager->createQueryBuilder();
        
        $queryBuilder->select('p')
            ->from(Product::class, 'p')
            ->join('p.category', 'c')
            ->where('c.id = ?1')
            ->setParameter('1', $categoryId);

        return $queryBuilder->getQuery();
   }

}
