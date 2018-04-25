<?php
// src/MilesApart/AdminBundle/Entity/PostageBandType.php -- Defines the customer type object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\PostageBandTypeRepository")
 * @ORM\Table(name="postage_band_type")
 * @ORM\HasLifecycleCallbacks()
 */

class PostageBandType
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
     * @ORM\Column(type="string", unique=true, nullable=false)
     */
    protected $postage_band_type_name;

    /**
     * @ORM\Column(type="string", unique=false, nullable=false)
     */
    protected $royal_mail_postage_band_type_code;

    /**
     * @ORM\OneToMany(targetEntity="PostageBand", mappedBy="postage_band_type")
     */
    protected $postage_band;



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->postage_band = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set postage_band_type_name
     *
     * @param string $postageBandTypeName
     * @return PostageBandType
     */
    public function setPostageBandTypeName($postageBandTypeName)
    {
        $this->postage_band_type_name = $postageBandTypeName;

        return $this;
    }

    /**
     * Get postage_band_type_name
     *
     * @return string 
     */
    public function getPostageBandTypeName()
    {
        return $this->postage_band_type_name;
    }

    /**
     * Add postage_band
     *
     * @param \MilesApart\AdminBundle\Entity\PostageBand $postageBand
     * @return PostageBandType
     */
    public function addPostageBand(\MilesApart\AdminBundle\Entity\PostageBand $postageBand)
    {
        $this->postage_band[] = $postageBand;

        return $this;
    }

    /**
     * Remove postage_band
     *
     * @param \MilesApart\AdminBundle\Entity\PostageBand $postageBand
     */
    public function removePostageBand(\MilesApart\AdminBundle\Entity\PostageBand $postageBand)
    {
        $this->postage_band->removeElement($postageBand);
    }

    /**
     * Get postage_band
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPostageBand()
    {
        return $this->postage_band;
    }


    /**
     * Set royal_mail_postage_band_type_code
     *
     * @param string $royalMailPostageBandTypeCode
     * @return PostageBandType
     */
    public function setRoyalMailPostageBandTypeCode($royalMailPostageBandTypeCode)
    {
        $this->royal_mail_postage_band_type_code = $royalMailPostageBandTypeCode;

        return $this;
    }

    /**
     * Get royal_mail_postage_band_type_code
     *
     * @return string 
     */
    public function getRoyalMailPostageBandTypeCode()
    {
        return $this->royal_mail_postage_band_type_code;
    }
}
