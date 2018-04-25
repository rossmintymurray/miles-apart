<?php
// src/MilesApart/AdminBundle/Entity/RoyalMailShipmentWarning.php -- Defines the admin user object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;



use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\RoyalMailShipmentWarningRepository")
 * @ORM\Table(name="royal_mail_shipment_warning")
 * @ORM\HasLifecycleCallbacks()
 */

class RoyalMailShipmentWarning
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
     * @ORM\Column(type="string", length=100, nullable=false)
     */
    protected $royal_mail_shipment_warning_code;

    /**
     * @ORM\Column(type="string", length=2000, nullable=false)
     */
    protected $royal_mail_shipment_warning_description;

     /**
     * @ORM\Column(type="string", length=2000, nullable=true)
     */
    protected $royal_mail_shipment_warning_resolution;

    /**
     * @ORM\ManyToMany(targetEntity="RoyalMailShipment", mappedBy="royal_mail_shipment_warning", cascade={"persist"})
     */
    protected $royal_mail_shipment;

    /**
     * @ORM\ManyToOne(targetEntity="RoyalMailShipmentWarningType", inversedBy="royal_mail_shipment_warning", cascade={"persist"})
     * @ORM\JoinTable(name="royal_mail_shipment_warning_type")
     * @ORM\JoinColumn(name="royal_mail_shipment_warning_type_id", referencedColumnName="id")
     */
    protected $royal_mail_shipment_warning_type;
    

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
     * Set royal_mail_shipment_warning_code
     *
     * @param string $royalMailShipmentWarningCode
     * @return RoyalMailShipmentWarning
     */
    public function setRoyalMailShipmentWarningCode($royalMailShipmentWarningCode)
    {
        $this->royal_mail_shipment_warning_code = $royalMailShipmentWarningCode;

        return $this;
    }

    /**
     * Get royal_mail_shipment_warning_code
     *
     * @return string 
     */
    public function getRoyalMailShipmentWarningCode()
    {
        return $this->royal_mail_shipment_warning_code;
    }

    /**
     * Set royal_mail_shipment_warning_description
     *
     * @param string $royalMailShipmentWarningDescription
     * @return RoyalMailShipmentWarning
     */
    public function setRoyalMailShipmentWarningDescription($royalMailShipmentWarningDescription)
    {
        $this->royal_mail_shipment_warning_description = $royalMailShipmentWarningDescription;

        return $this;
    }

    /**
     * Get royal_mail_shipment_warning_description
     *
     * @return string 
     */
    public function getRoyalMailShipmentWarningDescription()
    {
        return $this->royal_mail_shipment_warning_description;
    }

    /**
     * Set royal_mail_shipment_warning_resolution
     *
     * @param string $royalMailShipmentWarningResolution
     * @return RoyalMailShipmentWarning
     */
    public function setRoyalMailShipmentWarningResolution($royalMailShipmentWarningResolution)
    {
        $this->royal_mail_shipment_warning_resolution = $royalMailShipmentWarningResolution;

        return $this;
    }

    /**
     * Get royal_mail_shipment_warning_resolution
     *
     * @return string 
     */
    public function getRoyalMailShipmentWarningResolution()
    {
        return $this->royal_mail_shipment_warning_resolution;
    }

    /**
     * Add royal_mail_shipment
     *
     * @param \MilesApart\AdminBundle\Entity\RoyalMailShipment $royalMailShipment
     * @return RoyalMailShipmentWarning
     */
    public function addRoyalMailShipment(\MilesApart\AdminBundle\Entity\RoyalMailShipment $royalMailShipment)
    {
        $this->royal_mail_shipment[] = $royalMailShipment;

        return $this;
    }

    /**
     * Remove royal_mail_shipment
     *
     * @param \MilesApart\AdminBundle\Entity\RoyalMailShipment $royalMailShipment
     */
    public function removeRoyalMailShipment(\MilesApart\AdminBundle\Entity\RoyalMailShipment $royalMailShipment)
    {
        $this->royal_mail_shipment->removeElement($royalMailShipment);
    }

    /**
     * Get royal_mail_shipment
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRoyalMailShipment()
    {
        return $this->royal_mail_shipment;
    }

    /**
     * Set royal_mail_shipment_warning_type
     *
     * @param \MilesApart\AdminBundle\Entity\RoyalMailShipmentWarningType $royalMailShipmentWarningType
     * @return RoyalMailShipmentWarning
     */
    public function setRoyalMailShipmentWarningType(\MilesApart\AdminBundle\Entity\RoyalMailShipmentWarningType $royalMailShipmentWarningType = null)
    {
        $this->royal_mail_shipment_warning_type = $royalMailShipmentWarningType;

        return $this;
    }

    /**
     * Get royal_mail_shipment_warning_type
     *
     * @return \MilesApart\AdminBundle\Entity\RoyalMailShipmentWarningType 
     */
    public function getRoyalMailShipmentWarningType()
    {
        return $this->royal_mail_shipment_warning_type;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->royal_mail_shipment = new \Doctrine\Common\Collections\ArrayCollection();
    }

}
