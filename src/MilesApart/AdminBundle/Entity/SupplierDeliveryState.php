<?php
// src/MilesApart/AdminBundle/Entity/SupplierDeliveryState.php -- Defines the customer type object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\PurchaseOrderStateRepository")
 * @ORM\Table(name="supplier_delivery_state")
 * @ORM\HasLifecycleCallbacks()
 */

class SupplierDeliveryState
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
    protected $supplier_delivery_state;

    /**
     * @ORM\OneToMany(targetEntity="SupplierDelivery", mappedBy="supplier_delivery_state")
     */
    protected $supplier_delivery;
    


    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //Admin User Username
        $metadata->addPropertyConstraint('supplier_delivery_state', new Assert\NotBlank());
        $metadata->addPropertyConstraint('supplier_delivery_state', new Assert\Length(array(
            'min'        => 2,
            'max'        => 100,
            'minMessage' => 'The supplier delivery state must be at least {{ limit }} characters length',
            'maxMessage' => 'The supplier delivery state cannot be longer than {{ limit }} characters length',
        )));
    }


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->supplier_delivery = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set supplier_delivery_state
     *
     * @param string $supplierDeliveryState
     * @return SupplierDeliveryState
     */
    public function setSupplierDeliveryState($supplierDeliveryState)
    {
        $this->supplier_delivery_state = $supplierDeliveryState;

        return $this;
    }

    /**
     * Get supplier_delivery_state
     *
     * @return string 
     */
    public function getSupplierDeliveryState()
    {
        return $this->supplier_delivery_state;
    }

    /**
     * Add supplier_delivery
     *
     * @param \MilesApart\AdminBundle\Entity\SupplierDelivery $supplierDelivery
     * @return SupplierDeliveryState
     */
    public function addSupplierDelivery(\MilesApart\AdminBundle\Entity\SupplierDelivery $supplierDelivery)
    {
        $this->supplier_delivery[] = $supplierDelivery;

        return $this;
    }

    /**
     * Remove supplier_delivery
     *
     * @param \MilesApart\AdminBundle\Entity\SupplierDelivery $supplierDelivery
     */
    public function removeSupplierDelivery(\MilesApart\AdminBundle\Entity\SupplierDelivery $supplierDelivery)
    {
        $this->supplier_delivery->removeElement($supplierDelivery);
    }

    /**
     * Get supplier_delivery
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSupplierDelivery()
    {
        return $this->supplier_delivery;
    }
}
