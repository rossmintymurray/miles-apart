<?php
// src/MilesApart/AdminBundle/Entity/CustomerInteractionReason.php -- Defines the customer type object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\CustomerInteractionReasonRepository")
 * @ORM\Table(name="customer_interaction_reason")
 * @ORM\HasLifecycleCallbacks()
 */

class CustomerInteractionReason
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
    protected $customer_interaction_reason_name;

    /**
     * @ORM\Column(type="string", length=1000, unique=false, nullable=false)
     */
    protected $customer_interaction_reason_description;

    /**
     * @ORM\OneToMany(targetEntity="CustomerInteraction", mappedBy="customer_interaction_reason")
     */
    protected $customer_interaction;



    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //Customer interaction reason name
        $metadata->addPropertyConstraint('customer_interaction_reason_name', new Assert\NotBlank());
        $metadata->addPropertyConstraint('customer_interaction_reason_name', new Assert\Length(array(
            'min'        => 2,
            'max'        => 100,
            'minMessage' => 'The reason name must be at least {{ limit }} characters length',
            'maxMessage' => 'The reason name cannot be longer than {{ limit }} characters length',
        )));

        //Customer interaction reason description
        $metadata->addPropertyConstraint('customer_interaction_reason_description', new Assert\NotBlank());
        $metadata->addPropertyConstraint('customer_interaction_reason_description', new Assert\Length(array(
            'min'        => 2,
            'max'        => 1000,
            'minMessage' => 'The reason description must be at least {{ limit }} characters length',
            'maxMessage' => 'The reason description cannot be longer than {{ limit }} characters length',
        )));
    }


    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->customer_interaction = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set customer_interaction_reason_name
     *
     * @param string $customerInteractionReasonName
     * @return CustomerInteractionReason
     */
    public function setCustomerInteractionReasonName($customerInteractionReasonName)
    {
        $this->customer_interaction_reason_name = $customerInteractionReasonName;
    
        return $this;
    }

    /**
     * Get customer_interaction_reason_name
     *
     * @return string 
     */
    public function getCustomerInteractionReasonName()
    {
        return $this->customer_interaction_reason_name;
    }

    /**
     * Set customer_interaction_reason_description
     *
     * @param string $customerInteractionReasonDescription
     * @return CustomerInteractionReason
     */
    public function setCustomerInteractionReasonDescription($customerInteractionReasonDescription)
    {
        $this->customer_interaction_reason_description = $customerInteractionReasonDescription;
    
        return $this;
    }

    /**
     * Get customer_interaction_reason_description
     *
     * @return string 
     */
    public function getCustomerInteractionReasonDescription()
    {
        return $this->customer_interaction_reason_description;
    }

    /**
     * Add customer_interaction
     *
     * @param \MilesApart\AdminBundle\Entity\CustomerInteraction $customerInteraction
     * @return CustomerInteractionReason
     */
    public function addCustomerInteraction(\MilesApart\AdminBundle\Entity\CustomerInteraction $customerInteraction)
    {
        $this->customer_interaction[] = $customerInteraction;
    
        return $this;
    }

    /**
     * Remove customer_interaction
     *
     * @param \MilesApart\AdminBundle\Entity\CustomerInteraction $customerInteraction
     */
    public function removeCustomerInteraction(\MilesApart\AdminBundle\Entity\CustomerInteraction $customerInteraction)
    {
        $this->customer_interaction->removeElement($customerInteraction);
    }

    /**
     * Get customer_interaction
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCustomerInteraction()
    {
        return $this->customer_interaction;
    }
}