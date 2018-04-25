<?php
// src/MilesApart/AdminBundle/Entity/RoyalMailShipmentWarningType.php -- Defines the admin user object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;



use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\RoyalMailShipmentWarningTypeRepository")
 * @ORM\Table(name="royal_mail_shipment_warning_type")
 * @ORM\HasLifecycleCallbacks()
 */

class RoyalMailShipmentWarningType
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
    protected $royal_mail_shipment_warning_type;

    

    /**
     * @ORM\OneToMany(targetEntity="RoyalMailShipmentWarning", mappedBy="royal_mail_shipment_warning_type", cascade={"persist"})
     */
    protected $royal_mail_shipment_warning;

    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->royal_mail_shipment_warning = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set royal_mail_shipment_warning_type
     *
     * @param string $royalMailShipmentWarningType
     * @return RoyalMailShipmentWarningType
     */
    public function setRoyalMailShipmentWarningType($royalMailShipmentWarningType)
    {
        $this->royal_mail_shipment_warning_type = $royalMailShipmentWarningType;

        return $this;
    }

    /**
     * Get royal_mail_shipment_warning_type
     *
     * @return string 
     */
    public function getRoyalMailShipmentWarningType()
    {
        return $this->royal_mail_shipment_warning_type;
    }

    /**
     * Add royal_mail_shipment_warning
     *
     * @param \MilesApart\AdminBundle\Entity\RoyalMailShipmentWarning $royalMailShipmentWarning
     * @return RoyalMailShipmentWarningType
     */
    public function addRoyalMailShipmentWarning(\MilesApart\AdminBundle\Entity\RoyalMailShipmentWarning $royalMailShipmentWarning)
    {
        $this->royal_mail_shipment_warning[] = $royalMailShipmentWarning;

        return $this;
    }

    /**
     * Remove royal_mail_shipment_warning
     *
     * @param \MilesApart\AdminBundle\Entity\RoyalMailShipmentWarning $royalMailShipmentWarning
     */
    public function removeRoyalMailShipmentWarning(\MilesApart\AdminBundle\Entity\RoyalMailShipmentWarning $royalMailShipmentWarning)
    {
        $this->royal_mail_shipment_warning->removeElement($royalMailShipmentWarning);
    }

    /**
     * Get royal_mail_shipment_warning
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRoyalMailShipmentWarning()
    {
        return $this->royal_mail_shipment_warning;
    }
}
