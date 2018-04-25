<?php
// src/MilesApart/PublicBundle/Entity/Feedback.php -- Defines the contact us message object

namespace MilesApart\PublicBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use EWZ\Bundle\RecaptchaBundle\Validator\Constraints as Recaptcha;
/**
 * @ORM\Entity(repositoryClass="MilesApart\BasketBundle\Entity\Repository\FeedbackRepository")
 * @ORM\Table(name="feedback")
 * @ORM\HasLifecycleCallbacks()
 */

class Feedback
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
    protected $feedback_date_created;

    /**
     * @ORM\Column(type="datetime", unique=false, nullable=true)
     */
    protected $feedback_date_modified;

    /**
     *  @ORM\Column(type="string", length=200, unique=false, nullable=false)
     */
    protected $feedback_name;

    /**
     * @ORM\Column(type="string", length=200, unique=false, nullable=false)
     */
    protected $feedback_email_address;

    /**
     * @ORM\Column(type="string", length=3000, nullable=false)
     */
    protected $feedback_content;

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
        $this->setFeedbackDateModified(new \DateTime(date('Y-m-d H:i:s')));

        if($this->getFeedbackDateCreated() == null)
        {
            $this->setFeedbackDateCreated(new \DateTime(date('Y-m-d H:i:s')));
        }
    }



    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //Contact us message name
        $metadata->addPropertyConstraint('feedback_name', new Assert\NotBlank());
        $metadata->addPropertyConstraint('feedback_name', new Assert\Length(array(
            'min'        => 2,
            'max'        => 100,
            'minMessage' => 'The \'Your Name\' field must be at least {{ limit }} characters length',
            'maxMessage' => 'The \'Your Name\' field cannot be longer than {{ limit }} characters length',
        )));

        //Contact us message email address
        $metadata->addPropertyConstraint('feedback_email_address', new Assert\NotBlank());
        $metadata->addPropertyConstraint('feedback_email_address', new Assert\Email(array(
            'message' => 'The email address - "{{ value }}" - is not valid.',
            'checkMX' => true,
        )));

        //Contact us message name
        $metadata->addPropertyConstraint('feedback_content', new Assert\NotBlank());
        $metadata->addPropertyConstraint('feedback_content', new Assert\Length(array(
            'min'        => 2,
            'max'        => 3000,
            'minMessage' => 'The feedback must be at least {{ limit }} characters length',
            'maxMessage' => 'The feedback cannot be longer than {{ limit }} characters length',
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
     * Set feedback_date_created
     *
     * @param \DateTime $feedbackDateCreated
     * @return Feedback
     */
    public function setFeedbackDateCreated($feedbackDateCreated)
    {
        $this->feedback_date_created = $feedbackDateCreated;

        return $this;
    }

    /**
     * Get feedback_date_created
     *
     * @return \DateTime 
     */
    public function getFeedbackDateCreated()
    {
        return $this->feedback_date_created;
    }

    /**
     * Set feedback_date_modified
     *
     * @param \DateTime $feedbackDateModified
     * @return Feedback
     */
    public function setFeedbackDateModified($feedbackDateModified)
    {
        $this->feedback_date_modified = $feedbackDateModified;

        return $this;
    }

    /**
     * Get feedback_date_modified
     *
     * @return \DateTime 
     */
    public function getFeedbackDateModified()
    {
        return $this->feedback_date_modified;
    }

    /**
     * Set feedback_name
     *
     * @param string $feedbackName
     * @return Feedback
     */
    public function setFeedbackName($feedbackName)
    {
        $this->feedback_name = $feedbackName;

        return $this;
    }

    /**
     * Get feedback_name
     *
     * @return string 
     */
    public function getFeedbackName()
    {
        return $this->feedback_name;
    }

    /**
     * Set feedback_email_address
     *
     * @param string $feedbackEmailAddress
     * @return Feedback
     */
    public function setFeedbackEmailAddress($feedbackEmailAddress)
    {
        $this->feedback_email_address = $feedbackEmailAddress;

        return $this;
    }

    /**
     * Get feedback_email_address
     *
     * @return string 
     */
    public function getFeedbackEmailAddress()
    {
        return $this->feedback_email_address;
    }

    /**
     * Set feedback_content
     *
     * @param string $feedbackContent
     * @return Feedback
     */
    public function setFeedbackContent($feedbackContent)
    {
        $this->feedback_content = $feedbackContent;

        return $this;
    }

    /**
     * Get feedback_content
     *
     * @return string 
     */
    public function getFeedbackContent()
    {
        return $this->feedback_content;
    }
}
