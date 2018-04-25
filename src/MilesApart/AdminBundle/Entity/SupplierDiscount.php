<?php
// src/MilesApart/AdminBundle/Entity/SupplierDiscount.php -- Defines the customer type object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\SupplierDiscountRepository")
 * @ORM\Table(name="supplier_discount")
 * @ORM\HasLifecycleCallbacks()
 */

class SupplierDiscount
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
     * @ORM\Column(type="decimal", precision=4, scale=2, nullable=false)
     */
    protected $supplier_discount_percentage;

    /**
     * @ORM\Column(type="date", unique=false, nullable=false)
     */
    protected $supplier_discount_date_valid_from;

    /**
     * @ORM\Column(type="date", unique=false, nullable=true)
     */
    protected $supplier_discount_date_valid_until;

    /**
     * @ORM\ManyToOne(targetEntity="Supplier", inversedBy="supplier_discount")
     * @ORM\JoinTable(name="supplier")
     * @ORM\JoinColumn(name="supplier_id", referencedColumnName="id")
     */
    protected $supplier;



    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //Supplier discount percentage
        $metadata->addPropertyConstraint('supplier_discount_percentage', new Assert\NotBlank());

        //Supplier discount date valid from
        $metadata->addPropertyConstraint('supplier_discount_date_valid_from', new Assert\NotBlank());
        $metadata->addPropertyConstraint('supplier_discount_date_valid_from', new Assert\Date());

        //Supplier discount date valid until
        $metadata->addPropertyConstraint('supplier_discount_date_valid_until', new Assert\Date());

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
     * Set supplier_discount_percentage
     *
     * @param float $supplierDiscountPercentage
     * @return SupplierDiscount
     */
    public function setSupplierDiscountPercentage($supplierDiscountPercentage)
    {
        $this->supplier_discount_percentage = $supplierDiscountPercentage;
    
        return $this;
    }

    /**
     * Get supplier_discount_percentage
     *
     * @return float 
     */
    public function getSupplierDiscountPercentage()
    {
        return $this->supplier_discount_percentage;
    }

    /**
     * Set supplier_discount_date_valid_from
     *
     * @param \DateTime $supplierDiscountDateValidFrom
     * @return SupplierDiscount
     */
    public function setSupplierDiscountDateValidFrom($supplierDiscountDateValidFrom)
    {
        $this->supplier_discount_date_valid_from = $supplierDiscountDateValidFrom;
    
        return $this;
    }

    /**
     * Get supplier_discount_date_valid_from
     *
     * @return \DateTime 
     */
    public function getSupplierDiscountDateValidFrom()
    {
        return $this->supplier_discount_date_valid_from;
    }

    /**
     * Set supplier_discount_date_valid_until
     *
     * @param \DateTime $supplierDiscountDateValidUntil
     * @return SupplierDiscount
     */
    public function setSupplierDiscountDateValidUntil($supplierDiscountDateValidUntil)
    {
        $this->supplier_discount_date_valid_until = $supplierDiscountDateValidUntil;
    
        return $this;
    }

    /**
     * Get supplier_discount_date_valid_until
     *
     * @return \DateTime 
     */
    public function getSupplierDiscountDateValidUntil()
    {
        return $this->supplier_discount_date_valid_until;
    }

    /**
     * Set supplier
     *
     * @param \MilesApart\AdminBundle\Entity\Supplier $supplier
     * @return SupplierDiscount
     */
    public function setSupplier(\MilesApart\AdminBundle\Entity\Supplier $supplier = null)
    {
        $this->supplier = $supplier;
    
        return $this;
    }

    /**
     * Get supplier
     *
     * @return \MilesApart\AdminBundle\Entity\Supplier 
     */
    public function getSupplier()
    {
        return $this->supplier;
    }
}