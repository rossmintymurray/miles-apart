<?php

namespace MilesApart\AdminBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * ProductSupplierRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProductSupplierRepository extends EntityRepository
{
	

	//Check the products for array match. (For CSV import)
    public function findProductSuppliersNotDiscontinued($supplierId)
    {
        //Check if there is a value for product id.
        if ($supplierId) {
                $query = $this->getEntityManager()
                        ->createQuery('
                            SELECT ps, p, s FROM MilesApartAdminBundle:ProductSupplier ps
                            LEFT JOIN ps.product p
                            Left JOIN ps.supplier s
                            WHERE p.is_product_discontinued != true
                            AND s.id = :supplierId 
                            ')
                        ->setParameter('supplierId', $supplierId);
            } 
        return $query
                  ->getResult(); 
   }
}
