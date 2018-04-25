<?php
// src/MilesApart/AdminBundle/Entity/SupplierInvoicePayment.php -- Defines the customer type object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\SupplierInvoicePaymentRepository")
 * @ORM\Table(name="supplier_invoice_payment")
 * @ORM\HasLifecycleCallbacks()
 */

class SupplierInvoicePayment
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
     * @ORM\ManyToOne(targetEntity="SupplierInvoice", inversedBy="supplier_invoice_payment")
     * @ORM\JoinTable(name="supplier_invoice")
     * @ORM\JoinColumn(name="supplier_invoice_id", referencedColumnName="id")
     */
    protected $supplier_invoice;

    /**
     * @ORM\ManyToOne(targetEntity="SupplierPayment", inversedBy="supplier_invoice_payment")
     * @ORM\JoinTable(name="supplier_payment")
     * @ORM\JoinColumn(name="supplier_payment_id", referencedColumnName="id")
     */
    protected $supplier_payment;



    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //Employee
        $metadata->addPropertyConstraint('supplier_invoice', new Assert\Choice(array(
            'callback' => 'getSupplierInvoice',
        )));

        //Admin User Type
        $metadata->addPropertyConstraint('supplier_payment', new Assert\Choice(array(
            'callback' => 'getSupplierPayment',
        )));
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
     * Set supplier_invoice
     *
     * @param \MilesApart\AdminBundle\Entity\SupplierInvoice $supplierInvoice
     * @return SupplierInvoicePayment
     */
    public function setSupplierInvoice(\MilesApart\AdminBundle\Entity\SupplierInvoice $supplierInvoice = null)
    {
        $this->supplier_invoice = $supplierInvoice;
    
        return $this;
    }

    /**
     * Get supplier_invoice
     *
     * @return \MilesApart\AdminBundle\Entity\SupplierInvoice 
     */
    public function getSupplierInvoice()
    {
        return $this->supplier_invoice;
    }

    /**
     * Set supplier_payment
     *
     * @param \MilesApart\AdminBundle\Entity\SupplierPayment $supplierPayment
     * @return SupplierInvoicePayment
     */
    public function setSupplierPayment(\MilesApart\AdminBundle\Entity\SupplierPayment $supplierPayment = null)
    {
        $this->supplier_payment = $supplierPayment;
    
        return $this;
    }

    /**
     * Get supplier_payment
     *
     * @return \MilesApart\AdminBundle\Entity\SupplierPayment 
     */
    public function getSupplierPayment()
    {
        return $this->supplier_payment;
    }
}