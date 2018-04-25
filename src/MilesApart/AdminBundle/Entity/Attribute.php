<?php
// src/MilesApart/AdminBundle/Entity/Attribute.php -- Defines the attribute object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use MilesApart\AdminBundle\Utils\MilesApart as MilesApart;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\AttributeRepository")
 * @ORM\Table(name="attribute")
 * @ORM\HasLifecycleCallbacks()
 */

class Attribute
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
     * 
     * @ORM\Column(type="string", length=200, unique=true, nullable=false)
     */
    protected $attribute_name;

    /**
     * @ORM\ManyToOne(targetEntity="AttributeUnitOfMeasurement", inversedBy="attribute")
     * @ORM\JoinTable(name="attribute_unit_of_measurement")
     * @ORM\JoinColumn(name="attribute_unit_of_measurement_id", referencedColumnName="id")
     */
    protected $attribute_unit_of_measurement;

    /**
     * @ORM\OneToMany(targetEntity="AttributeValue", mappedBy="attribute")
     */
    protected $attribute_value;

    /**
     * Person domain object class
     *
     * @Gedmo\Slug(fields={"attribute_name"},)
     * @ORM\Column(type="string", length=200, unique=true)
     */
    protected $attribute_slug;

    //Validators for data 
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //Attribute name
        $metadata->addPropertyConstraint('attribute_name', new Assert\NotBlank());
        $metadata->addPropertyConstraint('attribute_name', new Assert\Length(array(
            'min'        => 4,
            'max'        => 200,
            'minMessage' => 'The attribute name must be at least {{ limit }} characters length',
            'maxMessage' => 'The attribute name cannot be longer than {{ limit }} characters length',
        )));

        
    }

    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->attribute_value = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set attribute_name
     *
     * @param string $attributeName
     * @return Attribute
     */
    public function setAttributeName($attributeName)
    {
        $this->attribute_name = $attributeName;
    
        return $this;
    }

    /**
     * Get attribute_name
     *
     * @return string 
     */
    public function getAttributeName()
    {
        return $this->attribute_name;
    }

    /**
     * Set attribute_slug
     *
     * @param string $attributeSlug
     * @return Attribute
     */
    public function setAttributeSlug($attributeSlug)
    {
        $this->attribute_slug = $attributeSlug;
    
        return $this;
    }

    /**
     * Get attribute_slug
     *
     * @return string 
     */
    public function getAttributeSlug()
    {
        return MilesApart::slugify($this->getAttributeName());
    }

    /**
     * Set attribute_unit_of_measurement
     *
     * @param \MilesApart\AdminBundle\Entity\AttributeUnitOfMeasurement $attributeUnitOfMeasurement
     * @return Attribute
     */
    public function setAttributeUnitOfMeasurement(\MilesApart\AdminBundle\Entity\AttributeUnitOfMeasurement $attributeUnitOfMeasurement = null)
    {
        $this->attribute_unit_of_measurement = $attributeUnitOfMeasurement;
    
        return $this;
    }

    /**
     * Get attribute_unit_of_measurement
     *
     * @return \MilesApart\AdminBundle\Entity\AttributeUnitOfMeasurement 
     */
    public function getAttributeUnitOfMeasurement()
    {
        return $this->attribute_unit_of_measurement;
    }

    /**
     * Add attribute_value
     *
     * @param \MilesApart\AdminBundle\Entity\AttributeValue $attributeValue
     * @return Attribute
     */
    public function addAttributeValue(\MilesApart\AdminBundle\Entity\AttributeValue $attributeValue)
    {
        $this->attribute_value[] = $attributeValue;
    
        return $this;
    }

    /**
     * Remove attribute_value
     *
     * @param \MilesApart\AdminBundle\Entity\AttributeValue $attributeValue
     */
    public function removeAttributeValue(\MilesApart\AdminBundle\Entity\AttributeValue $attributeValue)
    {
        $this->attribute_value->removeElement($attributeValue);
    }

    /**
     * Get attribute_value
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAttributeValue()
    {
        return $this->attribute_value;
    }
}