<?php
// src/MilesApart/AdminBundle/Entity/EmailSendListCustomer.php -- Defines the customer type object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\EmailSendListCustomerRepository")
 * @ORM\Table(name="email_send_list_customer")
 * @ORM\HasLifecycleCallbacks()
 */

class EmailSendListCustomer
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
     * @ORM\Column(type="date", unique=false, nullable=true)
     */
    protected $email_send_list_customer_date_added;

    /**
     * @ORM\ManyToOne(targetEntity="EmailSendList", inversedBy="email_send_list_customer")
     * @ORM\JoinTable(name="email_send_list")
     * @ORM\JoinColumn(name="email_send_list_id", referencedColumnName="id")
     */
    protected $email_send_list;

    /**
     * @ORM\ManyToOne(targetEntity="Customer", inversedBy="email_send_list_customer")
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
        if($this->getMarketingEmailSendListCustomerDateAdded() == null)
        {
            $this->setMarketingEmailSendListCustomerDateAdded(new \DateTime(date('Y-m-d H:i:s')));
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
     * Set email_send_list_customer_date_added
     *
     * @param \DateTime $emailSendListCustomerDateAdded
     * @return EmailSendListCustomer
     */
    public function setEmailSendListCustomerDateAdded($emailSendListCustomerDateAdded)
    {
        $this->email_send_list_customer_date_added = $emailSendListCustomerDateAdded;
    
        return $this;
    }

    /**
     * Get email_send_list_customer_date_added
     *
     * @return \DateTime 
     */
    public function getEmailSendListCustomerDateAdded()
    {
        return $this->email_send_list_customer_date_added;
    }

    /**
     * Set email_send_list
     *
     * @param \MilesApart\AdminBundle\Entity\EmailSendList $emailSendList
     * @return EmailSendListCustomer
     */
    public function setEmailSendList(\MilesApart\AdminBundle\Entity\EmailSendList $emailSendList = null)
    {
        $this->email_send_list = $emailSendList;
    
        return $this;
    }

    /**
     * Get email_send_list
     *
     * @return \MilesApart\AdminBundle\Entity\EmailSendList 
     */
    public function getEmailSendList()
    {
        return $this->email_send_list;
    }

    /**
     * Set customer
     *
     * @param \MilesApart\AdminBundle\Entity\Customer $customer
     * @return EmailSendListCustomer
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