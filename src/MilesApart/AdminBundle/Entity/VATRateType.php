<?php
// src/MilesApart/AdminBundle/Entity/VATRateType.php -- Defines the VAT rate type object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\VATRateTypeRepository")
 * @ORM\Table(name="vat_rate_type")
 * @ORM\HasLifecycleCallbacks()
 */

class VATRateType
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
     * @ORM\Column(type="string", length=20, unique=true, nullable=false)
     */
    protected $vat_rate_type_name;

    /**
     * @ORM\OneToMany(targetEntity="Product", mappedBy="vat_rate_type")
     */
    protected $product;

    /**
     * @ORM\OneToMany(targetEntity="VATRate", mappedBy="vat_rate_type")
     */
    protected $vat_rate;



    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //VAT rate type name
        $metadata->addPropertyConstraint('vat_rate_type_name', new Assert\NotBlank());
        $metadata->addPropertyConstraint('vat_rate_type_name', new Assert\Length(array(
            'min'        => 2,
            'max'        => 20,
            'minMessage' => 'The VAT rate type name must be at least {{ limit }} characters length',
            'maxMessage' => 'The VAT rate type name cannot be longer than {{ limit }} characters length',
        )));
    }



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->product = new \Doctrine\Common\Collections\ArrayCollection();
        $this->vat_rate = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set vat_rate_type_name
     *
     * @param string $vatRateTypeName
     * @return VATRateType
     */
    public function setVatRateTypeName($vatRateTypeName)
    {
        $this->vat_rate_type_name = $vatRateTypeName;
    
        return $this;
    }

    /**
     * Get vat_rate_type_name
     *
     * @return string 
     */
    public function getVatRateTypeName()
    {
        return $this->vat_rate_type_name;
    }

    /**
     * Add product
     *
     * @param \MilesApart\AdminBundle\Entity\Product $product
     * @return VATRateType
     */
    public function addProduct(\MilesApart\AdminBundle\Entity\Product $product)
    {
        $this->product[] = $product;
    
        return $this;
    }

    /**
     * Remove product
     *
     * @param \MilesApart\AdminBundle\Entity\Product $product
     */
    public function removeProduct(\MilesApart\AdminBundle\Entity\Product $product)
    {
        $this->product->removeElement($product);
    }

    /**
     * Get product
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Add vat_rate
     *
     * @param \MilesApart\AdminBundle\Entity\VATRate $vatRate
     * @return VATRateType
     */
    public function addVatRate(\MilesApart\AdminBundle\Entity\VATRate $vatRate)
    {
        $this->vat_rate[] = $vatRate;
    
        return $this;
    }

    /**
     * Remove vat_rate
     *
     * @param \MilesApart\AdminBundle\Entity\VATRate $vatRate
     */
    public function removeVatRate(\MilesApart\AdminBundle\Entity\VATRate $vatRate)
    {
        $this->vat_rate->removeElement($vatRate);
    }

    /**
     * Get vat_rate
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getVatRate()
    {
        return $this->vat_rate;
    }
}