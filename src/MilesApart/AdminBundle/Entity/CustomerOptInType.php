<?php
// src/MilesApart/AdminBundle/Entity/CustomerOptInType.php -- Defines the customer type object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\CustomerOptInTypeReasonRepository")
 * @ORM\Table(name="customer_opt_in_type")
 * @ORM\HasLifecycleCallbacks()
 */

class CustomerOptInType
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
    protected $customer_opt_in_type_text;

    /**
     * @ORM\OneToMany(targetEntity="CustomerOptIn", mappedBy="customer_opt_in_type")
     */
    protected $customer_opt_in;



    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //Customer opt in type
        $metadata->addPropertyConstraint('customer_opt_in_type_text', new Assert\NotBlank());
        $metadata->addPropertyConstraint('customer_opt_in_type_text', new Assert\Length(array(
            'min'        => 2,
            'max'        => 100,
            'minMessage' => 'Customer opt in type text must be at least {{ limit }} characters length',
            'maxMessage' => 'Customer opt in type text cannot be longer than {{ limit }} characters length',
        )));
    }


    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->customer_opt_in = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set customer_opt_in_type_text
     *
     * @param string $customerOptInTypeText
     * @return CustomerOptInType
     */
    public function setCustomerOptInTypeText($customerOptInTypeText)
    {
        $this->customer_opt_in_type_text = $customerOptInTypeText;
    
        return $this;
    }

    /**
     * Get customer_opt_in_type_text
     *
     * @return string 
     */
    public function getCustomerOptInTypeText()
    {
        return $this->customer_opt_in_type_text;
    }

    /**
     * Add customer_opt_in
     *
     * @param \MilesApart\AdminBundle\Entity\CustomerOptIn $customerOptIn
     * @return CustomerOptInType
     */
    public function addCustomerOptIn(\MilesApart\AdminBundle\Entity\CustomerOptIn $customerOptIn)
    {
        $this->customer_opt_in[] = $customerOptIn;
    
        return $this;
    }

    /**
     * Remove customer_opt_in
     *
     * @param \MilesApart\AdminBundle\Entity\CustomerOptIn $customerOptIn
     */
    public function removeCustomerOptIn(\MilesApart\AdminBundle\Entity\CustomerOptIn $customerOptIn)
    {
        $this->customer_opt_in->removeElement($customerOptIn);
    }

    /**
     * Get customer_opt_in
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCustomerOptIn()
    {
        return $this->customer_opt_in;
    }
}