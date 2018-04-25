<?php
// src/MilesApart/AdminBundle/Entity/AttributeUnitOfMeasurement.php -- Defines the attribute unit of measurement object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\AttributeUnitOfMeasurementRepository")
 * @ORM\Table(name="attribute_unit_of_measurement")
 * @ORM\HasLifecycleCallbacks()
 */

class AttributeUnitOfMeasurement
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
    protected $attribute_unit_of_measurement_name;

    /**
     * 
     * @ORM\Column(type="string", length=10, unique=true, nullable=false)
     */
    protected $attribute_unit_of_measurement_abr;

    /**
     * @ORM\OneToMany(targetEntity="Attribute", mappedBy="attribute_unit_of_measurement")
     */
    protected $attribute;
    


    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //Attribute unit of measurement name
        $metadata->addPropertyConstraint('attribute_unit_of_measurement_name', new Assert\NotBlank());
        $metadata->addPropertyConstraint('attribute_unit_of_measurement_name', new Assert\Length(array(
            'min'        => 4,
            'max'        => 200,
            'minMessage' => 'The attribute unit of measurement must be at least {{ limit }} characters length',
            'maxMessage' => 'The attribute unit of measurement cannot be longer than {{ limit }} characters length',
        )));

        //Attribute unit of measurement abbreviation
        $metadata->addPropertyConstraint('attribute_unit_of_measurement_abr', new Assert\NotBlank());
        $metadata->addPropertyConstraint('attribute_unit_of_measurement_abr', new Assert\Length(array(
            'min'        => 1,
            'max'        => 10,
            'minMessage' => 'The attribute unit of measurement abbreviation must be at least {{ limit }} characters length',
            'maxMessage' => 'The attribute unit of measurement abbreviation cannot be longer than {{ limit }} characters length',
        )));
    }

    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->attribute = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set attribute_unit_of_measurement_name
     *
     * @param string $attributeUnitOfMeasurementName
     * @return AttributeUnitOfMeasurement
     */
    public function setAttributeUnitOfMeasurementName($attributeUnitOfMeasurementName)
    {
        $this->attribute_unit_of_measurement_name = $attributeUnitOfMeasurementName;
    
        return $this;
    }

    /**
     * Get attribute_unit_of_measurement_name
     *
     * @return string 
     */
    public function getAttributeUnitOfMeasurementName()
    {
        return $this->attribute_unit_of_measurement_name;
    }

    /**
     * Set attribute_unit_of_measurement_abr
     *
     * @param string $attributeUnitOfMeasurementAbr
     * @return AttributeUnitOfMeasurement
     */
    public function setAttributeUnitOfMeasurementAbr($attributeUnitOfMeasurementAbr)
    {
        $this->attribute_unit_of_measurement_abr = $attributeUnitOfMeasurementAbr;
    
        return $this;
    }

    /**
     * Get attribute_unit_of_measurement_abr
     *
     * @return string 
     */
    public function getAttributeUnitOfMeasurementAbr()
    {
        return $this->attribute_unit_of_measurement_abr;
    }

    /**
     * Add attribute
     *
     * @param \MilesApart\AdminBundle\Entity\Attribute $attribute
     * @return AttributeUnitOfMeasurement
     */
    public function addAttribute(\MilesApart\AdminBundle\Entity\Attribute $attribute)
    {
        $this->attribute[] = $attribute;
    
        return $this;
    }

    /**
     * Remove attribute
     *
     * @param \MilesApart\AdminBundle\Entity\Attribute $attribute
     */
    public function removeAttribute(\MilesApart\AdminBundle\Entity\Attribute $attribute)
    {
        $this->attribute->removeElement($attribute);
    }

    /**
     * Get attribute
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAttribute()
    {
        return $this->attribute;
    }
}