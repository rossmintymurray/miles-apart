<?php
// src/MilesApart/AdminBundle/Entity/CustomerType.php -- Defines the customer type object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\CustomerTypeRepository")
 * @ORM\Table(name="customer_type")
 * @ORM\HasLifecycleCallbacks()
 */

class CustomerType
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
    protected $customer_type_name;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $vat_invoice_default;

    /**
     * @ORM\OneToMany(targetEntity="Customer", mappedBy="customer_type")
     */
     protected $customer;



    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //Custeomr type name
        $metadata->addPropertyConstraint('customer_type_name', new Assert\NotBlank());
        $metadata->addPropertyConstraint('customer_type_name', new Assert\Length(array(
            'min'        => 2,
            'max'        => 20,
            'minMessage' => 'The customer type name must be at least {{ limit }} characters length',
            'maxMessage' => 'The customer type name cannot be longer than {{ limit }} characters length',
        )));
    }



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->customer = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set customer_type_name
     *
     * @param string $customerTypeName
     * @return CustomerType
     */
    public function setCustomerTypeName($customerTypeName)
    {
        $this->customer_type_name = $customerTypeName;
    
        return $this;
    }

    /**
     * Get customer_type_name
     *
     * @return string 
     */
    public function getCustomerTypeName()
    {
        return $this->customer_type_name;
    }

    /**
     * Set vat_invoice_default
     *
     * @param boolean $vatInvoiceDefault
     * @return CustomerType
     */
    public function setVatInvoiceDefault($vatInvoiceDefault)
    {
        $this->vat_invoice_default = $vatInvoiceDefault;
    
        return $this;
    }

    /**
     * Get vat_invoice_default
     *
     * @return boolean 
     */
    public function getVatInvoiceDefault()
    {
        return $this->vat_invoice_default;
    }

    /**
     * Add customer
     *
     * @param \MilesApart\AdminBundle\Entity\Customer $customer
     * @return CustomerType
     */
    public function addCustomer(\MilesApart\AdminBundle\Entity\Customer $customer)
    {
        $this->customer[] = $customer;
    
        return $this;
    }

    /**
     * Remove customer
     *
     * @param \MilesApart\AdminBundle\Entity\Customer $customer
     */
    public function removeCustomer(\MilesApart\AdminBundle\Entity\Customer $customer)
    {
        $this->customer->removeElement($customer);
    }

    /**
     * Get customer
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCustomer()
    {
        return $this->customer;
    }
}