<?php

namespace MilesApart\AdminBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * StaffPickProductRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class StaffPickProductRepository extends EntityRepository
{
	//Check the products for array match. (For CSV import)
	public function findStaffPickProducts()
	{
        //Check if there is a value for product id.
        
        $query = $this->getEntityManager()
            	->createQuery('
            		SELECT spp FROM MilesApartAdminBundle:StaffPickProduct spp
                    LEFT JOIN spp.product p
                    ORDER BY spp.staff_pick_product_date_created DESC
            		')
           		->setMaxResults(4);
        return $query
                  ->getResult(); 
   	}

}