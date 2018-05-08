<?php

namespace MilesApart\AdminBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * EmployeeRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EmployeeRepository extends EntityRepository
{
  //Get all employees from the database
	public function getAllEmployees($limit = null)
	{

		$query = $this->getEntityManager()
                    	->createQuery('
                    		SELECT e FROM MilesApartAdminBundle:Employee e  		
                    		ORDER BY e.employee_surname ASC
                    		');
    return $query
                  ->getResult();
   }

   //Get all employees from the database
  public function getCurrentEmployees()
  {

    $query = $this->getEntityManager()
                      ->createQuery('
                        SELECT e FROM MilesApartAdminBundle:Employee e
                        WHERE e.employee_leaving_date IS null      
                        ORDER BY e.employee_surname ASC
                        ');
    return $query
                  ->getResult();
   }

   //Check the products for array match. (For CSV import)
    public function getEmployeesByStartAndEndDate($start_date, $end_date)
    {

        $query = $this->getEntityManager()
        ->createQuery('
            SELECT e, ep, dt, dtbp
            FROM MilesApartAdminBundle:Employee e
            INNER JOIN e.employee_payment ep
            INNER JOIN ep.daily_take_business_premises dtbp
            INNER JOIN dtbp.daily_take dt
            WITH ep.employee_payment_week_end_date BETWEEN :start_date AND :end_date
            ORDER BY dt.daily_take_date ASC'
         )->setParameter('start_date', $start_date)
        ->setParameter('end_date', $end_date);

        //Return result set.
        return $query
                  ->getResult(); 
   }
   
   public function findOneEmployeeById($id) 
   {
    
    $query = $this->getEntityManager()
        ->createQuery('
            SELECT e, ep
            FROM MilesApartAdminBundle:Employee e
            INNER JOIN e.employee_payment ep
            INNER JOIN ep.daily_take_business_premises dtbp
            INNER JOIN dtbp.daily_take dt
            WHERE e.id = :id
            ORDER BY ep.employee_payment_week_end_date DESC'
         )->setParameter('id', $id)
        ;

        //Return result set.
        return $query
                  ->getResult();
   }

   public function findPastEmployees() 
   {
    
    $query = $this->getEntityManager()
        ->createQuery('
            SELECT e FROM MilesApartAdminBundle:Employee e
            WHERE e.employee_leaving_date IS NOT NULL
            ORDER BY e.employee_surname ASC'
         )
        ;

        //Return result set.
        return $query
                  ->getResult();
   }
}