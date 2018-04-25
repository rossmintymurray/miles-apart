<?php
// src/MilesApart/AdminBundle/Entity/DailyTakeBusinessPremisesPettyCash.php -- Defines the customer type object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\DailyTakeBusinessPremisesPettyCashRepository")
 * @ORM\Table(name="daily_take_business_premises_petty_cash")
 * @ORM\HasLifecycleCallbacks()
 */

class DailyTakeBusinessPremisesPettyCash
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
     * @ORM\ManyToOne(targetEntity="ExpensesType", inversedBy="daily_take_business_premises_petty_cash")
     * @ORM\JoinTable(name="expenses_type")
     * @ORM\JoinColumn(name="expenses_type_id", referencedColumnName="id")
     */
    protected $expenses_type;

    /**
     * @ORM\ManyToOne(targetEntity="ExpensesCompany", inversedBy="daily_take_business_premises_petty_cash")
     * @ORM\JoinTable(name="expenses_company")
     * @ORM\JoinColumn(name="expenses_company_id", referencedColumnName="id")
     */
    protected $expenses_company;

    /**
     * @ORM\ManyToOne(targetEntity="DailyTakeBusinessPremises", inversedBy="daily_take_business_premises_petty_cash", cascade={"persist"})
     * @ORM\JoinTable(name="daily_take_business_premises")
     * @ORM\JoinColumn(name="daily_take_business_premises_id", referencedColumnName="id")
     */
    protected $daily_take_business_premises;

    /**
     * @ORM\Column(type="decimal", precision=6, scale=2, nullable=false)
     */
    protected $petty_cash_value;

    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //Business Customer Representative Date Of Birth
        $metadata->addPropertyConstraint('petty_cash_value', new Assert\NotBlank());
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
     * Set petty_cash_value
     *
     * @param string $pettyCashValue
     * @return DailyTakeBusinessPremisesPettyCash
     */
    public function setPettyCashValue($pettyCashValue)
    {
        $this->petty_cash_value = $pettyCashValue;
    
        return $this;
    }

    /**
     * Get petty_cash_value
     *
     * @return string 
     */
    public function getPettyCashValue()
    {
        return $this->petty_cash_value;
    }

    /**
     * Set expenses_type
     *
     * @param \MilesApart\AdminBundle\Entity\ExpensesType $expensesType
     * @return DailyTakeBusinessPremisesPettyCash
     */
    public function setExpensesType(\MilesApart\AdminBundle\Entity\ExpensesType $expensesType = null)
    {
        $this->expenses_type = $expensesType;
    
        return $this;
    }

    /**
     * Get expenses_type
     *
     * @return \MilesApart\AdminBundle\Entity\ExpensesType 
     */
    public function getExpensesType()
    {
        return $this->expenses_type;
    }

    /**
     * Set expenses_company
     *
     * @param \MilesApart\AdminBundle\Entity\ExpensesCompany $expensesCompany
     * @return DailyTakeBusinessPremisesPettyCash
     */
    public function setExpensesCompany(\MilesApart\AdminBundle\Entity\ExpensesCompany $expensesCompany = null)
    {
        $this->expenses_company = $expensesCompany;
    
        return $this;
    }

    /**
     * Get expenses_company
     *
     * @return \MilesApart\AdminBundle\Entity\ExpensesCompany 
     */
    public function getExpensesCompany()
    {
        return $this->expenses_company;
    }

    /**
     * Set daily_take_business_premises
     *
     * @param \MilesApart\AdminBundle\Entity\DailyTakeBusinessPremises $dailyTakeBusinessPremises
     * @return DailyTakeBusinessPremisesPettyCash
     */
    public function setDailyTakeBusinessPremises(\MilesApart\AdminBundle\Entity\DailyTakeBusinessPremises $dailyTakeBusinessPremises = null)
    {
        $this->daily_take_business_premises = $dailyTakeBusinessPremises;
    
        return $this;
    }

    /**
     * Get daily_take_business_premises
     *
     * @return \MilesApart\AdminBundle\Entity\DailyTakeBusinessPremises 
     */
    public function getDailyTakeBusinessPremises()
    {
        return $this->daily_take_business_premises;
    }
}