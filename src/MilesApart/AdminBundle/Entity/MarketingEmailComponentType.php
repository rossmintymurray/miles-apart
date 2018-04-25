<?php
// src/MilesApart/AdminBundle/Entity/MarketingEmailComponentType.php -- Defines the customer type object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\MarketingEmailComponentTypeRepository")
 * @ORM\Table(name="marketing_email_component_type")
 * @ORM\HasLifecycleCallbacks()
 */

class MarketingEmailComponentType
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
    protected $marketing_email_component_type_name;

    /**
     * @ORM\Column(type="string", length=5000, unique=false, nullable=false)
     */
    protected $marketing_email_component_type_default_content;

    /**
     * @ORM\Column(type="string", length=2000, unique=false, nullable=false)
     */
    protected $marketing_email_component_type_description;

    /**
     * @ORM\Column(type="string", length=100, unique=false, nullable=true)
     */
    protected $marketing_email_component_type_stylesheet_url;

    /**
     * @ORM\Column(type="string", length=100, unique=false, nullable=true)
     */
    protected $marketing_email_component_type_template_url;

    /**
     * @ORM\OneToMany(targetEntity="MarketingEmailComponent", mappedBy="marketing_email_component_type")
     */
    protected $marketing_email_component;

   

    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //Marketing email component type name
        $metadata->addPropertyConstraint('marketing_email_component_type_name', new Assert\NotBlank());
        $metadata->addPropertyConstraint('marketing_email_component_type_name', new Assert\Length(array(
            'min'        => 3,
            'max'        => 100,
            'minMessage' => 'The component type name must be at least {{ limit }} characters length',
            'maxMessage' => 'The component type name cannot be longer than {{ limit }} characters length',
        )));

        //Marketing email component type default content
        $metadata->addPropertyConstraint('marketing_email_component_type_default_content', new Assert\NotBlank());
        $metadata->addPropertyConstraint('marketing_email_component_type_default_content', new Assert\Length(array(
            'min'        => 20,
            'max'        => 5000,
            'minMessage' => 'The default content must be at least {{ limit }} characters length',
            'maxMessage' => 'The default content cannot be longer than {{ limit }} characters length',
        )));

        //Marketing email component type description
        $metadata->addPropertyConstraint('marketing_email_component_type_description', new Assert\NotBlank());
        $metadata->addPropertyConstraint('marketing_email_component_type_description', new Assert\Length(array(
            'min'        => 20,
            'max'        => 2000,
            'minMessage' => 'The component type description must be at least {{ limit }} characters length',
            'maxMessage' => 'The component type description cannot be longer than {{ limit }} characters length',
        )));

        //Marketing email component type stylesheet url
        $metadata->addPropertyConstraint('marketing_email_component_type_stylesheet_url', new Assert\Length(array(
            'min'        => 5,
            'max'        => 100,
            'minMessage' => 'The stylesheet url must be at least {{ limit }} characters length',
            'maxMessage' => 'The stylesheet url cannot be longer than {{ limit }} characters length',
        )));

        //Marketing email component type template url
        $metadata->addPropertyConstraint('marketing_email_component_type_template_url', new Assert\Length(array(
            'min'        => 5,
            'max'        => 100,
            'minMessage' => 'The template url must be at least {{ limit }} characters length',
            'maxMessage' => 'The template url cannot be longer than {{ limit }} characters length',
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
     * Set marketing_email_component_type_name
     *
     * @param string $marketingEmailComponentTypeName
     * @return MarketingEmailComponentType
     */
    public function setMarketingEmailComponentTypeName($marketingEmailComponentTypeName)
    {
        $this->marketing_email_component_type_name = $marketingEmailComponentTypeName;
    
        return $this;
    }

    /**
     * Get marketing_email_component_type_name
     *
     * @return string 
     */
    public function getMarketingEmailComponentTypeName()
    {
        return $this->marketing_email_component_type_name;
    }

    /**
     * Set marketing_email_component_type_default_content
     *
     * @param string $marketingEmailComponentTypeDefaultContent
     * @return MarketingEmailComponentType
     */
    public function setMarketingEmailComponentTypeDefaultContent($marketingEmailComponentTypeDefaultContent)
    {
        $this->marketing_email_component_type_default_content = $marketingEmailComponentTypeDefaultContent;
    
        return $this;
    }

    /**
     * Get marketing_email_component_type_default_content
     *
     * @return string 
     */
    public function getMarketingEmailComponentTypeDefaultContent()
    {
        return $this->marketing_email_component_type_default_content;
    }

    /**
     * Set marketing_email_component_type_description
     *
     * @param string $marketingEmailComponentTypeDescription
     * @return MarketingEmailComponentType
     */
    public function setMarketingEmailComponentTypeDescription($marketingEmailComponentTypeDescription)
    {
        $this->marketing_email_component_type_description = $marketingEmailComponentTypeDescription;
    
        return $this;
    }

    /**
     * Get marketing_email_component_type_description
     *
     * @return string 
     */
    public function getMarketingEmailComponentTypeDescription()
    {
        return $this->marketing_email_component_type_description;
    }

    /**
     * Set marketing_email_component_type_stylesheet_url
     *
     * @param string $marketingEmailComponentTypeStylesheetUrl
     * @return MarketingEmailComponentType
     */
    public function setMarketingEmailComponentTypeStylesheetUrl($marketingEmailComponentTypeStylesheetUrl)
    {
        $this->marketing_email_component_type_stylesheet_url = $marketingEmailComponentTypeStylesheetUrl;
    
        return $this;
    }

    /**
     * Get marketing_email_component_type_stylesheet_url
     *
     * @return string 
     */
    public function getMarketingEmailComponentTypeStylesheetUrl()
    {
        return $this->marketing_email_component_type_stylesheet_url;
    }

    /**
     * Set marketing_email_component_type_template_url
     *
     * @param string $marketingEmailComponentTypeTemplateUrl
     * @return MarketingEmailComponentType
     */
    public function setMarketingEmailComponentTypeTemplateUrl($marketingEmailComponentTypeTemplateUrl)
    {
        $this->marketing_email_component_type_template_url = $marketingEmailComponentTypeTemplateUrl;
    
        return $this;
    }

    /**
     * Get marketing_email_component_type_template_url
     *
     * @return string 
     */
    public function getMarketingEmailComponentTypeTemplateUrl()
    {
        return $this->marketing_email_component_type_template_url;
    }

    /**
     * Add marketing_email_component
     *
     * @param \MilesApart\AdminBundle\Entity\MarketingEmailComponent $marketingEmailComponent
     * @return MarketingEmailComponentType
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