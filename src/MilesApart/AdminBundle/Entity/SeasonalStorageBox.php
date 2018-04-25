<?php
// src/MilesApart/AdminBundle/Entity/SeasonalStorageBox.php -- Defines the stock location object (for storage of products)

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\SeasonalStorageBoxRepository")
 * @ORM\Table(name="seasonal_storage_box")
 * @ORM\HasLifecycleCallbacks()
 */

class SeasonalStorageBox
{
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
    protected $seasonal_storage_box_code;

    /**
     * @ORM\ManyToOne(targetEntity="Season", inversedBy="seasonal_storage_box", cascade={"persist"})
     * @ORM\JoinTable(name="season")
     * @ORM\JoinColumn(name="season_id", referencedColumnName="id")
     */
    protected $season;

    /**
     * @ORM\ManyToOne(targetEntity="BusinessPremises", inversedBy="seasonal_storage_box", cascade={"persist"})
     * @ORM\JoinTable(name="business_premises")
     * @ORM\JoinColumn(name="business_premises_id", referencedColumnName="id")
     */
    protected $business_premises;

    /**
     * @ORM\OneToMany(targetEntity="SeasonalStorageBoxProduct", mappedBy="seasonal_storage_box")
     */
    protected $seasonal_storage_box_product;

    /**
     * @ORM\OneToMany(targetEntity="StocktakeSeasonalStorageBox", mappedBy="seasonal_storage_box")
     */
    protected $stocktake_seasonal_storage_box;

    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->seasonal_storage_box_product = new \Doctrine\Common\Collections\ArrayCollection();
        $this->stocktake_seasonal_storage_box = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set seasonal_storage_box_code
     *
     * @param string $seasonalStorageBoxCode
     * @return SeasonalStorageBox
     */
    public function setSeasonalStorageBoxCode($seasonalStorageBoxCode)
    {
        $this->seasonal_storage_box_code = $seasonalStorageBoxCode;
    
        return $this;
    }

    /**
     * Get seasonal_storage_box_code
     *
     * @return string 
     */
    public function getSeasonalStorageBoxCode()
    {
        return $this->seasonal_storage_box_code;
    }

    /**
     * Set season
     *
     * @param \MilesApart\AdminBundle\Entity\Season $season
     * @return SeasonalStorageBox
     */
    public function setSeason(\MilesApart\AdminBundle\Entity\Season $season = null)
    {
        $this->season = $season;
    
        return $this;
    }

    /**
     * Get season
     *
     * @return \MilesApart\AdminBundle\Entity\Season 
     */
    public function getSeason()
    {
        return $this->season;
    }

    /**
     * Set business_premises
     *
     * @param \MilesApart\AdminBundle\Entity\BusinessPremises $businessPremises
     * @return SeasonalStorageBox
     */
    public function setBusinessPremises(\MilesApart\AdminBundle\Entity\BusinessPremises $businessPremises = null)
    {
        $this->business_premises = $businessPremises;
    
        return $this;
    }

    /**
     * Get business_premises
     *
     * @return \MilesApart\AdminBundle\Entity\BusinessPremises 
     */
    public function getBusinessPremises()
    {
        return $this->business_premises;
    }

    /**
     * Add seasonal_storage_box_product
     *
     * @param \MilesApart\AdminBundle\Entity\SeasonalStorageBoxProduct $seasonalStorageBoxProduct
     * @return SeasonalStorageBox
     */
    public function addSeasonalStorageBoxProduct(\MilesApart\AdminBundle\Entity\SeasonalStorageBoxProduct $seasonalStorageBoxProduct)
    {
        $this->seasonal_storage_box_product[] = $seasonalStorageBoxProduct;
    
        return $this;
    }

    /**
     * Remove seasonal_storage_box_product
     *
     * @param \MilesApart\AdminBundle\Entity\SeasonalStorageBoxProduct $seasonalStorageBoxProduct
     */
    public function removeSeasonalStorageBoxProduct(\MilesApart\AdminBundle\Entity\SeasonalStorageBoxProduct $seasonalStorageBoxProduct)
    {
        $this->seasonal_storage_box_product->removeElement($seasonalStorageBoxProduct);
    }

    /**
     * Get seasonal_storage_box_product
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSeasonalStorageBoxProduct()
    {
        return $this->seasonal_storage_box_product;
    }

    /**
     * Add stocktake_seasonal_storage_box
     *
     * @param \MilesApart\AdminBundle\Entity\StocktakeSeasonalStorageBox $stocktakeSeasonalStorageBox
     * @return SeasonalStorageBox
     */
    public function addStocktakeSeasonalStorageBox(\MilesApart\AdminBundle\Entity\StocktakeSeasonalStorageBox $stocktakeSeasonalStorageBox)
    {
        $this->stocktake_seasonal_storage_box[] = $stocktakeSeasonalStorageBox;
    
        return $this;
    }

    /**
     * Remove stocktake_seasonal_storage_box
     *
     * @param \MilesApart\AdminBundle\Entity\StocktakeSeasonalStorageBox $stocktakeSeasonalStorageBox
     */
    public function removeStocktakeSeasonalStorageBox(\MilesApart\AdminBundle\Entity\StocktakeSeasonalStorageBox $stocktakeSeasonalStorageBox)
    {
        $this->stocktake_seasonal_storage_box->removeElement($stocktakeSeasonalStorageBox);
    }

    /**
     * Get stocktake_seasonal_storage_box
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getStocktakeSeasonalStorageBox()
    {
        return $this->stocktake_seasonal_storage_box;
    }
}