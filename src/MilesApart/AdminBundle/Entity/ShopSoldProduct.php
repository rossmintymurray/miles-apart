<?php
// src/MilesApart/AdminBundle/Entity/ShopSoldProduct.php -- Defines the customer type object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\ShopSoldProductRepository")
 * @ORM\Table(name="shop_sold_product")
 * @ORM\HasLifecycleCallbacks()
 */

class ShopSoldProduct
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
    protected $shop_sold_product_quantity;

    /**
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="shop_sold_product")
     * @ORM\JoinTable(name="product")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    protected $product;

    /**
     * @ORM\Column(type="datetime", unique=false, nullable=false)
     */
    protected $shop_sold_product_date_created;

    /**
     * @ORM\Column(type="datetime", unique=false, nullable=true)
     */
    protected $shop_sold_product_date_modified;
    
    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        $this->setShopSoldProductDateModified(new \DateTime(date('Y-m-d H:i:s')));

        if($this->getShopSoldProductDateCreated() == null)
        {
            $this->setShopSoldProductDateCreated(new \DateTime(date('Y-m-d H:i:s')));
        }
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->shop_sold_product_quantity = 1;
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
     * Set shop_sold_product_quantity
     *
     * @param integer $shopSoldProductQuantity
     * @return SoldProduct
     */
    public function setSoldProductQuantity($shopSoldProductQuantity)
    {
        $this->shop_sold_product_quantity = $shopSoldProductQuantity;

        return $this;
    }

    /**
     * Get shop_sold_product_quantity
     *
     * @return integer 
     */
    public function getShopSoldProductQuantity()
    {
        return $this->shop_sold_product_quantity;
    }

    /**
     * Set shop_sold_product_date_created
     *
     * @param \DateTime $shopSoldProductDateCreated
     * @return ShopSoldProduct
     */
    public function setShopSoldProductDateCreated($shopSoldProductDateCreated)
    {
        $this->shop_sold_product_date_created = $shopSoldProductDateCreated;

        return $this;
    }

    /**
     * Get shop_sold_product_date_created
     *
     * @return \DateTime 
     */
    public function getShopSoldProductDateCreated()
    {
        return $this->shop_sold_product_date_created;
    }

    /**
     * Set shop_sold_product_date_modified
     *
     * @param \DateTime $shopSoldProductDateModified
     * @return ShopSoldProduct
     */
    public function setShopSoldProductDateModified($shopSoldProductDateModified)
    {
        $this->shop_sold_product_date_modified = $shopSoldProductDateModified;

        return $this;
    }

    /**
     * Get shop_sold_product_date_modified
     *
     * @return \DateTime 
     */
    public function getShopSoldProductDateModified()
    {
        return $this->shop_sold_product_date_modified;
    }

    /**
     * Set product
     *
     * @param \MilesApart\AdminBundle\Entity\Product $product
     * @return SoldProduct
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

    /**
     * Set shop_sold_product_quantity
     *
     * @param integer $shopSoldProductQuantity
     * @return ShopSoldProduct
     */
    public function setShopSoldProductQuantity($shopSoldProductQuantity)
    {
        $this->shop_sold_product_quantity = $shopSoldProductQuantity;

        return $this;
    }
}
