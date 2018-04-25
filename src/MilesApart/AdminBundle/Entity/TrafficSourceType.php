<?php
// src/MilesApart/AdminBundle/Entity/TrafficSourceType.php -- Defines the customer type object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\TrafficSourceTypeRepository")
 * @ORM\Table(name="traffic_source_type")
 * @ORM\HasLifecycleCallbacks()
 */

class TrafficSourceType
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
    protected $traffic_source_type_name;

    /**
     * @Gedmo\Slug(fields={"traffic_source_type_name"}, separator="-")
     * @ORM\Column(type="string", length=200, unique=false, nullable=false)
     */
    protected $traffic_source_type_name_slug;

    /**
     * @ORM\Column(type="string", length=100, unique=true, nullable=true)
     */
    protected $traffic_source_type_description;

    /**
     * @ORM\OneToMany(targetEntity="TrafficSource", mappedBy="traffic_source_type")
     */
    protected $traffic_source;


    
    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //Traffic source type name
        $metadata->addPropertyConstraint('traffic_source_type_name', new Assert\NotBlank());
        $metadata->addPropertyConstraint('traffic_source_type_name', new Assert\Length(array(
            'min'        => 2,
            'max'        => 100,
            'minMessage' => 'The traffic source type name must be at least {{ limit }} characters length',
            'maxMessage' => 'The traffic source type name cannot be longer than {{ limit }} characters length',
        )));

        //Traffic source type description
        $metadata->addPropertyConstraint('traffic_source_type_description', new Assert\Length(array(
            'min'        => 2,
            'max'        => 100,
            'minMessage' => 'The traffic source type description must be at least {{ limit }} characters length',
            'maxMessage' => 'The traffic source type description cannot be longer than {{ limit }} characters length',
        )));
    }


   
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->traffic_source = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set traffic_source_type_name
     *
     * @param string $trafficSourceTypeName
     * @return TrafficSourceType
     */
    public function setTrafficSourceTypeName($trafficSourceTypeName)
    {
        $this->traffic_source_type_name = $trafficSourceTypeName;
    
        return $this;
    }

    /**
     * Get traffic_source_type_name
     *
     * @return string 
     */
    public function getTrafficSourceTypeName()
    {
        return $this->traffic_source_type_name;
    }

    /**
     * Set traffic_source_type_description
     *
     * @param string $trafficSourceTypeDescription
     * @return TrafficSourceType
     */
    public function setTrafficSourceTypeDescription($trafficSourceTypeDescription)
    {
        $this->traffic_source_type_description = $trafficSourceTypeDescription;
    
        return $this;
    }

    /**
     * Get traffic_source_type_description
     *
     * @return string 
     */
    public function getTrafficSourceTypeDescription()
    {
        return $this->traffic_source_type_description;
    }

    /**
     * Add traffic_source
     *
     * @param \MilesApart\AdminBundle\Entity\TrafficSource $trafficSource
     * @return TrafficSourceType
     */
    public function addTrafficSource(\MilesApart\AdminBundle\Entity\TrafficSource $trafficSource)
    {
        $this->traffic_source[] = $trafficSource;
    
        return $this;
    }

    /**
     * Remove traffic_source
     *
     * @param \MilesApart\AdminBundle\Entity\TrafficSource $trafficSource
     */
    public function removeTrafficSource(\MilesApart\AdminBundle\Entity\TrafficSource $trafficSource)
    {
        $this->traffic_source->removeElement($trafficSource);
    }

    /**
     * Get traffic_source
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTrafficSource()
    {
        return $this->traffic_source;
    }

    /**
     * Set traffic_source_type_name_slug
     *
     * @param string $trafficSourceTypeNameSlug
     * @return TrafficSourceType
     */
    public function setTrafficSourceTypeNameSlug($trafficSourceTypeNameSlug)
    {
        $this->traffic_source_type_name_slug = $trafficSourceTypeNameSlug;

        return $this;
    }

    /**
     * Get traffic_source_type_name_slug
     *
     * @return string 
     */
    public function getTrafficSourceTypeNameSlug()
    {
        return $this->traffic_source_type_name_slug;
    }
}
