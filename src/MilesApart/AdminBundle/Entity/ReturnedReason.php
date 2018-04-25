<?php
// src/MilesApart/AdminBundle/Entity/ReturnedReason.php -- Defines the customer type object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\ReturnedReasonRepository")
 * @ORM\Table(name="returned_reason")
 * @ORM\HasLifecycleCallbacks()
 */

class ReturnedReason
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
    protected $returned_reason;

    /**
     * @ORM\Column(type="string", length=500, unique=false, nullable=false)
     */
    protected $returned_reason_description;

    /**
     * @ORM\OneToMany(targetEntity="ReturnedProduct", mappedBy="returned_reason")
     */
    protected $returned_product;

   

    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //Returned reason
        $metadata->addPropertyConstraint('returned_reason', new Assert\NotBlank());
        $metadata->addPropertyConstraint('returned_reason', new Assert\Length(array(
            'min'        => 4,
            'max'        => 100,
            'minMessage' => 'The returned reason must be at least {{ limit }} characters length',
            'maxMessage' => 'The returned reason cannot be longer than {{ limit }} characters length',
        )));

        //Returned reason description
        $metadata->addPropertyConstraint('returned_reason_description', new Assert\NotBlank());
        $metadata->addPropertyConstraint('returned_reason_description', new Assert\Length(array(
            'min'        => 4,
            'max'        => 500,
            'minMessage' => 'The returned reason description must be at least {{ limit }} characters length',
            'maxMessage' => 'The returned reason description cannot be longer than {{ limit }} characters length',
        )));
    }




    /**
     * Constructor
     */
    public function __construct()
    {
        $this->returned_product = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set returned_reason
     *
     * @param string $returnedReason
     * @return ReturnedReason
     */
    public function setReturnedReason($returnedReason)
    {
        $this->returned_reason = $returnedReason;
    
        return $this;
    }

    /**
     * Get returned_reason
     *
     * @return string 
     */
    public function getReturnedReason()
    {
        return $this->returned_reason;
    }

    /**
     * Set returned_reason_description
     *
     * @param string $returnedReasonDescription
     * @return ReturnedReason
     */
    public function setReturnedReasonDescription($returnedReasonDescription)
    {
        $this->returned_reason_description = $returnedReasonDescription;
    
        return $this;
    }

    /**
     * Get returned_reason_description
     *
     * @return string 
     */
    public function getReturnedReasonDescription()
    {
        return $this->returned_reason_description;
    }

    /**
     * Add returned_product
     *
     * @param \MilesApart\AdminBundle\Entity\ReturnedProduct $returnedProduct
     * @return ReturnedReason
     */
    public function addReturnedProduct(\MilesApart\AdminBundle\Entity\ReturnedProduct $returnedProduct)
    {
        $this->returned_product[] = $returnedProduct;
    
        return $this;
    }

    /**
     * Remove returned_product
     *
     * @param \MilesApart\AdminBundle\Entity\ReturnedProduct $returnedProduct
     */
    public function removeReturnedProduct(\MilesApart\AdminBundle\Entity\ReturnedProduct $returnedProduct)
    {
        $this->returned_product->removeElement($returnedProduct);
    }

    /**
     * Get returned_product
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getReturnedProduct()
    {
        return $this->returned_product;
    }
}