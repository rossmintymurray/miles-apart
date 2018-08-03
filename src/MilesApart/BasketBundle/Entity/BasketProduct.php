<?php
// src/MilesApart/AdminBundle/Entity/BasketProduct.php -- Defines the customer type object

namespace MilesApart\BasketBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\BasketBundle\Entity\Repository\BasketProductRepository")
 * @ORM\Table(name="basket_product")
 * @ORM\HasLifecycleCallbacks()
 */

class BasketProduct
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
    protected $basket_product_date_created;

    /**
     * @ORM\Column(type="datetime", unique=false, nullable=true)
     */
    protected $basket_product_date_modified;


    /**
     * @ORM\Column(type="integer")
     */
    protected $basket_added_product_quantity;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $basket_removed_product_quantity;

    /**
     * @ORM\ManyToOne(targetEntity="Basket", inversedBy="basket_product", cascade={"persist"})
     * @ORM\JoinTable(name="basket")
     * @ORM\JoinColumn(name="basket_id", referencedColumnName="id")
     */
    protected $basket;

    /**
     * @ORM\ManyToOne(targetEntity="MilesApart\AdminBundle\Entity\Product", inversedBy="basket_product")
     * @ORM\JoinTable(name="product")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    protected $product;


     /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        $this->setBasketProductDateModified(new \DateTime(date('Y-m-d H:i:s')));

        if($this->getBasketProductDateCreated() == null)
        {
            $this->setBasketProductDateCreated(new \DateTime(date('Y-m-d H:i:s')));
        }
    }



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->removed_basket_product = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set basket_product_date_created
     *
     * @param \DateTime $basketProductDateCreated
     * @return BasketProduct
     */
    public function setBasketProductDateCreated($basketProductDateCreated)
    {
        $this->basket_product_date_created = $basketProductDateCreated;
    
        return $this;
    }

    /**
     * Get basket_product_date_created
     *
     * @return \DateTime 
     */
    public function getBasketProductDateCreated()
    {
        return $this->basket_product_date_created;
    }

    /**
     * Set basket_product_date_modified
     *
     * @param \DateTime $basketProductDateModified
     * @return BasketProduct
     */
    public function setBasketProductDateModified($basketProductDateModified)
    {
        $this->basket_product_date_modified = $basketProductDateModified;
    
        return $this;
    }

    /**
     * Get basket_product_date_modified
     *
     * @return \DateTime 
     */
    public function getBasketProductDateModified()
    {
        return $this->basket_product_date_modified;
    }

    /**
     * Set basket_added_product_quantity
     *
     * @param integer $basketAddedProductQuantity
     * @return BasketProduct
     */
    public function setBasketAddedProductQuantity($basketAddedProductQuantity)
    {
        $this->basket_added_product_quantity = $basketAddedProductQuantity;

        return $this;
    }

    /**
     * Get basket_added_product_quantity
     *
     * @return integer
     */
    public function getBasketAddedProductQuantity()
    {
        return $this->basket_added_product_quantity;
    }

    /**
     * Set basket_removed_product_quantity
     *
     * @param integer $basketRemovedProductQuantity
     * @return BasketProduct
     */
    public function setBasketRemovedProductQuantity($basketRemovedProductQuantity)
    {
        $this->basket_removed_product_quantity = $basketRemovedProductQuantity;

        return $this;
    }

    /**
     * Get basket_removed_product_quantity
     *
     * @return integer
     */
    public function getBasketRemovedProductQuantity()
    {
        return $this->basket_removed_product_quantity;
    }

    /**
     * Get basket_product_quantity
     *
     * @return integer 
     */
    public function getBasketProductQuantity()
    {
        return $this->getBasketAddedProductQuantity() - $this->getBasketRemovedProductQuantity();
    }

    /**
     * Set basket
     *
     * @param \MilesApart\BasketBundle\Entity\Basket $basket
     * @return BasketProduct
     */
    public function setBasket(\MilesApart\BasketBundle\Entity\Basket $basket = null)
    {
        $this->basket = $basket;
    
        return $this;
    }

    /**
     * Get basket
     *
     * @return \MilesApart\BasketBundle\Entity\Basket 
     */
    public function getBasket()
    {
        return $this->basket;
    }

    /**
     * Set product
     *
     * @param \MilesApart\BasketBundle\Entity\Product $product
     * @return BasketProduct
     */
    public function setProduct(\MilesApart\AdminBundle\Entity\Product $product = null)
    {
        $this->product = $product;
    
        return $this;
    }

    /**
     * Get product
     *
     * @return \MilesApart\BasketBundle\Entity\Product 
     */
    public function getProduct()
    {
        return $this->product;
    }

    public function getBasketProductTotalPrice()
    {
        $basket_product_total_price = $this->getBasketProductQuantity() * $this->getProduct()->getCurrentPriceDecimal();

        return $basket_product_total_price;
    }

     public function getBasketProductTotalPriceDisplay()
    {
        $formatted_price = number_format($this->getBasketProductTotalPrice(), 2, '.', ',');

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
}