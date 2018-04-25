<?php
// src/MilesApart/AdminBundle/Entity/MarketingEmailType.php -- Defines the customer type object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\MarketingEmailTypeRepository")
 * @ORM\Table(name="marketing_email_type")
 * @ORM\HasLifecycleCallbacks()
 */

class MarketingEmailType
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
     * @ORM\Column(type="string", length=100, unique=true, nullable=false)
     */
    protected $marketing_email_type_name;

    /**
     * @ORM\Column(type="string", length=2000, unique=false, nullable=false)
     */
    protected $marketing_email_type_description;

    /**
     * @ORM\Column(type="string", length=100, unique=false, nullable=false)
     */
    protected $marketing_email_type_stylesheet_url;

    /**
     * @ORM\Column(type="string", length=100, unique=false, nullable=false)
     */
    protected $marketing_email_type_template_url;

     /**
     * @ORM\OneToMany(targetEntity="MarketingEmail", mappedBy="marketing_email_type")
     */
    protected $marketing_email;
    


    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //Marketing email type name
        $metadata->addPropertyConstraint('marketing_email_type_name', new Assert\NotBlank());
        $metadata->addPropertyConstraint('marketing_email_type_name', new Assert\Length(array(
            'min'        => 4,
            'max'        => 100,
            'minMessage' => 'The marketing email type name must be at least {{ limit }} characters length',
            'maxMessage' => 'The marketing email type name cannot be longer than {{ limit }} characters length',
        )));

        //Marketing email type description
        $metadata->addPropertyConstraint('marketing_email_type_description', new Assert\NotBlank());
        $metadata->addPropertyConstraint('marketing_email_type_description', new Assert\Length(array(
            'min'        => 4,
            'max'        => 2000,
            'minMessage' => 'The marketing email type description must be at least {{ limit }} characters length',
            'maxMessage' => 'The marketing email type description cannot be longer than {{ limit }} characters length',
        )));

        //Marketing email type stylesheet url
        $metadata->addPropertyConstraint('marketing_email_type_stylesheet_url', new Assert\NotBlank());
        $metadata->addPropertyConstraint('marketing_email_type_stylesheet_url', new Assert\Length(array(
            'min'        => 4,
            'max'        => 100,
            'minMessage' => 'The stylesheet url must be at least {{ limit }} characters length',
            'maxMessage' => 'The stylesheet cannot be longer than {{ limit }} characters length',
        )));

        //Marketing email type template url
        $metadata->addPropertyConstraint('marketing_email_type_template_url', new Assert\NotBlank());
        $metadata->addPropertyConstraint('marketing_email_type_template_url', new Assert\Length(array(
            'min'        => 4,
            'max'        => 100,
            'minMessage' => 'The template must be at least {{ limit }} characters length',
            'maxMessage' => 'The template cannot be longer than {{ limit }} characters length',
        )));
    }

    
   
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->marketing_email = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set marketing_email_type_name
     *
     * @param string $marketingEmailTypeName
     * @return MarketingEmailType
     */
    public function setMarketingEmailTypeName($marketingEmailTypeName)
    {
        $this->marketing_email_type_name = $marketingEmailTypeName;
    
        return $this;
    }

    /**
     * Get marketing_email_type_name
     *
     * @return string 
     */
    public function getMarketingEmailTypeName()
    {
        return $this->marketing_email_type_name;
    }

    /**
     * Set marketing_email_type_description
     *
     * @param string $marketingEmailTypeDescription
     * @return MarketingEmailType
     */
    public function setMarketingEmailTypeDescription($marketingEmailTypeDescription)
    {
        $this->marketing_email_type_description = $marketingEmailTypeDescription;
    
        return $this;
    }

    /**
     * Get marketing_email_type_description
     *
     * @return string 
     */
    public function getMarketingEmailTypeDescription()
    {
        return $this->marketing_email_type_description;
    }

    /**
     * Set marketing_email_type_stylesheet_url
     *
     * @param string $marketingEmailTypeStylesheetUrl
     * @return MarketingEmailType
     */
    public function setMarketingEmailTypeStylesheetUrl($marketingEmailTypeStylesheetUrl)
    {
        $this->marketing_email_type_stylesheet_url = $marketingEmailTypeStylesheetUrl;
    
        return $this;
    }

    /**
     * Get marketing_email_type_stylesheet_url
     *
     * @return string 
     */
    public function getMarketingEmailTypeStylesheetUrl()
    {
        return $this->marketing_email_type_stylesheet_url;
    }

    /**
     * Set marketing_email_type_template_url
     *
     * @param string $marketingEmailTypeTemplateUrl
     * @return MarketingEmailType
     */
    public function setMarketingEmailTypeTemplateUrl($marketingEmailTypeTemplateUrl)
    {
        $this->marketing_email_type_template_url = $marketingEmailTypeTemplateUrl;
    
        return $this;
    }

    /**
     * Get marketing_email_type_template_url
     *
     * @return string 
     */
    public function getMarketingEmailTypeTemplateUrl()
    {
        return $this->marketing_email_type_template_url;
    }

    /**
     * Add marketing_email
     *
     * @param \MilesApart\AdminBundle\Entity\MarketingEmail $marketingEmail
     * @return MarketingEmailType
     */
    public function addMarketingEmail(\MilesApart\AdminBundle\Entity\MarketingEmail $marketingEmail)
    {
        $this->marketing_email[] = $marketingEmail;
    
        return $this;
    }

    /**
     * Remove marketing_email
     *
     * @param \MilesApart\AdminBundle\Entity\MarketingEmail $marketingEmail
     */
    public function removeMarketingEmail(\MilesApart\AdminBundle\Entity\MarketingEmail $marketingEmail)
    {
        $this->marketing_email->removeElement($marketingEmail);
    }

    /**
     * Get marketing_email
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMarketingEmail()
    {
        return $this->marketing_email;
    }
}