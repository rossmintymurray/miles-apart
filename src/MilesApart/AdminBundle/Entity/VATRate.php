<?php
// src/MilesApart/AdminBundle/Entity/VATRate.php -- Defines the VAT rate object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\VATRateRepository")
 * @ORM\Table(name="vat_rate")
 * @ORM\HasLifecycleCallbacks()
 */

class VATRate
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
     * @ORM\ManyToOne(targetEntity="VATRateType", inversedBy="vat_rate")
     * @ORM\JoinTable(name="vat_rate_type")
     * @ORM\JoinColumn(name="vat_rate_type_id", referencedColumnName="id")
     */
    protected $vat_rate_type;

    /**
     * @ORM\Column(type="decimal", precision=4, scale=2, nullable=false)
     */
    protected $vat_rate_value;

    /**
     * @ORM\Column(type="date", unique=false, nullable=false)
     */
    protected $vat_effective_date;



    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //VAT rate value
        $metadata->addPropertyConstraint('vat_rate_value', new Assert\NotBlank());

        //VAT effective date
        $metadata->addPropertyConstraint('vat_effective_date', new Assert\NotBlank());
        $metadata->addPropertyConstraint('vat_effective_date', new Assert\Date());

        
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
     * Set vat_rate_value
     *
     * @param float $vatRateValue
     * @return VATRate
     */
    public function setVatRateValue($vatRateValue)
    {
        $this->vat_rate_value = $vatRateValue;
    
        return $this;
    }

    /**
     * Get vat_rate_value
     *
     * @return float 
     */
    public function getVatRateValue()
    {
        return $this->vat_rate_value;
    }

    /**
     * Set vat_effective_date
     *
     * @param \DateTime $vatEffectiveDate
     * @return VATRate
     */
    public function setVatEffectiveDate($vatEffectiveDate)
    {
        $this->vat_effective_date = $vatEffectiveDate;
    
        return $this;
    }

    /**
     * Get vat_effective_date
     *
     * @return \DateTime 
     */
    public function getVatEffectiveDate()
    {
        return $this->vat_effective_date;
    }

    /**
     * Set vat_rate_type
     *
     * @param \MilesApart\AdminBundle\Entity\VATRateType $vatRateType
     * @return VATRate
     */
    public function setVatRateType(\MilesApart\AdminBundle\Entity\VATRateType $vatRateType = null)
    {
        $this->vat_rate_type = $vatRateType;
    
        return $this;
    }

    /**
     * Get vat_rate_type
     *
     * @return \MilesApart\AdminBundle\Entity\VATRateType 
     */
    public function getVatRateType()
    {
        return $this->vat_rate_type;
    }
}