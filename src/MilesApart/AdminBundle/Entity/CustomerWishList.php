<?php
// src/MilesApart/AdminBundle/Entity/CustomerWishList.php -- Defines the customer type object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\CustomerWishListRepository")
 * @ORM\Table(name="customer_wish_list")
 * @ORM\HasLifecycleCallbacks()
 */

class CustomerWishList
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
     * @ORM\Column(type="string", length=100, unique=false, nullable=true)
     */
    protected $customer_wish_list_name;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $customer_wish_list_date_created;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $customer_wish_list_date_modified;

    /**
     * @ORM\ManyToOne(targetEntity="Customer", inversedBy="customer_wish_list")
     * @ORM\JoinTable(name="customer")
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id")
     */
    protected $customer;

    /**
     * @ORM\OneToMany(targetEntity="CustomerWishListProduct", mappedBy="customer_wish_list")
     */
    protected $customer_wish_list_product;



    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        $this->setCustomerWishListDateModified(new \DateTime(date('Y-m-d H:i:s')));

        if($this->getCustomerWishListDateCreated() == null)
        {
            $this->setCustomerWishListDateCreated(new \DateTime(date('Y-m-d H:i:s')));
        }
    }

    

    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //Wish list name
        $metadata->addPropertyConstraint('customer_wish_list_name', new Assert\NotBlank());
        $metadata->addPropertyConstraint('customer_wish_list_name', new Assert\Length(array(
            'min'        => 2,
            'max'        => 100,
            'minMessage' => 'The wish list name must be at least {{ limit }} characters length',
            'maxMessage' => 'The wish list name cannot be longer than {{ limit }} characters length',
        )));


    }


    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->customer_wish_list_product = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set customer_wish_list_name
     *
     * @param string $customerWishListName
     * @return CustomerWishList
     */
    public function setCustomerWishListName($customerWishListName)
    {
        $this->customer_wish_list_name = $customerWishListName;
    
        return $this;
    }

    /**
     * Get customer_wish_list_name
     *
     * @return string 
     */
    public function getCustomerWishListName()
    {
        return $this->customer_wish_list_name;
    }

    /**
     * Set customer_wish_list_date_created
     *
     * @param \DateTime $customerWishListDateCreated
     * @return CustomerWishList
     */
    public function setCustomerWishListDateCreated($customerWishListDateCreated)
    {
        $this->customer_wish_list_date_created = $customerWishListDateCreated;
    
        return $this;
    }

    /**
     * Get customer_wish_list_date_created
     *
     * @return \DateTime 
     */
    public function getCustomerWishListDateCreated()
    {
        return $this->customer_wish_list_date_created;
    }

    /**
     * Set customer_wish_list_date_modified
     *
     * @param \DateTime $customerWishListDateModified
     * @return CustomerWishList
     */
    public function setCustomerWishListDateModified($customerWishListDateModified)
    {
        $this->customer_wish_list_date_modified = $customerWishListDateModified;
    
        return $this;
    }

    /**
     * Get customer_wish_list_date_modified
     *
     * @return \DateTime 
     */
    public function getCustomerWishListDateModified()
    {
        return $this->customer_wish_list_date_modified;
    }

    /**
     * Set customer
     *
     * @param \MilesApart\AdminBundle\Entity\Customer $customer
     * @return CustomerWishList
     */
    public function setCustomer(\MilesApart\AdminBundle\Entity\Customer $customer = null)
    {
        $this->customer = $customer;
    
        return $this;
    }

    /**
     * Get customer
     *
     * @return \MilesApart\AdminBundle\Entity\Customer 
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * Add customer_wish_list_product
     *
     * @param \MilesApart\AdminBundle\Entity\CustomerWishListProduct $customerWishListProduct
     * @return CustomerWishList
     */
    public function addCustomerWishListProduct(\MilesApart\AdminBundle\Entity\CustomerWishListProduct $customerWishListProduct)
    {
        $this->customer_wish_list_product[] = $customerWishListProduct;
    
        return $this;
    }

    /**
     * Remove customer_wish_list_product
     *
     * @param \MilesApart\AdminBundle\Entity\CustomerWishListProduct $customerWishListProduct
     */
    public function removeCustomerWishListProduct(\MilesApart\AdminBundle\Entity\CustomerWishListProduct $customerWishListProduct)
    {
        $this->customer_wish_list_product->removeElement($customerWishListProduct);
    }

    /**
     * Get customer_wish_list_product
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCustomerWishListProduct()
    {
        return $this->customer_wish_list_product;
    }
}