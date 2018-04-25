<?php
// src/MilesApart/AdminBundle/Entity/MarketingEmailComponent.php -- Defines the customer type object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\MarketingEmailComponentRepository")
 * @ORM\Table(name="marketing_email_component")
 * @ORM\HasLifecycleCallbacks()
 */

class MarketingEmailComponent
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
    protected $marketing_email_component_name;

    /**
     * @ORM\Column(type="string", length=5000, unique=false, nullable=false)
     */
    protected $marketing_email_component_content;

    /**
     * @ORM\Column(type="date", unique=false, nullable=false)
     */
    protected $marketing_email_component_date_created;

    /**
     * @ORM\Column(type="date", unique=false, nullable=true)
     */
    protected $marketing_email_component_date_modified;
    
    /**
     * @ORM\ManyToOne(targetEntity="MarketingEmailComponentType", inversedBy="marketing_email_component")
     * @ORM\JoinTable(name="marketing_email_component_type")
     * @ORM\JoinColumn(name="marketing_email_component_type_id", referencedColumnName="id")
     */
    protected $marketing_email_component_type;
   
    /**
     * @ORM\ManyToOne(targetEntity="AdminUser", inversedBy="marketing_email_component_creator")
     * @ORM\JoinTable(name="admin_user")
     * @ORM\JoinColumn(name="marketing_email_component_creator_admin_user_id", referencedColumnName="id")
     */
    protected $marketing_email_component_creator_admin_user;

    /**
     * @ORM\ManyToOne(targetEntity="AdminUser", inversedBy="marketing_email_component_modifier")
     * @ORM\JoinTable(name="admin_user")
     * @ORM\JoinColumn(name="marketing_email_component_modifier_admin_user_id", referencedColumnName="id")
     */
    protected $marketing_email_component_modifier_admin_user;

    /**
     * @ORM\ManyToOne(targetEntity="MarketingEmail", inversedBy="marketing_email_component")
     * @ORM\JoinTable(name="marketing_email")
     * @ORM\JoinColumn(name="marketing_email_id", referencedColumnName="id")
     */
    protected $marketing_email;



    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        $this->setMarketingEmailComponentDateModified(new \DateTime(date('Y-m-d H:i:s')));

        if($this->getMarketingEmailComponentDateCreated() == null)
        {
            $this->setMarketingEmailComponentDateCreated(new \DateTime(date('Y-m-d H:i:s')));
        }
    }



    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //Marketing email component name
        $metadata->addPropertyConstraint('marketing_email_component_name', new Assert\NotBlank());
        $metadata->addPropertyConstraint('marketing_email_component_name', new Assert\Length(array(
            'min'        => 3,
            'max'        => 100,
            'minMessage' => 'The component name must be at least {{ limit }} characters length',
            'maxMessage' => 'The component name cannot be longer than {{ limit }} characters length',
        )));

        //Marketing email component content
        $metadata->addPropertyConstraint('marketing_email_component_content', new Assert\NotBlank());
        $metadata->addPropertyConstraint('marketing_email_component_content', new Assert\Length(array(
            'min'        => 20,
            'max'        => 5000,
            'minMessage' => 'The email name must be at least {{ limit }} characters length',
            'maxMessage' => 'The email name cannot be longer than {{ limit }} characters length',
        )));

        //Marketing email component creator admin user
        $metadata->addPropertyConstraint('marketing_email_component_creator_admin_user', new Assert\Choice(array(
            'callback' => 'getAdminUser',
        )));

        //Marketing email component modifier admin user
        $metadata->addPropertyConstraint('marketing_email_component_modifier_admin_user', new Assert\Choice(array(
            'callback' => 'getAdminUser',
        )));

        //Marketing email 
        $metadata->addPropertyConstraint('marketing_email', new Assert\Choice(array(
            'callback' => 'getMarketingEmail',
        )));

        //Marketing email component type
        $metadata->addPropertyConstraint('marketing_email_component_type', new Assert\Choice(array(
            'callback' => 'getMarketingEmailComponentType',
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
     * Set marketing_email_component_name
     *
     * @param string $marketingEmailComponentName
     * @return MarketingEmailComponent
     */
    public function setMarketingEmailComponentName($marketingEmailComponentName)
    {
        $this->marketing_email_component_name = $marketingEmailComponentName;
    
        return $this;
    }

    /**
     * Get marketing_email_component_name
     *
     * @return string 
     */
    public function getMarketingEmailComponentName()
    {
        return $this->marketing_email_component_name;
    }

    /**
     * Set marketing_email_component_content
     *
     * @param string $marketingEmailComponentContent
     * @return MarketingEmailComponent
     */
    public function setMarketingEmailComponentContent($marketingEmailComponentContent)
    {
        $this->marketing_email_component_content = $marketingEmailComponentContent;
    
        return $this;
    }

    /**
     * Get marketing_email_component_content
     *
     * @return string 
     */
    public function getMarketingEmailComponentContent()
    {
        return $this->marketing_email_component_content;
    }

    /**
     * Set marketing_email_component_date_created
     *
     * @param \DateTime $marketingEmailComponentDateCreated
     * @return MarketingEmailComponent
     */
    public function setMarketingEmailComponentDateCreated($marketingEmailComponentDateCreated)
    {
        $this->marketing_email_component_date_created = $marketingEmailComponentDateCreated;
    
        return $this;
    }

    /**
     * Get marketing_email_component_date_created
     *
     * @return \DateTime 
     */
    public function getMarketingEmailComponentDateCreated()
    {
        return $this->marketing_email_component_date_created;
    }

    /**
     * Set marketing_email_component_date_modified
     *
     * @param \DateTime $marketingEmailComponentDateModified
     * @return MarketingEmailComponent
     */
    public function setMarketingEmailComponentDateModified($marketingEmailComponentDateModified)
    {
        $this->marketing_email_component_date_modified = $marketingEmailComponentDateModified;
    
        return $this;
    }

    /**
     * Get marketing_email_component_date_modified
     *
     * @return \DateTime 
     */
    public function getMarketingEmailComponentDateModified()
    {
        return $this->marketing_email_component_date_modified;
    }

    /**
     * Set marketing_email_component_type
     *
     * @param \MilesApart\AdminBundle\Entity\MarketingEmailComponentType $marketingEmailComponentType
     * @return MarketingEmailComponent
     */
    public function setMarketingEmailComponentType(\MilesApart\AdminBundle\Entity\MarketingEmailComponentType $marketingEmailComponentType = null)
    {
        $this->marketing_email_component_type = $marketingEmailComponentType;
    
        return $this;
    }

    /**
     * Get marketing_email_component_type
     *
     * @return \MilesApart\AdminBundle\Entity\MarketingEmailComponentType 
     */
    public function getMarketingEmailComponentType()
    {
        return $this->marketing_email_component_type;
    }

    /**
     * Set marketing_email_component_creator_admin_user
     *
     * @param \MilesApart\AdminBundle\Entity\AdminUser $marketingEmailComponentCreatorAdminUser
     * @return MarketingEmailComponent
     */
    public function setMarketingEmailComponentCreatorAdminUser(\MilesApart\AdminBundle\Entity\AdminUser $marketingEmailComponentCreatorAdminUser = null)
    {
        $this->marketing_email_component_creator_admin_user = $marketingEmailComponentCreatorAdminUser;
    
        return $this;
    }

    /**
     * Get marketing_email_component_creator_admin_user
     *
     * @return \MilesApart\AdminBundle\Entity\AdminUser 
     */
    public function getMarketingEmailComponentCreatorAdminUser()
    {
        return $this->marketing_email_component_creator_admin_user;
    }

    /**
     * Set marketing_email_component_modifier_admin_user
     *
     * @param \MilesApart\AdminBundle\Entity\AdminUser $marketingEmailComponentModifierAdminUser
     * @return MarketingEmailComponent
     */
    public function setMarketingEmailComponentModifierAdminUser(\MilesApart\AdminBundle\Entity\AdminUser $marketingEmailComponentModifierAdminUser = null)
    {
        $this->marketing_email_component_modifier_admin_user = $marketingEmailComponentModifierAdminUser;
    
        return $this;
    }

    /**
     * Get marketing_email_component_modifier_admin_user
     *
     * @return \MilesApart\AdminBundle\Entity\AdminUser 
     */
    public function getMarketingEmailComponentModifierAdminUser()
    {
        return $this->marketing_email_component_modifier_admin_user;
    }

    /**
     * Set marketing_email
     *
     * @param \MilesApart\AdminBundle\Entity\MarketingEmail $marketingEmail
     * @return MarketingEmailComponent
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
}