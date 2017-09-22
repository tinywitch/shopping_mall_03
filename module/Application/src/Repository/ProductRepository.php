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

    public function findProductsByCategory($arr, $data = null)
    {
        $entityManager = $this->getEntityManager();

        $queryBuilder = $entityManager->createQueryBuilder();
        if ($data == null)
        $queryBuilder->select('p')
            ->from(Product::class, 'p')
            ->join('p.category', 'c')
            ->where('c.id IN(:arr)')
            ->setParameter('arr', $arr);
        else {
            $queryBuilder->select('p')
            ->from(Product::class, 'p')
            ->join('p.category', 'c')
            ->where('c.id IN(:arr)')
            ->andWhere($queryBuilder->expr()->between('p.price', '?1', '?2'))
            ->setParameters(['arr' => $arr, 1 => $data['price1'], 2 => $data['price2']]);
        }
        return $queryBuilder->getQuery();
    }

}
