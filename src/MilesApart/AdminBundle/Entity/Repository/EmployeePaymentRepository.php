<?php

namespace MilesApart\AdminBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * EmployeePaymentRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EmployeePaymentRepository extends EntityRepository
{
	function getEmployeePaymentByStartAndEndDate($start_date, $end_date)
	{

		$query = $this->getEntityManager()
        ->createQuery('
            SELECT  ep, e
            FROM MilesApartAdminBundle:EmployeePayment ep 
            INNER JOIN ep.employee e 
            WITH ep.employee_payment_week_end_date
            BETWEEN :start_date AND :end_date
            ORDER BY e.employee_surname ASC'
         )->setParameter('start_date', $start_date)
        ->setParameter('end_date', $end_date);

        //Return result set.
        return $query
                  ->getResult(); 
   }
}
