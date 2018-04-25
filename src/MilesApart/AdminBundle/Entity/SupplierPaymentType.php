<?php
// src/MilesApart/AdminBundle/Entity/SupplierPaymentType.php -- Defines the customer type object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\SupplierPaymentTypeRepository")
 * @ORM\Table(name="supplier_payment_type")
 * @ORM\HasLifecycleCallbacks()
 */

class SupplierPaymentType
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
     * @ORM\Column(type="string", length=100, unique=false, nullable=false)
     */
    protected $supplier_payment_type_name;

    /**
     * @ORM\OneToMany(targetEntity="SupplierPayment", mappedBy="supplier_payment_type")
     */
    protected $supplier_payment;



    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //Supplier payment type name
        $metadata->addPropertyConstraint('supplier_payment_type_name', new Assert\NotBlank());
        $metadata->addPropertyConstraint('supplier_payment_type_name', new Assert\Length(array(
            'min'        => 2,
            'max'        => 100,
            'minMessage' => 'The supplier type payment name must be at least {{ limit }} characters length',
            'maxMessage' => 'The supplier payment type name cannot be longer than {{ limit }} characters length',
        )));
    }



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->supplier_payment = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set supplier_payment_type_name
     *
     * @param string $supplierPaymentTypeName
     * @return SupplierPaymentType
     */
    public function setSupplierPaymentTypeName($supplierPaymentTypeName)
    {
        $this->supplier_payment_type_name = $supplierPaymentTypeName;
    
        return $this;
    }

    /**
     * Get supplier_payment_type_name
     *
     * @return string 
     */
    public function getSupplierPaymentTypeName()
    {
        return $this->supplier_payment_type_name;
    }

    /**
     * Add supplier_payment
     *
     * @param \MilesApart\AdminBundle\Entity\SupplierPayment $supplierPayment
     * @return SupplierPaymentType
     */
    public function addSupplierPayment(\MilesApart\AdminBundle\Entity\SupplierPayment $supplierPayment)
    {
        $this->supplier_payment[] = $supplierPayment;
    
        return $this;
    }

    /**
     * Remove supplier_payment
     *
     * @param \MilesApart\AdminBundle\Entity\SupplierPayment $supplierPayment
     */
    public function removeSupplierPayment(\MilesApart\AdminBundle\Entity\SupplierPayment $supplierPayment)
    {
        $this->supplier_payment->removeElement($supplierPayment);
    }

    /**
     * Get supplier_payment
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSupplierPayment()
    {
        return $this->supplier_payment;
    }
}