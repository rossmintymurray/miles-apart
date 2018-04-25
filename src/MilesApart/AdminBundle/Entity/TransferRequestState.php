<?php
// src/MilesApart/AdminBundle/Entity/TransferRequestState.php -- Defines the customer type object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\TransferRequestStateRepository")
 * @ORM\Table(name="transfer_request_state")
 * @ORM\HasLifecycleCallbacks()
 */

class TransferRequestState
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
     * @ORM\Column(type="string", length=50, unique=false, nullable=false)
     */
    protected $transfer_request_state_name;

    /**
     * @ORM\OneToMany(targetEntity="TransferRequest", mappedBy="transfer_request_state")
     */
    protected $transfer_request;



    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //Admin User Username
        $metadata->addPropertyConstraint('transfer_request_state_name', new Assert\NotBlank());
        $metadata->addPropertyConstraint('transfer_request_state_name', new Assert\Length(array(
            'min'        => 2,
            'max'        => 50,
            'minMessage' => 'The state name must be at least {{ limit }} characters length',
            'maxMessage' => 'The state name cannot be longer than {{ limit }} characters length',
        )));
    }



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->transfer_request = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set transfer_request_state_name
     *
     * @param string $transferRequestStateName
     * @return TransferRequestState
     */
    public function setTransferRequestStateName($transferRequestStateName)
    {
        $this->transfer_request_state_name = $transferRequestStateName;
    
        return $this;
    }

    /**
     * Get transfer_request_state_name
     *
     * @return string 
     */
    public function getTransferRequestStateName()
    {
        return $this->transfer_request_state_name;
    }

    /**
     * Add transfer_request
     *
     * @param \MilesApart\AdminBundle\Entity\TransferRequest $transferRequest
     * @return TransferRequestState
     */
    public function addTransferRequest(\MilesApart\AdminBundle\Entity\TransferRequest $transferRequest)
    {
        $this->transfer_request[] = $transferRequest;
    
        return $this;
    }

    /**
     * Remove transfer_request
     *
     * @param \MilesApart\AdminBundle\Entity\TransferRequest $transferRequest
     */
    public function removeTransferRequest(\MilesApart\AdminBundle\Entity\TransferRequest $transferRequest)
    {
        $this->transfer_request->removeElement($transferRequest);
    }

    /**
     * Get transfer_request
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTransferRequest()
    {
        return $this->transfer_request;
    }
}