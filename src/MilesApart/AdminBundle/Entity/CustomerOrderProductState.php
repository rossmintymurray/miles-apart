<?php
// src/MilesApart/AdminBundle/Entity/CustomerOrderProductState.php -- Defines the customer type object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\CustomerOrderProductStateRepository")
 * @ORM\Table(name="customer_order_product_state")
 * @ORM\HasLifecycleCallbacks()
 */

class CustomerOrderProductState
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
    protected $customer_order_product_state;

    /**
     * @ORM\OneToMany(targetEntity="CustomerOrderProduct", mappedBy="customer_order_product_state")
     */
    protected $customer_order_product;



    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //Customer order state
        $metadata->addPropertyConstraint('customer_order_product_state', new Assert\NotBlank());
        $metadata->addPropertyConstraint('customer_order_product_state', new Assert\Length(array(
            'min'        => 2,
            'max'        => 100,
            'minMessage' => 'The customer order product state must be at least {{ limit }} characters length',
            'maxMessage' => 'The customer order product state cannot be longer than {{ limit }} characters length',
        )));
    }



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->customer_order_product = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set customer_order_product_state
     *
     * @param string $customerOrderProductState
     * @return CustomerOrderProductState
     */
    public function setCustomerOrderProductState($customerOrderProductState)
    {
        $this->customer_order_product_state = $customerOrderProductState;
    
        return $this;
    }

    /**
     * Get customer_order_product_state
     *
     * @return string 
     */
    public function getCustomerOrderProductState()
    {
        return $this->customer_order_product_state;
    }

    /**
     * Add customer_order_product
     *
     * @param \MilesApart\AdminBundle\Entity\CustomerOrderProduct $customerOrderProduct
     * @return CustomerOrderState
     */
    public function addCustomerOrderProduct(\MilesApart\AdminBundle\Entity\CustomerOrderProduct $customerOrderProduct)
    {
        $this->customer_order_product[] = $customerOrderProduct;
    
        return $this;
    }

    /**
     * Remove customer_order_product
     *
     * @param \MilesApart\AdminBundle\Entity\CustomerOrderProduct $customerOrderProduct
     */
    public function removeCustomerOrderProduct(\MilesApart\AdminBundle\Entity\CustomerOrderProduct $customerOrderProduct)
    {
        $this->customer_order_product->removeElement($customerOrderProduct);
    }

    /**
     * Get customer_order_product
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCustomerOrderProduct()
    {
        return $this->customer_order_product;
    }
}