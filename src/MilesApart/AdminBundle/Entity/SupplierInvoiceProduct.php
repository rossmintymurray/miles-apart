<?php
// src/MilesApart/AdminBundle/Entity/SupplierInvoiceProduct.php -- Defines the customer type object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\SupplierInvoiceProductRepository")
 * @ORM\Table(name="supplier_invoice_product")
 * @ORM\HasLifecycleCallbacks()
 */

class SupplierInvoiceProduct
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
     * @ORM\ManyToOne(targetEntity="SupplierInvoice", inversedBy="supplier_invoice_product")
     * @ORM\JoinTable(name="supplier_invoice")
     * @ORM\JoinColumn(name="supplier_invoice_id", referencedColumnName="id")
     */
    protected $supplier_invoice;

    /**
     * @ORM\ManyToOne(targetEntity="SupplierDeliveryProduct", inversedBy="supplier_invoice_product")
     * @ORM\JoinTable(name="supplier_delivery_product")
     * @ORM\JoinColumn(name="supplier_delivery_product_id", referencedColumnName="id")
     */
    protected $supplier_delivery_product;

   

    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //Employee
        $metadata->addPropertyConstraint('supplier_invoice', new Assert\Choice(array(
            'callback' => 'getSupplierInvoice',
        )));

        //Admin User Type
        $metadata->addPropertyConstraint('supplier_delivery_product', new Assert\Choice(array(
            'callback' => 'getSupplierDeliveryProduct',
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
     * @return SupplierInvoiceProduct
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
     * Set supplier_delivery_product
     *
     * @param \MilesApart\AdminBundle\Entity\SupplierDeliveryProduct $supplierDeliveryProduct
     * @return SupplierInvoiceProduct
     */
    public function setSupplierDeliveryProduct(\MilesApart\AdminBundle\Entity\SupplierDeliveryProduct $supplierDeliveryProduct = null)
    {
        $this->supplier_delivery_product = $supplierDeliveryProduct;
    
        return $this;
    }

    /**
     * Get supplier_delivery_product
     *
     * @return \MilesApart\AdminBundle\Entity\SupplierDeliveryProduct 
     */
    public function getSupplierDeliveryProduct()
    {
        return $this->supplier_delivery_product;
    }
}