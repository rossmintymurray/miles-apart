<?php
// src/MilesApart/AdminBundle/Entity/BusinessPremisesType.php -- Defines the business premises type

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\BusinessPremisesTypeRepository")
 * @ORM\Table(name="business_premises_type")
 * @ORM\HasLifecycleCallbacks()
 */

class BusinessPremisesType
{
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
    protected $business_premises_type_name;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    protected $business_premises_is_retail;

    /**
     * @ORM\OneToMany(targetEntity="BusinessPremises", mappedBy="business_premises_type")
     */
    protected $business_premises;
    


    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //Business premises type name
        $metadata->addPropertyConstraint('business_premises_type_name', new Assert\NotBlank());
        $metadata->addPropertyConstraint('business_premises_type_name', new Assert\Length(array(
            'min'        => 2,
            'max'        => 100,
            'minMessage' => 'The business premises type name must be at least {{ limit }} characters length',
            'maxMessage' => 'The business premises type name cannot be longer than {{ limit }} characters length',
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
     * Set business_premises_type_name
     *
     * @param string $businessPremisesTypeName
     * @return BusinessPremisesType
     */
    public function setBusinessPremisesTypeName($businessPremisesTypeName)
    {
        $this->business_premises_type_name = $businessPremisesTypeName;
    
        return $this;
    }

    /**
     * Get business_premises_type_name
     *
     * @return string 
     */
    public function getBusinessPremisesTypeName()
    {
        return $this->business_premises_type_name;
    }

    /**
     * Set business_premises_is_retail
     *
     * @param boolean $businessPremisesIsRetail
     * @return BusinessPremisesType
     */
    public function setBusinessPremisesIsRetail($businessPremisesIsRetail)
    {
        $this->business_premises_is_retail = $businessPremisesIsRetail;
    
        return $this;
    }

    /**
     * Get business_premises_is_retail
     *
     * @return boolean 
     */
    public function getBusinessPremisesIsRetail()
    {
        return $this->business_premises_is_retail;
    }

    /**
     * Add business_premises
     *
     * @param \MilesApart\AdminBundle\Entity\BusinessPremises $businessPremises
     * @return BusinessPremisesType
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
}