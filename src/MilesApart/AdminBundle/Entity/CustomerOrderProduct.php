<?php
// src/MilesApart/AdminBundle/Entity/CustomerOrderProduct.php -- Defines the customer type object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\CustomerOrderProductRepository")
 * @ORM\Table(name="customer_order_product")
 * @ORM\HasLifecycleCallbacks()
 */

class CustomerOrderProduct
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
    protected $customer_order_product_date_modified;

    /**
     * @ORM\Column(type="datetime", unique=false, nullable=false)
     */
    protected $customer_order_product_date_created;

    /**
     * @ORM\Column(type="integer")
     */
    protected $customer_order_product_quantity;

    /**
     * @ORM\ManyToOne(targetEntity="AdminUser", inversedBy="customer_order_product")
     * @ORM\JoinTable(name="admin_user")
     * @ORM\JoinColumn(name="admin_user_id", referencedColumnName="id")
     */
    protected $customer_order_state_changed_admin_user;

    /**
     * @ORM\ManyToOne(targetEntity="CustomerOrder", inversedBy="customer_order_product", cascade={"persist"})
     * @ORM\JoinTable(name="customer_order")
     * @ORM\JoinColumn(name="customer_order_id", referencedColumnName="id")
     */
    protected $customer_order;

    /**
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="customer_order_product")
     * @ORM\JoinTable(name="product")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    protected $product;

    /**
     * @ORM\ManyToOne(targetEntity="CustomerOrderProductState", inversedBy="customer_order_product")
     * @ORM\JoinTable(name="customer_order_product_state")
     * @ORM\JoinColumn(name="customer_order_product_state_id", referencedColumnName="id")
     */
    protected $customer_order_product_state;

    /**
     * @ORM\OneToMany(targetEntity="ReturnedProduct", mappedBy="customer_order_product")
     */
    protected $returned_product;



    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        $this->setCustomerOrderProductDateModified(new \DateTime(date('Y-m-d H:i:s')));

        if($this->getCustomerOrderProductDateCreated() == null)
        {
            $this->setCustomerOrderProductDateCreated(new \DateTime(date('Y-m-d H:i:s')));
        }
    }

    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        
        $metadata->addPropertyConstraint('customer_order_product_quantity', new Assert\Range(array(
            'min'        => 1,
            'max'        => 50,
            'minMessage' => 'You cannot order less than {{ limit }} products',
            'maxMessage' => 'You cannot order more than {{ limit }} of one product',
        )));

        //Admin user
        $metadata->addPropertyConstraint('customer_order_state_changed_admin_user', new Assert\Choice(array(
            'callback' => 'getAdminUser',
        )));

    }


    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->returned_product = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set customer_order_product_quantity
     *
     * @param integer $customerOrderProductQuantity
     * @return CustomerOrderProduct
     */
    public function setCustomerOrderProductQuantity($customerOrderProductQuantity)
    {
        $this->customer_order_product_quantity = $customerOrderProductQuantity;
    
        return $this;
    }

    /**
     * Get customer_order_product_quantity
     *
     * @return integer 
     */
    public function getCustomerOrderProductQuantity()
    {
        return $this->customer_order_product_quantity;
    }

    /**
     * Set customer_order_state_changed_admin_user
     *
     * @param \MilesApart\AdminBundle\Entity\AdminUser $customerOrderStateChangedAdminUser
     * @return CustomerOrderProduct
     */
    public function setCustomerOrderStateChangedAdminUser(\MilesApart\AdminBundle\Entity\AdminUser $customerOrderStateChangedAdminUser = null)
    {
        $this->customer_order_state_changed_admin_user = $customerOrderStateChangedAdminUser;
    
        return $this;
    }

    /**
     * Get customer_order_state_changed_admin_user
     *
     * @return \MilesApart\AdminBundle\Entity\AdminUser 
     */
    public function getCustomerOrderStateChangedAdminUser()
    {
        return $this->customer_order_state_changed_admin_user;
    }

    /**
     * Set customer_order
     *
     * @param \MilesApart\AdminBundle\Entity\CustomerOrder $customerOrder
     * @return CustomerOrderProduct
     */
    public function setCustomerOrder(\MilesApart\AdminBundle\Entity\CustomerOrder $customerOrder = null)
    {
        $this->customer_order = $customerOrder;
    
        return $this;
    }

    /**
     * Get customer_order
     *
     * @return \MilesApart\AdminBundle\Entity\CustomerOrder 
     */
    public function getCustomerOrder()
    {
        return $this->customer_order;
    }

    /**
     * Set product
     *
     * @param \MilesApart\AdminBundle\Entity\Product $product
     * @return CustomerOrderProduct
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
     * Set customer_order_product_state
     *
     * @param \MilesApart\AdminBundle\Entity\CustomerOrderState $customerOrderProductState
     * @return CustomerOrderProduct
     */
    public function setCustomerOrderProductState(\MilesApart\AdminBundle\Entity\CustomerOrderProductState $customerOrderProductState = null)
    {
        $this->customer_order_product_state = $customerOrderProductState;
    
        return $this;
    }

    /**
     * Get customer_order_product_state
     *
     * @return \MilesApart\AdminBundle\Entity\CustomerOrderState 
     */
    public function getCustomerOrderProductState()
    {
        return $this->customer_order_product_state;
    }

    /**
     * Add returned_product
     *
     * @param \MilesApart\AdminBundle\Entity\ReturnedProduct $returnedProduct
     * @return CustomerOrderProduct
     */
    public function addReturnedProduct(\MilesApart\AdminBundle\Entity\ReturnedProduct $returnedProduct)
    {
        $this->returned_product[] = $returnedProduct;
    
        return $this;
    }

    /**
     * Remove returned_product
     *
     * @param \MilesApart\AdminBundle\Entity\ReturnedProduct $returnedProduct
     */
    public function removeReturnedProduct(\MilesApart\AdminBundle\Entity\ReturnedProduct $returnedProduct)
    {
        $this->returned_product->removeElement($returnedProduct);
    }

    /**
     * Get returned_product
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getReturnedProduct()
    {
        return $this->returned_product;
    }

    /**
     * Set customer_order_product_date_modified
     *
     * @param \DateTime $customerOrderProductDateModified
     * @return CustomerOrderProduct
     */
    public function setCustomerOrderProductDateModified($customerOrderProductDateModified)
    {
        $this->customer_order_product_date_modified = $customerOrderProductDateModified;

        return $this;
    }

    /**
     * Get customer_order_product_date_modified
     *
     * @return \DateTime 
     */
    public function getCustomerOrderProductDateModified()
    {
        return $this->customer_order_product_date_modified;
    }

    /**
     * Set customer_order_product_date_created
     *
     * @param \DateTime $customerOrderProductDateCreated
     * @return CustomerOrderProduct
     */
    public function setCustomerOrderProductDateCreated($customerOrderProductDateCreated)
    {
        $this->customer_order_product_date_created = $customerOrderProductDateCreated;

        return $this;
    }

    /**
     * Get customer_order_product_date_created
     *
     * @return \DateTime 
     */
    public function getCustomerOrderProductDateCreated()
    {
        return $this->customer_order_product_date_created;
    }

    public function getCustomerOrderProductPrice()
    {
        $product_price =  $this->getProduct()->getProductPriceByDateDecimal($this->getCustomerOrderProductDateCreated());

        return $product_price;
    }

    public function getCustomerOrderProductTotalPrice()
    {
        $total_price = $this->getCustomerOrderProductQuantity() * $this->getProduct()->getProductPriceByDateDecimal($this->getCustomerOrderProductDateCreated());

        return $total_price;
    }


    //Get product total price display
    public function getCustomerOrderProductTotalPriceDisplay()
    {
        $total_price = number_format($this->getCustomerOrderProductTotalPrice(), 2, '.', ',');
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

    //Get remaining quantity allowed to return.
    public function getCustomerOrderProductReturnRemaining()
    {

        $total_remaining = $this->getCustomerOrderProductQuantity();
        foreach($this->getReturnedProduct() as $value) {
            $total_remaining = $total_remaining - $value->getReturnedProductQuantity();
        }
         
        return $total_remaining;
    }
}
