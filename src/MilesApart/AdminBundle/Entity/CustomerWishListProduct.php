<?php
// src/MilesApart/AdminBundle/Entity/CustomerWishListProduct.php -- Defines the customer type object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\CustomerWishListProductRepository")
 * @ORM\Table(name="customer_wish_list_product")
 * @ORM\HasLifecycleCallbacks()
 */

class CustomerWishListProduct
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
     * @ORM\Column(type="datetime")
     */
    protected $customer_wish_list_product_added;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $customer_wish_list_product_purchased;

    /**
     * @ORM\ManyToOne(targetEntity="CustomerWishList", inversedBy="customer_wish_list_product")
     * @ORM\JoinTable(name="customer_wish_list")
     * @ORM\JoinColumn(name="customer_wish_list_id", referencedColumnName="id")
     */
    protected $customer_wish_list;

    /**
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="customer_wish_list_product")
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
        if($this->getCustomerWishListProductAdded() == null)
        {
            $this->setCustomerWishListProductAdded(new \DateTime(date('Y-m-d H:i:s')));
        }
    }



    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {


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
     * Set customer_wish_list_product_added
     *
     * @param \DateTime $customerWishListProductAdded
     * @return CustomerWishListProduct
     */
    public function setCustomerWishListProductAdded($customerWishListProductAdded)
    {
        $this->customer_wish_list_product_added = $customerWishListProductAdded;
    
        return $this;
    }

    /**
     * Get customer_wish_list_product_added
     *
     * @return \DateTime 
     */
    public function getCustomerWishListProductAdded()
    {
        return $this->customer_wish_list_product_added;
    }

    /**
     * Set customer_wish_list_product_purchased
     *
     * @param boolean $customerWishListProductPurchased
     * @return CustomerWishListProduct
     */
    public function setCustomerWishListProductPurchased($customerWishListProductPurchased)
    {
        $this->customer_wish_list_product_purchased = $customerWishListProductPurchased;
    
        return $this;
    }

    /**
     * Get customer_wish_list_product_purchased
     *
     * @return boolean 
     */
    public function getCustomerWishListProductPurchased()
    {
        return $this->customer_wish_list_product_purchased;
    }

    /**
     * Set customer_wish_list
     *
     * @param \MilesApart\AdminBundle\Entity\CustomerWishList $customerWishList
     * @return CustomerWishListProduct
     */
    public function setCustomerWishList(\MilesApart\AdminBundle\Entity\CustomerWishList $customerWishList = null)
    {
        $this->customer_wish_list = $customerWishList;
    
        return $this;
    }

    /**
     * Get customer_wish_list
     *
     * @return \MilesApart\AdminBundle\Entity\CustomerWishList 
     */
    public function getCustomerWishList()
    {
        return $this->customer_wish_list;
    }

    /**
     * Set product
     *
     * @param \MilesApart\AdminBundle\Entity\Product $product
     * @return CustomerWishListProduct
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