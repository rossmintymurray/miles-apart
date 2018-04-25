<?php
// src/MilesApart/AdminBundle/Entity/StocktakeSeasonalStorageBox.php -- Defines the customer type object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\StocktakeSeasonalStorageBoxRepository")
 * @ORM\Table(name="stocktake_seasonal_storage_box")
 * @ORM\HasLifecycleCallbacks()
 */

class StocktakeSeasonalStorageBox
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
     * @ORM\ManyToOne(targetEntity="Stocktake", inversedBy="stocktake_seasonal_storage_box", cascade={"persist"})
     * @ORM\JoinTable(name="stocktake")
     * @ORM\JoinColumn(name="stocktake_id", referencedColumnName="id")
     */
    protected $stocktake;

    /**
     * @ORM\ManyToOne(targetEntity="StockLocationShelf", inversedBy="stocktake_seasonal_storage_box")
     * @ORM\JoinTable(name="stock_location_shelf")
     * @ORM\JoinColumn(name="stock_location_shelf_id", referencedColumnName="id")
     */
    protected $stock_location_shelf;

    /**
     * @ORM\ManyToOne(targetEntity="SeasonalStorageBox", inversedBy="stocktake_seasonal_storage_box")
     * @ORM\JoinTable(name="seasonal_storage_box")
     * @ORM\JoinColumn(name="seasonal_storage_box_id", referencedColumnName="id")
     */
    protected $seasonal_storage_box;

    /**
     * @ORM\Column(type="datetime", unique=false, nullable=true)
     */
    protected $stocktake_seasonal_storage_box_date_created;

    /**
     * @ORM\Column(type="datetime", unique=false, nullable=false)
     */
    protected $stocktake_seasonal_storage_box_date_modified;

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        $this->setStocktakeSeasonalStorageBoxDateModified(new \DateTime(date('Y-m-d H:i:s')));

        if($this->getStocktakeSeasonalStorageBoxDateCreated() == null)
        {
            $this->setStocktakeSeasonalStorageBoxDateCreated(new \DateTime(date('Y-m-d H:i:s')));
        }
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
     * Set stocktake_seasonal_storage_box_date_created
     *
     * @param \DateTime $stocktakeSeasonalStorageBoxDateCreated
     * @return StocktakeSeasonalStorageBox
     */
    public function setStocktakeSeasonalStorageBoxDateCreated($stocktakeSeasonalStorageBoxDateCreated)
    {
        $this->stocktake_seasonal_storage_box_date_created = $stocktakeSeasonalStorageBoxDateCreated;
    
        return $this;
    }

    /**
     * Get stocktake_seasonal_storage_box_date_created
     *
     * @return \DateTime 
     */
    public function getStocktakeSeasonalStorageBoxDateCreated()
    {
        return $this->stocktake_seasonal_storage_box_date_created;
    }

    /**
     * Set stocktake_seasonal_storage_box_date_modified
     *
     * @param \DateTime $stocktakeSeasonalStorageBoxDateModified
     * @return StocktakeSeasonalStorageBox
     */
    public function setStocktakeSeasonalStorageBoxDateModified($stocktakeSeasonalStorageBoxDateModified)
    {
        $this->stocktake_seasonal_storage_box_date_modified = $stocktakeSeasonalStorageBoxDateModified;
    
        return $this;
    }

    /**
     * Get stocktake_seasonal_storage_box_date_modified
     *
     * @return \DateTime 
     */
    public function getStocktakeSeasonalStorageBoxDateModified()
    {
        return $this->stocktake_seasonal_storage_box_date_modified;
    }

    /**
     * Set stocktake
     *
     * @param \MilesApart\AdminBundle\Entity\Stocktake $stocktake
     * @return StocktakeSeasonalStorageBox
     */
    public function setStocktake(\MilesApart\AdminBundle\Entity\Stocktake $stocktake = null)
    {
        $this->stocktake = $stocktake;
    
        return $this;
    }

    /**
     * Get stocktake
     *
     * @return \MilesApart\AdminBundle\Entity\Stocktake 
     */
    public function getStocktake()
    {
        return $this->stocktake;
    }

    /**
     * Set stock_location_shelf
     *
     * @param \MilesApart\AdminBundle\Entity\StockLocationShelf $stockLocationShelf
     * @return StocktakeSeasonalStorageBox
     */
    public function setStockLocationShelf(\MilesApart\AdminBundle\Entity\StockLocationShelf $stockLocationShelf = null)
    {
        $this->stock_location_shelf = $stockLocationShelf;
    
        return $this;
    }

    /**
     * Get stock_location_shelf
     *
     * @return \MilesApart\AdminBundle\Entity\StockLocationShelf 
     */
    public function getStockLocationShelf()
    {
        return $this->stock_location_shelf;
    }

    /**
     * Set seasonal_storage_box
     *
     * @param \MilesApart\AdminBundle\Entity\SeasonalStorageBox $seasonalStorageBox
     * @return StocktakeSeasonalStorageBox
     */
    public function setSeasonalStorageBox(\MilesApart\AdminBundle\Entity\SeasonalStorageBox $seasonalStorageBox = null)
    {
        $this->seasonal_storage_box = $seasonalStorageBox;
    
        return $this;
    }

    /**
     * Get seasonal_storage_box
     *
     * @return \MilesApart\AdminBundle\Entity\SeasonalStorageBox 
     */
    public function getSeasonalStorageBox()
    {
        return $this->seasonal_storage_box;
    }
}