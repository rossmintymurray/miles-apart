<?php
// src/MilesApart/AdminBundle/Entity/MarketingEmail.php -- Defines the customer type object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\MarketingEmailRepository")
 * @ORM\Table(name="marketing_email")
 * @ORM\HasLifecycleCallbacks()
 */

class MarketingEmail
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
     * @ORM\Column(type="string", length=200, unique=false, nullable=false)
     */
    protected $marketing_email_subject_line;

    /**
     * @ORM\Column(type="string", length=200, unique=false, nullable=false)
     */
    protected $marketing_email_name;

    /**
     * @ORM\Column(type="string", length=2000, unique=false, nullable=false)
     */
    protected $marketing_email_description;

    /**
     * @ORM\Column(type="datetime", unique=false, nullable=false)
     */
    protected $marketing_email_date_created;

    /**
     * @ORM\Column(type="datetime", unique=false, nullable=true)
     */
    protected $marketing_email_date_modified;

    /**
     * @ORM\ManyToOne(targetEntity="AdminUser", inversedBy="marketing_email_creator")
     * @ORM\JoinTable(name="admin_user")
     * @ORM\JoinColumn(name="marketing_email_creator_admin_user_id", referencedColumnName="id")
     */
    protected $marketing_email_creator_admin_user;

    /**
     * @ORM\ManyToOne(targetEntity="AdminUser", inversedBy="marketing_email_modifier")
     * @ORM\JoinTable(name="admin_user")
     * @ORM\JoinColumn(name="marketing_email_modifier_admin_user_id", referencedColumnName="id")
     */
    protected $marketing_email_modifier_admin_user;

    /**
     * @ORM\ManyToOne(targetEntity="MarketingEmailType", inversedBy="marketing_email")
     * @ORM\JoinTable(name="marketing_email_type")
     * @ORM\JoinColumn(name="marketing_email_type_id", referencedColumnName="id")
     */
    protected $marketing_email_type;

    /**
     * @ORM\ManyToOne(targetEntity="Promotion", inversedBy="marketing_email")
     * @ORM\JoinTable(name="promotion")
     * @ORM\JoinColumn(name="promotion_id", referencedColumnName="id")
     */
    protected $promotion;

    /**
     * @ORM\OneToMany(targetEntity="MarketingEmailComponent", mappedBy="marketing_email")
     */
    protected $marketing_email_component;

     /**
     * @ORM\OneToMany(targetEntity="MarketingEmailSendList", mappedBy="marketing_email")
     */
    protected $marketing_email_send_list;



    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //Marketing email subject line
        $metadata->addPropertyConstraint('marketing_email_subject_line', new Assert\NotBlank());
        $metadata->addPropertyConstraint('marketing_email_subject_line', new Assert\Length(array(
            'min'        => 20,
            'max'        => 200,
            'minMessage' => 'The email subject line must be at least {{ limit }} characters length',
            'maxMessage' => 'The email subject line cannot be longer than {{ limit }} characters length',
        )));

        //Marketing email name
        $metadata->addPropertyConstraint('marketing_email_name', new Assert\NotBlank());
        $metadata->addPropertyConstraint('marketing_email_name', new Assert\Length(array(
            'min'        => 20,
            'max'        => 200,
            'minMessage' => 'The email name must be at least {{ limit }} characters length',
            'maxMessage' => 'The email name cannot be longer than {{ limit }} characters length',
        )));

        //Marketing email description
        $metadata->addPropertyConstraint('marketing_email_description', new Assert\NotBlank());
        $metadata->addPropertyConstraint('marketing_email_description', new Assert\Length(array(
            'min'        => 20,
            'max'        => 2000,
            'minMessage' => 'The email description must be at least {{ limit }} characters length',
            'maxMessage' => 'The email description cannot be longer than {{ limit }} characters length',
        )));

        //Marketing email creator admin user
        $metadata->addPropertyConstraint('marketing_email_creator_admin_user', new Assert\Choice(array(
            'callback' => 'getAdminUser',
        )));

        //Marketing email modifier admin user
        $metadata->addPropertyConstraint('marketing_email_modifier_admin_user', new Assert\Choice(array(
            'callback' => 'getAdminUser',
        )));

        //Marketing email type
        $metadata->addPropertyConstraint('marketing_email_type', new Assert\Choice(array(
            'callback' => 'getMarketingEmailType',
        )));

        //Promotion
        $metadata->addPropertyConstraint('promotion', new Assert\Choice(array(
            'callback' => 'getPromotion',
        )));
    }
   

   
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->marketing_email_component = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set marketing_email_subject_line
     *
     * @param string $marketingEmailSubjectLine
     * @return MarketingEmail
     */
    public function setMarketingEmailSubjectLine($marketingEmailSubjectLine)
    {
        $this->marketing_email_subject_line = $marketingEmailSubjectLine;
    
        return $this;
    }

    /**
     * Get marketing_email_subject_line
     *
     * @return string 
     */
    public function getMarketingEmailSubjectLine()
    {
        return $this->marketing_email_subject_line;
    }

    /**
     * Set marketing_email_name
     *
     * @param string $marketingEmailName
     * @return MarketingEmail
     */
    public function setMarketingEmailName($marketingEmailName)
    {
        $this->marketing_email_name = $marketingEmailName;
    
        return $this;
    }

    /**
     * Get marketing_email_name
     *
     * @return string 
     */
    public function getMarketingEmailName()
    {
        return $this->marketing_email_name;
    }

    /**
     * Set marketing_email_description
     *
     * @param string $marketingEmailDescription
     * @return MarketingEmail
     */
    public function setMarketingEmailDescription($marketingEmailDescription)
    {
        $this->marketing_email_description = $marketingEmailDescription;
    
        return $this;
    }

    /**
     * Get marketing_email_description
     *
     * @return string 
     */
    public function getMarketingEmailDescription()
    {
        return $this->marketing_email_description;
    }

    /**
     * Set marketing_email_date_created
     *
     * @param \DateTime $marketingEmailDateCreated
     * @return MarketingEmail
     */
    public function setMarketingEmailDateCreated($marketingEmailDateCreated)
    {
        $this->marketing_email_date_created = $marketingEmailDateCreated;
    
        return $this;
    }

    /**
     * Get marketing_email_date_created
     *
     * @return \DateTime 
     */
    public function getMarketingEmailDateCreated()
    {
        return $this->marketing_email_date_created;
    }

    /**
     * Set marketing_email_date_modified
     *
     * @param \DateTime $marketingEmailDateModified
     * @return MarketingEmail
     */
    public function setMarketingEmailDateModified($marketingEmailDateModified)
    {
        $this->marketing_email_date_modified = $marketingEmailDateModified;
    
        return $this;
    }

    /**
     * Get marketing_email_date_modified
     *
     * @return \DateTime 
     */
    public function getMarketingEmailDateModified()
    {
        return $this->marketing_email_date_modified;
    }

    /**
     * Set marketing_email_creator_admin_user
     *
     * @param \MilesApart\AdminBundle\Entity\AdminUser $marketingEmailCreatorAdminUser
     * @return MarketingEmail
     */
    public function setMarketingEmailCreatorAdminUser(\MilesApart\AdminBundle\Entity\AdminUser $marketingEmailCreatorAdminUser = null)
    {
        $this->marketing_email_creator_admin_user = $marketingEmailCreatorAdminUser;
    
        return $this;
    }

    /**
     * Get marketing_email_creator_admin_user
     *
     * @return \MilesApart\AdminBundle\Entity\AdminUser 
     */
    public function getMarketingEmailCreatorAdminUser()
    {
        return $this->marketing_email_creator_admin_user;
    }

    /**
     * Set marketing_email_modifier_admin_user
     *
     * @param \MilesApart\AdminBundle\Entity\AdminUser $marketingEmailModifierAdminUser
     * @return MarketingEmail
     */
    public function setMarketingEmailModifierAdminUser(\MilesApart\AdminBundle\Entity\AdminUser $marketingEmailModifierAdminUser = null)
    {
        $this->marketing_email_modifier_admin_user = $marketingEmailModifierAdminUser;
    
        return $this;
    }

    /**
     * Get marketing_email_modifier_admin_user
     *
     * @return \MilesApart\AdminBundle\Entity\AdminUser 
     */
    public function getMarketingEmailModifierAdminUser()
    {
        return $this->marketing_email_modifier_admin_user;
    }

    /**
     * Set marketing_email_type
     *
     * @param \MilesApart\AdminBundle\Entity\MarketingEmailType $marketingEmailType
     * @return MarketingEmail
     */
    public function setMarketingEmailType(\MilesApart\AdminBundle\Entity\MarketingEmailType $marketingEmailType = null)
    {
        $this->marketing_email_type = $marketingEmailType;
    
        return $this;
    }

    /**
     * Get marketing_email_type
     *
     * @return \MilesApart\AdminBundle\Entity\MarketingEmailType 
     */
    public function getMarketingEmailType()
    {
        return $this->marketing_email_type;
    }

    /**
     * Set promotion
     *
     * @param \MilesApart\AdminBundle\Entity\Promotion $promotion
     * @return MarketingEmail
     */
    public function setPromotion(\MilesApart\AdminBundle\Entity\Promotion $promotion = null)
    {
        $this->promotion = $promotion;
    
        return $this;
    }

    /**
     * Get promotion
     *
     * @return \MilesApart\AdminBundle\Entity\Promotion 
     */
    public function getPromotion()
    {
        return $this->promotion;
    }

    /**
     * Add marketing_email_component
     *
     * @param \MilesApart\AdminBundle\Entity\MarketingEmailComponent $marketingEmailComponent
     * @return MarketingEmail
     */
    public function addMarketingEmailComponent(\MilesApart\AdminBundle\Entity\MarketingEmailComponent $marketingEmailComponent)
    {
        $this->marketing_email_component[] = $marketingEmailComponent;
    
        return $this;
    }

    /**
     * Remove marketing_email_component
     *
     * @param \MilesApart\AdminBundle\Entity\MarketingEmailComponent $marketingEmailComponent
     */
    public function removeMarketingEmailComponent(\MilesApart\AdminBundle\Entity\MarketingEmailComponent $marketingEmailComponent)
    {
        $this->marketing_email_component->removeElement($marketingEmailComponent);
    }

    /**
     * Get marketing_email_component
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMarketingEmailComponent()
    {
        return $this->marketing_email_component;
    }
}