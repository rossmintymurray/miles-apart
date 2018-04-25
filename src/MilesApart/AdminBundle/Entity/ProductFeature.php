<?php
// src/MilesApart/AdminBundle/Entity/ProductFeature.php -- Defines the product feature object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\ProductFeatureRepository")
 * @ORM\Table(name="product_feature")
 * @ORM\HasLifecycleCallbacks()
 */

class ProductFeature
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
     * @ORM\Column(type="string", length=1000, unique=false, nullable=false)
     */
    protected $product_feature_text;

    /**
     * @ORM\ManyToMany(targetEntity="Product", mappedBy="product_feature", cascade={"persist"})
     */
    protected $product;



    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //Product feature text
        $metadata->addPropertyConstraint('product_feature_text', new Assert\NotBlank());
        $metadata->addPropertyConstraint('product_feature_text', new Assert\Length(array(
            'min'        => 4,
            'max'        => 1000,
            'minMessage' => 'The product feature text must be at least {{ limit }} characters length',
            'maxMessage' => 'The product feature text cannot be longer than {{ limit }} characters length',
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
     * Set product_feature_text
     *
     * @param string $productFeatureText
     * @return ProductFeature
     */
    public function setProductFeatureText($productFeatureText)
    {
        $this->product_feature_text = $productFeatureText;
    
        return $this;
    }

    /**
     * Get product_feature_text
     *
     * @return string 
     */
    public function getProductFeatureText()
    {
        return $this->product_feature_text;
    }

    /**
     * Set product
     *
     * @param \MilesApart\AdminBundle\Entity\Product $product
     * @return ProductFeature
     */
    public function setProduct(\MilesApart\AdminBundle\Entity\Product $product = null)
    {
        $this->product = $product;
    
        return $this;
    }

    /**
     * Add product
     *
     * @param \MilesApart\AdminBundle\Entity\Product $product
     * @return ProductFeature
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