<?php
// src/MilesApart/AdminBundle/Entity/ProductTransferRequestState.php -- Defines the customer type object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\ProductTransferRequestStateRepository")
 * @ORM\Table(name="product_transfer_request_state")
 * @ORM\HasLifecycleCallbacks()
 */

class ProductTransferRequestState
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
    protected $product_transfer_request_state_name;

    /**
     * @ORM\OneToMany(targetEntity="ProductTransferRequest", mappedBy="product_transfer_request_state")
     */
    protected $product_transfer_request;



    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //Transfer request state name
        $metadata->addPropertyConstraint('product_transfer_request_state_name', new Assert\NotBlank());
        $metadata->addPropertyConstraint('product_transfer_request_state_name', new Assert\Length(array(
            'min'        => 4,
            'max'        => 50,
            'minMessage' => 'The product transfer request state name must be at least {{ limit }} characters length',
            'maxMessage' => 'The product transfer request state name cannot be longer than {{ limit }} characters length',
        )));
    }



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->product_transfer_request = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set product_transfer_request_state_name
     *
     * @param string $productTransferRequestStateName
     * @return ProductTransferRequestState
     */
    public function setProductTransferRequestStateName($productTransferRequestStateName)
    {
        $this->product_transfer_request_state_name = $productTransferRequestStateName;
    
        return $this;
    }

    /**
     * Get product_transfer_request_state_name
     *
     * @return string 
     */
    public function getProductTransferRequestStateName()
    {
        return $this->product_transfer_request_state_name;
    }

    /**
     * Add product_transfer_request
     *
     * @param \MilesApart\AdminBundle\Entity\ProductTransferRequest $productTransferRequest
     * @return ProductTransferRequestState
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
}