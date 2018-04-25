<?php
// src/MilesApart/AdminBundle/Entity/ProductGroupTransferRequest.php -- Defines the customer type object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\ProductGroupTransferRequestRepository")
 * @ORM\Table(name="product_group_transfer_request")
 * @ORM\HasLifecycleCallbacks()
 */

class ProductGroupTransferRequest
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
     * @ORM\ManyToOne(targetEntity="ProductGroup", inversedBy="product_group_transfer_request")
     * @ORM\JoinTable(name="product_group")
     * @ORM\JoinColumn(name="product_group_id", referencedColumnName="id")
     */
    protected $product_group;

    /**
     * @ORM\ManyToOne(targetEntity="TransferRequest", inversedBy="product_group_transfer_request", cascade={"persist"})
     * @ORM\JoinTable(name="transfer_request")
     * @ORM\JoinColumn(name="transfer_request_id", referencedColumnName="id")
     */
    protected $transfer_request;

    /**
     * @ORM\ManyToOne(targetEntity="ProductGroupTransferRequestState", inversedBy="product_group_transfer_request")
     * @ORM\JoinTable(name="product_group_transfer_request_state")
     * @ORM\JoinColumn(name="product_group_transfer_request_state_id", referencedColumnName="id")
     */
    protected $product_group_transfer_request_state;

    /**
     * @ORM\Column(type="integer", unique=false, nullable=false)
     */
    protected $product_group_transfer_request_qty;

    /**
     * @ORM\Column(type="datetime", unique=false, nullable=true)
     */
    protected $product_group_transfer_request_date_created;

    /**
     * @ORM\Column(type="datetime", unique=false, nullable=false)
     */
    protected $product_group_transfer_request_date_modified;



    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        $this->setProductGroupTransferRequestDateModified(new \DateTime(date('Y-m-d H:i:s')));

        if($this->getProductGroupTransferRequestDateCreated() == null)
        {
            $this->setProductGroupTransferRequestDateCreated(new \DateTime(date('Y-m-d H:i:s')));
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
     * Set product_group_transfer_request_qty
     *
     * @param integer $productGroupTransferRequestQty
     * @return ProductGroupTransferRequest
     */
    public function setProductGroupTransferRequestQty($productGroupTransferRequestQty)
    {
        $this->product_group_transfer_request_qty = $productGroupTransferRequestQty;

        return $this;
    }

    /**
     * Get product_group_transfer_request_qty
     *
     * @return integer 
     */
    public function getProductGroupTransferRequestQty()
    {
        return $this->product_group_transfer_request_qty;
    }

    /**
     * Set product_group_transfer_request_date_created
     *
     * @param \DateTime $productGroupTransferRequestDateCreated
     * @return ProductGroupTransferRequest
     */
    public function setProductGroupTransferRequestDateCreated($productGroupTransferRequestDateCreated)
    {
        $this->product_group_transfer_request_date_created = $productGroupTransferRequestDateCreated;

        return $this;
    }

    /**
     * Get product_group_transfer_request_date_created
     *
     * @return \DateTime 
     */
    public function getProductGroupTransferRequestDateCreated()
    {
        return $this->product_group_transfer_request_date_created;
    }

    /**
     * Set product_group_transfer_request_date_modified
     *
     * @param \DateTime $productGroupTransferRequestDateModified
     * @return ProductGroupTransferRequest
     */
    public function setProductGroupTransferRequestDateModified($productGroupTransferRequestDateModified)
    {
        $this->product_group_transfer_request_date_modified = $productGroupTransferRequestDateModified;

        return $this;
    }

    /**
     * Get product_group_transfer_request_date_modified
     *
     * @return \DateTime 
     */
    public function getProductGroupTransferRequestDateModified()
    {
        return $this->product_group_transfer_request_date_modified;
    }

    /**
     * Set product_group
     *
     * @param \MilesApart\AdminBundle\Entity\ProductGroup $productGroup
     * @return ProductGroupTransferRequest
     */
    public function setProductGroup(\MilesApart\AdminBundle\Entity\ProductGroup $productGroup = null)
    {
        $this->product_group = $productGroup;

        return $this;
    }

    /**
     * Get product_group
     *
     * @return \MilesApart\AdminBundle\Entity\ProductGroup 
     */
    public function getProductGroup()
    {
        return $this->product_group;
    }

    /**
     * Set transfer_request
     *
     * @param \MilesApart\AdminBundle\Entity\TransferRequest $transferRequest
     * @return ProductGroupTransferRequest
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
     * Set product_group_transfer_request_state
     *
     * @param \MilesApart\AdminBundle\Entity\ProductGroupTransferRequestState $productGroupTransferRequestState
     * @return ProductGroupTransferRequest
     */
    public function setProductGroupTransferRequestState(\MilesApart\AdminBundle\Entity\ProductGroupTransferRequestState $productGroupTransferRequestState = null)
    {
        $this->product_group_transfer_request_state = $productGroupTransferRequestState;

        return $this;
    }

    /**
     * Get product_group_transfer_request_state
     *
     * @return \MilesApart\AdminBundle\Entity\ProductGroupTransferRequestState 
     */
    public function getProductGroupTransferRequestState()
    {
        return $this->product_group_transfer_request_state;
    }
}
