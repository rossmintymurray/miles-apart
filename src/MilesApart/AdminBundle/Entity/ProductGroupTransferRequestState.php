<?php
// src/MilesApart/AdminBundle/Entity/ProductGroupTransferRequestState.php -- Defines the customer type object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\ProductGroupTransferRequestStateRepository")
 * @ORM\Table(name="product_group_transfer_request_state")
 * @ORM\HasLifecycleCallbacks()
 */

class ProductGroupTransferRequestState
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
    protected $product_group_transfer_request_state_name;

    /**
     * @ORM\OneToMany(targetEntity="ProductGroupTransferRequest", mappedBy="product_group_transfer_request_state")
     */
    protected $product_group_transfer_request;



    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //Transfer request state name
        $metadata->addPropertyConstraint('product_group_transfer_request_state_name', new Assert\NotBlank());
        $metadata->addPropertyConstraint('product_group_transfer_request_state_name', new Assert\Length(array(
            'min'        => 4,
            'max'        => 50,
            'minMessage' => 'The product group transfer request state name must be at least {{ limit }} characters length',
            'maxMessage' => 'The product group transfer request state name cannot be longer than {{ limit }} characters length',
        )));
    }



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->product_group_transfer_request = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set product_group_transfer_request_state_name
     *
     * @param string $productGroupTransferRequestStateName
     * @return ProductGroupTransferRequestState
     */
    public function setProductGroupTransferRequestStateName($productGroupTransferRequestStateName)
    {
        $this->product_group_transfer_request_state_name = $productGroupTransferRequestStateName;

        return $this;
    }

    /**
     * Get product_group_transfer_request_state_name
     *
     * @return string 
     */
    public function getProductGroupTransferRequestStateName()
    {
        return $this->product_group_transfer_request_state_name;
    }

    /**
     * Add product_group_transfer_request
     *
     * @param \MilesApart\AdminBundle\Entity\ProductGroupTransferRequest $productGroupTransferRequest
     * @return ProductGroupTransferRequestState
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
}
