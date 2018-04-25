<?php
// src/MilesApart/AdminBundle/Entity/CustomerInteractionResolution.php -- Defines the customer type object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\CustomerInteractionResolutionRepository")
 * @ORM\Table(name="customer_interaction_resolution")
 * @ORM\HasLifecycleCallbacks()
 */

class CustomerInteractionResolution
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
    protected $customer_interaction_resolution_name;

    /**
     * @ORM\Column(type="string", length=1000, unique=false, nullable=false)
     */
    protected $customer_interaction_resolution_description;

    /**
     * @ORM\OneToMany(targetEntity="CustomerInteraction", mappedBy="customer_interaction_resolution")
     */
    protected $customer_interaction;



    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //Customer interaction resolution name
        $metadata->addPropertyConstraint('customer_interaction_resolution_name', new Assert\NotBlank());
        $metadata->addPropertyConstraint('customer_interaction_resolution_name', new Assert\Length(array(
            'min'        => 2,
            'max'        => 100,
            'minMessage' => 'The resolution name must be at least {{ limit }} characters length',
            'maxMessage' => 'The resolution name cannot be longer than {{ limit }} characters length',
        )));

        //Customer interaction resolution description
        $metadata->addPropertyConstraint('customer_interaction_resolution_description', new Assert\NotBlank());
        $metadata->addPropertyConstraint('customer_interaction_resolution_description', new Assert\Length(array(
            'min'        => 2,
            'max'        => 1000,
            'minMessage' => 'The resolution description must be at least {{ limit }} characters length',
            'maxMessage' => 'The resolution description cannot be longer than {{ limit }} characters length',
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
     * Set customer_interaction_resolution_name
     *
     * @param string $customerInteractionResolutionName
     * @return CustomerInteractionResolution
     */
    public function setCustomerInteractionResolutionName($customerInteractionResolutionName)
    {
        $this->customer_interaction_resolution_name = $customerInteractionResolutionName;
    
        return $this;
    }

    /**
     * Get customer_interaction_resolution_name
     *
     * @return string 
     */
    public function getCustomerInteractionResolutionName()
    {
        return $this->customer_interaction_resolution_name;
    }

    /**
     * Set customer_interaction_resolution_description
     *
     * @param string $customerInteractionResolutionDescription
     * @return CustomerInteractionResolution
     */
    public function setCustomerInteractionResolutionDescription($customerInteractionResolutionDescription)
    {
        $this->customer_interaction_resolution_description = $customerInteractionResolutionDescription;
    
        return $this;
    }

    /**
     * Get customer_interaction_resolution_description
     *
     * @return string 
     */
    public function getCustomerInteractionResolutionDescription()
    {
        return $this->customer_interaction_resolution_description;
    }

    /**
     * Add customer_interaction
     *
     * @param \MilesApart\AdminBundle\Entity\CustomerInteraction $customerInteraction
     * @return CustomerInteractionResolution
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