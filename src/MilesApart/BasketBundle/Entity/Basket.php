<?php
// src/MilesApart/BasketBundle/Entity/Basket.php -- Defines the customer type object

namespace MilesApart\BasketBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\BasketBundle\Entity\Repository\BasketRepository")
 * @ORM\Table(name="basket")
 * @ORM\HasLifecycleCallbacks()
 */

class Basket
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
     * @ORM\Column(type="datetime", unique=false, nullable=true)
     */
    protected $basket_date_created;

    /**
     * @ORM\Column(type="datetime", unique=false, nullable=true)
     */
    protected $basket_date_modified;

    /**
     * @ORM\ManyToOne(targetEntity="MilesApart\AdminBundle\Entity\Customer", inversedBy="basket", cascade={"persist"})
     * @ORM\JoinTable(name="customer")
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id")
     */
    protected $customer;

    /**
     * @ORM\OneToMany(targetEntity="BasketProduct", mappedBy="basket", cascade={"persist"})
     */
    protected $basket_product;

    /**
     * @ORM\Column(type="string", length=200, unique=false, nullable=false)
     */
    protected $session_id;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $basket_checked_out;

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        $this->setBasketDateModified(new \DateTime(date('Y-m-d H:i:s')));

        if($this->getBasketDateCreated() == null)
        {
            $this->setBasketDateCreated(new \DateTime(date('Y-m-d H:i:s')));
        }
    }



    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        

    }
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->basket_product = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set basket_date_created
     *
     * @param \DateTime $basketDateCreated
     * @return Basket
     */
    public function setBasketDateCreated($basketDateCreated)
    {
        $this->basket_date_created = $basketDateCreated;
    
        return $this;
    }

    /**
     * Get basket_date_created
     *
     * @return \DateTime 
     */
    public function getBasketDateCreated()
    {
        return $this->basket_date_created;
    }

    /**
     * Set basket_date_modified
     *
     * @param \DateTime $basketDateModified
     * @return Basket
     */
    public function setBasketDateModified($basketDateModified)
    {
        $this->basket_date_modified = $basketDateModified;
    
        return $this;
    }

    /**
     * Get basket_date_modified
     *
     * @return \DateTime 
     */
    public function getBasketDateModified()
    {
        return $this->basket_date_modified;
    }

    /**
     * Set session_id
     *
     * @param string $sessionId
     * @return Basket
     */
    public function setSessionId($sessionId)
    {
        $this->session_id = $sessionId;
    
        return $this;
    }

    /**
     * Get session_id
     *
     * @return string 
     */
    public function getSessionId()
    {
        return $this->session_id;
    }

    /**
     * Set customer
     *
     * @param \MilesApart\AdminBundle\Entity\Customer $customer
     * @return Basket
     */
    public function setCustomer(\MilesApart\AdminBundle\Entity\Customer $customer = null)
    {
        $this->customer = $customer;
    
        return $this;
    }

    /**
     * Get customer
     *
     * @return \MilesApart\BasketBundle\Entity\Customer 
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * Add basket_product
     *
     * @param \MilesApart\BasketBundle\Entity\BasketProduct $basketProduct
     * @return Basket
     */
    public function addBasketProduct(\MilesApart\BasketBundle\Entity\BasketProduct $basketProduct)
    {
        $this->basket_product[] = $basketProduct;
    
        return $this;
    }

    /**
     * Remove basket_product
     *
     * @param \MilesApart\BasketBundle\Entity\BasketProduct $basketProduct
     */
    public function removeBasketProduct(\MilesApart\BasketBundle\Entity\BasketProduct $basketProduct)
    {
        $this->basket_product->removeElement($basketProduct);
    }

    /**
     * Get basket_product
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getBasketProduct()
    {
        return $this->basket_product;
    }

    //Get basket total qty
    public function getBasketTotalQuantity()
    {
        $basket_total_quantity = 0;
        //If there are items in the basket
        if(count($this->getBasketProduct()) > 0) {
            foreach($this->getBasketProduct() as $key => $value) {
                $basket_total_quantity = $basket_total_quantity + $value->getBasketProductQuantity();
            }
        }

        return $basket_total_quantity;
    }


    //Get basket total value.
    //Get basket total qty
    public function getBasketTotalPrice()
    {
        $basket_total_price = 0;
        //If there are items in the basket
        if(count($this->getBasketProduct()) > 0) {
            foreach($this->getBasketProduct() as $key => $value) {
                $basket_total_price = $basket_total_price + $value->getBasketProductTotalPrice();
            }
        }

        return $basket_total_price;
    }

     //Get basket total qty
    public function getBasketTotalPriceDisplay()
    {
       $formatted_price = number_format($this->getBasketTotalPrice(), 2, '.', ',');

        if ($formatted_price < 1 && $formatted_price > 0) {
            $formatted_price = $formatted_price * 100;
            $formatted_price = $formatted_price . "p";
        } else if ($formatted_price >= 1) {
            $formatted_price = "£" . $formatted_price;
        } else {
            $formatted_price = "N/A";
        }
        return $formatted_price;
    }

    
    //Get basket grand total (including shipping)
    public function getBasketGrandTotalPrice()
    {
        $basket_total_price = 0;
        //If there are items in the basket
        if(count($this->getBasketProduct()) > 0) {
            foreach($this->getBasketProduct() as $key => $value) {
                $basket_total_price = $basket_total_price + $value->getBasketProductTotalPrice();
            }
        }

        return $basket_total_price;
    }

    /**
     * Get largest length item
     *
     */
    public function getBasketLargestLength()
    {
        //Get largest item
        //Set initial length
        $length = 0;
        
        //Set maximums
        foreach($this->getBasketProduct() as $basket_product){
            
            //For length
            if($basket_product->getProduct()->getProductHeight() > $length) {
                $length = $basket_product->getProduct()->getProductHeight();
            }
        }

        return $length;
    }

    /**
     * Get largest width item
     *
     */
    public function getBasketLargestWidth()
    {
        //Get largest item
        //Set initial width
        $width = 0;
        
        //Set maximums
        foreach($this->getBasketProduct() as $basket_product){
            
            //For width
            if($basket_product->getProduct()->getProductWidth() > $width) {
                $width = $basket_product->getProduct()->getProductWidth();
            }
        }

        return $width;
    }

     

     /**
     * Get largest depth item
     *
     */
    public function getBasketLargestDepth()
    {
        //Get largest item
        //Set initial depth
        $depth = 0;
        
        //Set maximums
        foreach($this->getBasketProduct() as $basket_product){
            
            //For depth
            if($basket_product->getProduct()->getProductDepth() > $depth) {
                $depth = $basket_product->getProduct()->getProductDepth();
            }
        }

        return $depth;
    }

    /**
     * Get basket_total_weight
     *
     *  
     */
    public function getBasketTotalWeight()
    {
        $total_weight = 0;
        //For each product
        foreach($this->getBasketProduct() as $key => $value) {
            
            //Add the weight of each item to the total
            $total_weight = $total_weight + $value->getProduct()->getProductWeight();
        }

        return $total_weight;
    }

    /**
     * Set basket_checked_out
     *
     * @param boolean $basketCheckedOut
     * @return Basket
     */
    public function setBasketCheckedOut($basketCheckedOut)
    {
        $this->basket_checked_out = $basketCheckedOut;

        return $this;
    }

    /**
     * Get basket_checked_out
     *
     * @return boolean 
     */
    public function getBasketCheckedOut()
    {
        return $this->basket_checked_out;
    }
}
