<?php
// src/MilesApart/AdminBundle/Entity/CustomerInteraction.php -- Defines the customer type object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\CustomerInteractionRepository")
 * @ORM\Table(name="customer_interaction")
 * @ORM\HasLifecycleCallbacks()
 */

class CustomerInteraction
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
     * @ORM\ManyToOne(targetEntity="Customer", inversedBy="customer_interaction")
     * @ORM\JoinTable(name="customer")
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id")
     */
    protected $customer;

    /**
     * @ORM\ManyToOne(targetEntity="CustomerInteractionReason", inversedBy="customer_interaction")
     * @ORM\JoinTable(name="customer_interaction_reason")
     * @ORM\JoinColumn(name="customer_interaction_reason_id", referencedColumnName="id")
     */
    protected $customer_interaction_reason;

    /**
     * @ORM\ManyToOne(targetEntity="CustomerInteractionResolution", inversedBy="customer_interaction")
     * @ORM\JoinTable(name="customer_interaction_resolution")
     * @ORM\JoinColumn(name="customer_interaction_resolution_id", referencedColumnName="id")
     */
    protected $customer_interaction_resolution;

    /**
     * @ORM\ManyToOne(targetEntity="CustomerInteractionType", inversedBy="customer_interaction")
     * @ORM\JoinTable(name="customer_interaction_type")
     * @ORM\JoinColumn(name="customer_interaction_type_id", referencedColumnName="id")
     */
    protected $customer_interaction_type;

    /**
     * @ORM\Column(type="datetime", unique=false, nullable=false)
     */
    protected $customer_interaction_date_created;

    /**
     * @ORM\Column(type="datetime", unique=false, nullable=false)
     */
    protected $customer_interaction_date_modified;

    /**
     * @ORM\Column(type="datetime", unique=false, nullable=true)
     */
    protected $customer_interaction_date_resolved;

    /**
     * @ORM\Column(type="string", length=2000, unique=false, nullable=true)
     */
    protected $customer_interaction_notes;

   

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        $this->setCustomerInteractionDateModified(new \DateTime(date('Y-m-d H:i:s')));

        if($this->getCustomerInteractionDateCreated() == null)
        {
            $this->setCustomerInteractionDateCreated(new \DateTime(date('Y-m-d H:i:s')));
        }
    }



    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //Customer interaction notes
        $metadata->addPropertyConstraint('customer_interaction_notes', new Assert\Length(array(
            'min'        => 2,
            'max'        => 2000,
            'minMessage' => 'The notes must be at least {{ limit }} characters length',
            'maxMessage' => 'The notes cannot be longer than {{ limit }} characters length',
        )));

        //Customer interaction date resolved
        $metadata->addPropertyConstraint('customer_interaction_date_resolved', new Assert\Date());
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
     * Set customer_interaction_date_created
     *
     * @param \DateTime $customerInteractionDateCreated
     * @return CustomerInteraction
     */
    public function setCustomerInteractionDateCreated($customerInteractionDateCreated)
    {
        $this->customer_interaction_date_created = $customerInteractionDateCreated;
    
        return $this;
    }

    /**
     * Get customer_interaction_date_created
     *
     * @return \DateTime 
     */
    public function getCustomerInteractionDateCreated()
    {
        return $this->customer_interaction_date_created;
    }

    /**
     * Set customer_interaction_date_modified
     *
     * @param \DateTime $customerInteractionDateModified
     * @return CustomerInteraction
     */
    public function setCustomerInteractionDateModified($customerInteractionDateModified)
    {
        $this->customer_interaction_date_modified = $customerInteractionDateModified;
    
        return $this;
    }

    /**
     * Get customer_interaction_date_modified
     *
     * @return \DateTime 
     */
    public function getCustomerInteractionDateModified()
    {
        return $this->customer_interaction_date_modified;
    }

    /**
     * Set customer_interaction_date_resolved
     *
     * @param \DateTime $customerInteractionDateResolved
     * @return CustomerInteraction
     */
    public function setCustomerInteractionDateResolved($customerInteractionDateResolved)
    {
        $this->customer_interaction_date_resolved = $customerInteractionDateResolved;
    
        return $this;
    }

    /**
     * Get customer_interaction_date_resolved
     *
     * @return \DateTime 
     */
    public function getCustomerInteractionDateResolved()
    {
        return $this->customer_interaction_date_resolved;
    }

    /**
     * Set customer_interaction_notes
     *
     * @param string $customerInteractionNotes
     * @return CustomerInteraction
     */
    public function setCustomerInteractionNotes($customerInteractionNotes)
    {
        $this->customer_interaction_notes = $customerInteractionNotes;
    
        return $this;
    }

    /**
     * Get customer_interaction_notes
     *
     * @return string 
     */
    public function getCustomerInteractionNotes()
    {
        return $this->customer_interaction_notes;
    }

    /**
     * Set customer
     *
     * @param \MilesApart\AdminBundle\Entity\Customer $customer
     * @return CustomerInteraction
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
     * Set customer_interaction_reason
     *
     * @param \MilesApart\AdminBundle\Entity\CustomerInteractionReason $customerInteractionReason
     * @return CustomerInteraction
     */
    public function setCustomerInteractionReason(\MilesApart\AdminBundle\Entity\CustomerInteractionReason $customerInteractionReason = null)
    {
        $this->customer_interaction_reason = $customerInteractionReason;
    
        return $this;
    }

    /**
     * Get customer_interaction_reason
     *
     * @return \MilesApart\AdminBundle\Entity\CustomerInteractionReason 
     */
    public function getCustomerInteractionReason()
    {
        return $this->customer_interaction_reason;
    }

    /**
     * Set customer_interaction_resolution
     *
     * @param \MilesApart\AdminBundle\Entity\CustomerInteractionResolution $customerInteractionResolution
     * @return CustomerInteraction
     */
    public function setCustomerInteractionResolution(\MilesApart\AdminBundle\Entity\CustomerInteractionResolution $customerInteractionResolution = null)
    {
        $this->customer_interaction_resolution = $customerInteractionResolution;
    
        return $this;
    }

    /**
     * Get customer_interaction_resolution
     *
     * @return \MilesApart\AdminBundle\Entity\CustomerInteractionResolution 
     */
    public function getCustomerInteractionResolution()
    {
        return $this->customer_interaction_resolution;
    }

    /**
     * Set customer_interaction_type
     *
     * @param \MilesApart\AdminBundle\Entity\CustomerInteractionType $customerInteractionType
     * @return CustomerInteraction
     */
    public function setCustomerInteractionType(\MilesApart\AdminBundle\Entity\CustomerInteractionType $customerInteractionType = null)
    {
        $this->customer_interaction_type = $customerInteractionType;
    
        return $this;
    }

    /**
     * Get customer_interaction_type
     *
     * @return \MilesApart\AdminBundle\Entity\CustomerInteractionType 
     */
    public function getCustomerInteractionType()
    {
        return $this->customer_interaction_type;
    }
}
