<?php
// src/MilesApart/BasketBundle/Entity/RemovedBasketProduct.php -- Defines the customer type object

namespace MilesApart\BasketBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\BasketBundle\Entity\Repository\RemovedBasketProductRepository")
 * @ORM\Table(name="removed_basket_product")
 * @ORM\HasLifecycleCallbacks()
 */

class RemovedBasketProduct
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
     * @ORM\ManyToOne(targetEntity="BasketProduct", inversedBy="removed_basket_product")
     * @ORM\JoinTable(name="basket_product")
     * @ORM\JoinColumn(name="basket_product_id", referencedColumnName="id")
     */
    protected $basket_product;



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
     * Set basket_product
     *
     * @param \MilesApart\BasketBundle\Entity\BasketProduct $basketProduct
     * @return RemovedBasketProduct
     */
    public function setBasketProduct(\MilesApart\BasketBundle\Entity\BasketProduct $basketProduct = null)
    {
        $this->basket_product = $basketProduct;
    
        return $this;
    }

    /**
     * Get basket_product
     *
     * @return \MilesApart\BasketBundle\Entity\BasketProduct 
     */
    public function getBasketProduct()
    {
        return $this->basket_product;
    }
}