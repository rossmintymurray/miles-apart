<?php
// src/MilesApart/AdminBundle/Entity/AttributeValue.php -- Defines the attribute value object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use MilesApart\AdminBundle\Utils\MilesApart as MilesApart;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\AttributeValueRepository")
 * @ORM\Table(name="attribute_value")
 * @ORM\HasLifecycleCallbacks()
 */

class AttributeValue
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
     * @ORM\ManyToOne(targetEntity="Attribute", inversedBy="attribute_value")
     * @ORM\JoinTable(name="attribute")
     * @ORM\JoinColumn(name="attribute_id", referencedColumnName="id")
     */
    protected $attribute;

    /**
     * @ORM\Column(type="string", length=100, unique=false, nullable=true)
     */
    protected $attribute_value;

    /**
     * @ORM\ManyToMany(targetEntity="Product", mappedBy="attribute_value", cascade={"persist"})
     */
    protected $product;

    /**
     * Person domain object class
     *
     * @Gedmo\Slug(fields={"attribute_value"})
     * @ORM\Column(type="string", length=200, unique=true)
     */
    protected $attribute_value_slug;

    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {

        //Attribute value
        $metadata->addPropertyConstraint('attribute_value', new Assert\NotBlank());
        $metadata->addPropertyConstraint('attribute_value', new Assert\Length(array(
            'min'        => 1,
            'max'        => 100,
            'minMessage' => 'The attribute value must be at least {{ limit }} characters length',
            'maxMessage' => 'The attribute value cannot be longer than {{ limit }} characters length',
        )));
    }



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->product = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set attribute_value
     *
     * @param string $attributeValue
     * @return AttributeValue
     */
    public function setAttributeValue($attributeValue)
    {
        $this->attribute_value = $attributeValue;
    
        return $this;
    }

    /**
     * Get attribute_value
     *
     * @return string 
     */
    public function getAttributeValue()
    {
        return $this->attribute_value;
    }

    /**
     * Set attribute_value_slug
     *
     * @param string $attributeValueSlug
     * @return AttributeValue
     */
    public function setAttributeValueSlug($attributeValueSlug)
    {
        $this->attribute_value_slug = $attributeValueSlug;
    
        return $this;
    }

    /**
     * Get attribute_value_slug
     *
     * @return string 
     */
    public function getAttributeValueSlug()
    {
        return MilesApart::slugify($this->getAttributeValue());
    }

    /**
     * Set attribute
     *
     * @param \MilesApart\AdminBundle\Entity\Attribute $attribute
     * @return AttributeValue
     */
    public function setAttribute(\MilesApart\AdminBundle\Entity\Attribute $attribute = null)
    {
        $this->attribute = $attribute;
    
        return $this;
    }

    /**
     * Get attribute
     *
     * @return \MilesApart\AdminBundle\Entity\Attribute 
     */
    public function getAttribute()
    {
        return $this->attribute;
    }

    /**
     * Add product
     *
     * @param \MilesApart\AdminBundle\Entity\Product $product
     * @return AttributeValue
     */
    public function addProduct(\MilesApart\AdminBundle\Entity\Product $product)
    {
        $this->product[] = $product;
    
        return $this;
    }

    /**
     * Remove product
     *
     * @param \MilesApart\AdminBundle\Entity\Product $product
     */
    public function removeProduct(\MilesApart\AdminBundle\Entity\Product $product)
    {
        $this->product->removeElement($product);
    }

    /**
     * Get product
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProduct()
    {
        return $this->product;
    }
}