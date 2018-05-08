<?php

namespace MilesApart\AdminBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * PostageBandRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PostageBandRepository extends EntityRepository
{
	//Find postage band that fits the sizes of products in the order
	public function findPostageBandBySizes($width, $length, $depth, $weight)
	{
		$query = $this->getEntityManager()
            ->createQuery('
                SELECT pb FROM MilesApartAdminBundle:PostageBand pb
                WHERE pb.postage_band_max_width >= :width
                AND pb.postage_band_max_length >= :length
                AND pb.postage_band_max_depth >= :depth
                AND pb.postage_band_max_weight >= :weight 
                ORDER BY pb.postage_band_max_weight ASC
                ')
            ->setParameter('width', $width)
            ->setParameter('length', $length)
            ->setParameter('depth', $depth)
            ->setParameter('weight', $weight)
            ->setMaxResults(1);
             
        return $query->getResult(); 
   
	}
}