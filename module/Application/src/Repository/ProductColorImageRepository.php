<?php

namespace Application\Repository;

use Doctrine\ORM\EntityRepository;
use Application\Entity\ProductColorImage;


/**
 * This is the custom repository class for Post entity.
 */
class ProductColorImageRepository extends EntityRepository
{
	public function findProductsByColor($arr, $color_id) {
        $entityManager = $this->getEntityManager();

        $queryBuilder = $entityManager->createQueryBuilder();

        $queryBuilder->select('pm')
            ->from(ProductColorImage::class, 'pm')
            ->join('pm.product', 'p')
            ->join('p.category', 'c')
            ->where('c.id IN(:arr)')
            ->andWhere('pm.color_id = :color_id')
            ->setParameters(array('arr' => $arr,'color_id' => $color_id))
            ->distinct(true);

        return $queryBuilder->getQuery();
    }


}