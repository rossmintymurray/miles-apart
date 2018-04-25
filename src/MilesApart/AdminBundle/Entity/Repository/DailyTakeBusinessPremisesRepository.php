<?php

namespace MilesApart\AdminBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * DailyTakeBusinessPremisesRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class DailyTakeBusinessPremisesRepository extends EntityRepository
{
	//Check the products for array match. (For CSV import)
	public function findExistingDailyTakeBusinessPremisesByDate($dailyTakeDate)
	{
        //Check if there is a value for product id.
        if ($dailyTakeDate) {
                $query = $this->getEntityManager()
                    	->createQuery('
                    		SELECT p FROM MilesApartAdminBundle:DailyTakeBusinessPremises p
                            WHERE p.daily_take = :dailyTakeDate	
                    		')
                        ->setParameter('dailyTakeDate', $dailyTakeDate);
            } 
        return $query
                  ->getResult(); 
   }

   //Check the products for array match. (For CSV import)
    public function getDailyTakeBusinessPremisesByStartAndEndDate($start_date, $end_date)
    {
        //Get Daily Take Business Premises WHERE daily_take_date is between start date and end date.
        $qb = $this->getEntityManager()
                        ->createQueryBuilder();
                        $qb->select('dtbp')
                        ->from('MilesApartAdminBundle:DailyTakeBusinessPremises', 'dtbp')
                        ->join('dtbp.daily_take', 'dt')
                        ->join('dtbp.business_premises', 'bp')
                        ->where('dt.daily_take_date BETWEEN :start_date AND :end_date')
                        ->setParameter('start_date', $start_date)
                        ->setParameter('end_date', $end_date);
        
        $query = $qb->getQuery();

        //Return result set.
        return $query
                  ->getResult(); 
   }
   
}
