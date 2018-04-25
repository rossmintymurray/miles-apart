<?php
// src/MilesApart/StaffBundle/Entity/PackUpSeasonal.php -- Defines the pack up seasonal object

namespace MilesApart\StaffBundle\Entity\Products;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;


class PackUpSeasonal
{
    //Define the values

    protected $season;

    protected $business_premises;

    /**
     * Set season
     *
     * @param string $season
     * @return season
     */
    public function setSeason($season)
    {
        $this->season = $season;
    
        return $this;
    }

    /**
     * Get season
     *
     * @return string 
     */
    public function getSeason()
    {
        return $this->season;
    }

    /**
     * Set business_premises
     *
     * @param string $businessPremises
     * @return businessPremises
     */
    public function setBusinessPremises($businessPremises)
    {
        $this->business_premises = $businessPremises;
    
        return $this;
    }

    /**
     * Get business_premises
     *
     * @return string 
     */
    public function getBusinessPremises()
    {
        return $this->business_premises;
    }
}
