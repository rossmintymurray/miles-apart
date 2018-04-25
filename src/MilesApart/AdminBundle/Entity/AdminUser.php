<?php
// src/MilesApart/AdminBundle/Entity/AdminUser.php -- Defines the admin user object

namespace MilesApart\AdminBundle\Entity;


use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\AdminUserRepository")
 * @ORM\Table(name="admin_user")
 * @ORM\HasLifecycleCallbacks()
 */

class AdminUser implements UserInterface, \Serializable
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
     * @ORM\Column(type="string", length=20, unique=true, nullable=false)
     */
    protected $username;

    /**
     * @ORM\Column(type="string", length=204, nullable=false)
     */
    protected $password;

    /**
     * @ORM\ManyToOne(targetEntity="Employee", inversedBy="admin_user")
     * @ORM\JoinTable(name="employee")
     * @ORM\JoinColumn(name="employee_id", referencedColumnName="id")
     */
    protected $employee;

    /**
     * @ORM\ManyToOne(targetEntity="AdminUserType", inversedBy="admin_user")
     * @ORM\JoinTable(name="admin_user_type")
     * @ORM\JoinColumn(name="admin_user_type_id", referencedColumnName="id")
     */
    protected $admin_user_type;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $admin_user_date_created;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $admin_user_date_modified;

    /**
     * @ORM\OneToMany(targetEntity="ProductAnswer", mappedBy="admin_user")
     */
    protected $product_answer;

    /**
     * @ORM\OneToMany(targetEntity="ProductReview", mappedBy="admin_user")
     */
    protected $product_review;

    /**
     * @ORM\OneToMany(targetEntity="ProductPrice", mappedBy="admin_user")
     */
    protected $product_price;

    /**
     * @ORM\OneToMany(targetEntity="Product", mappedBy="product_creator_admin_user")
     */
    protected $product_creator;

    /**
     * @ORM\OneToMany(targetEntity="Product", mappedBy="product_modifier_admin_user")
     */
    protected $product_modifier;

    /**
     * @ORM\OneToMany(targetEntity="CustomerOrderProduct", mappedBy="customer_order_state_changed_admin_user")
     */
    protected $customer_order_product;

    /**
     * @ORM\OneToMany(targetEntity="Promotion", mappedBy="promotion_creator_admin_user")
     */
    protected $promotion_creator;

    /**
     * @ORM\OneToMany(targetEntity="Promotion", mappedBy="promotion_modifier_admin_user")
     */
    protected $promotion_modifier;

    /**
     * @ORM\OneToMany(targetEntity="MarketingEmail", mappedBy="marketing_email_creator_admin_user")
     */
    protected $marketing_email_creator;

    /**
     * @ORM\OneToMany(targetEntity="MarketingEmail", mappedBy="marketing_email_modifier_admin_user")
     */
    protected $marketing_email_modifier;

    /**
     * @ORM\OneToMany(targetEntity="MarketingEmailComponent", mappedBy="marketing_email_component_creator_admin_user")
     */
    protected $marketing_email_component_creator;

    /**
     * @ORM\OneToMany(targetEntity="MarketingEmailComponent", mappedBy="marketing_email_component_modifier_admin_user")
     */
    protected $marketing_email_component_modifier;

    /**
     * @ORM\OneToMany(targetEntity="EmailSendList", mappedBy="email_send_list_creator_admin_user")
     */
    protected $email_send_list_creator;

    /**
     * @ORM\OneToMany(targetEntity="EmailSendList", mappedBy="email_send_list_modifier_admin_user")
     */
    protected $email_send_list_modifier;

    /**
     * @ORM\OneToMany(targetEntity="EmployeeHoliday", mappedBy="employee_holiday_authorised_by")
     */
    protected $employee_holiday;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;



    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        $this->setAdminUserDateModified(new \DateTime(date('Y-m-d H:i:s')));

        if($this->getAdminUserDateCreated() == null)
        {
            $this->setAdminUserDateCreated(new \DateTime(date('Y-m-d H:i:s')));
        }
    }



    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //Admin user username
        $metadata->addPropertyConstraint('username', new Assert\NotBlank());
        $metadata->addPropertyConstraint('username', new Assert\Length(array(
            'min'        => 4,
            'max'        => 50,
            'minMessage' => 'Your username must be at least {{ limit }} characters length',
            'maxMessage' => 'Your username cannot be longer than {{ limit }} characters length',
        )));

        //Admin user password
        $metadata->addPropertyConstraint('password', new Assert\NotBlank());
        $metadata->addPropertyConstraint('password', new Assert\Length(array(
            'min'        => 4,
            'max'        => 4096,
            'minMessage' => 'Your password must be at least {{ limit }} characters length',
            'maxMessage' => 'Your password cannot be longer than {{ limit }} characters length',
        )));

    }

    

    /**
     * Constructor
     */
    public function __construct()
    {
    
        $this->product_answer = new \Doctrine\Common\Collections\ArrayCollection();
        $this->product_price = new \Doctrine\Common\Collections\ArrayCollection();
        $this->product_creator = new \Doctrine\Common\Collections\ArrayCollection();
        $this->product_modifier = new \Doctrine\Common\Collections\ArrayCollection();
        $this->customer_order_product = new \Doctrine\Common\Collections\ArrayCollection();
        $this->promotion_creator = new \Doctrine\Common\Collections\ArrayCollection();
        $this->promotion_modifier = new \Doctrine\Common\Collections\ArrayCollection();
        $this->marketing_email_creator = new \Doctrine\Common\Collections\ArrayCollection();
        $this->marketing_email_modifier = new \Doctrine\Common\Collections\ArrayCollection();
        $this->marketing_email_component_creator = new \Doctrine\Common\Collections\ArrayCollection();
        $this->marketing_email_component_modifier = new \Doctrine\Common\Collections\ArrayCollection();
        $this->marketing_email_send_list_creator = new \Doctrine\Common\Collections\ArrayCollection();
        $this->marketing_email_send_list_modifier = new \Doctrine\Common\Collections\ArrayCollection();
        $this->employee_holiday = new \Doctrine\Common\Collections\ArrayCollection();
        $this->isActive = true;
       
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
     * Set username
     *
     * 
     * @param string $username
     * @return AdminUser
     */
    public function setUsername($username)
    {
        $this->username = $username;
    
        return $this;
    }

    /**
     * Get username
     *
     * @inheritDoc
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }


     /**
     * @inheritDoc
     */
    public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return AdminUser
     */
    public function setPassword($password)
    {
        $this->password = $password;
    
        return $this;
    }

    /**
     * Get password
     * @inheritDoc
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set admin_user_date_created
     *
     * @param \DateTime $adminUserDateCreated
     * @return AdminUser
     */
    public function setAdminUserDateCreated($adminUserDateCreated)
    {
        $this->admin_user_date_created = $adminUserDateCreated;
    
        return $this;
    }

    /**
     * Get admin_user_date_created
     *
     * @return \DateTime 
     */
    public function getAdminUserDateCreated()
    {
        return $this->admin_user_date_created;
    }

    /**
     * Set admin_user_date_modified
     *
     * @param \DateTime $adminUserDateModified
     * @return AdminUser
     */
    public function setAdminUserDateModified($adminUserDateModified)
    {
        $this->admin_user_date_modified = $adminUserDateModified;
    
        return $this;
    }

    /**
     * Get admin_user_date_modified
     *
     * @return \DateTime 
     */
    public function getAdminUserDateModified()
    {
        return $this->admin_user_date_modified;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return AdminUser
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    
        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean 
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set employee
     *
     * @param \MilesApart\AdminBundle\Entity\Employee $employee
     * @return AdminUser
     */
    public function setEmployee(\MilesApart\AdminBundle\Entity\Employee $employee = null)
    {
        $this->employee = $employee;
    
        return $this;
    }

    /**
     * Get employee
     *
     * @return \MilesApart\AdminBundle\Entity\Employee 
     */
    public function getEmployee()
    {
        return $this->employee;
    }

    /**
     * Set admin_user_type
     *
     * @param \MilesApart\AdminBundle\Entity\AdminUserType $adminUserType
     * @return AdminUser
     */
    public function setAdminUserType(\MilesApart\AdminBundle\Entity\AdminUserType $adminUserType = null)
    {
        $this->admin_user_type = $adminUserType;
    
        return $this;
    }

    /**
     * Get admin_user_type
     *
     * @return \MilesApart\AdminBundle\Entity\AdminUserType 
     */
    public function getAdminUserType()
    {
        return $this->admin_user_type;
    }

    /**
     * Add product_answer
     *
     * @param \MilesApart\AdminBundle\Entity\ProductAnswer $productAnswer
     * @return AdminUser
     */
    public function addProductAnswer(\MilesApart\AdminBundle\Entity\ProductAnswer $productAnswer)
    {
        $this->product_answer[] = $productAnswer;
    
        return $this;
    }

    /**
     * Remove product_answer
     *
     * @param \MilesApart\AdminBundle\Entity\ProductAnswer $productAnswer
     */
    public function removeProductAnswer(\MilesApart\AdminBundle\Entity\ProductAnswer $productAnswer)
    {
        $this->product_answer->removeElement($productAnswer);
    }

    /**
     * Get product_answer
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProductAnswer()
    {
        return $this->product_answer;
    }

    /**
     * Add product_price
     *
     * @param \MilesApart\AdminBundle\Entity\ProductPrice $productPrice
     * @return AdminUser
     */
    public function addProductPrice(\MilesApart\AdminBundle\Entity\ProductPrice $productPrice)
    {
        $this->product_price[] = $productPrice;
    
        return $this;
    }

    /**
     * Remove product_price
     *
     * @param \MilesApart\AdminBundle\Entity\ProductPrice $productPrice
     */
    public function removeProductPrice(\MilesApart\AdminBundle\Entity\ProductPrice $productPrice)
    {
        $this->product_price->removeElement($productPrice);
    }

    /**
     * Get product_price
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProductPrice()
    {
        return $this->product_price;
    }

    /**
     * Add product_creator
     *
     * @param \MilesApart\AdminBundle\Entity\Product $productCreator
     * @return AdminUser
     */
    public function addProductCreator(\MilesApart\AdminBundle\Entity\Product $productCreator)
    {
        $this->product_creator[] = $productCreator;
    
        return $this;
    }

    /**
     * Remove product_creator
     *
     * @param \MilesApart\AdminBundle\Entity\Product $productCreator
     */
    public function removeProductCreator(\MilesApart\AdminBundle\Entity\Product $productCreator)
    {
        $this->product_creator->removeElement($productCreator);
    }

    /**
     * Get product_creator
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProductCreator()
    {
        return $this->product_creator;
    }

    /**
     * Add product_modifier
     *
     * @param \MilesApart\AdminBundle\Entity\Product $productModifier
     * @return AdminUser
     */
    public function addProductModifier(\MilesApart\AdminBundle\Entity\Product $productModifier)
    {
        $this->product_modifier[] = $productModifier;
    
        return $this;
    }

    /**
     * Remove product_modifier
     *
     * @param \MilesApart\AdminBundle\Entity\Product $productModifier
     */
    public function removeProductModifier(\MilesApart\AdminBundle\Entity\Product $productModifier)
    {
        $this->product_modifier->removeElement($productModifier);
    }

    /**
     * Get product_modifier
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProductModifier()
    {
        return $this->product_modifier;
    }

    /**
     * Add customer_order_product
     *
     * @param \MilesApart\AdminBundle\Entity\CustomerOrderProduct $customerOrderProduct
     * @return AdminUser
     */
    public function addCustomerOrderProduct(\MilesApart\AdminBundle\Entity\CustomerOrderProduct $customerOrderProduct)
    {
        $this->customer_order_product[] = $customerOrderProduct;
    
        return $this;
    }

    /**
     * Remove customer_order_product
     *
     * @param \MilesApart\AdminBundle\Entity\CustomerOrderProduct $customerOrderProduct
     */
    public function removeCustomerOrderProduct(\MilesApart\AdminBundle\Entity\CustomerOrderProduct $customerOrderProduct)
    {
        $this->customer_order_product->removeElement($customerOrderProduct);
    }

    /**
     * Get customer_order_product
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCustomerOrderProduct()
    {
        return $this->customer_order_product;
    }

    /**
     * Add promotion_creator
     *
     * @param \MilesApart\AdminBundle\Entity\Promotion $promotionCreator
     * @return AdminUser
     */
    public function addPromotionCreator(\MilesApart\AdminBundle\Entity\Promotion $promotionCreator)
    {
        $this->promotion_creator[] = $promotionCreator;
    
        return $this;
    }

    /**
     * Remove promotion_creator
     *
     * @param \MilesApart\AdminBundle\Entity\Promotion $promotionCreator
     */
    public function removePromotionCreator(\MilesApart\AdminBundle\Entity\Promotion $promotionCreator)
    {
        $this->promotion_creator->removeElement($promotionCreator);
    }

    /**
     * Get promotion_creator
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPromotionCreator()
    {
        return $this->promotion_creator;
    }

    /**
     * Add promotion_modifier
     *
     * @param \MilesApart\AdminBundle\Entity\Promotion $promotionModifier
     * @return AdminUser
     */
    public function addPromotionModifier(\MilesApart\AdminBundle\Entity\Promotion $promotionModifier)
    {
        $this->promotion_modifier[] = $promotionModifier;
    
        return $this;
    }

    /**
     * Remove promotion_modifier
     *
     * @param \MilesApart\AdminBundle\Entity\Promotion $promotionModifier
     */
    public function removePromotionModifier(\MilesApart\AdminBundle\Entity\Promotion $promotionModifier)
    {
        $this->promotion_modifier->removeElement($promotionModifier);
    }

    /**
     * Get promotion_modifier
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPromotionModifier()
    {
        return $this->promotion_modifier;
    }

    /**
     * Add marketing_email_creator
     *
     * @param \MilesApart\AdminBundle\Entity\MarketingEmail $marketingEmailCreator
     * @return AdminUser
     */
    public function addMarketingEmailCreator(\MilesApart\AdminBundle\Entity\MarketingEmail $marketingEmailCreator)
    {
        $this->marketing_email_creator[] = $marketingEmailCreator;
    
        return $this;
    }

    /**
     * Remove marketing_email_creator
     *
     * @param \MilesApart\AdminBundle\Entity\MarketingEmail $marketingEmailCreator
     */
    public function removeMarketingEmailCreator(\MilesApart\AdminBundle\Entity\MarketingEmail $marketingEmailCreator)
    {
        $this->marketing_email_creator->removeElement($marketingEmailCreator);
    }

    /**
     * Get marketing_email_creator
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMarketingEmailCreator()
    {
        return $this->marketing_email_creator;
    }

    /**
     * Add marketing_email_modifier
     *
     * @param \MilesApart\AdminBundle\Entity\MarketingEmail $marketingEmailModifier
     * @return AdminUser
     */
    public function addMarketingEmailModifier(\MilesApart\AdminBundle\Entity\MarketingEmail $marketingEmailModifier)
    {
        $this->marketing_email_modifier[] = $marketingEmailModifier;
    
        return $this;
    }

    /**
     * Remove marketing_email_modifier
     *
     * @param \MilesApart\AdminBundle\Entity\MarketingEmail $marketingEmailModifier
     */
    public function removeMarketingEmailModifier(\MilesApart\AdminBundle\Entity\MarketingEmail $marketingEmailModifier)
    {
        $this->marketing_email_modifier->removeElement($marketingEmailModifier);
    }

    /**
     * Get marketing_email_modifier
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMarketingEmailModifier()
    {
        return $this->marketing_email_modifier;
    }

    /**
     * Add marketing_email_component_creator
     *
     * @param \MilesApart\AdminBundle\Entity\MarketingEmailComponent $marketingEmailComponentCreator
     * @return AdminUser
     */
    public function addMarketingEmailComponentCreator(\MilesApart\AdminBundle\Entity\MarketingEmailComponent $marketingEmailComponentCreator)
    {
        $this->marketing_email_component_creator[] = $marketingEmailComponentCreator;
    
        return $this;
    }

    /**
     * Remove marketing_email_component_creator
     *
     * @param \MilesApart\AdminBundle\Entity\MarketingEmailComponent $marketingEmailComponentCreator
     */
    public function removeMarketingEmailComponentCreator(\MilesApart\AdminBundle\Entity\MarketingEmailComponent $marketingEmailComponentCreator)
    {
        $this->marketing_email_component_creator->removeElement($marketingEmailComponentCreator);
    }

    /**
     * Get marketing_email_component_creator
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMarketingEmailComponentCreator()
    {
        return $this->marketing_email_component_creator;
    }

    /**
     * Add marketing_email_component_modifier
     *
     * @param \MilesApart\AdminBundle\Entity\MarketingEmailComponent $marketingEmailComponentModifier
     * @return AdminUser
     */
    public function addMarketingEmailComponentModifier(\MilesApart\AdminBundle\Entity\MarketingEmailComponent $marketingEmailComponentModifier)
    {
        $this->marketing_email_component_modifier[] = $marketingEmailComponentModifier;
    
        return $this;
    }

    /**
     * Remove marketing_email_component_modifier
     *
     * @param \MilesApart\AdminBundle\Entity\MarketingEmailComponent $marketingEmailComponentModifier
     */
    public function removeMarketingEmailComponentModifier(\MilesApart\AdminBundle\Entity\MarketingEmailComponent $marketingEmailComponentModifier)
    {
        $this->marketing_email_component_modifier->removeElement($marketingEmailComponentModifier);
    }

    /**
     * Get marketing_email_component_modifier
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMarketingEmailComponentModifier()
    {
        return $this->marketing_email_component_modifier;
    }

    /**
     * Add marketing_email_send_list_creator
     *
     * @param \MilesApart\AdminBundle\Entity\MarketingEmailComponent $marketingEmailSendListCreator
     * @return AdminUser
     */
    public function addMarketingEmailSendListCreator(\MilesApart\AdminBundle\Entity\MarketingEmailComponent $marketingEmailSendListCreator)
    {
        $this->marketing_email_send_list_creator[] = $marketingEmailSendListCreator;
    
        return $this;
    }

    /**
     * Remove marketing_email_send_list_creator
     *
     * @param \MilesApart\AdminBundle\Entity\MarketingEmailComponent $marketingEmailSendListCreator
     */
    public function removeMarketingEmailSendListCreator(\MilesApart\AdminBundle\Entity\MarketingEmailComponent $marketingEmailSendListCreator)
    {
        $this->marketing_email_send_list_creator->removeElement($marketingEmailSendListCreator);
    }

    /**
     * Get marketing_email_send_list_creator
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMarketingEmailSendListCreator()
    {
        return $this->marketing_email_send_list_creator;
    }

    /**
     * Add marketing_email_send_list_modifier
     *
     * @param \MilesApart\AdminBundle\Entity\MarketingEmailComponent $marketingEmailSendListModifier
     * @return AdminUser
     */
    public function addMarketingEmailSendListModifier(\MilesApart\AdminBundle\Entity\MarketingEmailComponent $marketingEmailSendListModifier)
    {
        $this->marketing_email_send_list_modifier[] = $marketingEmailSendListModifier;
    
        return $this;
    }

    /**
     * Remove marketing_email_send_list_modifier
     *
     * @param \MilesApart\AdminBundle\Entity\MarketingEmailComponent $marketingEmailSendListModifier
     */
    public function removeMarketingEmailSendListModifier(\MilesApart\AdminBundle\Entity\MarketingEmailComponent $marketingEmailSendListModifier)
    {
        $this->marketing_email_send_list_modifier->removeElement($marketingEmailSendListModifier);
    }

    /**
     * Get marketing_email_send_list_modifier
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMarketingEmailSendListModifier()
    {
        return $this->marketing_email_send_list_modifier;
    }

    /**
     * Add employee_holiday
     *
     * @param \MilesApart\AdminBundle\Entity\EmployeeHoliday $employeeHoliday
     * @return AdminUser
     */
    public function addEmployeeHoliday(\MilesApart\AdminBundle\Entity\EmployeeHoliday $employeeHoliday)
    {
        $this->employee_holiday[] = $employeeHoliday;
    
        return $this;
    }

    /**
     * Remove employee_holiday
     *
     * @param \MilesApart\AdminBundle\Entity\EmployeeHoliday $employeeHoliday
     */
    public function removeEmployeeHoliday(\MilesApart\AdminBundle\Entity\EmployeeHoliday $employeeHoliday)
    {
        $this->employee_holiday->removeElement($employeeHoliday);
    }

    /**
     * Get employee_holiday
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEmployeeHoliday()
    {
        return $this->employee_holiday;
    }

    /**
     * Add email_send_list_creator
     *
     * @param \MilesApart\AdminBundle\Entity\EmailSendList $emailSendListCreator
     * @return AdminUser
     */
    public function addEmailSendListCreator(\MilesApart\AdminBundle\Entity\EmailSendList $emailSendListCreator)
    {
        $this->email_send_list_creator[] = $emailSendListCreator;
    
        return $this;
    }

    /**
     * Remove email_send_list_creator
     *
     * @param \MilesApart\AdminBundle\Entity\EmailSendList $emailSendListCreator
     */
    public function removeEmailSendListCreator(\MilesApart\AdminBundle\Entity\EmailSendList $emailSendListCreator)
    {
        $this->email_send_list_creator->removeElement($emailSendListCreator);
    }

    /**
     * Get email_send_list_creator
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEmailSendListCreator()
    {
        return $this->email_send_list_creator;
    }

    /**
     * Add email_send_list_modifier
     *
     * @param \MilesApart\AdminBundle\Entity\EmailSendList $emailSendListModifier
     * @return AdminUser
     */
    public function addEmailSendListModifier(\MilesApart\AdminBundle\Entity\EmailSendList $emailSendListModifier)
    {
        $this->email_send_list_modifier[] = $emailSendListModifier;
    
        return $this;
    }

    /**
     * Remove email_send_list_modifier
     *
     * @param \MilesApart\AdminBundle\Entity\EmailSendList $emailSendListModifier
     */
    public function removeEmailSendListModifier(\MilesApart\AdminBundle\Entity\EmailSendList $emailSendListModifier)
    {
        $this->email_send_list_modifier->removeElement($emailSendListModifier);
    }

    /**
     * Get email_send_list_modifier
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEmailSendListModifier()
    {
        return $this->email_send_list_modifier;
    }

    /**
     * @inheritDoc
     */
    public function getRoles()
    {
        return array('ROLE_ADMIN');
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
    }

    /**
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            //$this->salt,
        ));
    }

    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            //$this->salt
        ) = unserialize($serialized);
    }
}