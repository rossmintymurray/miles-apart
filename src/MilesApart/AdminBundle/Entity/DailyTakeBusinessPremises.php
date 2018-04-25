<?php
// src/MilesApart/AdminBundle/Entity/DailyTakeBusinessPremises.php -- Defines the customer type object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\DailyTakeBusinessPremisesRepository")
 * @ORM\Table(name="daily_take_business_premises")
 * @ORM\HasLifecycleCallbacks()
 */

class DailyTakeBusinessPremises
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
     * @ORM\ManyToOne(targetEntity="DailyTake", inversedBy="daily_take_business_premises", cascade={"persist"})
     * @ORM\JoinTable(name="daily_take")
     * @ORM\JoinColumn(name="daily_take_id", referencedColumnName="id")
     */
    protected $daily_take;

    /**
     * @ORM\ManyToOne(targetEntity="BusinessPremises", inversedBy="daily_take_business_premises")
     * @ORM\JoinTable(name="business_premises")
     * @ORM\JoinColumn(name="business_premises_id", referencedColumnName="id")
     */
    protected $business_premises;

    /**
     * @ORM\Column(type="decimal", precision=6, scale=2, nullable=true)
     */
    protected $z_reading_cash;

    /**
     * @ORM\Column(type="decimal", precision=6, scale=2, nullable=true)
     */
    protected $z_reading_card;

    /**
     * @ORM\Column(type="decimal", precision=6, scale=2, nullable=true)
     */
    protected $counted_cash;

    /**
     * @ORM\Column(type="decimal", precision=6, scale=2, nullable=true)
     */
    protected $counted_card;

     /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $transactions;

    /**
     * @ORM\Column(type="datetime", unique=false, nullable=true)
     */
    protected $daily_take_business_premises_date_created;

    /**
     * @ORM\Column(type="datetime", unique=false, nullable=true)
     */
    protected $daily_take_business_premises_date_modified;

    /**
     * @ORM\OneToMany(targetEntity="DailyTakeBusinessPremisesPettyCash", mappedBy="daily_take_business_premises", cascade={"persist"})
     */
    protected $daily_take_business_premises_petty_cash;

    /**
     * @ORM\OneToMany(targetEntity="DailyTakeBusinessPremisesShopDepartment", mappedBy="daily_take_business_premises", cascade={"persist"})
     */
    protected $daily_take_business_premises_shop_department;

    /**
     * @ORM\OneToMany(targetEntity="EmployeePayment", mappedBy="daily_take_business_premises", cascade={"persist"})
     */
    protected $employee_payment;

    /**
     * @ORM\OneToMany(targetEntity="EmployeeStatutoryPayment", mappedBy="daily_take_business_premises", cascade={"persist"})
     */
    protected $employee_statutory_payment;

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        $this->setDailyTakeBusinessPremisesDateModified(new \DateTime(date('Y-m-d H:i:s')));

        if($this->getDailyTakeBusinessPremisesDateCreated() == null)
        {
            $this->setDailyTakeBusinessPremisesDateCreated(new \DateTime(date('Y-m-d H:i:s')));
        }
    }



    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {

        $metadata->addPropertyConstraint('z_reading_cash', new Assert\NotBlank());
        $metadata->addPropertyConstraint('z_reading_cash', new Assert\Range(array(
            'min'        => 0,
            'max'        => 10000,
            'minMessage' => 'Z reading cash must be at least {{ limit }}',
            'maxMessage' => 'Z reading cash cannot be more than {{ limit }}',
        )));

        $metadata->addPropertyConstraint('z_reading_card', new Assert\NotBlank());
        $metadata->addPropertyConstraint('z_reading_card', new Assert\Range(array(
            'min'        => 0,
            'max'        => 10000,
            'minMessage' => 'Z reading card must be at least {{ limit }}',
            'maxMessage' => 'Z reading card cannot be more than {{ limit }}',
        )));

        $metadata->addPropertyConstraint('counted_cash', new Assert\NotBlank());
        $metadata->addPropertyConstraint('counted_cash', new Assert\Range(array(
            'min'        => 0,
            'max'        => 10000,
            'minMessage' => 'Counted cash must be at least {{ limit }}',
            'maxMessage' => 'Counted cash cannot be more than {{ limit }}',
        )));

        $metadata->addPropertyConstraint('counted_card', new Assert\NotBlank());
        $metadata->addPropertyConstraint('counted_card', new Assert\Range(array(
            'min'        => 0,
            'max'        => 10000,
            'minMessage' => 'Counted card must be at least {{ limit }}',
            'maxMessage' => 'Counted card cannot be more than {{ limit }}',
        )));

        $metadata->addPropertyConstraint('transactions', new Assert\NotBlank());
        $metadata->addPropertyConstraint('transactions', new Assert\Range(array(
            'min'        => 0,
            'max'        => 1000,
            'minMessage' => 'Number of transactions must be at least {{ limit }}',
            'maxMessage' => 'Number of transactions cannot be more than {{ limit }}',
        )));

   
        

    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->daily_take_business_premises_petty_cash = new \Doctrine\Common\Collections\ArrayCollection();
        $this->daily_take_business_premises_shop_department = new \Doctrine\Common\Collections\ArrayCollection();
        $this->employee_payment = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set z_reading_cash
     *
     * @param string $zReadingCash
     * @return DailyTakeBusinessPremises
     */
    public function setZReadingCash($zReadingCash)
    {
        $this->z_reading_cash = $zReadingCash;
    
        return $this;
    }

    /**
     * Get z_reading_cash
     *
     * @return string 
     */
    public function getZReadingCash()
    {
        return $this->z_reading_cash;
    }

    /**
     * Set z_reading_card
     *
     * @param string $zReadingCard
     * @return DailyTakeBusinessPremises
     */
    public function setZReadingCard($zReadingCard)
    {
        $this->z_reading_card = $zReadingCard;
    
        return $this;
    }

    /**
     * Get z_reading_card
     *
     * @return string 
     */
    public function getZReadingCard()
    {
        return $this->z_reading_card;
    }

    /**
     * Set counted_cash
     *
     * @param string $countedCash
     * @return DailyTakeBusinessPremises
     */
    public function setCountedCash($countedCash)
    {
        $this->counted_cash = $countedCash;
    
        return $this;
    }

    /**
     * Get counted_cash
     *
     * @return string 
     */
    public function getCountedCash()
    {
        return $this->counted_cash;
    }

    /**
     * Set transactions
     *
     * @param string $transactions
     * @return DailyTakeBusinessPremises
     */
    public function setTransactions($transactions)
    {
        $this->transactions = $transactions;
    
        return $this;
    }

    /**
     * Get transactions
     *
     * @return string 
     */
    public function getTransactions()
    {
        return $this->transactions;
    }

     /**
     * Get remumeration_cash
     *
     * @return string 
     */
    public function getRenumerationCash()
    {

        return $this->counted_cash + $this->getTotalDeductions() - $this->z_reading_cash;
    }

    /**
     * Get remumeration_card
     *
     * @return string 
     */
    public function getRenumerationCard()
    {
        return $this->counted_card - $this->z_reading_card;
    }

    /**
     * Get total_z
     *
     * @return string 
     */
    public function getTotalZ()
    {
        return $this->z_reading_cash + $this->z_reading_card;
    }

    /**
     * Get total_counted
     *
     * @return string 
     */
    public function getTotalCounted()
    {
        return $this->counted_cash + $this->counted_card ;
    }

    /**
     * Get total_renumeration
     *
     * @return string 
     */
    public function getTotalRenumeration()
    {
        return $this->getRenumerationCash() + $this->getRenumerationCard();
    }

    

    /**
     * Set counted_card
     *
     * @param string $countedCard
     * @return DailyTakeBusinessPremises
     */
    public function setCountedCard($countedCard)
    {
        $this->counted_card = $countedCard;
    
        return $this;
    }

    /**
     * Get counted_card
     *
     * @return string 
     */
    public function getCountedCard()
    {
        return $this->counted_card;
    }

    /**
     * Set daily_take_business_premises_date_created
     *
     * @param \DateTime $dailyTakeBusinessPremisesDateCreated
     * @return DailyTakeBusinessPremises
     */
    public function setDailyTakeBusinessPremisesDateCreated($dailyTakeBusinessPremisesDateCreated)
    {
        $this->daily_take_business_premises_date_created = $dailyTakeBusinessPremisesDateCreated;
    
        return $this;
    }

    /**
     * Get daily_take_business_premises_date_created
     *
     * @return \DateTime 
     */
    public function getDailyTakeBusinessPremisesDateCreated()
    {
        return $this->daily_take_business_premises_date_created;
    }

    /**
     * Set daily_take_business_premises_date_modified
     *
     * @param \DateTime $dailyTakeBusinessPremisesDateModified
     * @return DailyTakeBusinessPremises
     */
    public function setDailyTakeBusinessPremisesDateModified($dailyTakeBusinessPremisesDateModified)
    {
        $this->daily_take_business_premises_date_modified = $dailyTakeBusinessPremisesDateModified;
    
        return $this;
    }

    /**
     * Get daily_take_business_premises_date_modified
     *
     * @return \DateTime 
     */
    public function getDailyTakeBusinessPremisesDateModified()
    {
        return $this->daily_take_business_premises_date_modified;
    }

    /**
     * Set daily_take
     *
     * @param \MilesApart\AdminBundle\Entity\DailyTake $dailyTake
     * @return DailyTakeBusinessPremises
     */
    public function setDailyTake(\MilesApart\AdminBundle\Entity\DailyTake $dailyTake = null)
    {
        $this->daily_take = $dailyTake;
    
        return $this;
    }

    /**
     * Get daily_take
     *
     * @return \MilesApart\AdminBundle\Entity\DailyTake 
     */
    public function getDailyTake()
    {
        return $this->daily_take;
    }

    /**
     * Set business_premises
     *
     * @param \MilesApart\AdminBundle\Entity\BusinessPremises $businessPremises
     * @return DailyTakeBusinessPremises
     */
    public function setBusinessPremises(\MilesApart\AdminBundle\Entity\BusinessPremises $businessPremises = null)
    {
        $this->business_premises = $businessPremises;
    
        return $this;
    }

    /**
     * Get business_premises
     *
     * @return \MilesApart\AdminBundle\Entity\BusinessPremises 
     */
    public function getBusinessPremises()
    {
        return $this->business_premises;
    }

    /**
     * Add daily_take_business_premises_petty_cash
     *
     * @param \MilesApart\AdminBundle\Entity\DailyTakeBusinessPremisesPettyCash $dailyTakeBusinessPremisesPettyCash
     * @return DailyTakeBusinessPremises
     */
    public function addDailyTakeBusinessPremisesPettyCash(\MilesApart\AdminBundle\Entity\DailyTakeBusinessPremisesPettyCash $dailyTakeBusinessPremisesPettyCash)
    {
        $this->daily_take_business_premises_petty_cash[] = $dailyTakeBusinessPremisesPettyCash;
    
        return $this;
    }

    /**
     * Remove daily_take_business_premises_petty_cash
     *
     * @param \MilesApart\AdminBundle\Entity\DailyTakeBusinessPremisesPettyCash $dailyTakeBusinessPremisesPettyCash
     */
    public function removeDailyTakeBusinessPremisesPettyCash(\MilesApart\AdminBundle\Entity\DailyTakeBusinessPremisesPettyCash $dailyTakeBusinessPremisesPettyCash)
    {
        $this->daily_take_business_premises_petty_cash->removeElement($dailyTakeBusinessPremisesPettyCash);
    }

    /**
     * Get daily_take_business_premises_petty_cash
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDailyTakeBusinessPremisesPettyCash()
    {
        return $this->daily_take_business_premises_petty_cash;
    }

    /**
     * Get total_petty_cash
     *
     * @return string 
     */
    public function getTotalPettyCash()
    {
        $total = "0.00";
        foreach ($this->getDailyTakeBusinessPremisesPettyCash() as $key => $value) {
            $total = $total + $value->getPettyCashValue();
        }
            

            return $total;
    }

    /**
     * Get total_employee_payment
     *
     * @return string 
     */
    public function getTotalEmployeePayment()
    {
        $total = "0.00";
        foreach ($this->getEmployeePayment() as $key => $value) {
            $total = $total + $value->getEmployeePaymentTotal();
        }
            

            return $total;
    }

    /**
     * Get total_employee_statutory_payment
     *
     * @return string 
     */
    public function getTotalEmployeeStatutoryPayment()
    {
        $total = "0.00";
        if(count($this->getEmployeeStatutoryPayment()) > 0) {
            foreach ($this->getEmployeeStatutoryPayment() as $key => $value) {
                $total = $total + $value->getEmployeeStatutoryPaymentValue();
            }
        }
            

            return $total;
    }

    /**
     * Get total_deductions
     *
     * @return string 
     */
    public function getTotalDeductions()
    {
       
            $total = $this->getTotalPettyCash() + $this->getTotalEmployeePayment() + $this->getTotalEmployeeStatutoryPayment();
        
            

            return $total;
    }

    /**
     * Add daily_take_business_premises_shop_department
     *
     * @param \MilesApart\AdminBundle\Entity\DailyTakeBusinessPremisesShopDepartment $dailyTakeBusinessPremisesShopDepartment
     * @return DailyTakeBusinessPremises
     */
    public function addDailyTakeBusinessPremisesShopDepartment(\MilesApart\AdminBundle\Entity\DailyTakeBusinessPremisesShopDepartment $dailyTakeBusinessPremisesShopDepartment)
    {
        $this->daily_take_business_premises_shop_department[] = $dailyTakeBusinessPremisesShopDepartment;
    
        return $this;
    }

    /**
     * Remove daily_take_business_premises_shop_department
     *
     * @param \MilesApart\AdminBundle\Entity\DailyTakeBusinessPremisesShopDepartment $dailyTakeBusinessPremisesShopDepartment
     */
    public function removeDailyTakeBusinessPremisesShopDepartment(\MilesApart\AdminBundle\Entity\DailyTakeBusinessPremisesShopDepartment $dailyTakeBusinessPremisesShopDepartment)
    {
        $this->daily_take_business_premises_shop_department->removeElement($dailyTakeBusinessPremisesShopDepartment);
    }

    /**
     * Get daily_take_business_premises_shop_department
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDailyTakeBusinessPremisesShopDepartment()
    {
        return $this->daily_take_business_premises_shop_department;
    }

   
    /**
     * Add employee_payment
     *
     * @param \MilesApart\AdminBundle\Entity\EmployeePayment $employeePayment
     * @return DailyTakeBusinessPremises
     */
    public function addEmployeePayment(\MilesApart\AdminBundle\Entity\EmployeePayment $employeePayment)
    {
        $this->employee_payment[] = $employeePayment;
    
        return $this;
    }

    /**
     * Remove employee_payment
     *
     * @param \MilesApart\AdminBundle\Entity\EmployeePayment $employeePayment
     */
    public function removeEmployeePayment(\MilesApart\AdminBundle\Entity\EmployeePayment $employeePayment)
    {
        $this->employee_payment->removeElement($employeePayment);
    }

    /**
     * Add employee_payment
     *
     * @param \MilesApart\AdminBundle\Entity\EmployeePayment $employeePayment
     * @return DailyTakeBusinessPremises
     */
    public function addEmployooPayment(\MilesApart\AdminBundle\Entity\EmployeePayment $employeePayment)
    {
        $this->employee_payment[] = $employeePayment;
    
        return $this;
    }

    /**
     * Remove employee_payment
     *
     * @param \MilesApart\AdminBundle\Entity\EmployeePayment $employeePayment
     */
    public function removeEmployooPayment(\MilesApart\AdminBundle\Entity\EmployeePayment $employeePayment)
    {
        $this->employee_payment->removeElement($employeePayment);
    }
    
    /**
     * Get employee_payment
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEmployeePayment()
    {
        return $this->employee_payment;
    }

     /**
     * Get daily_take_business_premises_petty_cash_total
     *
     * @return string 
     */
    public function getDailyTakeBusinessPremisesPettyCashTotal()
    {
        $total = "0.00";
        foreach ($this->getDailyTakeBusinessPremises() as $key => $value) {
            $total = $total + $value->getZReadingCash();
        }
            

            return $total;
    }

    /**
     * Add employee_statutory_payment
     *
     * @param \MilesApart\AdminBundle\Entity\EmployeeStatutoryPayment $employeeStatutoryPayment
     * @return DailyTakeBusinessPremises
     */
    public function addEmployooStatutoryPayment(\MilesApart\AdminBundle\Entity\EmployeeStatutoryPayment $employeeStatutoryPayment)
    {
        $this->employee_statutory_payment[] = $employeeStatutoryPayment;
    
        return $this;
    }

    /**
     * Remove employee_statutory_payment
     *
     * @param \MilesApart\AdminBundle\Entity\EmployeeStatutoryPayment $employeeStatutoryPayment
     */
    public function removeEmployooStatutoryPayment(\MilesApart\AdminBundle\Entity\EmployeeStatutoryPayment $employeeStatutoryPayment)
    {
        $this->employee_statutory_payment->removeElement($employeeStatutoryPayment);
    }

    /**
     * Get employee_statutory_payment
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEmployeeStatutoryPayment()
    {
        return $this->employee_statutory_payment;
    }
}