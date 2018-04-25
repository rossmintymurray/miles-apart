<?php
// src/MilesApart/AdminBundle/Entity/ExpensesType.php -- Defines the customer type object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\ExpensesTypeRepository")
 * @ORM\Table(name="expenses_type")
 * @ORM\HasLifecycleCallbacks()
 */

class ExpensesType
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
     * @ORM\Column(type="string", length=100, unique=true, nullable=false)
     */
    protected $expenses_type_name;

    /**
     * @ORM\OneToMany(targetEntity="DailyTakeBusinessPremisesPettyCash", mappedBy="expenses_type")
     */
    protected $daily_take_business_premises_petty_cash;

    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //Business Customer Representative Date Of Birth
        $metadata->addPropertyConstraint('expenses_type_name', new Assert\NotBlank());
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->daily_take_business_premises_petty_cash = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set expenses_type_name
     *
     * @param string $expensesTypeName
     * @return ExpensesType
     */
    public function setExpensesTypeName($expensesTypeName)
    {
        $this->expenses_type_name = $expensesTypeName;
    
        return $this;
    }

    /**
     * Get expenses_type_name
     *
     * @return string 
     */
    public function getExpensesTypeName()
    {
        return $this->expenses_type_name;
    }

    /**
     * Add daily_take_business_premises_petty_cash
     *
     * @param \MilesApart\AdminBundle\Entity\DailyTakeBusinessPremisesPettyCash $dailyTakeBusinessPremisesPettyCash
     * @return ExpensesType
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
     * Get period_total_petty_cash_type
     *
     * @return string 
     */
    public function getPeriodTotalPettyCashType()
    {
        $total = "0.00";
        foreach ($this->getDailyTakeBusinessPremisesPettyCash() as $key => $value) {
            
            $total = $total + $value->getPettyCashValue();
            
        }
        
        return $total;
    }
}