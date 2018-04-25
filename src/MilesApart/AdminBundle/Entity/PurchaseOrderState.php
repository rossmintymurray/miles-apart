<?php
// src/MilesApart/AdminBundle/Entity/PurchaseOrderState.php -- Defines the customer type object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\PurchaseOrderStateRepository")
 * @ORM\Table(name="purchase_order_state")
 * @ORM\HasLifecycleCallbacks()
 */

class PurchaseOrderState
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
     * @ORM\Column(type="string", length=100, unique=true, nullable=false)
     */
    protected $purchase_order_state;

    /**
     * @ORM\OneToMany(targetEntity="PurchaseOrder", mappedBy="purchase_order_state")
     */
    protected $purchase_order;
    


    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //Admin User Username
        $metadata->addPropertyConstraint('purchase_order_state', new Assert\NotBlank());
        $metadata->addPropertyConstraint('purchase_order_state', new Assert\Length(array(
            'min'        => 2,
            'max'        => 100,
            'minMessage' => 'The purchase order state must be at least {{ limit }} characters length',
            'maxMessage' => 'The purchase order state cannot be longer than {{ limit }} characters length',
        )));
    }


   
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->purchase_order = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set purchase_order_state
     *
     * @param string $purchaseOrderState
     * @return PurchaseOrderState
     */
    public function setPurchaseOrderState($purchaseOrderState)
    {
        $this->purchase_order_state = $purchaseOrderState;
    
        return $this;
    }

    /**
     * Get purchase_order_state
     *
     * @return string 
     */
    public function getPurchaseOrderState()
    {
        return $this->purchase_order_state;
    }

    /**
     * Add purchase_order
     *
     * @param \MilesApart\AdminBundle\Entity\PurchaseOrder $purchaseOrder
     * @return PurchaseOrderState
     */
    public function addPurchaseOrder(\MilesApart\AdminBundle\Entity\PurchaseOrder $purchaseOrder)
    {
        $this->purchase_order[] = $purchaseOrder;
    
        return $this;
    }

    /**
     * Remove purchase_order
     *
     * @param \MilesApart\AdminBundle\Entity\PurchaseOrder $purchaseOrder
     */
    public function removePurchaseOrder(\MilesApart\AdminBundle\Entity\PurchaseOrder $purchaseOrder)
    {
        $this->purchase_order->removeElement($purchaseOrder);
    }

    /**
     * Get purchase_order
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPurchaseOrder()
    {
        return $this->purchase_order;
    }
}