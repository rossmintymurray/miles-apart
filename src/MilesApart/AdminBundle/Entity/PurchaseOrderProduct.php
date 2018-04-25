<?php
// src/MilesApart/AdminBundle/Entity/PurchaseOrderProduct.php -- Defines the customer type object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\PurchaseOrderProductRepository")
 * @ORM\Table(name="purchase_order_product")
 * @ORM\HasLifecycleCallbacks()
 */

class PurchaseOrderProduct
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
     * @ORM\ManyToOne(targetEntity="PurchaseOrder", inversedBy="purchase_order_product")
     * @ORM\JoinTable(name="purchase_order")
     * @ORM\JoinColumn(name="purchase_order_id", referencedColumnName="id")
     */
    protected $purchase_order;

    /**
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="purchase_order_product")
     * @ORM\JoinTable(name="product")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     * @ORM\OrderBy({"product_supplier_code" = "ASC"})
     */
    protected $product;

    /**
     * @ORM\Column(type="integer", unique=false, nullable=false)
     */
    protected $purchase_order_product_quantity;



    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //Purchase order
        $metadata->addPropertyConstraint('purchase_order', new Assert\Choice(array(
            'callback' => 'getPurchaseOrder',
        )));

        //Product
        $metadata->addPropertyConstraint('product', new Assert\Choice(array(
            'callback' => 'getProduct',
        )));

        //Purchase order product quantity
        $metadata->addPropertyConstraint('purchase_order_product_quantity', new Assert\NotBlank());
        $metadata->addPropertyConstraint('purchase_order_product_quantity', new Assert\Range(array(
            'min'        => 1,
            'max'        => 2000,
            'minMessage' => 'The purchase order qty must be at least {{ limit }} characters length',
            'maxMessage' => 'The purchase order qty cannot be longer than {{ limit }} characters length',
        )));
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
     * Set purchase_order_product_quantity
     *
     * @param integer $purchaseOrderProductQuantity
     * @return PurchaseOrderProduct
     */
    public function setPurchaseOrderProductQuantity($purchaseOrderProductQuantity)
    {
        $this->purchase_order_product_quantity = $purchaseOrderProductQuantity;
    
        return $this;
    }

    /**
     * Get purchase_order_product_quantity
     *
     * @return integer 
     */
    public function getPurchaseOrderProductQuantity()
    {
        return $this->purchase_order_product_quantity;
    }

    /**
     * Set purchase_order
     *
     * @param \MilesApart\AdminBundle\Entity\PurchaseOrder $purchaseOrder
     * @return PurchaseOrderProduct
     */
    public function setPurchaseOrder(\MilesApart\AdminBundle\Entity\PurchaseOrder $purchaseOrder = null)
    {
        $this->purchase_order = $purchaseOrder;
    
        return $this;
    }

    /**
     * Get purchase_order
     *
     * @return \MilesApart\AdminBundle\Entity\PurchaseOrder 
     */
    public function getPurchaseOrder()
    {
        return $this->purchase_order;
    }

    /**
     * Set product
     *
     * @param \MilesApart\AdminBundle\Entity\Product $product
     * @return PurchaseOrderProduct
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
     * Get purchase_order_product_outers
     *
     * @return integer 
     */
    public function getPurchaseOrderProductOuters()
    {
        if ($this->getProduct()->getProductOuterQuantity() != NULL) {

            $number_of_cases = $this->getPurchaseOrderProductQuantity() / $this->getProduct()->getProductOuterQuantity();

            if ($number_of_cases < 1) {
                $number_of_cases = "-";
            }
        } else {
            $number_of_cases = "-";
        }

        return $number_of_cases;
        
    }

    /**
     * Get purchase_order_product_inners
     *
     * @return integer 
     */
    public function getPurchaseOrderProductInners()
    {
        if ($this->getProduct()->getProductInnerQuantity() != NULL) {

            $number_of_inners = $this->getPurchaseOrderProductQuantity() / $this->getProduct()->getProductInnerQuantity();

        } else {
            $number_of_inners = "-";
        }

        return $number_of_inners;
        
    }

    /**
     * Get purchase_order_product_total
     *
     * @return float 
     */
    public function getPurchaseOrderProductCostTotal()
    {
        
        $purchase_order_product_total = $this->getProduct()->getCurrentCostDecimal() * $this->getPurchaseOrderProductQuantity();
            

        return number_format($purchase_order_product_total, 2);

    }

    /**
     * Get purchase_order_product_total_display
     *
     *  
     */
    public function getPurchaseOrderProductCostTotalDisplay()
    {
        $current_total = $this->getPurchaseOrderProductCostTotal();

        if ($current_total < 1 && $current_total > 0) {
            $current_total = $current_total * 100;
            $current_total = $current_total . "p";
        } else if ($current_total >= 1) {
            $current_total = "£" . $current_total;
        } else {
            $current_total = "N/A";
        }
        return $current_total;
    
    }


}