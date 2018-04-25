<?php
// src/MilesApart/AdminBundle/Entity/ReturnedProduct.php -- Defines the customer type object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\ReturnedProductRepository")
 * @ORM\Table(name="returned_product_state")
 * @ORM\HasLifecycleCallbacks()
 */

class ReturnedProductState
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
     * @ORM\Column(type="string", length=100, unique=false, nullable=false)
     */
    protected $returned_product_state;
    
   /**
     * @ORM\OneToMany(targetEntity="ReturnedProduct", mappedBy="returned_product_state")
     */
    protected $returned_product;
   

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->returned_product = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set returned_product_state
     *
     * @param string $returnedProductState
     * @return ReturnedProductState
     */
    public function setReturnedProductState($returnedProductState)
    {
        $this->returned_product_state = $returnedProductState;

        return $this;
    }

    /**
     * Get returned_product_state
     *
     * @return string 
     */
    public function getReturnedProductState()
    {
        return $this->returned_product_state;
    }

    /**
     * Add returned_product
     *
     * @param \MilesApart\AdminBundle\Entity\ReturnedProduct $returnedProduct
     * @return ReturnedProductState
     */
    public function addReturnedProduct(\MilesApart\AdminBundle\Entity\ReturnedProduct $returnedProduct)
    {
        $this->returned_product[] = $returnedProduct;

        return $this;
    }

    /**
     * Remove returned_product
     *
     * @param \MilesApart\AdminBundle\Entity\ReturnedProduct $returnedProduct
     */
    public function removeReturnedProduct(\MilesApart\AdminBundle\Entity\ReturnedProduct $returnedProduct)
    {
        $this->returned_product->removeElement($returnedProduct);
    }

    /**
     * Get returned_product
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getReturnedProduct()
    {
        return $this->returned_product;
    }
}
