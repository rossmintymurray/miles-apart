<?php
// src/MilesApart/AdminBundle/Entity/PostageBand.php -- Defines the customer type object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\PostageBandRepository")
 * @ORM\Table(name="postage_band")
 * @ORM\HasLifecycleCallbacks()
 */

class PostageBand
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
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $postage_band_max_length;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $postage_band_max_width;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $postage_band_max_depth;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $postage_band_max_weight;

    /**
     * @ORM\OneToMany(targetEntity="PostageBandDispatchLogistics", mappedBy="postage_band", cascade={"persist"})
     */
    protected $postage_band_dispatch_logistics;

    /**
     * @ORM\ManyToOne(targetEntity="PostageBandType", inversedBy="postage_band")
     * @ORM\JoinTable(name="postage_band_type")
     * @ORM\JoinColumn(name="postage_band_type_id", referencedColumnName="id")
     */
    protected $postage_band_type;

    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //Postage band min weight
        $metadata->addPropertyConstraint('postage_band_min_weight', new Assert\NotBlank());

        //Postage band max weight
        $metadata->addPropertyConstraint('postage_band_max_weight', new Assert\NotBlank());
    }



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->postage_band_dispatch_logistics = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set postage_band_max_length
     *
     * @param integer $postageBandMaxLength
     * @return PostageBand
     */
    public function setPostageBandMaxLength($postageBandMaxLength)
    {
        $this->postage_band_max_length = $postageBandMaxLength;
    
        return $this;
    }

    /**
     * Get postage_band_max_length
     *
     * @return integer 
     */
    public function getPostageBandMaxLength()
    {
        return $this->postage_band_max_length;
    }

    /**
     * Set postage_band_max_width
     *
     * @param integer $postageBandMaxWidth
     * @return PostageBand
     */
    public function setPostageBandMaxWidth($postageBandMaxWidth)
    {
        $this->postage_band_max_width = $postageBandMaxWidth;
    
        return $this;
    }

    /**
     * Get postage_band_max_width
     *
     * @return integer 
     */
    public function getPostageBandMaxWidth()
    {
        return $this->postage_band_max_width;
    }

    /**
     * Set postage_band_max_depth
     *
     * @param integer $postageBandMaxDepth
     * @return PostageBand
     */
    public function setPostageBandMaxDepth($postageBandMaxDepth)
    {
        $this->postage_band_max_depth = $postageBandMaxDepth;
    
        return $this;
    }

    /**
     * Get postage_band_max_depth
     *
     * @return integer 
     */
    public function getPostageBandMaxDepth()
    {
        return $this->postage_band_max_depth;
    }

    /**
     * Set postage_band_max_weight
     *
     * @param integer $postageBandMaxWeight
     * @return PostageBand
     */
    public function setPostageBandMaxWeight($postageBandMaxWeight)
    {
        $this->postage_band_max_weight = $postageBandMaxWeight;
    
        return $this;
    }

    /**
     * Get postage_band_max_weight
     *
     * @return integer 
     */
    public function getPostageBandMaxWeight()
    {
        return $this->postage_band_max_weight;
    }

    /**
     * Add postage_band_dispatch_logistics
     *
     * @param \MilesApart\AdminBundle\Entity\PostageBandDispatchLogistics $postageBandDispatchLogistics
     * @return PostageBand
     */
    public function addPostageBandDispatchLogistic(\MilesApart\AdminBundle\Entity\PostageBandDispatchLogistics $postageBandDispatchLogistics)
    {
        $this->postage_band_dispatch_logistics[] = $postageBandDispatchLogistics;
    
        return $this;
    }

    /**
     * Remove postage_band_dispatch_logistics
     *
     * @param \MilesApart\AdminBundle\Entity\PostageBandDispatchLogistics $postageBandDispatchLogistics
     */
    public function removePostageBandDispatchLogistic(\MilesApart\AdminBundle\Entity\PostageBandDispatchLogistics $postageBandDispatchLogistics)
    {
        $this->postage_band_dispatch_logistics->removeElement($postageBandDispatchLogistics);
    }

    /**
     * Get postage_band_dispatch_logistics
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPostageBandDispatchLogistics()
    {
        return $this->postage_band_dispatch_logistics;
    }

    

    /**
     * Set postage_band_type
     *
     * @param \MilesApart\AdminBundle\Entity\PostageBandType $postageBandType
     * @return PostageBand
     */
    public function setPostageBandType(\MilesApart\AdminBundle\Entity\PostageBandType $postageBandType = null)
    {
        $this->postage_band_type = $postageBandType;

        return $this;
    }

    /**
     * Get postage_band_type
     *
     * @return \MilesApart\AdminBundle\Entity\PostageBandType 
     */
    public function getPostageBandType()
    {
        return $this->postage_band_type;
    }
}
