<?php
// src/MilesApart/AdminBundle/Entity/CustomerInteractionType.php -- Defines the customer type object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\CustomerInteractionTypeRepository")
 * @ORM\Table(name="customer_interaction_type")
 * @ORM\HasLifecycleCallbacks()
 */

class CustomerInteractionType
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
    protected $customer_interaction_type_name;

    /**
     * @ORM\Column(type="string", length=500, unique=false, nullable=false)
     */
    protected $customer_interaction_type_description;

    /**
     * @ORM\OneToMany(targetEntity="CustomerInteraction", mappedBy="customer_interaction_type")
     */
    protected $customer_interaction;



    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //Customer interaction type name
        $metadata->addPropertyConstraint('customer_interaction_type_name', new Assert\NotBlank());
        $metadata->addPropertyConstraint('customer_interaction_type_name', new Assert\Length(array(
            'min'        => 2,
            'max'        => 100,
            'minMessage' => 'The interaction type name must be at least {{ limit }} characters length',
            'maxMessage' => 'The interaction type name cannot be longer than {{ limit }} characters length',
        )));

         //Customer interaction type description
        $metadata->addPropertyConstraint('customer_interaction_type_description', new Assert\NotBlank());
        $metadata->addPropertyConstraint('customer_interaction_type_description', new Assert\Length(array(
            'min'        => 2,
            'max'        => 100,
            'minMessage' => 'The interaction type description must be at least {{ limit }} characters length',
            'maxMessage' => 'The interaction type description cannot be longer than {{ limit }} characters length',
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
     * Set customer_interaction_type_name
     *
     * @param string $customerInteractionTypeName
     * @return CustomerInteractionType
     */
    public function setCustomerInteractionTypeName($customerInteractionTypeName)
    {
        $this->customer_interaction_type_name = $customerInteractionTypeName;
    
        return $this;
    }

    /**
     * Get customer_interaction_type_name
     *
     * @return string 
     */
    public function getCustomerInteractionTypeName()
    {
        return $this->customer_interaction_type_name;
    }

    /**
     * Set customer_interaction_type_description
     *
     * @param string $customerInteractionTypeDescription
     * @return CustomerInteractionType
     */
    public function setCustomerInteractionTypeDescription($customerInteractionTypeDescription)
    {
        $this->customer_interaction_type_description = $customerInteractionTypeDescription;
    
        return $this;
    }

    /**
     * Get customer_interaction_type_description
     *
     * @return string 
     */
    public function getCustomerInteractionTypeDescription()
    {
        return $this->customer_interaction_type_description;
    }

    /**
     * Add customer_interaction
     *
     * @param \MilesApart\AdminBundle\Entity\CustomerInteraction $customerInteraction
     * @return CustomerInteractionType
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