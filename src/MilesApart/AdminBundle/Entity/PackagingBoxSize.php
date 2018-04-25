<?php
// src/MilesApart/AdminBundle/Entity/PackagingBoxSize.php -- Defines the customer type object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\PackagingBoxSizeRepository")
 * @ORM\Table(name="packaging_box_size")
 * @ORM\HasLifecycleCallbacks()
 */

class PackagingBoxSize
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
     * @ORM\Column(type="integer", unique=false, nullable=false)
     */
    protected $packaging_box_size_width;

    /**
     * @ORM\Column(type="integer", unique=false, nullable=false)
     */
    protected $packaging_box_size_height;

    /**
     * @ORM\Column(type="integer", unique=false, nullable=false)
     */
    protected $packaging_box_size_depth;

   

    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //Packing box size width
        $metadata->addPropertyConstraint('packaging_box_size_width', new Assert\NotBlank());

        //Packing box size height
        $metadata->addPropertyConstraint('packaging_box_size_height', new Assert\NotBlank());

        //Packing box size depth
        $metadata->addPropertyConstraint('packaging_box_size_depth', new Assert\NotBlank());
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
     * Set packaging_box_size_width
     *
     * @param integer $packagingBoxSizeWidth
     * @return PackagingBoxSize
     */
    public function setPackagingBoxSizeWidth($packagingBoxSizeWidth)
    {
        $this->packaging_box_size_width = $packagingBoxSizeWidth;
    
        return $this;
    }

    /**
     * Get packaging_box_size_width
     *
     * @return integer 
     */
    public function getPackagingBoxSizeWidth()
    {
        return $this->packaging_box_size_width;
    }

    /**
     * Set packaging_box_size_height
     *
     * @param integer $packagingBoxSizeHeight
     * @return PackagingBoxSize
     */
    public function setPackagingBoxSizeHeight($packagingBoxSizeHeight)
    {
        $this->packaging_box_size_height = $packagingBoxSizeHeight;
    
        return $this;
    }

    /**
     * Get packaging_box_size_height
     *
     * @return integer 
     */
    public function getPackagingBoxSizeHeight()
    {
        return $this->packaging_box_size_height;
    }

    /**
     * Set packaging_box_size_depth
     *
     * @param integer $packagingBoxSizeDepth
     * @return PackagingBoxSize
     */
    public function setPackagingBoxSizeDepth($packagingBoxSizeDepth)
    {
        $this->packaging_box_size_depth = $packagingBoxSizeDepth;
    
        return $this;
    }

    /**
     * Get packaging_box_size_depth
     *
     * @return integer 
     */
    public function getPackagingBoxSizeDepth()
    {
        return $this->packaging_box_size_depth;
    }
}