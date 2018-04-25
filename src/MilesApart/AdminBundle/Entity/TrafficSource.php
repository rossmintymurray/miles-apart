<?php
// src/MilesApart/AdminBundle/Entity/TrafficSource.php -- Defines the customer type object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\TrafficSourceRepository")
 * @ORM\Table(name="traffic_source")
 * @ORM\HasLifecycleCallbacks()
 */

class TrafficSource
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
    protected $traffic_source_company_name;

    /**
     * @Gedmo\Slug(fields={"traffic_source_company_name"}, separator="-")
     * @ORM\Column(type="string", length=200, unique=false, nullable=false)
     */
    protected $traffic_source_company_name_slug;

    /**
     * @ORM\Column(type="string", length=100, unique=true, nullable=false)
     */
    protected $traffic_source_company_url;

    /**
     * @ORM\ManyToOne(targetEntity="TrafficSourceType", inversedBy="traffic_source")
     * @ORM\JoinTable(name="traffic_source_type")
     * @ORM\JoinColumn(name="traffic_source_type_id", referencedColumnName="id")
     */
    protected $traffic_source_type;

    /**
     * @ORM\OneToMany(targetEntity="Promotion", mappedBy="traffic_source")
     */
    protected $promotion;

   
    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //Traffic source company name
        $metadata->addPropertyConstraint('traffic_source_company_name', new Assert\NotBlank());
        $metadata->addPropertyConstraint('traffic_source_company_name', new Assert\Length(array(
            'min'        => 2,
            'max'        => 100,
            'minMessage' => 'The traffic source company name must be at least {{ limit }} characters length',
            'maxMessage' => 'The traffic source company name cannot be longer than {{ limit }} characters length',
        )));

        //Traffic source company URL
        $metadata->addPropertyConstraint('traffic_source_company_url', new Assert\NotBlank());
        $metadata->addPropertyConstraint('traffic_source_company_url', new Assert\Length(array(
            'min'        => 2,
            'max'        => 4096,
            'minMessage' => 'The traffic source comapny url must be at least {{ limit }} characters length',
            'maxMessage' => 'The traffic source comapny url cannot be longer than {{ limit }} characters length',
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
     * Set traffic_source_company_name
     *
     * @param string $trafficSourceCompanyName
     * @return TrafficSource
     */
    public function setTrafficSourceCompanyName($trafficSourceCompanyName)
    {
        $this->traffic_source_company_name = $trafficSourceCompanyName;

        return $this;
    }

    /**
     * Get traffic_source_company_name
     *
     * @return string 
     */
    public function getTrafficSourceCompanyName()
    {
        return $this->traffic_source_company_name;
    }

    /**
     * Set traffic_source_company_url
     *
     * @param string $trafficSourceCompanyUrl
     * @return TrafficSource
     */
    public function setTrafficSourceCompanyUrl($trafficSourceCompanyUrl)
    {
        $this->traffic_source_company_url = $trafficSourceCompanyUrl;

        return $this;
    }

    /**
     * Get traffic_source_company_url
     *
     * @return string 
     */
    public function getTrafficSourceCompanyUrl()
    {
        return $this->traffic_source_company_url;
    }

    /**
     * Set traffic_source_type
     *
     * @param \MilesApart\AdminBundle\Entity\TrafficSourceType $trafficSourceType
     * @return TrafficSource
     */
    public function setTrafficSourceType(\MilesApart\AdminBundle\Entity\TrafficSourceType $trafficSourceType = null)
    {
        $this->traffic_source_type = $trafficSourceType;

        return $this;
    }

    /**
     * Get traffic_source_type
     *
     * @return \MilesApart\AdminBundle\Entity\TrafficSourceType 
     */
    public function getTrafficSourceType()
    {
        return $this->traffic_source_type;
    }


    /**
     * Set promotion
     *
     * @param \MilesApart\AdminBundle\Entity\Promotion $promotion
     * @return TrafficSource
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
     * Set traffic_source_company_name_slug
     *
     * @param string $trafficSourceCompanyNameSlug
     * @return TrafficSource
     */
    public function setTrafficSourceCompanyNameSlug($trafficSourceCompanyNameSlug)
    {
        $this->traffic_source_company_name_slug = $trafficSourceCompanyNameSlug;

        return $this;
    }

    /**
     * Get traffic_source_company_name_slug
     *
     * @return string 
     */
    public function getTrafficSourceCompanyNameSlug()
    {
        return $this->traffic_source_company_name_slug;
    }
}
