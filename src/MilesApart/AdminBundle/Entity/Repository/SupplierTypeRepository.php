<?php

namespace MilesApart\AdminBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * SupplierTypeRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SupplierTypeRepository extends EntityRepository
{
  //Get all employees from the database
	public function getAllSupplierTypes($limit = null)
	{

		$query = $this->getEntityManager()
                    	->createQuery('
                    		SELECT st FROM MilesApartAdminBundle:SupplierType st  		
                    		ORDER BY st.supplier_type_name ASC
                    		');
    return $query
                  ->getResult();
   }


   
}