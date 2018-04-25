<?php 
// src/StaffBundle/Service/GetVATAtDate.php
namespace MilesApart\StaffBundle\Service;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;

class GetVATAtDate
{
    /**
     *
     * @var EntityManager 
     */
    protected $em;

    private $logger;

    private $orderDate;

    public function __construct(EntityManager $entityManager, LoggerInterface $logger)
    {
        $this->em = $entityManager;
        $this->logger = $logger;
    }

    public function setOrderDate($order_date)
    {
        $this->orderDate = $order_date;
    }

    public function getVATRate($order_date)
    {
        //Get entity manager
        $logger = $this->logger;
        $em = $this->em;
        
        $vat_rate = $em->getRepository('MilesApartAdminBundle:VATRate')->findVATRateAtDate($order_date->format('Y-m-d'));
        $logger->info('I just got the logger add update price1');
        $logger->info(count($vat_rate));

        foreach($vat_rate as $vat) {
            $logger->info('I just got the logger add update price2');
            $logger->info($vat->getVatRateValue());
        }
        
        //Calculate vat multiplier for use
        $vat_multiplier = $vat->getVatRateValue()/100 + 1;
        
        return $vat_multiplier;
    }
    
}