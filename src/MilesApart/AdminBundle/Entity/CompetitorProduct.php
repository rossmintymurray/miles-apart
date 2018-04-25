<?php
// src/MilesApart/AdminBundle/Entity/CompetitorProduct.php -- Defines the competitor product object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\CompetitorProductRepository")
 * @ORM\Table(name="competitor_product")
 * @ORM\HasLifecycleCallbacks()
 */

class CompetitorProduct
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
     * @ORM\ManyToOne(targetEntity="Competitor", inversedBy="competitor_product")
     * @ORM\JoinTable(name="competitor")
     * @ORM\JoinColumn(name="competitor_id", referencedColumnName="id")
     */
    protected $competitor;

    /**
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="competitor_product")
     * @ORM\JoinTable(name="product")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    protected $product;

    /**
     * @ORM\Column(type="decimal", precision=4, scale=2, nullable=false)
     */
    protected $competitor_product_current_price;

    /**
     * @ORM\Column(type="datetime", unique=true, nullable=false)
     */
    protected $competitor_product_current_price_date_created;

    /**
     * @ORM\Column(type="datetime", unique=true, nullable=false)
     */
    protected $competitor_product_current_price_date_modified;




    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        $this->setCompetitorProductCurrentPriceDateModified(new \DateTime(date('Y-m-d H:i:s')));

        if($this->getCompetitorProductCurrentPriceDateCreated() == null)
        {
            $this->setCompetitorProductCurrentPriceDateCreated(new \DateTime(date('Y-m-d H:i:s')));
        }
    }



    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //Competitor product current price
        $metadata->addPropertyConstraint('competitor_product_current_price', new Assert\NotBlank());

        //Competitor product current price set 
        $metadata->addPropertyConstraint('competitor_product_current_price_set', new Assert\DateTime());
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
     * Set competitor_product_current_price
     *
     * @param float $competitorProductCurrentPrice
     * @return CompetitorProduct
     */
    public function setCompetitorProductCurrentPrice($competitorProductCurrentPrice)
    {
        $this->competitor_product_current_price = $competitorProductCurrentPrice;
    
        return $this;
    }

    /**
     * Get competitor_product_current_price
     *
     * @return float 
     */
    public function getCompetitorProductCurrentPrice()
    {
        return $this->competitor_product_current_price;
    }

    /**
     * Set competitor_product_current_price_date_created
     *
     * @param \DateTime $competitorProductCurrentPriceDateCreated
     * @return CompetitorProduct
     */
    public function setCompetitorProductCurrentPriceDateCreated($competitorProductCurrentPriceDateCreated)
    {
        $this->competitor_product_current_price_date_created = $competitorProductCurrentPriceDateCreated;
    
        return $this;
    }

    /**
     * Get competitor_product_current_price_date_created
     *
     * @return \DateTime 
     */
    public function getCompetitorProductCurrentPriceDateCreated()
    {
        return $this->competitor_product_current_price_date_created;
    }

    /**
     * Set competitor_product_current_price_date_modified
     *
     * @param \DateTime $competitorProductCurrentPriceDateModified
     * @return CompetitorProduct
     */
    public function setCompetitorProductCurrentPriceDateModified($competitorProductCurrentPriceDateModified)
    {
        $this->competitor_product_current_price_date_modified = $competitorProductCurrentPriceDateModified;
    
        return $this;
    }

    /**
     * Get competitor_product_current_price_date_modified
     *
     * @return \DateTime 
     */
    public function getCompetitorProductCurrentPriceDateModified()
    {
        return $this->competitor_product_current_price_date_modified;
    }

    /**
     * Set competitor
     *
     * @param \MilesApart\AdminBundle\Entity\Competitor $competitor
     * @return CompetitorProduct
     */
    public function setCompetitor(\MilesApart\AdminBundle\Entity\Competitor $competitor = null)
    {
        $this->competitor = $competitor;
    
        return $this;
    }

    /**
     * Get competitor
     *
     * @return \MilesApart\AdminBundle\Entity\Competitor 
     */
    public function getCompetitor()
    {
        return $this->competitor;
    }

    /**
     * Set product
     *
     * @param \MilesApart\AdminBundle\Entity\Product $product
     * @return CompetitorProduct
     */
    public function setProduct(\MilesApart\AdminBundle\Entity\Product $product = null)
    {
        $this->product = $product;
    
        return $this;
    }

    /**
     * Get product
     *
     * @return \MilesApart\AdminBundle\Entity\Product 
     */
    public function getProduct()
    {
        return $this->product;
    }
}