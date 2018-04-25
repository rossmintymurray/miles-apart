<?php
// src/MilesApart/AdminBundle/Entity/RoyalMailShipmentState.php -- Defines the admin user object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;



use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\RoyalMailShipmentStateRepository")
 * @ORM\Table(name="royal_mail_shipment_state")
 * @ORM\HasLifecycleCallbacks()
 */

class RoyalMailShipmentState
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
     * @ORM\Column(type="string", length=100, nullable=false, unique=true)
     */
    protected $royal_mail_shipment_state;

     /**
     * @ORM\OneToMany(targetEntity="RoyalMailShipment", mappedBy="royal_mail_shipment_state")
     */
    protected $royal_mail_shipment;
   

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
     * Set royal_mail_shipment_state
     *
     * @param string $royalMailShipmentState
     * @return RoyalMailShipmentState
     */
    public function setRoyalMailShipmentState($royalMailShipmentState)
    {
        $this->royal_mail_shipment_state = $royalMailShipmentState;

        return $this;
    }

    /**
     * Get royal_mail_shipment_state
     *
     * @return string 
     */
    public function getRoyalMailShipmentState()
    {
        return $this->royal_mail_shipment_state;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->royal_mail_shipment = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add royal_mail_shipment
     *
     * @param \MilesApart\AdminBundle\Entity\RoyalMailShipment $royalMailShipment
     * @return RoyalMailShipmentState
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
}
