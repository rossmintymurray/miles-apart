<?php
// src/MilesApart/AdminBundle/Entity/ProductCost.php -- Defines the product question object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\ProductCostRepository")
 * @ORM\Table(name="product_cost")
 * @ORM\HasLifecycleCallbacks()
 */

class ProductCost
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
     * @ORM\ManyToOne(targetEntity="ProductSupplier", inversedBy="product_cost", cascade={"persist"})
     * @ORM\JoinTable(name="product_supplier")
     * @ORM\JoinColumn(name="product_supplier_id", referencedColumnName="id")
     */
    protected $product_supplier;

    /**
     * @ORM\Column(type="date", unique=false, nullable=false)
     */
    protected $product_cost_valid_from;

    /**
     * @ORM\Column(type="date", unique=false, nullable=true)
     */
    protected $product_cost_valid_until;

    /**
     * @ORM\Column(type="decimal", precision=4, scale=2, nullable=false)
     */
    protected $product_cost_value;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $product_cost_is_special;

    /**
     * @ORM\Column(type="datetime", unique=false, nullable=true)
     */
    protected $product_cost_date_created;

    /**
     * @ORM\Column(type="datetime", unique=false, nullable=false)
     */
    protected $product_cost_date_modified;



    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        $this->setProductCostDateModified(new \DateTime(date('Y-m-d H:i:s')));

        if($this->getProductCostDateCreated() == null)
        {
            $this->setProductCostDateCreated(new \DateTime(date('Y-m-d H:i:s')));
        }
    }



    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        

        //Product cost valid from
        $metadata->addPropertyConstraint('product_cost_valid_from', new Assert\NotBlank());
        $metadata->addPropertyConstraint('product_cost_valid_from', new Assert\Date());

        //Product cost valid until
        $metadata->addPropertyConstraint('product_cost_valid_until', new Assert\NotBlank());
        $metadata->addPropertyConstraint('product_cost_valid_until', new Assert\Date());

        //Product cost value
        $metadata->addPropertyConstraint('product_cost_value', new Assert\NotBlank());
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
     * Set product_cost_valid_from
     *
     * @param \DateTime $productCostValidFrom
     * @return ProductCost
     */
    public function setProductCostValidFrom($productCostValidFrom)
    {
        $this->product_cost_valid_from = $productCostValidFrom;
    
        return $this;
    }

    /**
     * Get product_cost_valid_from
     *
     * @return \DateTime 
     */
    public function getProductCostValidFrom()
    {
        return $this->product_cost_valid_from;
    }

    /**
     * Set product_cost_valid_until
     *
     * @param \DateTime $productCostValidUntil
     * @return ProductCost
     */
    public function setProductCostValidUntil($productCostValidUntil)
    {
        $this->product_cost_valid_until = $productCostValidUntil;
    
        return $this;
    }

    /**
     * Get product_cost_valid_until
     *
     * @return \DateTime 
     */
    public function getProductCostValidUntil()
    {
        return $this->product_cost_valid_until;
    }

    /**
     * Set product_cost_value
     *
     * @param float $productCostValue
     * @return ProductCost
     */
    public function setProductCostValue($productCostValue)
    {
        $this->product_cost_value = $productCostValue;
    
        return $this;
    }

    /**
     * Get product_cost_value
     *
     * @return float 
     */
    public function getProductCostValue()
    {
        return $this->product_cost_value;
    }

    /**
     * Set product_cost_is_special
     *
     * @param boolean $productCostIsSpecial
     * @return ProductCost
     */
    public function setProductCostIsSpecial($productCostIsSpecial)
    {
        $this->product_cost_is_special = $productCostIsSpecial;
    
        return $this;
    }

    /**
     * Get product_cost_is_special
     *
     * @return boolean 
     */
    public function getProductCostIsSpecial()
    {
        return $this->product_cost_is_special;
    }

    /**
     * Set product_cost_date_created
     *
     * @param \DateTime $productCostDateCreated
     * @return ProductCost
     */
    public function setProductCostDateCreated($productCostDateCreated)
    {
        $this->product_cost_date_created = $productCostDateCreated;
    
        return $this;
    }

    /**
     * Get product_cost_date_created
     *
     * @return \DateTime 
     */
    public function getProductCostDateCreated()
    {
        return $this->product_cost_date_created;
    }

    /**
     * Set product_cost_date_modified
     *
     * @param \DateTime $productCostDateModified
     * @return ProductCost
     */
    public function setProductCostDateModified($productCostDateModified)
    {
        $this->product_cost_date_modified = $productCostDateModified;
    
        return $this;
    }

    /**
     * Get product_cost_date_modified
     *
     * @return \DateTime 
     */
    public function getProductCostDateModified()
    {
        return $this->product_cost_date_modified;
    }

    /**
     * Set product_supplier
     *
     * @param \MilesApart\AdminBundle\Entity\ProductSupplier $productSupplier
     * @return ProductCost
     */
    public function setProductSupplier(\MilesApart\AdminBundle\Entity\ProductSupplier $productSupplier = null)
    {
        $this->product_supplier = $productSupplier;
    
        return $this;
    }

    /**
     * Get product_supplier
     *
     * @return \MilesApart\AdminBundle\Entity\ProductSupplier 
     */
    public function getProductSupplier()
    {
        return $this->product_supplier;
    }

}