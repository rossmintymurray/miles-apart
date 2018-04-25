<?php
// src/MilesApart/AdminBundle/Entity/ShopReturnedProduct.php -- Defines the customer type object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\ShopReturnedProductRepository")
 * @ORM\Table(name="shop_returned_product")
 * @ORM\HasLifecycleCallbacks()
 */

class ShopReturnedProduct
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
     * @ORM\Column(type="integer")
     */
    protected $shop_returned_product_quantity;

    /**
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="shop_returned_product")
     * @ORM\JoinTable(name="product")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    protected $product;

    /**
     * @ORM\Column(type="datetime", unique=false, nullable=false)
     */
    protected $shop_returned_product_date_created;

    /**
     * @ORM\Column(type="datetime", unique=false, nullable=true)
     */
    protected $shop_returned_product_date_modified;
    
    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        $this->setShopReturnedProductDateModified(new \DateTime(date('Y-m-d H:i:s')));

        if($this->getShopReturnedProductDateCreated() == null)
        {
            $this->setShopReturnedProductDateCreated(new \DateTime(date('Y-m-d H:i:s')));
        }
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->shop_returned_product_quantity = 1;
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
     * Set shop_returned_product_quantity
     *
     * @param integer $shopReturnedProductQuantity
     * @return ShopReturnedProduct
     */
    public function setShopReturnedProductQuantity($shopReturnedProductQuantity)
    {
        $this->shop_returned_product_quantity = $shopReturnedProductQuantity;

        return $this;
    }

    /**
     * Get shop_returned_product_quantity
     *
     * @return integer 
     */
    public function getShopReturnedProductQuantity()
    {
        return $this->shop_returned_product_quantity;
    }

    /**
     * Set shop_returned_product_date_created
     *
     * @param \DateTime $shopReturnedProductDateCreated
     * @return ShopReturnedProduct
     */
    public function setShopReturnedProductDateCreated($shopReturnedProductDateCreated)
    {
        $this->shop_returned_product_date_created = $shopReturnedProductDateCreated;

        return $this;
    }

    /**
     * Get shop_returned_product_date_created
     *
     * @return \DateTime 
     */
    public function getShopReturnedProductDateCreated()
    {
        return $this->shop_returned_product_date_created;
    }

    /**
     * Set shop_returned_product_date_modified
     *
     * @param \DateTime $shopReturnedProductDateModified
     * @return ShopReturnedProduct
     */
    public function setShopReturnedProductDateModified($shopReturnedProductDateModified)
    {
        $this->shop_returned_product_date_modified = $shopReturnedProductDateModified;

        return $this;
    }

    /**
     * Get shop_returned_product_date_modified
     *
     * @return \DateTime 
     */
    public function getShopReturnedProductDateModified()
    {
        return $this->shop_returned_product_date_modified;
    }

    /**
     * Set product
     *
     * @param \MilesApart\AdminBundle\Entity\Product $product
     * @return ShopReturnedProduct
     */
    public function setProduct(\MilesApart\AdminBundle\Entity\Product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return \MilesApart\AdminBundle\Entity\Product 
     */
    public function getProduct()
    {
        return $this->product;
    }
}
