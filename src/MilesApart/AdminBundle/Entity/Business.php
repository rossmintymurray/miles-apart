<?php
// src/MilesApart/AdminBundle/Entity/Business.php -- Defines the brand object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\BusinessRepository")
 * @ORM\Table(name="business")
 * @ORM\HasLifecycleCallbacks()
 */

class Business
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
    protected $business_name;

    /**
     * @ORM\Column(type="string", length=20, unique=true, nullable=false)
     */
    protected $business_vat_number;

   /**
     * @ORM\Column(type="string", length=20, unique=true, nullable=false)
     */
    protected $business_registration_number;

    /**
     * @ORM\Column(type="string", length=20, unique=true, nullable=false)
     */
    protected $business_tax_office_reference;

    /**
     * @ORM\OneToMany(targetEntity="BusinessPremises", mappedBy="business")
     */
    protected $business_premises;
    


    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //Business name
        $metadata->addPropertyConstraint('business_name', new Assert\NotBlank());
        $metadata->addPropertyConstraint('business_name', new Assert\Length(array(
            'min'        => 2,
            'max'        => 100,
            'minMessage' => 'The business name must be at least {{ limit }} characters length',
            'maxMessage' => 'The business name cannot be longer than {{ limit }} characters length',
        )));

        //Business VAT number
        $metadata->addPropertyConstraint('business_vat_number', new Assert\NotBlank());
        $metadata->addPropertyConstraint('business_vat_number', new Assert\Length(array(
            'min'        => 2,
            'max'        => 20,
            'minMessage' => 'The business VAT number must be at least {{ limit }} characters length',
            'maxMessage' => 'The business VAT number cannot be longer than {{ limit }} characters length',
        )));

        //Business registration number
        $metadata->addPropertyConstraint('business_registration_number', new Assert\NotBlank());
        $metadata->addPropertyConstraint('business_registration_number', new Assert\Length(array(
            'min'        => 2,
            'max'        => 20,
            'minMessage' => 'The business registration number must be at least {{ limit }} characters length',
            'maxMessage' => 'The business registration number cannot be longer than {{ limit }} characters length',
        )));

        //Business tax office reference
        $metadata->addPropertyConstraint('business_tax_office_reference', new Assert\NotBlank());
        $metadata->addPropertyConstraint('business_tax_office_reference', new Assert\Length(array(
            'min'        => 2,
            'max'        => 20,
            'minMessage' => 'The business tax office reference must be at least {{ limit }} characters length',
            'maxMessage' => 'The business tax office reference cannot be longer than {{ limit }} characters length',
        )));
    }   

    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->business_premises = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set business_name
     *
     * @param string $businessName
     * @return Business
     */
    public function setBusinessName($businessName)
    {
        $this->business_name = $businessName;
    
        return $this;
    }

    /**
     * Get business_name
     *
     * @return string 
     */
    public function getBusinessName()
    {
        return $this->business_name;
    }

    /**
     * Set business_vat_number
     *
     * @param string $businessVatNumber
     * @return Business
     */
    public function setBusinessVatNumber($businessVatNumber)
    {
        $this->business_vat_number = $businessVatNumber;
    
        return $this;
    }

    /**
     * Get business_vat_number
     *
     * @return string 
     */
    public function getBusinessVatNumber()
    {
        return $this->business_vat_number;
    }

    /**
     * Set business_registration_number
     *
     * @param string $businessRegistrationNumber
     * @return Business
     */
    public function setBusinessRegistrationNumber($businessRegistrationNumber)
    {
        $this->business_registration_number = $businessRegistrationNumber;
    
        return $this;
    }

    /**
     * Get business_registration_number
     *
     * @return string 
     */
    public function getBusinessRegistrationNumber()
    {
        return $this->business_registration_number;
    }

    /**
     * Set business_tax_office_reference
     *
     * @param string $businessTaxOfficeReference
     * @return Business
     */
    public function setBusinessTaxOfficeReference($businessTaxOfficeReference)
    {
        $this->business_tax_office_reference = $businessTaxOfficeReference;
    
        return $this;
    }

    /**
     * Get business_tax_office_reference
     *
     * @return string 
     */
    public function getBusinessTaxOfficeReference()
    {
        return $this->business_tax_office_reference;
    }

    /**
     * Add business_premises
     *
     * @param \MilesApart\AdminBundle\Entity\BusinessPremises $businessPremises
     * @return Business
     */
    public function addBusinessPremise(\MilesApart\AdminBundle\Entity\BusinessPremises $businessPremises)
    {
        $this->business_premises[] = $businessPremises;
    
        return $this;
    }

    /**
     * Remove business_premises
     *
     * @param \MilesApart\AdminBundle\Entity\BusinessPremises $businessPremises
     */
    public function removeBusinessPremise(\MilesApart\AdminBundle\Entity\BusinessPremises $businessPremises)
    {
        $this->business_premises->removeElement($businessPremises);
    }

    /**
     * Get business_premises
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getBusinessPremises()
    {
        return $this->business_premises;
    }

    /**
     * Get period_z_reading_cash
     *
     * @return string 
     */
    public function getPeriodZReadingCash()
    {
        $total = "0.00";
        foreach ($this->getBusinessPremises() as $key => $value) {
            foreach ($value->getDailyTakeBusinessPremises() as $key => $value2) {
                $total = $total + $value2->getZReadingCash();
            }
        }
        
        return $total;
    }

    /**
     * Get period_z_reading_card
     *
     * @return string 
     */
    public function getPeriodZReadingCard()
    {
        $total = "0.00";
        foreach ($this->getBusinessPremises() as $key => $value) {
            foreach ($value->getDailyTakeBusinessPremises() as $key => $value2) {
                $total = $total + $value2->getZReadingCard();
            }
        }
        
        return $total;
    }

    /**
     * Get period_total_z
     *
     * @return string 
     */
    public function getPeriodTotalZ()
    {
        $total = "0.00";
        foreach ($this->getBusinessPremises() as $key => $value) {
            foreach ($value->getDailyTakeBusinessPremises() as $key => $value2) {
                $total = $total + $value2->getTotalZ();
            }
        }
        
        return $total;
    }

    /**
     * Get period_total_renumeration
     *
     * @return string 
     */
    public function getPeriodTotalRenumeration()
    {
        $total = "0.00";
        foreach ($this->getBusinessPremises() as $key => $value) {
            foreach ($value->getDailyTakeBusinessPremises() as $key => $value2) {
                $total = $total + $value2->getTotalRenumeration();
            }
        }
        
        return $total;
    }

    /**
     * Get period_wage_bill
     *
     * @return string 
     */
    public function getPeriodWageBill()
    {
        $total = "0.00";
        foreach ($this->getBusinessPremises() as $key => $value) {
            foreach ($value->getDailyTakeBusinessPremises() as $key => $value2) {
                foreach ($value2->getEmployeePayment() as $key => $value3) {
                    $total = $total + $value3->getEmployeePaymentTotal();
                }
            }
        }
        
        return $total;
    }

    /**
     * Get period_total_petty_cash
     *
     * @return string 
     */
    public function getPeriodTotalPettyCash()
    {
        $total = "0.00";
        foreach ($this->getBusinessPremises() as $key => $value) {
            foreach ($value->getDailyTakeBusinessPremises() as $key => $value2) {
                foreach ($value2->getDailyTakeBusinessPremisesPettyCash() as $key => $value3) {
                    $total = $total + $value3->getPettyCashValue();
                }
            }
        }
        
        return $total;
    }
}