<?php
// src/MilesApart/AdminBundle/Entity/ReturnedProduct.php -- Defines the customer type object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\ExecutionContextInterface;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\ReturnedProductRepository")
 * @ORM\Table(name="returned_product")
 * @ORM\HasLifecycleCallbacks()
 */

class ReturnedProduct
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
     * @ORM\ManyToOne(targetEntity="CustomerOrderProduct", inversedBy="returned_product")
     * @ORM\JoinTable(name="customer_order_product")
     * @ORM\JoinColumn(name="customer_order_product_id", referencedColumnName="id")
     */
    protected $customer_order_product;

    /**
     * @ORM\ManyToOne(targetEntity="ReturnedReason", inversedBy="returned_product")
     * @ORM\JoinTable(name="returned_reason")
     * @ORM\JoinColumn(name="returned_reason_id", referencedColumnName="id")
     */
    protected $returned_reason;

    /**
     * @ORM\ManyToOne(targetEntity="ReturnedProductState", inversedBy="returned_product")
     * @ORM\JoinTable(name="returned_product_state")
     * @ORM\JoinColumn(name="returned_product_state_id", referencedColumnName="id")
     */
    protected $returned_product_state;

    /**
     * @ORM\Column(type="string", length=500, unique=false, nullable=true)
     */
    protected $return_notes;

    /**
     * @ORM\Column(type="integer")
     */
    protected $returned_product_quantity;

    /**
     * @ORM\Column(type="datetime", unique=false, nullable=true)
     */
    protected $returned_product_date_created;

    /**
     * @ORM\Column(type="datetime", unique=false, nullable=false)
     */
    protected $returned_product_date_modified;
   
    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        $this->setReturnedProductDateModified(new \DateTime(date('Y-m-d H:i:s')));

        if($this->getReturnedProductDateCreated() == null)
        {
            $this->setReturnedProductDateCreated(new \DateTime(date('Y-m-d H:i:s')));
        }
    }   

    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
         $metadata->addConstraint(new Assert\Callback('validate'));
    }


    public function validate(ExecutionContextInterface $context)
    {
        // somehow you have an array of "fake names"
        $ordered_quantity = $this->getCustomerOrderProduct()->getCustomerOrderProductQuantity();

        // check if the name is actually a fake name
        if ($this->getReturnedProductQuantity() > $ordered_quantity) {
            $context->addViolationAt(
                'returned_product_quantity',
                'Please ensure the number of products you are returning is not more than the number of products ordered.',
                array(),
                null
            );
        }
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
     * Set return_processed
     *
     * @param boolean $returnProcessed
     * @return ReturnedProduct
     */
    public function setReturnProcessed($returnProcessed)
    {
        $this->return_processed = $returnProcessed;
    
        return $this;
    }

    /**
     * Get return_processed
     *
     * @return boolean 
     */
    public function getReturnProcessed()
    {
        return $this->return_processed;
    }

    /**
     * Set return_notes
     *
     * @param string $returnNotes
     * @return ReturnedProduct
     */
    public function setReturnNotes($returnNotes)
    {
        $this->return_notes = $returnNotes;
    
        return $this;
    }

    /**
     * Get return_notes
     *
     * @return string 
     */
    public function getReturnNotes()
    {
        return $this->return_notes;
    }

    /**
     * Set customer_order_product
     *
     * @param \MilesApart\AdminBundle\Entity\CustomerOrderProduct $customerOrderProduct
     * @return ReturnedProduct
     */
    public function setCustomerOrderProduct(\MilesApart\AdminBundle\Entity\CustomerOrderProduct $customerOrderProduct = null)
    {
        $this->customer_order_product = $customerOrderProduct;
    
        return $this;
    }

    /**
     * Get customer_order_product
     *
     * @return \MilesApart\AdminBundle\Entity\CustomerOrderProduct 
     */
    public function getCustomerOrderProduct()
    {
        return $this->customer_order_product;
    }

    /**
     * Set returned_reason
     *
     * @param \MilesApart\AdminBundle\Entity\ReturnedReason $returnedReason
     * @return ReturnedProduct
     */
    public function setReturnedReason(\MilesApart\AdminBundle\Entity\ReturnedReason $returnedReason = null)
    {
        $this->returned_reason = $returnedReason;
    
        return $this;
    }

    /**
     * Get returned_reason
     *
     * @return \MilesApart\AdminBundle\Entity\ReturnedReason 
     */
    public function getReturnedReason()
    {
        return $this->returned_reason;
    }

    /**
     * Set returned_product_quantity
     *
     * @param integer $returnedProductQuantity
     * @return ReturnedProduct
     */
    public function setReturnedProductQuantity($returnedProductQuantity)
    {
        $this->returned_product_quantity = $returnedProductQuantity;

        return $this;
    }

    /**
     * Get returned_product_quantity
     *
     * @return integer 
     */
    public function getReturnedProductQuantity()
    {
        return $this->returned_product_quantity;
    }

    /**
     * Set returned_product_state
     *
     * @param \MilesApart\AdminBundle\Entity\ReturnedProductState $returnedProductState
     * @return ReturnedProduct
     */
    public function setReturnedProductState(\MilesApart\AdminBundle\Entity\ReturnedProductState $returnedProductState = null)
    {
        $this->returned_product_state = $returnedProductState;

        return $this;
    }

    /**
     * Get returned_product_state
     *
     * @return \MilesApart\AdminBundle\Entity\ReturnedProductState 
     */
    public function getReturnedProductState()
    {
        return $this->returned_product_state;
    }

    public function getReturnedProductTotalPrice()
    {
        $total_price = $this->getReturnedProductQuantity() * $this->getCustomerOrderProduct()->getProduct()->getCurrentPriceDecimal();

        return $total_price;
    }


    //Get product total price display
    public function getReturnedProductTotalPriceDisplay()
    {
        $total_price = number_format($this->getReturnedProductTotalPrice(), 2, '.', ',');
        if ($total_price < 1 && $total_price > 0) {
            $total_price = $total_price * 100;
            $total_price = $total_price . "p";
        } else if ($total_price >= 1) {
            $total_price = "£" . $total_price;
        } else {
            $total_price = "N/A";
        }

        return $total_price;
    }

    /**
     * Set returned_product_date_created
     *
     * @param \DateTime $returnedProductDateCreated
     * @return ReturnedProduct
     */
    public function setReturnedProductDateCreated($returnedProductDateCreated)
    {
        $this->returned_product_date_created = $returnedProductDateCreated;

        return $this;
    }

    /**
     * Get returned_product_date_created
     *
     * @return \DateTime 
     */
    public function getReturnedProductDateCreated()
    {
        return $this->returned_product_date_created;
    }

    /**
     * Set returned_product_date_modified
     *
     * @param \DateTime $returnedProductDateModified
     * @return ReturnedProduct
     */
    public function setReturnedProductDateModified($returnedProductDateModified)
    {
        $this->returned_product_date_modified = $returnedProductDateModified;

        return $this;
    }

    /**
     * Get returned_product_date_modified
     *
     * @return \DateTime 
     */
    public function getReturnedProductDateModified()
    {
        return $this->returned_product_date_modified;
    }
}
