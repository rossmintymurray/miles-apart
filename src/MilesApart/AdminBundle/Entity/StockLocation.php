<?php
// src/MilesApart/AdminBundle/Entity/StockLocation.php -- Defines the stock location object (for storage of products)

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\StockLocationRepository")
 * @ORM\Table(name="stock_location")
 * @ORM\HasLifecycleCallbacks()
 */

class StockLocation
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
    protected $stock_location_name;

    /**
     * @ORM\Column(type="string", length=4, unique=true, nullable=false)
     */
    protected $stock_location_code;

    /**
     * @ORM\ManyToOne(targetEntity="BusinessPremises", inversedBy="stock_location")
     * @ORM\JoinTable(name="business_premises")
     * @ORM\JoinColumn(name="business_premises_id", referencedColumnName="id")
     */
    protected $business_premises;

    /**
     * @ORM\OneToMany(targetEntity="StockLocationShelf", mappedBy="stock_location")
     * @ORM\OrderBy({"stock_location_shelf_code" = "ASC"})
     */
    protected $stock_location_shelf;


    

    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //Stock location name
        $metadata->addPropertyConstraint('stock_location_name', new Assert\NotBlank());
        $metadata->addPropertyConstraint('stock_location_name', new Assert\Length(array(
            'min'        => 4,
            'max'        => 50,
            'minMessage' => 'The stock location name must be at least {{ limit }} characters length',
            'maxMessage' => 'The stock location name cannot be longer than {{ limit }} characters length',
        )));

    }


    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->stock_location_shelf = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set stock_location_name
     *
     * @param string $stockLocationName
     * @return StockLocation
     */
    public function setStockLocationName($stockLocationName)
    {
        $this->stock_location_name = $stockLocationName;
    
        return $this;
    }

    /**
     * Get stock_location_name
     *
     * @return string 
     */
    public function getStockLocationName()
    {
        return $this->stock_location_name;
    }

    /**
     * Set business_premises
     *
     * @param \MilesApart\AdminBundle\Entity\BusinessPremises $businessPremises
     * @return StockLocation
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
     * Add stock_location_shelf
     *
     * @param \MilesApart\AdminBundle\Entity\StockLocationShelf $stockLocationShelf
     * @return StockLocation
     */
    public function addStockLocationShelf(\MilesApart\AdminBundle\Entity\StockLocationShelf $stockLocationShelf)
    {
        $this->stock_location_shelf[] = $stockLocationShelf;
    
        return $this;
    }

    /**
     * Remove stock_location_shelf
     *
     * @param \MilesApart\AdminBundle\Entity\StockLocationShelf $stockLocationShelf
     */
    public function removeStockLocationShelf(\MilesApart\AdminBundle\Entity\StockLocationShelf $stockLocationShelf)
    {
        $this->stock_location_shelf->removeElement($stockLocationShelf);
    }

    /**
     * Get stock_location_shelf
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getStockLocationShelf()
    {
        return $this->stock_location_shelf;
    }


    /**
     * Set stock_location_code
     *
     * @param string $stockLocationCode
     * @return StockLocation
     */
    public function setStockLocationCode($stockLocationCode)
    {
        $this->stock_location_code = $stockLocationCode;
    
        return $this;
    }

    /**
     * Get stock_location_code
     *
     * @return string 
     */
    public function getStockLocationCode()
    {
        return $this->stock_location_code;
    }
}