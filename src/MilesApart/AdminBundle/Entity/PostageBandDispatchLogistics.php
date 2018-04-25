<?php
// src/MilesApart/AdminBundle/Entity/PostageBandDispatchLogistics.php -- Defines the customer type object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\PostageBandDispatchLogisticsRepository")
 * @ORM\Table(name="postage_band_dispatch_logistics")
 * @ORM\HasLifecycleCallbacks()
 */

class PostageBandDispatchLogistics
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
     * @ORM\ManyToOne(targetEntity="LogisticsCompany", inversedBy="postage_band_dispatch_logistics")
     * @ORM\JoinTable(name="logistics_company")
     * @ORM\JoinColumn(name="logistics_company_id", referencedColumnName="id")
     */
    protected $logistics_company;

    /**
     * @ORM\ManyToOne(targetEntity="PostageBand", inversedBy="postage_band_dispatch_logistics", cascade={"persist"})
     * @ORM\JoinTable(name="postage_band")
     * @ORM\JoinColumn(name="postage_band_id", referencedColumnName="id")
     */
    protected $postage_band;

    /**
     * @ORM\ManyToOne(targetEntity="PostageType", inversedBy="postage_band_dispatch_logistics")
     * @ORM\JoinTable(name="postage_type")
     * @ORM\JoinColumn(name="postage_type_id", referencedColumnName="id")
     */
    protected $postage_type;

    /**
     * @ORM\Column(type="decimal", precision=4, scale=2, nullable=false)
     */
    protected $postage_band_price;

    /**
     * @ORM\Column(type="date", unique=false, nullable=false)
     */
    protected $postage_band_price_effective_date;

     /**
     * @ORM\OneToMany(targetEntity="CustomerOrder", mappedBy="delivery_option", cascade={"persist"})
     */
    protected $customer_order;

    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //Employee
        $metadata->addPropertyConstraint('logistics_company', new Assert\Choice(array(
            'callback' => 'getLogisticsCompany',
        )));

        //Postage band
        $metadata->addPropertyConstraint('postage_band', new Assert\Choice(array(
            'callback' => 'getPostageBand',
        )));

        //Postage band price
        $metadata->addPropertyConstraint('postage_band_price', new Assert\NotBlank());

        //Postage band price effective date
        $metadata->addPropertyConstraint('postage_band_price_effective_date', new Assert\NotBlank());
        $metadata->addPropertyConstraint('postage_band_price_effective_date', new Assert\Date());
    }


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->customer_order = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set postage_band_price
     *
     * @param float $postageBandPrice
     * @return PostageBandDispatchLogistics
     */
    public function setPostageBandPrice($postageBandPrice)
    {
        $this->postage_band_price = $postageBandPrice;
    
        return $this;
    }

    /**
     * Get postage_band_price
     *
     * @return float 
     */
    public function getPostageBandPrice()
    {
        return $this->postage_band_price;
    }

    /**
     * Set postage_band_price_effective_date
     *
     * @param \DateTime $postageBandPriceEffectiveDate
     * @return PostageBandDispatchLogistics
     */
    public function setPostageBandPriceEffectiveDate($postageBandPriceEffectiveDate)
    {
        $this->postage_band_price_effective_date = $postageBandPriceEffectiveDate;
    
        return $this;
    }

    /**
     * Get postage_band_price_effective_date
     *
     * @return \DateTime 
     */
    public function getPostageBandPriceEffectiveDate()
    {
        return $this->postage_band_price_effective_date;
    }

    /**
     * Set logistics_company
     *
     * @param \MilesApart\AdminBundle\Entity\LogisticsCompany $logisticsCompany
     * @return PostageBandDispatchLogistics
     */
    public function setLogisticsCompany(\MilesApart\AdminBundle\Entity\LogisticsCompany $logisticsCompany = null)
    {
        $this->logistics_company = $logisticsCompany;
    
        return $this;
    }

    /**
     * Get logistics_company
     *
     * @return \MilesApart\AdminBundle\Entity\LogisticsCompany 
     */
    public function getLogisticsCompany()
    {
        return $this->logistics_company;
    }

    /**
     * Set postage_band
     *
     * @param \MilesApart\AdminBundle\Entity\PostageBand $postageBand
     * @return PostageBandDispatchLogistics
     */
    public function setPostageBand(\MilesApart\AdminBundle\Entity\PostageBand $postageBand = null)
    {
        $this->postage_band = $postageBand;
    
        return $this;
    }

    /**
     * Get postage_band
     *
     * @return \MilesApart\AdminBundle\Entity\PostageBand 
     */
    public function getPostageBand()
    {
        return $this->postage_band;
    }

    /**
     * Set postage_type
     *
     * @param \MilesApart\AdminBundle\Entity\PostageType $postageType
     * @return PostageBandDispatchLogistics
     */
    public function setPostageType(\MilesApart\AdminBundle\Entity\PostageType $postageType = null)
    {
        $this->postage_type = $postageType;

        return $this;
    }

    /**
     * Get postage_type
     *
     * @return \MilesApart\AdminBundle\Entity\PostageType 
     */
    public function getPostageType()
    {
        return $this->postage_type;
    }
    

    /**
     * Add customer_order
     *
     * @param \MilesApart\AdminBundle\Entity\CustomerOrder $customerOrder
     * @return PostageBandDispatchLogistics
     */
    public function addCustomerOrder(\MilesApart\AdminBundle\Entity\CustomerOrder $customerOrder)
    {
        $this->customer_order[] = $customerOrder;

        return $this;
    }

    /**
     * Remove customer_order
     *
     * @param \MilesApart\AdminBundle\Entity\CustomerOrder $customerOrder
     */
    public function removeCustomerOrder(\MilesApart\AdminBundle\Entity\CustomerOrder $customerOrder)
    {
        $this->customer_order->removeElement($customerOrder);
    }

    /**
     * Get customer_order
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCustomerOrder()
    {
        return $this->customer_order;
    }
}
