<?php
// src/MilesApart/AdminBundle/Entity/Stocktake.php -- Defines the customer type object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\StocktakeRepository")
 * @ORM\Table(name="stocktake")
 * @ORM\HasLifecycleCallbacks()
 */

class Stocktake
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
     * @ORM\ManyToOne(targetEntity="BusinessPremises", inversedBy="stocktake")
     * @ORM\JoinTable(name="business_premises")
     * @ORM\JoinColumn(name="business_premises_id", referencedColumnName="id")
     */
    protected $business_premises;

    /**
     * @ORM\Column(type="date", unique=false, nullable=false)
     */
    protected $stocktake_start_date;

    /**
     * @ORM\Column(type="date", unique=false, nullable=true)
     */
    protected $stocktake_completed_date;

    /**
     * @ORM\OneToMany(targetEntity="StocktakeProduct", mappedBy="stocktake", cascade={"persist"})
     */
    protected $stocktake_product;

    /**
     * @ORM\OneToMany(targetEntity="StocktakeSeasonalStorageBox", mappedBy="stocktake", cascade={"persist"})
     */
    protected $stocktake_seasonal_storage_box;



    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //Business premises
        $metadata->addPropertyConstraint('business_premises', new Assert\Choice(array(
            'callback' => 'getBusinessPremises',
        )));

        //Stocktake date
        $metadata->addPropertyConstraint('stocktake_date', new Assert\NotBlank());
        $metadata->addPropertyConstraint('stocktake_date', new Assert\Date());

    }

    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->stocktake_product = new \Doctrine\Common\Collections\ArrayCollection();
        $this->stocktake_seasonal_storage_box = new \Doctrine\Common\Collections\ArrayCollection();
        $this->stocktake_start_date = new \DateTime();
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
     * Set stocktake_start_date
     *
     * @param \DateTime $stocktakeStartDate
     * @return Stocktake
     */
    public function setStocktakeStartDate($stocktakeStartDate)
    {
        $this->stocktake_start_date = $stocktakeStartDate;
    
        return $this;
    }

    /**
     * Get stocktake_start_date
     *
     * @return \DateTime 
     */
    public function getStocktakeStartDate()
    {
        return $this->stocktake_start_date;
    }

    /**
     * Set stocktake_completed_date
     *
     * @param \DateTime $stocktakeCompletedDate
     * @return Stocktake
     */
    public function setStocktakeCompletedDate($stocktakeCompletedDate)
    {
        $this->stocktake_completed_date = $stocktakeCompletedDate;
    
        return $this;
    }

    /**
     * Get stocktake_completed_date
     *
     * @return \DateTime 
     */
    public function getStocktakeCompletedDate()
    {
        return $this->stocktake_completed_date;
    }

    /**
     * Set business_premises
     *
     * @param \MilesApart\AdminBundle\Entity\BusinessPremises $businessPremises
     * @return Stocktake
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
     * Add stocktake_product
     *
     * @param \MilesApart\AdminBundle\Entity\StocktakeProduct $stocktakeProduct
     * @return Stocktake
     */
    public function addStocktakeProduct(\MilesApart\AdminBundle\Entity\StocktakeProduct $stocktakeProduct)
    {
        $this->stocktake_product[] = $stocktakeProduct;
    
        return $this;
    }

    /**
     * Remove stocktake_product
     *
     * @param \MilesApart\AdminBundle\Entity\StocktakeProduct $stocktakeProduct
     */
    public function removeStocktakeProduct(\MilesApart\AdminBundle\Entity\StocktakeProduct $stocktakeProduct)
    {
        $this->stocktake_product->removeElement($stocktakeProduct);
    }

    /**
     * Get stocktake_product
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getStocktakeProduct()
    {
        return $this->stocktake_product;
    }

    /**
     * Add stocktake_seasonal_storage_box
     *
     * @param \MilesApart\AdminBundle\Entity\StocktakeSeasonalStorageBox $stocktakeSeasonalStorageBox
     * @return Stocktake
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