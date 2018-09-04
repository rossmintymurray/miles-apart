<?php
// src/MilesApart/AdminBundle/Entity/ProductReview.php -- Defines the brand object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\ProductReviewRepository")
 * @ORM\Table(name="product_review")
 * @ORM\HasLifecycleCallbacks()
 */

class ProductReview
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
     * @ORM\Column(type="string", length=100, unique=false, nullable=false)
     */
    protected $product_review_title;

    /**
     * @ORM\Column(type="integer", unique=false, nullable=false)
     */
    protected $product_review_rating;

    /**
     * @ORM\Column(type="string", length=1000, nullable=false)
     */
    protected $product_review_content;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $product_review_approved;

    /**
     * @ORM\ManyToOne(targetEntity="AdminUser", inversedBy="product_review")
     * @ORM\JoinTable(name="admin_user")
     * @ORM\JoinColumn(name="admin_user_id", referencedColumnName="id")
     */
    protected $admin_user;

    /**
     * @ORM\Column(type="datetime", unique=false, nullable=true)
     */
    protected $product_review_date_created;

    /**
     * @ORM\Column(type="datetime", unique=false, nullable=false)
     */
    protected $product_review_date_modified;

    /**
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="product_review")
     * @ORM\JoinTable(name="product")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    protected $product;

    /**
     * @ORM\ManyToOne(targetEntity="Customer", inversedBy="product_review")
     * @ORM\JoinTable(name="customer")
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id")
     */
    protected $customer;


    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        $this->setProductReviewDateModified(new \DateTime(date('Y-m-d H:i:s')));

        if($this->getProductReviewDateCreated() == null)
        {
            $this->setProductReviewDateCreated(new \DateTime(date('Y-m-d H:i:s')));
        }
    }

    
    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //Review title
        $metadata->addPropertyConstraint('product_review_title', new Assert\NotBlank());
        $metadata->addPropertyConstraint('product_review_title', new Assert\Length(array(
            'min'        => 2,
            'max'        => 100,
            'minMessage' => 'The review title must be at least {{ limit }} characters length',
            'maxMessage' => 'The review title cannot be longer than {{ limit }} characters length',
        )));

        //Review rating
        $metadata->addPropertyConstraint('product_review_rating', new Assert\NotBlank());
        $metadata->addPropertyConstraint('product_review_rating', new Assert\Range(array(
            'min'        => 0,
            'max'        => 5,
            'minMessage' => 'The rating must be at least {{ limit }}',
            'maxMessage' => 'The rating cannot be higher than {{ limit }}',
        )));

        //Review content
        $metadata->addPropertyConstraint('product_review_content', new Assert\NotBlank());
        $metadata->addPropertyConstraint('product_review_content', new Assert\Length(array(
            'min'        => 2,
            'max'        => 1000,
            'minMessage' => 'The review content must be at least {{ limit }} characters length',
            'maxMessage' => 'The review content cannot be longer than {{ limit }} characters length',
        )));

        
        
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->product_review_approved = false;
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
     * Set product_review_approved
     *
     * @param boolean $productReviewApproved
     * @return ProductReview
     */
    public function setProductReviewApproved($productReviewApproved)
    {
        $this->product_review_approved = $productReviewApproved;
    
        return $this;
    }

    /**
     * Get product_review_approved
     *
     * @return boolean 
     */
    public function getProductReviewApproved()
    {
        return $this->product_review_approved;
    }

    /**
     * Set product_review_title
     *
     * @param string $productReviewTitle
     * @return ProductReview
     */
    public function setProductReviewTitle($productReviewTitle)
    {
        $this->product_review_title = $productReviewTitle;
    
        return $this;
    }

    /**
     * Get product_review_title
     *
     * @return string 
     */
    public function getProductReviewTitle()
    {
        return $this->product_review_title;
    }

    /**
     * Set product_review_rating
     *
     * @param integer $productReviewRating
     * @return ProductReview
     */
    public function setProductReviewRating($productReviewRating)
    {
        $this->product_review_rating = $productReviewRating;
    
        return $this;
    }

    /**
     * Get product_review_rating
     *
     * @return integer 
     */
    public function getProductReviewRating()
    {
        return $this->product_review_rating;
    }

    /**
     * Set product_review_content
     *
     * @param string $productReviewContent
     * @return ProductReview
     */
    public function setProductReviewContent($productReviewContent)
    {
        $this->product_review_content = $productReviewContent;
    
        return $this;
    }

    /**
     * Get product_review_content
     *
     * @return string 
     */
    public function getProductReviewContent()
    {
        return $this->product_review_content;
    }

    /**
     * Set admin_user
     *
     * @param \MilesApart\AdminBundle\Entity\AdminUser $adminUser
     * @return ProductReview
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
     * Set product_review_date_created
     *
     * @param \DateTime $productReviewDateCreated
     * @return ProductReview
     */
    public function setProductReviewDateCreated($productReviewDateCreated)
    {
        $this->product_review_date_created = $productReviewDateCreated;

        return $this;
    }

    /**
     * Get product_review_date_created
     *
     * @return \DateTime
     */
    public function getProductReviewDateCreated()
    {
        return $this->product_review_date_created;
    }

    /**
     * Set product_review_date_modified
     *
     * @param \DateTime $productReviewDateModified
     * @return ProductReview
     */
    public function setProductReviewDateModified($productReviewDateModified)
    {
        $this->product_review_date_modified = $productReviewDateModified;

        return $this;
    }

    /**
     * Get product_review_date_modified
     *
     * @return \DateTime
     */
    public function getProductReviewDateModified()
    {
        return $this->product_review_date_modified;
    }

    /**
     * Set product
     *
     * @param \MilesApart\AdminBundle\Entity\Product $product
     * @return ProductReview
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
     * Set customer
     *
     * @param \MilesApart\AdminBundle\Entity\Customer $customer
     * @return ProductReview
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
}