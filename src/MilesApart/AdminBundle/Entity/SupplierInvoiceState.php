<?php
// src/MilesApart/AdminBundle/Entity/SupplierInvoiceState.php -- Defines the customer type object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\SupplierInvoiceStateRepository")
 * @ORM\Table(name="supplier_invoice_state")
 * @ORM\HasLifecycleCallbacks()
 */

class SupplierInvoiceState
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
    protected $supplier_invoice_state_name;

    /**
     * @ORM\OneToMany(targetEntity="SupplierInvoice", mappedBy="supplier_invoice_state")
     */
    protected $supplier_invoice;



    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //Admin User Username
        $metadata->addPropertyConstraint('supplier_invoice_state_name', new Assert\NotBlank());
        $metadata->addPropertyConstraint('supplier_invoice_state_name', new Assert\Length(array(
            'min'        => 2,
            'max'        => 100,
            'minMessage' => 'The invoice state name must be at least {{ limit }} characters length',
            'maxMessage' => 'The invoice state name cannot be longer than {{ limit }} characters length',
        )));
    }



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->supplier_invoice = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set supplier_invoice_state_name
     *
     * @param string $supplierInvoiceStateName
     * @return SupplierInvoiceState
     */
    public function setSupplierInvoiceStateName($supplierInvoiceStateName)
    {
        $this->supplier_invoice_state_name = $supplierInvoiceStateName;
    
        return $this;
    }

    /**
     * Get supplier_invoice_state_name
     *
     * @return string 
     */
    public function getSupplierInvoiceStateName()
    {
        return $this->supplier_invoice_state_name;
    }

    /**
     * Add supplier_invoice
     *
     * @param \MilesApart\AdminBundle\Entity\SupplierInvoice $supplierInvoice
     * @return SupplierInvoiceState
     */
    public function addSupplierInvoice(\MilesApart\AdminBundle\Entity\SupplierInvoice $supplierInvoice)
    {
        $this->supplier_invoice[] = $supplierInvoice;
    
        return $this;
    }

    /**
     * Remove supplier_invoice
     *
     * @param \MilesApart\AdminBundle\Entity\SupplierInvoice $supplierInvoice
     */
    public function removeSupplierInvoice(\MilesApart\AdminBundle\Entity\SupplierInvoice $supplierInvoice)
    {
        $this->supplier_invoice->removeElement($supplierInvoice);
    }

    /**
     * Get supplier_invoice
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSupplierInvoice()
    {
        return $this->supplier_invoice;
    }
}