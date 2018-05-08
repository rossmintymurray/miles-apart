<?php

namespace MilesApart\AdminBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * VATRateRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class VATRateRepository extends EntityRepository
{
	//Check the products for array match. (For CSV import)
	public function findVATRateAtDate($orderDate)
	{
        //Check if there is a value for product id.
       
        $query = $this->getEntityManager()
            	->createQuery('
            		SELECT vr FROM MilesApartAdminBundle:VATRate vr
                    WHERE vr.vat_effective_date < :orderDate
                    AND vr.vat_rate_type = 1
                    ORDER BY vr.vat_effective_date DESC	

            		')
                ->setParameter('orderDate', $orderDate)
                ->setMaxResults(1);
            
        
        return $query->getResult(); 
   }

   
}