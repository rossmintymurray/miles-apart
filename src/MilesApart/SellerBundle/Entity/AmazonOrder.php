<?php
// src/MilesApart/SellerBundle/Entity/AmazonOrder.php -- Defines the admin user object

namespace MilesApart\SellerBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\SellerBundle\Entity\Repository\AmazonOrderRepository")
 * @ORM\Table(name="amazon_order")
 * @ORM\HasLifecycleCallbacks()
 */

class AmazonOrder
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
     * @ORM\Column(type="string", length=100, unique=true, nullable=false)
     */
    protected $amazon_order_id;

    /**
     * @ORM\Column(type="datetime", unique=false, nullable=false)
     */
    protected $purchase_date;

    /**
     * @ORM\Column(type="datetime", unique=false, nullable=false)
     */
    protected $last_update_date;

    /**
     * @ORM\Column(type="string", length=100, unique=false, nullable=false)
     */
    protected $marketplace_id;

     /**
     * @ORM\OneToOne(targetEntity="MilesApart\AdminBundle\Entity\CustomerOrder", inversedBy="amazon_order", cascade={"persist"})
     * @ORM\JoinTable(name="customer_order")
     * @ORM\JoinColumn(name="customer_order_id", referencedColumnName="id")
     */
    protected $customer_order;



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
     * Set amazon_order_id
     *
     * @param string $amazonOrderId
     * @return AmazonOrder
     */
    public function setAmazonOrderId($amazonOrderId)
    {
        $this->amazon_order_id = $amazonOrderId;

        return $this;
    }

    /**
     * Get amazon_order_id
     *
     * @return string 
     */
    public function getAmazonOrderId()
    {
        return $this->amazon_order_id;
    }

    /**
     * Set purchase_date
     *
     * @param \DateTime $purchaseDate
     * @return AmazonOrder
     */
    public function setPurchaseDate($purchaseDate)
    {
        $this->purchase_date = $purchaseDate;

        return $this;
    }

    /**
     * Get purchase_date
     *
     * @return \DateTime 
     */
    public function getPurchaseDate()
    {
        return $this->purchase_date;
    }

    /**
     * Set last_update_date
     *
     * @param \DateTime $lastUpdateDate
     * @return AmazonOrder
     */
    public function setLastUpdateDate($lastUpdateDate)
    {
        $this->last_update_date = $lastUpdateDate;

        return $this;
    }

    /**
     * Get last_update_date
     *
     * @return \DateTime 
     */
    public function getLastUpdateDate()
    {
        return $this->last_update_date;
    }

    /**
     * Set marketplace_id
     *
     * @param string $marketplaceId
     * @return AmazonOrder
     */
    public function setMarketplaceId($marketplaceId)
    {
        $this->marketplace_id = $marketplaceId;

        return $this;
    }

    /**
     * Get marketplace_id
     *
     * @return string 
     */
    public function getMarketplaceId()
    {
        return $this->marketplace_id;
    }

    /**
     * Set customer_order
     *
     * @param \MilesApart\AdminBundle\Entity\CustomerOrder $customerOrder
     * @return AmazonOrder
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
}
