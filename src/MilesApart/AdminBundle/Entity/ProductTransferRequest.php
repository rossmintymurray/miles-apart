<?php
// src/MilesApart/AdminBundle/Entity/ProductTransferRequest.php -- Defines the customer type object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\ProductTransferRequestRepository")
 * @ORM\Table(name="product_transfer_request")
 * @ORM\HasLifecycleCallbacks()
 */

class ProductTransferRequest
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
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="product_transfer_request")
     * @ORM\JoinTable(name="product")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    protected $product;

    /**
     * @ORM\ManyToOne(targetEntity="TransferRequest", inversedBy="product_transfer_request", cascade={"persist"})
     * @ORM\JoinTable(name="transfer_request")
     * @ORM\JoinColumn(name="transfer_request_id", referencedColumnName="id")
     */
    protected $transfer_request;

    /**
     * @ORM\ManyToOne(targetEntity="ProductTransferRequestState", inversedBy="product_transfer_request")
     * @ORM\JoinTable(name="product_transfer_request_state")
     * @ORM\JoinColumn(name="product_transfer_request_state_id", referencedColumnName="id")
     */
    protected $product_transfer_request_state;

    /**
     * @ORM\Column(type="integer", unique=false, nullable=false)
     */
    protected $product_transfer_request_qty;

    /**
     * @ORM\Column(type="datetime", unique=false, nullable=true)
     */
    protected $product_transfer_request_date_created;

    /**
     * @ORM\Column(type="datetime", unique=false, nullable=false)
     */
    protected $product_transfer_request_date_modified;



    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        $this->setProductTransferRequestDateModified(new \DateTime(date('Y-m-d H:i:s')));

        if($this->getProductTransferRequestDateCreated() == null)
        {
            $this->setProductTransferRequestDateCreated(new \DateTime(date('Y-m-d H:i:s')));
        }
    }



    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //Product
        $metadata->addPropertyConstraint('product', new Assert\Choice(array(
            'callback' => 'getProduct',
        )));

        //Transfer request
        $metadata->addPropertyConstraint('transfer_request', new Assert\Choice(array(
            'callback' => 'getTransferRequest',
        )));

        //Product transfer request state
        $metadata->addPropertyConstraint('product_transfer_request_state', new Assert\Choice(array(
            'callback' => 'getProductTransferRequestState',
        )));

        //Product transfer request qty
        $metadata->addPropertyConstraint('product_transfer_request_qty', new Assert\NotBlank());
        $metadata->addPropertyConstraint('product_transfer_request_qty', new Assert\Range(array(
            'min'        => 1,
            'max'        => 100,
            'minMessage' => 'Your password must be at least {{ limit }} characters length',
            'maxMessage' => 'Your password cannot be longer than {{ limit }} characters length',
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
     * Set product_transfer_request_qty
     *
     * @param integer $productTransferRequestQty
     * @return ProductTransferRequest
     */
    public function setProductTransferRequestQty($productTransferRequestQty)
    {
        $this->product_transfer_request_qty = $productTransferRequestQty;
    
        return $this;
    }

    /**
     * Get product_transfer_request_qty
     *
     * @return integer 
     */
    public function getProductTransferRequestQty()
    {
        return $this->product_transfer_request_qty;
    }

    /**
     * Set product_transfer_request_date_created
     *
     * @param \DateTime $productTransferRequestDateCreated
     * @return ProductTransferRequest
     */
    public function setProductTransferRequestDateCreated($productTransferRequestDateCreated)
    {
        $this->product_transfer_request_date_created = $productTransferRequestDateCreated;
    
        return $this;
    }

    /**
     * Get product_transfer_request_date_created
     *
     * @return \DateTime 
     */
    public function getProductTransferRequestDateCreated()
    {
        return $this->product_transfer_request_date_created;
    }

    /**
     * Set product_transfer_request_date_modified
     *
     * @param \DateTime $productTransferRequestDateModified
     * @return ProductTransferRequest
     */
    public function setProductTransferRequestDateModified($productTransferRequestDateModified)
    {
        $this->product_transfer_request_date_modified = $productTransferRequestDateModified;
    
        return $this;
    }

    /**
     * Get product_transfer_request_date_modified
     *
     * @return \DateTime 
     */
    public function getProductTransferRequestDateModified()
    {
        return $this->product_transfer_request_date_modified;
    }

    /**
     * Set product
     *
     * @param \MilesApart\AdminBundle\Entity\Product $product
     * @return ProductTransferRequest
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
     * Set transfer_request
     *
     * @param \MilesApart\AdminBundle\Entity\TransferRequest $transferRequest
     * @return ProductTransferRequest
     */
    public function setTransferRequest(\MilesApart\AdminBundle\Entity\TransferRequest $transferRequest = null)
    {
        $this->transfer_request = $transferRequest;
    
        return $this;
    }

    /**
     * Get transfer_request
     *
     * @return \MilesApart\AdminBundle\Entity\TransferRequest 
     */
    public function getTransferRequest()
    {
        return $this->transfer_request;
    }

    /**
     * Set product_transfer_request_state
     *
     * @param \MilesApart\AdminBundle\Entity\ProductTransferRequestState $productTransferRequestState
     * @return ProductTransferRequest
     */
    public function setProductTransferRequestState(\MilesApart\AdminBundle\Entity\ProductTransferRequestState $productTransferRequestState = null)
    {
        $this->product_transfer_request_state = $productTransferRequestState;
    
        return $this;
    }

    /**
     * Get product_transfer_request_state
     *
     * @return \MilesApart\AdminBundle\Entity\ProductTransferRequestState 
     */
    public function getProductTransferRequestState()
    {
        return $this->product_transfer_request_state;
    }
}