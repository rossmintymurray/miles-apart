<?php
// src/MilesApart/AdminBundle/Entity/FosUser.php -- Defines the customer type object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use EWZ\Bundle\RecaptchaBundle\Validator\Constraints as Recaptcha;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\FosUserRepository")
 * @ORM\Table(name="fos_user")
 * @ORM\HasLifecycleCallbacks()
 */

class FosUser extends BaseUser
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
     * @ORM\OneToOne(targetEntity="PersonalCustomer", inversedBy="fos_user", cascade={"persist"})
     * @ORM\JoinTable(name="personal_customer")
     * @ORM\JoinColumn(name="personal_customer_id", referencedColumnName="id")
     */
    protected $personal_customer;

     /**
     * @ORM\OneToOne(targetEntity="BusinessCustomerRepresentative", inversedBy="fos_user", cascade={"persist"})
     * @ORM\JoinTable(name="business_customer_representative")
     * @ORM\JoinColumn(name="business_customer_representative_id", referencedColumnName="id")
     */
    protected $business_customer_representative;
    
    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {

       
    }



    public function setEmail($email)
    {
        $email = is_null($email) ? '' : $email;
        parent::setEmail($email);
        $this->setUsername($email);
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
     * Set personal_customer
     *
     * @param \MilesApart\AdminBundle\Entity\PersonalCustomer $personalCustomer
     * @return FosUser
     */
    public function setPersonalCustomer(\MilesApart\AdminBundle\Entity\PersonalCustomer $personalCustomer = null)
    {
        $this->personal_customer = $personalCustomer;

        return $this;
    }

    /**
     * Get personal_customer
     *
     * @return \MilesApart\AdminBundle\Entity\PersonalCustomer 
     */
    public function getPersonalCustomer()
    {
        return $this->personal_customer;
    }

    /**
     * Set business_customer_representative
     *
     * @param \MilesApart\AdminBundle\Entity\BusinessCustomerRepresentative $businessCustomerRepresentative
     * @return FosUser
     */
    public function setBusinessCustomerRepresentative(\MilesApart\AdminBundle\Entity\BusinessCustomerRepresentative $businessCustomerRepresentative = null)
    {
        $this->business_customer_representative = $businessCustomerRepresentative;

        return $this;
    }

    /**
     * Get business_customer_representative
     *
     * @return \MilesApart\AdminBundle\Entity\BusinessCustomerRepresentative 
     */
    public function getBusinessCustomerRepresentative()
    {
        return $this->business_customer_representative;
    }

    /**
    *
    * Get full name
    *
    */
    public function getFullName()
    {
        if($this->getPersonalCustomer() != null) {
            $fullname = $this->getPersonalCustomer()->getPersonalCustomerFirstName() ." ". $this->getPersonalCustomer()->getPersonalCustomerSurname();
        } else {
            $fullname = $this->getBusinessCustomerRepresentative()->getBusinessCustomerRepresentativeFirstName() ." ". $this->getBusinessCustomerRepresentative()->getBusinessCustomerRepresentativeSurname();
        }

        return $fullname;
    }

    /**
    *
    * Get first name
    *
    */
    public function getFirstName()
    {
        if($this->getPersonalCustomer() != null) {
            $first_name = $this->getPersonalCustomer()->getPersonalCustomerFirstName();
        } else {
            $first_name = $this->getBusinessCustomerRepresentative()->getBusinessCustomerRepresentativeFirstName();
        }

        return $first_name;
    }


    /**
    *
    * Get customer
    *
    */
    public function getCustomer()
    {
        if($this->getPersonalCustomer() != null) {
            $customer = $this->getPersonalCustomer()->getCustomer();
        } else {
            $customer = $this->getBusinessCustomerRepresentative()->getBusinessCustomer()->getCustomer();
        }

        return $customer;
    }
}
