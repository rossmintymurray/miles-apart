<?php
// src/MilesApart/AdminBundle/Entity/TransferRequest.php -- Defines the customer type object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\TransferRequestRepository")
 * @ORM\Table(name="transfer_request")
 * @ORM\HasLifecycleCallbacks()
 */

class TransferRequest
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
     * @ORM\ManyToOne(targetEntity="BusinessPremises", inversedBy="transfer_request")
     * @ORM\JoinTable(name="BusinessPremises")
     * @ORM\JoinColumn(name="business_premises_id", referencedColumnName="id")
     */
    protected $business_premises;

    /**
     * @ORM\ManyToOne(targetEntity="TransferRequestState", inversedBy="transfer_request")
     * @ORM\JoinTable(name="transfer_request_state")
     * @ORM\JoinColumn(name="transfer_request_state_id", referencedColumnName="id")
     */
    protected $transfer_request_state;

    /**
     * @ORM\Column(type="datetime", unique=false, nullable=false)
     */
    protected $transfer_request_date_created;

    /**
     * @ORM\Column(type="datetime", unique=false, nullable=true)
     */
    protected $transfer_request_date_modified;

    /**
     * @ORM\Column(type="datetime", unique=false, nullable=true)
     */
    protected $transfer_request_date_completed;

    /**
     * @ORM\OneToMany(targetEntity="ProductTransferRequest", mappedBy="transfer_request", cascade={"persist"})
     */
    protected $product_transfer_request;

    /**
     * @ORM\OneToMany(targetEntity="ProductGroupTransferRequest", mappedBy="transfer_request", cascade={"persist"})
     */
    protected $product_group_transfer_request;

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        $this->setTransferRequestDateModified(new \DateTime(date('Y-m-d H:i:s')));

        if($this->getTransferRequestDateCreated() == null)
        {
            $this->setTransferRequestDateCreated(new \DateTime(date('Y-m-d H:i:s')));
        }
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->transfer_request_state = 1;
        $this->product_transfer_request = new \Doctrine\Common\Collections\ArrayCollection();
    }

    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {

        //Transfer request date completed
        $metadata->addPropertyConstraint('transfer_request_date_completed', new Assert\Date());
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
     * Set transfer_request_date_created
     *
     * @param \DateTime $transferRequestDateCreated
     * @return TransferRequest
     */
    public function setTransferRequestDateCreated($transferRequestDateCreated)
    {
        $this->transfer_request_date_created = $transferRequestDateCreated;
    
        return $this;
    }

    /**
     * Get transfer_request_date_created
     *
     * @return \DateTime 
     */
    public function getTransferRequestDateCreated()
    {
        return $this->transfer_request_date_created;
    }

    /**
     * Set transfer_request_date_modified
     *
     * @param \DateTime $transferRequestDateModified
     * @return TransferRequest
     */
    public function setTransferRequestDateModified($transferRequestDateModified)
    {
        $this->transfer_request_date_modified = $transferRequestDateModified;
    
        return $this;
    }

    /**
     * Get transfer_request_date_modified
     *
     * @return \DateTime 
     */
    public function getTransferRequestDateModified()
    {
        return $this->transfer_request_date_modified;
    }

    /**
     * Set transfer_request_date_completed
     *
     * @param \DateTime $transferRequestDateCompleted
     * @return TransferRequest
     */
    public function setTransferRequestDateCompleted($transferRequestDateCompleted)
    {
        $this->transfer_request_date_completed = $transferRequestDateCompleted;
    
        return $this;
    }

    /**
     * Get transfer_request_date_completed
     *
     * @return \DateTime 
     */
    public function getTransferRequestDateCompleted()
    {
        return $this->transfer_request_date_completed;
    }

    /**
     * Set business_premises
     *
     * @param \MilesApart\AdminBundle\Entity\BusinessPremises $businessPremises
     * @return TransferRequest
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
     * Set transfer_request_state
     *
     * @param \MilesApart\AdminBundle\Entity\TransferRequestState $transferRequestState
     * @return TransferRequest
     */
    public function setTransferRequestState(\MilesApart\AdminBundle\Entity\TransferRequestState $transferRequestState = null)
    {
        $this->transfer_request_state = $transferRequestState;
    
        return $this;
    }

    /**
     * Get transfer_request_state
     *
     * @return \MilesApart\AdminBundle\Entity\TransferRequestState 
     */
    public function getTransferRequestState()
    {
        return $this->transfer_request_state;
    }
   
    
    /**
     * Add product_transfer_request
     *
     * @param \MilesApart\AdminBundle\Entity\ProductTransferRequest $productTransferRequest
     * @return TransferRequest
     */
    public function addProductTransferRequest(\MilesApart\AdminBundle\Entity\ProductTransferRequest $productTransferRequest)
    {
        $this->product_transfer_request[] = $productTransferRequest;
    
        return $this;
    }

    /**
     * Remove product_transfer_request
     *
     * @param \MilesApart\AdminBundle\Entity\ProductTransferRequest $productTransferRequest
     */
    public function removeProductTransferRequest(\MilesApart\AdminBundle\Entity\ProductTransferRequest $productTransferRequest)
    {
        $this->product_transfer_request->removeElement($productTransferRequest);
    }

    /**
     * Get product_transfer_request
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProductTransferRequest()
    {
        return $this->product_transfer_request;
    }

    /**
     * Add product_group_transfer_request
     *
     * @param \MilesApart\AdminBundle\Entity\ProductTransferRequest $productGroupTransferRequest
     * @return TransferRequest
     */
    public function addProductGroupTransferRequest(\MilesApart\AdminBundle\Entity\ProductGroupTransferRequest $productGroupTransferRequest)
    {
        $this->product_group_transfer_request[] = $productGroupTransferRequest;
    
        return $this;
    }

    /**
     * Remove product_group_transfer_request
     *
     * @param \MilesApart\AdminBundle\Entity\ProductGroupTransferRequest $productGroupTransferRequest
     */
    public function removeProductGroupTransferRequest(\MilesApart\AdminBundle\Entity\ProductGroupTransferRequest $productGroupTransferRequest)
    {
        $this->product_group_transfer_request->removeElement($productGroupTransferRequest);
    }

    /**
     * Get product_group_transfer_request
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProductGroupTransferRequest()
    {
        return $this->product_group_transfer_request;
    }

    /**
     * Get total_products
     *
     * @return string 
     */
    public function getTotalProducts()
    {

        return count($this->getProductTransferRequest());
    }
}