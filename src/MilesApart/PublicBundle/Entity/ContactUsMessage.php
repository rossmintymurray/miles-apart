<?php
// src/MilesApart/PublicBundle/Entity/ContactUsMessage.php -- Defines the contact us message object

namespace MilesApart\PublicBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use EWZ\Bundle\RecaptchaBundle\Validator\Constraints as Recaptcha;

/**
 * @ORM\Entity(repositoryClass="MilesApart\BasketBundle\Entity\Repository\ContactUsMessageRepository")
 * @ORM\Table(name="contact_us_message")
 * @ORM\HasLifecycleCallbacks()
 */

class ContactUsMessage
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
     * @ORM\Column(type="datetime", unique=false, nullable=true)
     */
    protected $contact_us_message_date_created;

    /**
     * @ORM\Column(type="datetime", unique=false, nullable=true)
     */
    protected $contact_us_message_date_modified;

    /**
     *  @ORM\Column(type="string", length=200, unique=false, nullable=false)
     */
    protected $contact_us_message_name;

    /**
     * @ORM\Column(type="string", length=200, unique=false, nullable=false)
     */
    protected $contact_us_message_email_address;

    /**
     * @ORM\Column(type="string", length=3000, nullable=false)
     */
    protected $contact_us_message_content;

    /**
     * @Recaptcha\IsTrue
     */
    public $recaptcha;



    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        $this->setContactUsMessageDateModified(new \DateTime(date('Y-m-d H:i:s')));

        if($this->getContactUsMessageDateCreated() == null)
        {
            $this->setContactUsMessageDateCreated(new \DateTime(date('Y-m-d H:i:s')));
        }
    }



    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //Contact us message name
        $metadata->addPropertyConstraint('contact_us_message_name', new Assert\NotBlank());
        $metadata->addPropertyConstraint('contact_us_message_name', new Assert\Length(array(
            'min'        => 2,
            'max'        => 100,
            'minMessage' => 'The \'Your Name\' field must be at least {{ limit }} characters length',
            'maxMessage' => 'The \'Your Name\' field cannot be longer than {{ limit }} characters length',
        )));

        //Contact us message email address
        $metadata->addPropertyConstraint('contact_us_message_email_address', new Assert\NotBlank());
        $metadata->addPropertyConstraint('contact_us_message_email_address', new Assert\Email(array(
            'message' => 'The email address - "{{ value }}" - is not valid.',
            'checkMX' => true,
        )));

        //Contact us message name
        $metadata->addPropertyConstraint('contact_us_message_content', new Assert\NotBlank());
        $metadata->addPropertyConstraint('contact_us_message_content', new Assert\Length(array(
            'min'        => 2,
            'max'        => 3000,
            'minMessage' => 'The  message must be at least {{ limit }} characters length',
            'maxMessage' => 'The message cannot be longer than {{ limit }} characters length',
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
     * Set contact_us_message_date_created
     *
     * @param \DateTime $contactUsMessageDateCreated
     * @return ContactUsMessage
     */
    public function setContactUsMessageDateCreated($contactUsMessageDateCreated)
    {
        $this->contact_us_message_date_created = $contactUsMessageDateCreated;
    
        return $this;
    }

    /**
     * Get contact_us_message_date_created
     *
     * @return \DateTime 
     */
    public function getContactUsMessageDateCreated()
    {
        return $this->contact_us_message_date_created;
    }

    /**
     * Set contact_us_message_date_modified
     *
     * @param \DateTime $contactUsMessageDateModified
     * @return ContactUsMessage
     */
    public function setContactUsMessageDateModified($contactUsMessageDateModified)
    {
        $this->contact_us_message_date_modified = $contactUsMessageDateModified;
    
        return $this;
    }

    /**
     * Get contact_us_message_date_modified
     *
     * @return \DateTime 
     */
    public function getContactUsMessageDateModified()
    {
        return $this->contact_us_message_date_modified;
    }

    /**
     * Set contact_us_message_name
     *
     * @param string $contactUsMessageName
     * @return ContactUsMessage
     */
    public function setContactUsMessageName($contactUsMessageName)
    {
        $this->contact_us_message_name = $contactUsMessageName;
    
        return $this;
    }

    /**
     * Get contact_us_message_name
     *
     * @return string 
     */
    public function getContactUsMessageName()
    {
        return $this->contact_us_message_name;
    }

    /**
     * Set contact_us_message_email_address
     *
     * @param string $contactUsMessageEmailAddress
     * @return ContactUsMessage
     */
    public function setContactUsMessageEmailAddress($contactUsMessageEmailAddress)
    {
        $this->contact_us_message_email_address = $contactUsMessageEmailAddress;
    
        return $this;
    }

    /**
     * Get contact_us_message_email_address
     *
     * @return string 
     */
    public function getContactUsMessageEmailAddress()
    {
        return $this->contact_us_message_email_address;
    }

    /**
     * Set contact_us_message_content
     *
     * @param string $contactUsMessageContent
     * @return ContactUsMessage
     */
    public function setContactUsMessageContent($contactUsMessageContent)
    {
        $this->contact_us_message_content = $contactUsMessageContent;
    
        return $this;
    }

    /**
     * Get contact_us_message_content
     *
     * @return string 
     */
    public function getContactUsMessageContent()
    {
        return $this->contact_us_message_content;
    }
}