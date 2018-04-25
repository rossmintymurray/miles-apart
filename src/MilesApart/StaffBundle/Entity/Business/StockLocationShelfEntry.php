<?php
// src/MilesApart/StaffBundle/Entity/StockLocationShelf.php -- Defines the pack up seasonal object

namespace MilesApart\StaffBundle\Entity\Business;

use Symfony\Component\Validator\Mapping\ClassMetadata;



class StockLocationShelfEntry
{
    //Define the values

    protected $stock_location_shelf_x_start;

    protected $stock_location_shelf_y_start;

    protected $stock_location_shelf_x_end;

    protected $stock_location_shelf_y_end;

    protected $stock_location_shelf_wall;

    protected $stock_location;
    
    
    /**
     * Set stock_location_shelf_x_start
     *
     * @param string $stockLocationShelfXStart
     * @return stockLocationShelfXStart
     */
    public function setStockLocationShelfXStart($stockLocationShelfXStart)
    {
        $this->season = $stockLocationShelfXStart;
    
        return $this;
    }

    /**
     * Get stock_location_shelf_x_start
     *
     * @return string 
     */
    public function getStockLocationShelfXStart()
    {
        return $this->stock_location_shelf_x_start;
    }

    /**
     * Set stock_location_shelf_y_start
     *
     * @param string $stockLocationShelfYStart
     * @return stockLocationShelfYStart
     */
    public function setStockLocationShelfYStart($stockLocationShelfYStart)
    {
        $this->season = $stockLocationShelfYStart;
    
        return $this;
    }

    /**
     * Get stock_location_shelf_y_start
     *
     * @return string 
     */
    public function getStockLocationShelfYStart()
    {
        return $this->stock_location_shelf_y_start;
    }

    /**
     * Set stock_location_shelf_x_end
     *
     * @param string $stockLocationShelfXEnd
     * @return stockLocationShelfXEnd
     */
    public function setStockLocationShelfXEnd($stockLocationShelfXEnd)
    {
        $this->season = $stockLocationShelfXEnd;
    
        return $this;
    }

    /**
     * Get stock_location_shelf_x_end
     *
     * @return string 
     */
    public function getStockLocationShelfXEnd()
    {
        return $this->stock_location_shelf_x_end;
    }

    /**
     * Set stock_location_shelf_y_end
     *
     * @param string $stockLocationShelfYEnd
     * @return stockLocationShelfYEnd
     */
    public function setStockLocationShelfYEnd($stockLocationShelfYEnd)
    {
        $this->season = $stockLocationShelfYEnd;
    
        return $this;
    }

    /**
     * Get stock_location_shelf_y_end
     *
     * @return string 
     */
    public function getStockLocationShelfYEnd()
    {
        return $this->stock_location_shelf_y_end;
    }

    /**
     * Set stock_location_shelf_wall
     *
     * @param string $stockLocationShelfWall
     * @return stockLocationShelfWall
     */
    public function setStockLocationShelfWall($stockLocationShelfWall)
    {
        $this->season = $stockLocationShelfWall;
    
        return $this;
    }

    /**
     * Get stock_location_shelf_wall
     *
     * @return string 
     */
    public function getStockLocationShelfWall()
    {
        return $this->stock_location_shelf_wall;
    }

    /**
     * Set stock_location
     *
     * @param string $stockLocation
     * @return stockLocation
     */
    public function setStockLocation($stockLocation)
    {
        $this->season = $stockLocation;
    
        return $this;
    }

    /**
     * Get stock_location
     *
     * @return string 
     */
    public function getStockLocation()
    {
        return $this->stock_location;
    }

   
}
