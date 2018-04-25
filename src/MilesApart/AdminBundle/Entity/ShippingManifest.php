<?php
// src/MilesApart/AdminBundle/Entity/ShippingManifest.php -- Defines the customer type object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\ShippingManifestRepository")
 * @ORM\Table(name="shipping_manifest")
 * @ORM\HasLifecycleCallbacks()
 */

class ShippingManifest
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
     * @ORM\Column(type="datetime", unique=false, nullable=false)
     */
    protected $shipping_manifest_date_created;

    /**
     * @ORM\Column(type="datetime", unique=false, nullable=true)
     */
    protected $shipping_manifest_date_modified;

    /**
     * @ORM\ManyToOne(targetEntity="ShippingManifestState", inversedBy="shipping_manifest", cascade={"persist"})
     * @ORM\JoinTable(name="shipping_manifest_state")
     * @ORM\JoinColumn(name="shipping_manifest_state_id", referencedColumnName="id")
     */
    protected $shipping_manifest_state;

    /**
     * @ORM\OneToMany(targetEntity="RoyalMailShipment", mappedBy="shipping_manifest", cascade={"persist"})
     */
    protected $royal_mail_shipment;

   /**
     * @ORM\Column(type="integer")
     */
    protected $royal_mail_batch_number;

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        $this->setShippingManifestDateModified(new \DateTime(date('Y-m-d H:i:s')));

        if($this->getShippingManifestDateCreated() == null)
        {
            $this->setShippingManifestDateCreated(new \DateTime(date('Y-m-d H:i:s')));
        }
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->royal_mail_shipment = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set shipping_manifest_date_created
     *
     * @param \DateTime $shippingManifestDateCreated
     * @return ShippingManifest
     */
    public function setShippingManifestDateCreated($shippingManifestDateCreated)
    {
        $this->shipping_manifest_date_created = $shippingManifestDateCreated;

        return $this;
    }

    /**
     * Get shipping_manifest_date_created
     *
     * @return \DateTime 
     */
    public function getShippingManifestDateCreated()
    {
        return $this->shipping_manifest_date_created;
    }

    /**
     * Set shipping_manifest_date_modified
     *
     * @param \DateTime $shippingManifestDateModified
     * @return ShippingManifest
     */
    public function setShippingManifestDateModified($shippingManifestDateModified)
    {
        $this->shipping_manifest_date_modified = $shippingManifestDateModified;

        return $this;
    }

    /**
     * Get shipping_manifest_date_modified
     *
     * @return \DateTime 
     */
    public function getShippingManifestDateModified()
    {
        return $this->shipping_manifest_date_modified;
    }

    /**
     * Set royal_mail_batch_number
     *
     * @param integer $royalMailBatchNumber
     * @return ShippingManifest
     */
    public function setRoyalMailBatchNumber($royalMailBatchNumber)
    {
        $this->royal_mail_batch_number = $royalMailBatchNumber;

        return $this;
    }

    /**
     * Get royal_mail_batch_number
     *
     * @return integer 
     */
    public function getRoyalMailBatchNumber()
    {
        return $this->royal_mail_batch_number;
    }

    /**
     * Set shipping_manifest_state
     *
     * @param \MilesApart\AdminBundle\Entity\ShippingManifestState $shippingManifestState
     * @return ShippingManifest
     */
    public function setShippingManifestState(\MilesApart\AdminBundle\Entity\ShippingManifestState $shippingManifestState = null)
    {
        $this->shipping_manifest_state = $shippingManifestState;

        return $this;
    }

    /**
     * Get shipping_manifest_state
     *
     * @return \MilesApart\AdminBundle\Entity\ShippingManifestState 
     */
    public function getShippingManifestState()
    {
        return $this->shipping_manifest_state;
    }
    

    /**
     * Add royal_mail_shipment
     *
     * @param \MilesApart\AdminBundle\Entity\RoyalMailShipment $royalMailShipment
     * @return ShippingManifest
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
