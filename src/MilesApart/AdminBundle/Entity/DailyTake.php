<?php
// src/MilesApart/AdminBundle/Entity/DailyTake.php -- Defines the customer type object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\DailyTakeRepository")
 * @ORM\Table(name="daily_take")
 * @ORM\HasLifecycleCallbacks()
 */

class DailyTake
{
    //Define the values

    /**
     *  
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="date", unique=false, nullable=false)
     */
    protected $daily_take_date;

    /**
     * @ORM\OneToMany(targetEntity="DailyTakeBusinessPremises", mappedBy="daily_take", cascade={"persist"})
     */
    protected $daily_take_business_premises;



    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //Business Customer Representative Date Of Birth
        $metadata->addPropertyConstraint('daily_take_date', new Assert\Date());
    }


    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->daily_take_business_premises = new \Doctrine\Common\Collections\ArrayCollection();
        $this->daily_take_date = new \DateTime();
        
    }
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set daily_take_date
     *
     * @param \DateTime $dailyTakeDate
     * @return DailyTake
     */
    public function setDailyTakeDate($dailyTakeDate)
    {
        $this->daily_take_date = $dailyTakeDate;
    
        return $this;
    }

    /**
     * Get daily_take_date
     *
     * @return \DateTime 
     */
    public function getDailyTakeDate()
    {
        return $this->daily_take_date;
    }

    /**
     * Add daily_take_business_premises
     *
     * @param \MilesApart\AdminBundle\Entity\DailyTakeBusinessPremises $dailyTakeBusinessPremises
     * @return DailyTake
     */
    public function addDailyTakeBusinessPremise(\MilesApart\AdminBundle\Entity\DailyTakeBusinessPremises $dailyTakeBusinessPremises)
    {
        $this->daily_take_business_premises[] = $dailyTakeBusinessPremises;
    
        return $this;
    }

    /**
     * Remove daily_take_business_premises
     *
     * @param \MilesApart\AdminBundle\Entity\DailyTakeBusinessPremises $dailyTakeBusinessPremises
     */
    public function removeDailyTakeBusinessPremise(\MilesApart\AdminBundle\Entity\DailyTakeBusinessPremises $dailyTakeBusinessPremises)
    {
        $this->daily_take_business_premises->removeElement($dailyTakeBusinessPremises);
    }

    /**
     * Get daily_take_business_premises
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDailyTakeBusinessPremises()
    {
        return $this->daily_take_business_premises;
    }

    /**
     * Get z_reading_cash_daily_take_total
     *
     * @return string 
     */
    public function getZReadingCashDailyTakeTotal()
    {
        $total = "0.00";
        foreach ($this->getDailyTakeBusinessPremises() as $key => $value) {
            $total = $total + $value->getZReadingCash();
        }
            

            return $total;
    }

    /**
     * Get get_counted_cash_daily_take_total
     *
     * @return string 
     */
    public function getCountedCashDailyTakeTotal()
    {
        $total = "0.00";
        foreach ($this->getDailyTakeBusinessPremises() as $key => $value) {
            $total = $total + $value->getCountedCash();
        }
            

            return $total;
    }

    /**
     * Get total_deductions_daily_take_total
     *
     * @return string 
     */
    public function getTotalDeductionsDailyTakeTotal()
    {
        $total = "0.00";
        foreach ($this->getDailyTakeBusinessPremises() as $key => $value) {
            $total = $total + $value->getTotalDeductions();
        }
            

            return $total;
    }

    /**
     * Get renumeration_cash_daily_take_total
     *
     * @return string 
     */
    public function getRenumerationCashDailyTakeTotal()
    {
        $total = "0.00";
        foreach ($this->getDailyTakeBusinessPremises() as $key => $value) {
            $total = $total + $value->getRenumerationCash();
        }
            

            return $total;
    }

    /**
     * Get z_reading_card_daily_take_total
     *
     * @return string 
     */
    public function getZReadingCardDailyTakeTotal()
    {
        $total = "0.00";
        foreach ($this->getDailyTakeBusinessPremises() as $key => $value) {
            $total = $total + $value->getZReadingCard();
        }
            

            return $total;
    }

    /**
     * Get get_counted_card_daily_take_total
     *
     * @return string 
     */
    public function getCountedCardDailyTakeTotal()
    {
        $total = "0.00";
        foreach ($this->getDailyTakeBusinessPremises() as $key => $value) {
            $total = $total + $value->getCountedCard();
        }
            

            return $total;
    }

    

    /**
     * Get renumeration_cash_daily_take_total
     *
     * @return string 
     */
    public function getRenumerationCardDailyTakeTotal()
    {
        $total = "0.00";
        foreach ($this->getDailyTakeBusinessPremises() as $key => $value) {
            $total = $total + $value->getRenumerationCard();
        }
            

            return $total;
    }


    /**
     * Get z_reading_daily_take_total
     *
     * @return string 
     */
    public function getZReadingDailyTakeTotal()
    {
        
        $total = $this->getZReadingCashDailyTakeTotal() + $this->getZReadingCardDailyTakeTotal();
    
        return $total;
    }

    /**
     * Get get_counted_daily_take_total
     *
     * @return string 
     */
    public function getCountedDailyTakeTotal()
    {
        $total = $this->getCountedCashDailyTakeTotal() + $this->getCountedCardDailyTakeTotal();
    
        return $total;
    }

    

    /**
     * Get renumeration_daily_take_total
     *
     * @return string 
     */
    public function getRenumerationDailyTakeTotal()
    {
        $total = $this->getRenumerationCashDailyTakeTotal() + $this->getRenumerationCardDailyTakeTotal();
    
        return $total;
    }

    /**
     * Get test
     *
     * @return string 
     */
    public function getTest()
    {
        $total = 40;
        return $total;
    }


}