<?php
// src/MilesApart/AdminBundle/Entity/BusinessCustomer.php -- Defines the customer type object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\BusinessCustomerRepository")
 * @ORM\Table(name="business_customer")
 * @ORM\HasLifecycleCallbacks()
 */

class BusinessCustomer
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
    protected $business_customer_name;

    /**
     * @ORM\Column(type="string", length=15, unique=false, nullable=true)
     */
    protected $business_customer_vat_number;

    /**
     * @ORM\OneToOne(targetEntity="Customer", inversedBy="business_customer", cascade={"persist"})
     * @ORM\JoinTable(name="customer")
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id")
     */
    protected $customer;

    /**
     * @ORM\OneToMany(targetEntity="BusinessCustomerRepresentative", mappedBy="business_customer", cascade={"persist"})
     */
    protected $business_customer_representative;

    /**
     * @ORM\Column(type="datetime", unique=false, nullable=false)
     */
    protected $business_customer_date_created;

    /**
     * @ORM\Column(type="datetime", unique=false, nullable=true)
     */
    protected $business_customer_date_modified;

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        $this->setBusinessCustomerDateModified(new \DateTime(date('Y-m-d H:i:s')));

        if($this->getBusinessCustomerDateCreated() == null)
        {
            $this->setBusinessCustomerDateCreated(new \DateTime(date('Y-m-d H:i:s')));
        }
    }

    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //Business customer name
        $metadata->addPropertyConstraint('business_customer_name', new Assert\NotBlank(array(
            'groups' => array('business_customer'),
        )));
        $metadata->addPropertyConstraint('business_customer_name', new Assert\Length(array(
            'min'        => 2,
            'max'        => 100,
            'minMessage' => 'The business customer name must be at least {{ limit }} characters length',
            'maxMessage' => 'The business customer name cannot be longer than {{ limit }} characters length',
            'groups' => array('business_customer'),
        )));

        //Business customer vat number
        $metadata->addPropertyConstraint('business_customer_vat_number', new Assert\Length(array(
            'min'        => 2,
            'max'        => 15,
            'minMessage' => 'The business customer vat number name must be at least {{ limit }} characters length',
            'maxMessage' => 'The business customer vat number name cannot be longer than {{ limit }} characters length',
            'groups' => array('business_customer'),
        )));

    }


    


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->business_customer_representative = new \Doctrine\Common\Collections\ArrayCollection();
        
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
     * Set business_customer_date_created
     *
     * @param \DateTime $businessCustomerDateCreated
     * @return BusinessCustomer
     */
    public function setBusinessCustomerDateCreated($businessCustomerDateCreated)
    {
        $this->business_customer_date_created = $businessCustomerDateCreated;

        return $this;
    }

    /**
     * Get business_customer_date_created
     *
     * @return \DateTime
     */
    public function getBusinessCustomerDateCreated()
    {
        return $this->business_customer_date_created;
    }

    /**
     * Set business_customer_date_modified
     *
     * @param \DateTime $businessCustomerDateModified
     * @return BusinessCustomer
     */
    public function setBusinessCustomerDateModified($businessCustomerDateModified)
    {
        $this->business_customer_date_modified = $businessCustomerDateModified;

        return $this;
    }

    /**
     * Get business_customer_date_modified
     *
     * @return \DateTime
     */
    public function getBusinessCustomerDateModified()
    {
        return $this->business_customer_date_modified;
    }

    /**
     * Set business_customer_name
     *
     * @param string $businessCustomerName
     * @return BusinessCustomer
     */
    public function setBusinessCustomerName($businessCustomerName)
    {
        $this->business_customer_name = $businessCustomerName;

        return $this;
    }

    /**
     * Get business_customer_name
     *
     * @return string 
     */
    public function getBusinessCustomerName()
    {
        return $this->business_customer_name;
    }

    /**
     * Set business_customer_vat_number
     *
     * @param string $businessCustomerVatNumber
     * @return BusinessCustomer
     */
    public function setBusinessCustomerVatNumber($businessCustomerVatNumber)
    {
        $this->business_customer_vat_number = $businessCustomerVatNumber;

        return $this;
    }

    /**
     * Get business_customer_vat_number
     *
     * @return string 
     */
    public function getBusinessCustomerVatNumber()
    {
        return $this->business_customer_vat_number;
    }

    /**
     * Set customer
     *
     * @param \MilesApart\AdminBundle\Entity\Customer $customer
     * @return BusinessCustomer
     */
    public function setCustomer(\MilesApart\AdminBundle\Entity\Customer $customer = null)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * Get customer
     *
     * @return \MilesApart\AdminBundle\Entity\Customer 
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * Add business_customer_representative
     *
     * @param \MilesApart\AdminBundle\Entity\BusinessCustomerRepresentative $businessCustomerRepresentative
     * @return BusinessCustomer
     */
    public function addBusinessCustomerRepresentative(\MilesApart\AdminBundle\Entity\BusinessCustomerRepresentative $businessCustomerRepresentative)
    {
        $this->business_customer_representative[] = $businessCustomerRepresentative;

        return $this;
    }

    /**
     * Remove business_customer_representative
     *
     * @param \MilesApart\AdminBundle\Entity\BusinessCustomerRepresentative $businessCustomerRepresentative
     */
    public function removeBusinessCustomerRepresentative(\MilesApart\AdminBundle\Entity\BusinessCustomerRepresentative $businessCustomerRepresentative)
    {
        $this->business_customer_representative->removeElement($businessCustomerRepresentative);
    }

    /**
     * Get business_customer_representative
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getBusinessCustomerRepresentative()
    {
        return $this->business_customer_representative;
    }
}
