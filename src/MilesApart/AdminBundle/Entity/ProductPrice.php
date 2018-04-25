<?php
// src/MilesApart/AdminBundle/Entity/ProductPrice.php -- Defines the product price object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\ProductPriceRepository")
 * @ORM\Table(name="product_price")
 * @ORM\HasLifecycleCallbacks()
 */

class ProductPrice
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
     * @ORM\Column(type="datetime", unique=false, nullable=false)
     */
    protected $product_price_valid_from;

    /**
     * @ORM\Column(type="datetime", unique=false, nullable=true)
     */
    protected $product_price_valid_until;

    /**
     * @ORM\Column(type="decimal", precision=4, scale=2, nullable=false)
     */
    protected $product_price_value;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $product_price_is_special;

    /**
     * @ORM\ManyToOne(targetEntity="AdminUser", inversedBy="product_price")
     * @ORM\JoinTable(name="admin_user")
     * @ORM\JoinColumn(name="admin_user_id", referencedColumnName="id")
     */
    protected $admin_user;

    /**
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="product_price", cascade={"persist"})
     * @ORM\JoinTable(name="product")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    protected $product;

    /**
     * @ORM\Column(type="datetime", unique=false, nullable=true)
     */
    protected $product_price_date_created;

    /**
     * @ORM\Column(type="datetime", unique=false, nullable=false)
     */
    protected $product_price_date_modified;
    


    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        $this->setProductPriceDateModified(new \DateTime(date('Y-m-d H:i:s')));

        if($this->getProductPriceDateCreated() == null)
        {
            $this->setProductPriceDateCreated(new \DateTime(date('Y-m-d H:i:s')));
        }
    }



    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        
        //Product price valid from
        $metadata->addPropertyConstraint('product_price_valid_from', new Assert\NotBlank());
        $metadata->addPropertyConstraint('product_price_valid_from', new Assert\Date());

        //Product price valid until
        $metadata->addPropertyConstraint('product_price_valid_until', new Assert\Date());

        //Product price value
        $metadata->addPropertyConstraint('product_price_value', new Assert\NotBlank());

        
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
     * Set product_price_valid_from
     *
     * @param \DateTime $productPriceValidFrom
     * @return ProductPrice
     */
    public function setProductPriceValidFrom($productPriceValidFrom)
    {
        $this->product_price_valid_from = $productPriceValidFrom;
    
        return $this;
    }

    /**
     * Get product_price_valid_from
     *
     * @return \DateTime 
     */
    public function getProductPriceValidFrom()
    {
        return $this->product_price_valid_from;
    }

    /**
     * Set product_price_valid_until
     *
     * @param \DateTime $productPriceValidUntil
     * @return ProductPrice
     */
    public function setProductPriceValidUntil($productPriceValidUntil)
    {
        $this->product_price_valid_until = $productPriceValidUntil;
    
        return $this;
    }

    /**
     * Get product_price_valid_until
     *
     * @return \DateTime 
     */
    public function getProductPriceValidUntil()
    {
        return $this->product_price_valid_until;
    }

    /**
     * Set product_price_value
     *
     * @param float $productPriceValue
     * @return ProductPrice
     */
    public function setProductPriceValue($productPriceValue)
    {
        $this->product_price_value = $productPriceValue;
    
        return $this;
    }

    /**
     * Get product_price_value
     *
     * @return float 
     */
    public function getProductPriceValue()
    {
        return $this->product_price_value;
    }

    /**
     * Set product_price_is_special
     *
     * @param boolean $productPriceIsSpecial
     * @return ProductPrice
     */
    public function setProductPriceIsSpecial($productPriceIsSpecial)
    {
        $this->product_price_is_special = $productPriceIsSpecial;
    
        return $this;
    }

    /**
     * Get product_price_is_special
     *
     * @return boolean 
     */
    public function getProductPriceIsSpecial()
    {
        return $this->product_price_is_special;
    }

    /**
     * Set product_price_date_created
     *
     * @param \DateTime $productPriceDateCreated
     * @return ProductPrice
     */
    public function setProductPriceDateCreated($productPriceDateCreated)
    {
        $this->product_price_date_created = $productPriceDateCreated;
    
        return $this;
    }

    /**
     * Get product_price_date_created
     *
     * @return \DateTime 
     */
    public function getProductPriceDateCreated()
    {
        return $this->product_price_date_created;
    }

    /**
     * Set product_price_date_modified
     *
     * @param \DateTime $productPriceDateModified
     * @return ProductPrice
     */
    public function setProductPriceDateModified($productPriceDateModified)
    {
        $this->product_price_date_modified = $productPriceDateModified;
    
        return $this;
    }

    /**
     * Get product_price_date_modified
     *
     * @return \DateTime 
     */
    public function getProductPriceDateModified()
    {
        return $this->product_price_date_modified;
    }

    /**
     * Set admin_user
     *
     * @param \MilesApart\AdminBundle\Entity\AdminUser $adminUser
     * @return ProductPrice
     */
    public function setAdminUser(\MilesApart\AdminBundle\Entity\AdminUser $adminUser = null)
    {
        $this->admin_user = $adminUser;
    
        return $this;
    }

    /**
     * Get admin_user
     *
     * @return \MilesApart\AdminBundle\Entity\AdminUser 
     */
    public function getAdminUser()
    {
        return $this->admin_user;
    }

    /**
     * Set product
     *
     * @param \MilesApart\AdminBundle\Entity\Product $product
     * @return ProductPrice
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