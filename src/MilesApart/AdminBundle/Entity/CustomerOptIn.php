<?php
// src/MilesApart/AdminBundle/Entity/CustomerOptIn.php -- Defines the customer type object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\CustomerOptInReasonRepository")
 * @ORM\Table(name="customer_opt_in")
 * @ORM\HasLifecycleCallbacks()
 */

class CustomerOptIn
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
     * @ORM\ManyToOne(targetEntity="Customer", inversedBy="customer_opt_in")
     * @ORM\JoinTable(name="customer")
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id")
     */
    protected $customer;

    /**
     * @ORM\Column(type="datetime", unique=false, nullable=true)
     */
    protected $customer_opt_in_date_created;

    /**
     * @ORM\Column(type="datetime", unique=false, nullable=false)
     */
    protected $customer_opt_in_date_modified;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $opt_in;

    /**
     * @ORM\ManyToOne(targetEntity="CustomerOptInType", inversedBy="customer_opt_in")
     * @ORM\JoinTable(name="customer_opt_in_type")
     * @ORM\JoinColumn(name="customer_opt_in_type_id", referencedColumnName="id")
     */
    protected $customer_opt_in_type;



    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        $this->setCustomerOptInDateModified(new \DateTime(date('Y-m-d H:i:s')));

        if($this->getCustomerOptInDateCreated() == null)
        {
            $this->setCustomerOptInDateCreated(new \DateTime(date('Y-m-d H:i:s')));
        }
    }



    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        
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
     * Set customer_opt_in_date_created
     *
     * @param \DateTime $customerOptInDateCreated
     * @return CustomerOptIn
     */
    public function setCustomerOptInDateCreated($customerOptInDateCreated)
    {
        $this->customer_opt_in_date_created = $customerOptInDateCreated;
    
        return $this;
    }

    /**
     * Get customer_opt_in_date_created
     *
     * @return \DateTime 
     */
    public function getCustomerOptInDateCreated()
    {
        return $this->customer_opt_in_date_created;
    }

    /**
     * Set customer_opt_in_date_modified
     *
     * @param \DateTime $customerOptInDateModified
     * @return CustomerOptIn
     */
    public function setCustomerOptInDateModified($customerOptInDateModified)
    {
        $this->customer_opt_in_date_modified = $customerOptInDateModified;
    
        return $this;
    }

    /**
     * Get customer_opt_in_date_modified
     *
     * @return \DateTime 
     */
    public function getCustomerOptInDateModified()
    {
        return $this->customer_opt_in_date_modified;
    }

    /**
     * Set opt_in
     *
     * @param boolean $optIn
     * @return CustomerOptIn
     */
    public function setOptIn($optIn)
    {
        $this->opt_in = $optIn;
    
        return $this;
    }

    /**
     * Get opt_in
     *
     * @return boolean 
     */
    public function getOptIn()
    {
        return $this->opt_in;
    }

    /**
     * Set customer
     *
     * @param \MilesApart\AdminBundle\Entity\Customer $customer
     * @return CustomerOptIn
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
     * Set customer_opt_in_type
     *
     * @param \MilesApart\AdminBundle\Entity\CustomerOptInType $customerOptInType
     * @return CustomerOptIn
     */
    public function setCustomerOptInType(\MilesApart\AdminBundle\Entity\CustomerOptInType $customerOptInType = null)
    {
        $this->customer_opt_in_type = $customerOptInType;
    
        return $this;
    }

    /**
     * Get customer_opt_in_type
     *
     * @return \MilesApart\AdminBundle\Entity\CustomerOptInType 
     */
    public function getCustomerOptInType()
    {
        return $this->customer_opt_in_type;
    }
}