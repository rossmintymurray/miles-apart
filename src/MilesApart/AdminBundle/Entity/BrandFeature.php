<?php
// src/MilesApart/AdminBundle/Entity/BrandFeature.php -- Defines the brand object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\BrandFeatureRepository")
 * @ORM\Table(name="brand_feature")
 * @ORM\HasLifecycleCallbacks()
 */

class BrandFeature
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
     * @ORM\Column(type="string", length=200, nullable=false)
     */
    protected $brand_feature_text;

    /**
     * @ORM\ManyToOne(targetEntity="Brand", inversedBy="brand_feature", cascade={"persist"})
     * @ORM\JoinTable(name="brand")
     * @ORM\JoinColumn(name="brand_id", referencedColumnName="id")
     */
    protected $brand;

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
     * Set brand_feature_text
     *
     * @param string $brandFeatureText
     * @return BrandFeature
     */
    public function setBrandFeatureText($brandFeatureText)
    {
        $this->brand_feature_text = $brandFeatureText;
    
        return $this;
    }

    /**
     * Get brand_feature_text
     *
     * @return string 
     */
    public function getBrandFeatureText()
    {
        return $this->brand_feature_text;
    }

    /**
     * Set brand
     *
     * @param \MilesApart\AdminBundle\Entity\Brand $brand
     * @return BrandFeature
     */
    public function setBrand(\MilesApart\AdminBundle\Entity\Brand $brand = null)
    {
        $this->brand = $brand;
    
        return $this;
    }

    /**
     * Get brand
     *
     * @return \MilesApart\AdminBundle\Entity\Brand 
     */
    public function getBrand()
    {
        return $this->brand;
    }
}