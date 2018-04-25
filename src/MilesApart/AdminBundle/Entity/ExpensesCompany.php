<?php
// src/MilesApart/AdminBundle/Entity/ExpensesCompany.php -- Defines the customer type object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\ExpensesCompanyRepository")
 * @ORM\Table(name="expenses_company")
 * @ORM\HasLifecycleCallbacks()
 */

class ExpensesCompany
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
    protected $expenses_company_name;

    /**
     * @ORM\Column(type="string", length=20, unique=true, nullable=true)
     */
    protected $expenses_company_vat_number;

   /**
     * @ORM\OneToMany(targetEntity="DailyTakeBusinessPremisesPettyCash", mappedBy="expenses_company")
     */
    protected $daily_take_business_premises_petty_cash;

    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //Business Customer Representative Date Of Birth
        $metadata->addPropertyConstraint('expenses_company_name', new Assert\NotBlank());
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
     * Set expenses_company_name
     *
     * @param string $expensesCompanyName
     * @return ExpensesCompany
     */
    public function setExpensesCompanyName($expensesCompanyName)
    {
        $this->expenses_company_name = $expensesCompanyName;
    
        return $this;
    }

    /**
     * Get expenses_company_name
     *
     * @return string 
     */
    public function getExpensesCompanyName()
    {
        return $this->expenses_company_name;
    }

    /**
     * Set expenses_company_vat_number
     *
     * @param string $expensesCompanyVatNumber
     * @return ExpensesCompany
     */
    public function setExpensesCompanyVatNumber($expensesCompanyVatNumber)
    {
        $this->expenses_company_vat_number = $expensesCompanyVatNumber;
    
        return $this;
    }

    /**
     * Get expenses_company_vat_number
     *
     * @return string 
     */
    public function getExpensesCompanyVatNumber()
    {
        return $this->expenses_company_vat_number;
    }

    /**
     * Add daily_take_business_premises_petty_cash
     *
     * @param \MilesApart\AdminBundle\Entity\DailyTakeBusinessPremisesPettyCash $dailyTakeBusinessPremisesPettyCash
     * @return ExpensesCompany
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
}