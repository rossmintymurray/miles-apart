<?php
// src/MilesApart/AdminBundle/Entity/ProductSupplier.php -- Defines the product question object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\ProductSupplierRepository")
 * @ORM\Table(name="product_supplier")
 * @ORM\HasLifecycleCallbacks()
 */

class ProductSupplier
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
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="product_supplier", cascade={"persist"})
     * @ORM\JoinTable(name="product")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    protected $product;

    /**
     * @ORM\ManyToOne(targetEntity="Supplier", inversedBy="product_supplier", fetch="EAGER")
     * @ORM\JoinTable(name="supplier")
     * @ORM\JoinColumn(name="supplier_id", referencedColumnName="id")
     */
    protected $supplier;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $default_supplier;

    /**
     * @ORM\OneToMany(targetEntity="ProductCost", mappedBy="product_supplier", cascade={"persist"})
     */
    protected $product_cost;


    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        
    }


    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->product_cost = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set default_supplier
     *
     * @param boolean $defaultSupplier
     * @return ProductSupplier
     */
    public function setDefaultSupplier($defaultSupplier)
    {
        $this->default_supplier = $defaultSupplier;
    
        return $this;
    }

    /**
     * Get default_supplier
     *
     * @return boolean 
     */
    public function getDefaultSupplier()
    {
        return $this->default_supplier;
    }

    /**
     * Set product
     *
     * @param \MilesApart\AdminBundle\Entity\Product $product
     * @return ProductSupplier
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
     * Set supplier
     *
     * @param \MilesApart\AdminBundle\Entity\Supplier $supplier
     * @return ProductSupplier
     */
    public function setSupplier(\MilesApart\AdminBundle\Entity\Supplier $supplier = null)
    {
        $this->supplier = $supplier;
    
        return $this;
    }

    /**
     * Get supplier
     *
     * @return \MilesApart\AdminBundle\Entity\Supplier 
     */
    public function getSupplier()
    {
        return $this->supplier;
    }

    /**
     * Add product_cost
     *
     * @param \MilesApart\AdminBundle\Entity\ProductCost $productCost
     * @return ProductSupplier
     */
    public function addProductCost(\MilesApart\AdminBundle\Entity\ProductCost $productCost)
    {
        $this->product_cost[] = $productCost;
    
        return $this;
    }

    /**
     * Remove product_cost
     *
     * @param \MilesApart\AdminBundle\Entity\ProductCost $productCost
     */
    public function removeProductCost(\MilesApart\AdminBundle\Entity\ProductCost $productCost)
    {
        $this->product_cost->removeElement($productCost);
    }

    /**
     * Get product_cost
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProductCost()
    {
        return $this->product_cost;
    }

    /**
     * Set product cost value
     *
     * @param \MilesApart\AdminBundle\Entity\ProductCost $product_cost_value
     * @return ProductCost
     */
    public function setProductCost($product_cost_value)
    {
        $this->product_cost['product_cost_value'] = $product_cost_value;
    
        return $this;
    }


}