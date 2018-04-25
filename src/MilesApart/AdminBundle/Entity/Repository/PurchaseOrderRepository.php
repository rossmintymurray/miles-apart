<?php

namespace MilesApart\AdminBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * PurchaseOrderRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PurchaseOrderRepository extends EntityRepository
{

	public function getPurchaseOrderById($purchase_order_id)
    {
        $query = $this->getEntityManager()
                        ->createQuery('
                            SELECT pop FROM MilesApartAdminBundle:PurchaseOrderProduct pop
                            JOIN pop.purchase_order po
                            JOIN pop.product p
                            LEFT OUTER JOIN p.product_supplier ps
                            LEFT OUTER JOIN ps.supplier s
                            WHERE po.id = :purchase_order_id
                            ORDER BY p.product_supplier_code ASC
                            ')
                        ->setParameter('purchase_order_id', $purchase_order_id)
                        ;
            
        return $query
                  ->getResult(); 
    }
	
}
