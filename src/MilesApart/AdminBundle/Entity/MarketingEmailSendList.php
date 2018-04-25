<?php
// src/MilesApart/AdminBundle/Entity/MarketingEmailSendList.php -- Defines the customer type object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\MarketingEmailSendListRepository")
 * @ORM\Table(name="marketing_email_send_list")
 * @ORM\HasLifecycleCallbacks()
 */

class MarketingEmailSendList
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
     * @ORM\ManyToOne(targetEntity="MarketingEmail", inversedBy="marketing_email_send_list")
     * @ORM\JoinTable(name="marketing_email")
     * @ORM\JoinColumn(name="marketing_email_id", referencedColumnName="id")
     */
    protected $marketing_email;

    /**
     * @ORM\ManyToOne(targetEntity="EmailSendList", inversedBy="marketing_email_send_list")
     * @ORM\JoinTable(name="email_send_list")
     * @ORM\JoinColumn(name="email_send_list_id", referencedColumnName="id")
     */
    protected $email_send_list;

    /**
     * @ORM\Column(type="datetime", unique=false, nullable=true)
     */
    protected $marketing_email_send_list_date_created;

    /**
     * @ORM\Column(type="datetime", unique=false, nullable=false)
     */
    protected $marketing_email_send_list_date_modified;


    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        $this->setMarketingEmailSendListModified(new \DateTime(date('Y-m-d H:i:s')));

        if($this->getMarketingEmailSendListCreated() == null)
        {
            $this->setMarketingEmailSendListCreated(new \DateTime(date('Y-m-d H:i:s')));
        }
    }


   
    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //Marketing email 
        $metadata->addPropertyConstraint('marketing_email', new Assert\Choice(array(
            'callback' => 'getMarketingEmail',
        )));

        //Email send list
        $metadata->addPropertyConstraint('email_send_list', new Assert\Choice(array(
            'callback' => 'getEmailSendList',
        )));
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
     * Set marketing_email_send_list_date_created
     *
     * @param \DateTime $marketingEmailSendListDateCreated
     * @return MarketingEmailSendList
     */
    public function setMarketingEmailSendListDateCreated($marketingEmailSendListDateCreated)
    {
        $this->marketing_email_send_list_date_created = $marketingEmailSendListDateCreated;
    
        return $this;
    }

    /**
     * Get marketing_email_send_list_date_created
     *
     * @return \DateTime 
     */
    public function getMarketingEmailSendListDateCreated()
    {
        return $this->marketing_email_send_list_date_created;
    }

    /**
     * Set marketing_email_send_list_date_modified
     *
     * @param \DateTime $marketingEmailSendListDateModified
     * @return MarketingEmailSendList
     */
    public function setMarketingEmailSendListDateModified($marketingEmailSendListDateModified)
    {
        $this->marketing_email_send_list_date_modified = $marketingEmailSendListDateModified;
    
        return $this;
    }

    /**
     * Get marketing_email_send_list_date_modified
     *
     * @return \DateTime 
     */
    public function getMarketingEmailSendListDateModified()
    {
        return $this->marketing_email_send_list_date_modified;
    }

    /**
     * Set marketing_email
     *
     * @param \MilesApart\AdminBundle\Entity\MarketingEmail $marketingEmail
     * @return MarketingEmailSendList
     */
    public function setMarketingEmail(\MilesApart\AdminBundle\Entity\MarketingEmail $marketingEmail = null)
    {
        $this->marketing_email = $marketingEmail;
    
        return $this;
    }

    /**
     * Get marketing_email
     *
     * @return \MilesApart\AdminBundle\Entity\MarketingEmail 
     */
    public function getMarketingEmail()
    {
        return $this->marketing_email;
    }

    /**
     * Set email_send_list
     *
     * @param \MilesApart\AdminBundle\Entity\EmailSendList $emailSendList
     * @return MarketingEmailSendList
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
}