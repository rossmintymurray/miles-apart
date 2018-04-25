<?php
// src/MilesApart/AdminBundle/Entity/SeasonalStorageBoxProduct.php -- Defines the customer type object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\SeasonalStorageBoxProductRepository")
 * @ORM\Table(name="seasonal_storage_box_product")
 * @ORM\HasLifecycleCallbacks()
 */

class SeasonalStorageBoxProduct
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
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="seasonal_storage_box_product", cascade={"persist"})
     * @ORM\JoinTable(name="product")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    protected $product;

    /**
     * @ORM\ManyToOne(targetEntity="SeasonalStorageBox", inversedBy="seasonal_storage_box_product")
     * @ORM\JoinTable(name="seasonal_storage_box")
     * @ORM\JoinColumn(name="seasonal_storage_box_id", referencedColumnName="id")
     */
    protected $seasonal_storage_box;

    /**
     * @ORM\Column(type="integer", unique=false, nullable=false)
     */
    protected $seasonal_storage_box_product_qty;

    /**
     * @ORM\Column(type="datetime", unique=false, nullable=true)
     */
    protected $seasonal_storage_box_product_date_created;

    /**
     * @ORM\Column(type="datetime", unique=false, nullable=false)
     */
    protected $seasonal_storage_box_product_date_modified;

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        $this->setSeasonalStorageBoxProductDateModified(new \DateTime(date('Y-m-d H:i:s')));

        if($this->getSeasonalStorageBoxProductDateCreated() == null)
        {
            $this->setSeasonalStorageBoxProductDateCreated(new \DateTime(date('Y-m-d H:i:s')));
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
     * Set seasonal_storage_box_product_qty
     *
     * @param integer $seasonalStorageBoxProductQty
     * @return SeasonalStorageBoxProduct
     */
    public function setSeasonalStorageBoxProductQty($seasonalStorageBoxProductQty)
    {
        $this->seasonal_storage_box_product_qty = $seasonalStorageBoxProductQty;
    
        return $this;
    }

    /**
     * Get seasonal_storage_box_product_qty
     *
     * @return integer 
     */
    public function getSeasonalStorageBoxProductQty()
    {
        return $this->seasonal_storage_box_product_qty;
    }

    /**
     * Set seasonal_storage_box_product_date_created
     *
     * @param \DateTime $seasonalStorageBoxProductDateCreated
     * @return SeasonalStorageBoxProduct
     */
    public function setSeasonalStorageBoxProductDateCreated($seasonalStorageBoxProductDateCreated)
    {
        $this->seasonal_storage_box_product_date_created = $seasonalStorageBoxProductDateCreated;
    
        return $this;
    }

    /**
     * Get seasonal_storage_box_product_date_created
     *
     * @return \DateTime 
     */
    public function getSeasonalStorageBoxProductDateCreated()
    {
        return $this->seasonal_storage_box_product_date_created;
    }

    /**
     * Set seasonal_storage_box_product_date_modified
     *
     * @param \DateTime $seasonalStorageBoxProductDateModified
     * @return SeasonalStorageBoxProduct
     */
    public function setSeasonalStorageBoxProductDateModified($seasonalStorageBoxProductDateModified)
    {
        $this->seasonal_storage_box_product_date_modified = $seasonalStorageBoxProductDateModified;
    
        return $this;
    }

    /**
     * Get seasonal_storage_box_product_date_modified
     *
     * @return \DateTime 
     */
    public function getSeasonalStorageBoxProductDateModified()
    {
        return $this->seasonal_storage_box_product_date_modified;
    }

    /**
     * Set product
     *
     * @param \MilesApart\AdminBundle\Entity\Product $product
     * @return SeasonalStorageBoxProduct
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

    /**
     * Set seasonal_storage_box
     *
     * @param \MilesApart\AdminBundle\Entity\SeasonalStorageBox $seasonalStorageBox
     * @return SeasonalStorageBoxProduct
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