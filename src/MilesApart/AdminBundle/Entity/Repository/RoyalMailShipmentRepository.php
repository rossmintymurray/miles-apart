<?php

namespace MilesApart\AdminBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * RoyalMailShipmentRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RoyalMailShipmentRepository extends EntityRepository
{
	public function findExistingShipmentsByOrderId($order_id)
    {
        $query = $this->getEntityManager()
                        ->createQuery('
                            SELECT rms FROM MilesApartAdminBundle:RoyalMailShipment rms
                            JOIN rms.customer_order co
                            WHERE co.id = :order_id
                            
                            ')
                        ->setParameter('order_id', $order_id)
                        ;
            
        return $query
                  ->getResult(); 
    }
}