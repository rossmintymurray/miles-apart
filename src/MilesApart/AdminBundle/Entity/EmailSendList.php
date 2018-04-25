<?php
// src/MilesApart/AdminBundle/Entity/EmailSendList.php -- Defines the customer type object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\EmailSendListRepository")
 * @ORM\Table(name="email_send_list")
 * @ORM\HasLifecycleCallbacks()
 */

class EmailSendList
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
    protected $email_send_list_date_created;

    /**
     * @ORM\Column(type="datetime", unique=false, nullable=true)
     */
    protected $email_send_list_date_modified;

    /**
     * @ORM\Column(type="string", length=100, unique=false, nullable=false)
     */
    protected $email_send_list_name;

    /**
     * @ORM\OneToMany(targetEntity="MarketingEmailSendList", mappedBy="email_send_list")
     */
    protected $marketing_email_send_list;

    /**
     * @ORM\OneToMany(targetEntity="EmailSendListCustomer", mappedBy="email_send_list")
     */
    protected $email_send_list_customer;

    /**
     * @ORM\ManyToOne(targetEntity="AdminUser", inversedBy="email_send_list_creator")
     * @ORM\JoinTable(name="admin_user")
     * @ORM\JoinColumn(name="email_send_list_creator_admin_user_id", referencedColumnName="id")
     */
    protected $email_send_list_creator_admin_user;

    /**
     * @ORM\ManyToOne(targetEntity="AdminUser", inversedBy="email_send_list_modifier")
     * @ORM\JoinTable(name="admin_user")
     * @ORM\JoinColumn(name="email_send_list_modifier_admin_user_id", referencedColumnName="id")
     */
    protected $email_send_list_modifier_admin_user;



    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        $this->setEmailSendListDateModified(new \DateTime(date('Y-m-d H:i:s')));

        if($this->getEmailSendListDateCreated() == null)
        {
            $this->setEmailSendListDateCreated(new \DateTime(date('Y-m-d H:i:s')));
        }
    }



    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {

        //Email send list name
        $metadata->addPropertyConstraint('email_send_list_name', new Assert\NotBlank());
        $metadata->addPropertyConstraint('email_send_list_name', new Assert\Length(array(
            'min'        => 2,
            'max'        => 100,
            'minMessage' => 'The email send list name must be at least {{ limit }} characters length',
            'maxMessage' => 'The email send list name cannot be longer than {{ limit }} characters length',
        )));

    }


   
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->marketing_email_send_list = new \Doctrine\Common\Collections\ArrayCollection();
        $this->email_send_list_customer = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set email_send_list_date_created
     *
     * @param \DateTime $emailSendListDateCreated
     * @return EmailSendList
     */
    public function setEmailSendListDateCreated($emailSendListDateCreated)
    {
        $this->email_send_list_date_created = $emailSendListDateCreated;
    
        return $this;
    }

    /**
     * Get email_send_list_date_created
     *
     * @return \DateTime 
     */
    public function getEmailSendListDateCreated()
    {
        return $this->email_send_list_date_created;
    }

    /**
     * Set email_send_list_date_modified
     *
     * @param \DateTime $emailSendListDateModified
     * @return EmailSendList
     */
    public function setEmailSendListDateModified($emailSendListDateModified)
    {
        $this->email_send_list_date_modified = $emailSendListDateModified;
    
        return $this;
    }

    /**
     * Get email_send_list_date_modified
     *
     * @return \DateTime 
     */
    public function getEmailSendListDateModified()
    {
        return $this->email_send_list_date_modified;
    }

    /**
     * Set email_send_list_name
     *
     * @param string $emailSendListName
     * @return EmailSendList
     */
    public function setEmailSendListName($emailSendListName)
    {
        $this->email_send_list_name = $emailSendListName;
    
        return $this;
    }

    /**
     * Get email_send_list_name
     *
     * @return string 
     */
    public function getEmailSendListName()
    {
        return $this->email_send_list_name;
    }

    /**
     * Add marketing_email_send_list
     *
     * @param \MilesApart\AdminBundle\Entity\MarketingEmailSendList $marketingEmailSendList
     * @return EmailSendList
     */
    public function addMarketingEmailSendList(\MilesApart\AdminBundle\Entity\MarketingEmailSendList $marketingEmailSendList)
    {
        $this->marketing_email_send_list[] = $marketingEmailSendList;
    
        return $this;
    }

    /**
     * Remove marketing_email_send_list
     *
     * @param \MilesApart\AdminBundle\Entity\MarketingEmailSendList $marketingEmailSendList
     */
    public function removeMarketingEmailSendList(\MilesApart\AdminBundle\Entity\MarketingEmailSendList $marketingEmailSendList)
    {
        $this->marketing_email_send_list->removeElement($marketingEmailSendList);
    }

    /**
     * Get marketing_email_send_list
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMarketingEmailSendList()
    {
        return $this->marketing_email_send_list;
    }

    /**
     * Add email_send_list_customer
     *
     * @param \MilesApart\AdminBundle\Entity\EmailSendListCustomer $emailSendListCustomer
     * @return EmailSendList
     */
    public function addEmailSendListCustomer(\MilesApart\AdminBundle\Entity\EmailSendListCustomer $emailSendListCustomer)
    {
        $this->email_send_list_customer[] = $emailSendListCustomer;
    
        return $this;
    }

    /**
     * Remove email_send_list_customer
     *
     * @param \MilesApart\AdminBundle\Entity\EmailSendListCustomer $emailSendListCustomer
     */
    public function removeEmailSendListCustomer(\MilesApart\AdminBundle\Entity\EmailSendListCustomer $emailSendListCustomer)
    {
        $this->email_send_list_customer->removeElement($emailSendListCustomer);
    }

    /**
     * Get email_send_list_customer
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEmailSendListCustomer()
    {
        return $this->email_send_list_customer;
    }

    /**
     * Set email_send_list_creator_admin_user
     *
     * @param \MilesApart\AdminBundle\Entity\AdminUser $emailSendListCreatorAdminUser
     * @return EmailSendList
     */
    public function setEmailSendListCreatorAdminUser(\MilesApart\AdminBundle\Entity\AdminUser $emailSendListCreatorAdminUser = null)
    {
        $this->email_send_list_creator_admin_user = $emailSendListCreatorAdminUser;
    
        return $this;
    }

    /**
     * Get email_send_list_creator_admin_user
     *
     * @return \MilesApart\AdminBundle\Entity\AdminUser 
     */
    public function getEmailSendListCreatorAdminUser()
    {
        return $this->email_send_list_creator_admin_user;
    }

    /**
     * Set email_send_list_modifier_admin_user
     *
     * @param \MilesApart\AdminBundle\Entity\AdminUser $emailSendListModifierAdminUser
     * @return EmailSendList
     */
    public function setEmailSendListModifierAdminUser(\MilesApart\AdminBundle\Entity\AdminUser $emailSendListModifierAdminUser = null)
    {
        $this->email_send_list_modifier_admin_user = $emailSendListModifierAdminUser;
    
        return $this;
    }

    /**
     * Get email_send_list_modifier_admin_user
     *
     * @return \MilesApart\AdminBundle\Entity\AdminUser 
     */
    public function getEmailSendListModifierAdminUser()
    {
        return $this->email_send_list_modifier_admin_user;
    }
}