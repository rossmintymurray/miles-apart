<?php
// src/MilesApart/AdminBundle/Entity/CustomerOrderState.php -- Defines the customer type object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\CustomerOrderStateRepository")
 * @ORM\Table(name="customer_order_state")
 * @ORM\HasLifecycleCallbacks()
 */

class CustomerOrderState
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
    protected $customer_order_state;

    /**
     * @ORM\OneToMany(targetEntity="CustomerOrder", mappedBy="customer_order_state")
     */
    protected $customer_order;



    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //Customer order state
        $metadata->addPropertyConstraint('customer_order_state', new Assert\NotBlank());
        $metadata->addPropertyConstraint('customer_order_state', new Assert\Length(array(
            'min'        => 2,
            'max'        => 100,
            'minMessage' => 'The customer order state must be at least {{ limit }} characters length',
            'maxMessage' => 'The customer order state cannot be longer than {{ limit }} characters length',
        )));
    }



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->customer_order = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set customer_order_state
     *
     * @param string $customerOrderState
     * @return CustomerOrderState
     */
    public function setCustomerOrderState($customerOrderState)
    {
        $this->customer_order_state = $customerOrderState;
    
        return $this;
    }

    /**
     * Get customer_order_state
     *
     * @return string 
     */
    public function getCustomerOrderState()
    {
        return $this->customer_order_state;
    }

    /**
     * Add customer_order
     *
     * @param \MilesApart\AdminBundle\Entity\CustomerOrderProduct $customerOrder
     * @return CustomerOrderState
     */
    public function addCustomerOrder(\MilesApart\AdminBundle\Entity\CustomerOrder $customerOrder)
    {
        $this->customer_order[] = $customerOrder;
    
        return $this;
    }

    /**
     * Remove customer_order
     *
     * @param \MilesApart\AdminBundle\Entity\CustomerOrder $customerOrder
     */
    public function removeCustomerOrder(\MilesApart\AdminBundle\Entity\CustomerOrder $customerOrder)
    {
        $this->customer_order->removeElement($customerOrder);
    }

    /**
     * Get customer_order
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCustomerOrder()
    {
        return $this->customer_order;
    }
}