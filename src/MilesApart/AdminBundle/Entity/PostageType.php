<?php
// src/MilesApart/AdminBundle/Entity/PostageType.php -- Defines the customer type object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\PostageTypeRepository")
 * @ORM\Table(name="postage_type")
 * @ORM\HasLifecycleCallbacks()
 */

class PostageType
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
     * @ORM\Column(type="string", nullable=false)
     */
    protected $postage_type_name;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    protected $postage_type_royal_mail_code;

    /**
     * @ORM\OneToMany(targetEntity="PostageBandDispatchLogistics", mappedBy="postage_type")
     */
    protected $postage_band_dispatch_logistics;

    

  
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
     * Set postage_type_name
     *
     * @param string $postageTypeName
     * @return PostageType
     */
    public function setPostageTypeName($postageTypeName)
    {
        $this->postage_type_name = $postageTypeName;

        return $this;
    }

    /**
     * Get postage_type_name
     *
     * @return string 
     */
    public function getPostageTypeName()
    {
        return $this->postage_type_name;
    }

    /**
     * Add postage_band_dispatch_logistics
     *
     * @param \MilesApart\AdminBundle\Entity\PostageBandDispatchLogistics $postageBandDispatchLogistics
     * @return PostageType
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
     * Set postage_type_royal_mail_code
     *
     * @param string $postageTypeRoyalMailCode
     * @return PostageType
     */
    public function setPostageTypeRoyalMailCode($postageTypeRoyalMailCode)
    {
        $this->postage_type_royal_mail_code = $postageTypeRoyalMailCode;

        return $this;
    }

    /**
     * Get postage_type_royal_mail_code
     *
     * @return string 
     */
    public function getPostageTypeRoyalMailCode()
    {
        return $this->postage_type_royal_mail_code;
    }
}
