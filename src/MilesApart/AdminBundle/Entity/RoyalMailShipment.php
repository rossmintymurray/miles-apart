<?php
// src/MilesApart/AdminBundle/Entity/RoyalMailShipment.php -- Defines the admin user object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;



use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\RoyalMailShipmentRepository")
 * @ORM\Table(name="royal_mail_shipment")
 * @ORM\HasLifecycleCallbacks()
 */

class RoyalMailShipment
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
     * @ORM\Column(type="string", length=10000, nullable=true)
     */
    protected $royal_mail_create_shipment_response_raw_xml;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $royal_mail_create_label_response_raw_xml;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    protected $royal_mail_shipment_number;

    /**
     * @ORM\ManyToOne(targetEntity="RoyalMailShipmentState", inversedBy="royal_mail_shipment", cascade={"persist"})
     * @ORM\JoinTable(name="royal_mail_shipment_state")
     * @ORM\JoinColumn(name="royal_mail_shipment_state_id", referencedColumnName="id")
     */
    protected $royal_mail_shipment_state;

   /**
     * @ORM\ManyToOne(targetEntity="CustomerOrder", inversedBy="royal_mail_shipment", cascade={"persist"})
     * @ORM\JoinTable(name="customer_order")
     * @ORM\JoinColumn(name="customer_order_id", referencedColumnName="id")
     */
    protected $customer_order;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $royal_mail_shipment_weight;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $royal_mail_shipment_number_of_items;

    /**
     * @ORM\Column(type="datetime", unique=false, nullable=true)
     */
    protected $royal_mail_shipment_date_created;

    /**
     * @ORM\Column(type="datetime", unique=false, nullable=false)
     */
    protected $royal_mail_shipment_date_modified;

    /**
     * @ORM\ManyToMany(targetEntity="RoyalMailShipmentWarning", inversedBy="royal_mail_shipment", cascade={"persist"})
     * @ORM\JoinTable(name="royal_mail_shipment_royal_mail_shipment_warning",
     * joinColumns={@ORM\JoinColumn(name="royal_mail_shipment_id", referencedColumnName="id")},
     * inverseJoinColumns={@ORM\JoinColumn(name="royal_mail_shipment_warning_id", referencedColumnName="id")})
     */
    protected $royal_mail_shipment_warning;

    /**
     * @ORM\ManyToOne(targetEntity="ShippingManifest", inversedBy="royal_mail_shipment", cascade={"persist"})
     * @ORM\JoinTable(name="shipping_manifest")
     * @ORM\JoinColumn(name="shipping_manifest_id", referencedColumnName="id")
     */
    protected $shipping_manifest;

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        $this->setRoyalMailShipmentDateModified(new \DateTime(date('Y-m-d H:i:s')));

        if($this->getRoyalMailShipmentDateCreated() == null)
        {
            $this->setRoyalMailShipmentDateCreated(new \DateTime(date('Y-m-d H:i:s')));
        }
    }

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
     * Set royal_mail_create_shipment_response_raw_xml
     *
     * @param string $royalMailCreateShipmentResponseRawXml
     * @return RoyalMailShipment
     */
    public function setRoyalMailCreateShipmentResponseRawXml($royalMailCreateShipmentResponseRawXml)
    {
        $this->royal_mail_create_shipment_response_raw_xml = $royalMailCreateShipmentResponseRawXml;

        return $this;
    }

    /**
     * Get royal_mail_create_shipment_response_raw_xml
     *
     * @return string 
     */
    public function getRoyalMailCreateShipmentResponseRawXml()
    {
        return $this->royal_mail_create_shipment_response_raw_xml;
    }

    /**
     * Set royal_mail_create_label_response_raw_xml
     *
     * @param string $royalMailCreateLabelResponseRawXml
     * @return RoyalMailShipment
     */
    public function setRoyalMailCreateLabelResponseRawXml($royalMailCreateLabelResponseRawXml)
    {
        $this->royal_mail_create_label_response_raw_xml = $royalMailCreateLabelResponseRawXml;

        return $this;
    }

    /**
     * Get royal_mail_create_label_response_raw_xml
     *
     * @return string 
     */
    public function getRoyalMailCreateLabelResponseRawXml()
    {
        return $this->royal_mail_create_label_response_raw_xml;
    }

    /**
     * Set royal_mail_shipment_weight
     *
     * @param integer $royalMailShipmentWeight
     * @return RoyalMailShipment
     */
    public function setRoyalMailShipmentWeight($royalMailShipmentWeight)
    {
        $this->royal_mail_shipment_weight = $royalMailShipmentWeight;

        return $this;
    }

    /**
     * Get royal_mail_shipment_weight
     *
     * @return integer 
     */
    public function getRoyalMailShipmentWeight()
    {
        return $this->royal_mail_shipment_weight;
    }

    /**
     * Set royal_mail_shipment_number_of_items
     *
     * @param integer $royalMailShipmentNumberOfItems
     * @return RoyalMailShipment
     */
    public function setRoyalMailShipmentNumberOfItems($royalMailShipmentNumberOfItems)
    {
        $this->royal_mail_shipment_number_of_items = $royalMailShipmentNumberOfItems;

        return $this;
    }

    /**
     * Get royal_mail_shipment_number_of_items
     *
     * @return integer 
     */
    public function getRoyalMailShipmentNumberOfItems()
    {
        return $this->royal_mail_shipment_number_of_items;
    }

    /**
     * Set royal_mail_shipment_date_created
     *
     * @param \DateTime $royalMailShipmentDateCreated
     * @return RoyalMailShipment
     */
    public function setRoyalMailShipmentDateCreated($royalMailShipmentDateCreated)
    {
        $this->royal_mail_shipment_date_created = $royalMailShipmentDateCreated;

        return $this;
    }

    /**
     * Get royal_mail_shipment_date_created
     *
     * @return \DateTime 
     */
    public function getRoyalMailShipmentDateCreated()
    {
        return $this->royal_mail_shipment_date_created;
    }

    /**
     * Set royal_mail_shipment_date_modified
     *
     * @param \DateTime $royalMailShipmentDateModified
     * @return RoyalMailShipment
     */
    public function setRoyalMailShipmentDateModified($royalMailShipmentDateModified)
    {
        $this->royal_mail_shipment_date_modified = $royalMailShipmentDateModified;

        return $this;
    }

    /**
     * Get royal_mail_shipment_date_modified
     *
     * @return \DateTime 
     */
    public function getRoyalMailShipmentDateModified()
    {
        return $this->royal_mail_shipment_date_modified;
    }

    /**
     * Set royal_mail_shipment_state
     *
     * @param \MilesApart\AdminBundle\Entity\RoyalMailShipmentState $royalMailShipmentState
     * @return RoyalMailShipment
     */
    public function setRoyalMailShipmentState(\MilesApart\AdminBundle\Entity\RoyalMailShipmentState $royalMailShipmentState = null)
    {
        $this->royal_mail_shipment_state = $royalMailShipmentState;

        return $this;
    }

    /**
     * Get royal_mail_shipment_state
     *
     * @return \MilesApart\AdminBundle\Entity\RoyalMailShipmentState 
     */
    public function getRoyalMailShipmentState()
    {
        return $this->royal_mail_shipment_state;
    }

    /**
     * Set customer_order
     *
     * @param \MilesApart\AdminBundle\Entity\CustomerOrder $customerOrder
     * @return RoyalMailShipment
     */
    public function setCustomerOrder(\MilesApart\AdminBundle\Entity\CustomerOrder $customerOrder = null)
    {
        $this->customer_order = $customerOrder;

        return $this;
    }

    /**
     * Get customer_order
     *
     * @return \MilesApart\AdminBundle\Entity\CustomerOrder 
     */
    public function getCustomerOrder()
    {
        return $this->customer_order;
    }
    
    /**
     * Add royal_mail_shipment_warning
     *
     * @param \MilesApart\AdminBundle\Entity\RoyalMailShipmentWarning $royalMailShipmentWarning
     * @return RoyalMailShipment
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

    /**
     * Set royal_mail_shipment_number
     *
     * @param string $royalMailShipmentNumber
     * @return RoyalMailShipment
     */
    public function setRoyalMailShipmentNumber($royalMailShipmentNumber)
    {
        $this->royal_mail_shipment_number = $royalMailShipmentNumber;

        return $this;
    }

    /**
     * Get royal_mail_shipment_number
     *
     * @return string 
     */
    public function getRoyalMailShipmentNumber()
    {
        return $this->royal_mail_shipment_number;
    }

    /**
     * Set shipping_manifest
     *
     * @param \MilesApart\AdminBundle\Entity\ShippingManifest $shippingManifest
     * @return RoyalMailShipment
     */
    public function setShippingManifest(\MilesApart\AdminBundle\Entity\ShippingManifest $shippingManifest = null)
    {
        $this->shipping_manifest = $shippingManifest;

        return $this;
    }

    /**
     * Get shipping_manifest
     *
     * @return \MilesApart\AdminBundle\Entity\ShippingManifest 
     */
    public function getShippingManifest()
    {
        return $this->shipping_manifest;
    }
    

}
