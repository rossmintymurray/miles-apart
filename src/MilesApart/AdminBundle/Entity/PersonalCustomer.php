<?php
// src/MilesApart/AdminBundle/Entity/PersonalCustomer.php -- Defines the customer type object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ExecutionContextInterface;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\PersonalCustomerRepository")
 * @ORM\Table(name="personal_customer")
 * @ORM\HasLifecycleCallbacks()
 */

class PersonalCustomer
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
    protected $personal_customer_first_name;

    /**
     * @ORM\Column(type="string", length=100, unique=false, nullable=false)
     */
    protected $personal_customer_surname;

    /**
     * @ORM\Column(type="string", length=200, unique=false, nullable=false)
     */
    protected $personal_customer_email_address;

    /**
     * @ORM\OneToOne(targetEntity="Customer", inversedBy="personal_customer", cascade={"persist"})
     * @ORM\JoinTable(name="customer")
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id")
     */
    protected $customer;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    protected $personal_customer_email_opt_in;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    protected $personal_customer_phone_opt_in;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    protected $personal_customer_post_opt_in;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    protected $personal_customer_text_opt_in;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    protected $personal_customer_terms_agreed;

    /**
     * @ORM\OneToOne(targetEntity="FosUser", mappedBy="personal_customer")
     */
    protected $fos_user;

    //Set up validation callback to check that either personal customer or business customer exist
    public function validate(ExecutionContextInterface $context)
    {
        // check if the personal customer or business customer is set
        if ($this->getFosUser()->getBusinessCustomerRepresentative() == null) {

            //Check the values of each of the inputs that are required
            if ($this->getPersonalCustomerFirstName() == null || $this->getPersonalCustomerFirstName() == "") {
                $context->addViolationAt(
                    'personal_customer_first_name',
                    'Please enter your first name',
                    array(),
                    null
                );
            }


            if ($this->getPersonalCustomerSurname() == null || $this->getPersonalCustomerSurname() == "") {
                $context->addViolationAt(
                    'personal_customer_surname',
                    'Please enter your  surname',
                    array(),
                    null
                );
            }
        }

    }

    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //First name
        $metadata->addPropertyConstraint('personal_customer_first_name', new Assert\NotBlank());

        //Surname
        $metadata->addPropertyConstraint('personal_customer_surname', new Assert\NotBlank());
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->personal_customer_email_opt_in = FALSE;
        $this->personal_customer_phone_opt_in = FALSE;
        $this->personal_customer_post_opt_in = FALSE;
        $this->personal_customer_text_opt_in = FALSE;

        $this->personal_customer_terms_agreed = FALSE;
    }
    
    /**
     * Get personal_customer_full_name
     *
     * @return string 
     */
    public function getPersonalCustomerFullName()
    {
        return $this->personal_customer_first_name . " " . $this->personal_customer_surname;
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
     * Set personal_customer_first_name
     *
     * @param string $personalCustomerFirstName
     * @return PersonalCustomer
     */
    public function setPersonalCustomerFirstName($personalCustomerFirstName)
    {
        $this->personal_customer_first_name = $personalCustomerFirstName;

        return $this;
    }

    /**
     * Get personal_customer_first_name
     *
     * @return string 
     */
    public function getPersonalCustomerFirstName()
    {
        return $this->personal_customer_first_name;
    }

    /**
     * Set personal_customer_surname
     *
     * @param string $personalCustomerSurname
     * @return PersonalCustomer
     */
    public function setPersonalCustomerSurname($personalCustomerSurname)
    {
        $this->personal_customer_surname = $personalCustomerSurname;

        return $this;
    }

    /**
     * Get personal_customer_surname
     *
     * @return string 
     */
    public function getPersonalCustomerSurname()
    {
        return $this->personal_customer_surname;
    }

    /**
     * Set personal_customer_email_address
     *
     * @param string $personalCustomerEmailAddress
     * @return PersonalCustomer
     */
    public function setPersonalCustomerEmailAddress($personalCustomerEmailAddress)
    {
        $this->personal_customer_email_address = $personalCustomerEmailAddress;

        return $this;
    }

    /**
     * Get personal_customer_email_address
     *
     * @return string 
     */
    public function getPersonalCustomerEmailAddress()
    {
        return $this->personal_customer_email_address;
    }

    /**
     * Set personal_customer_email_opt_in
     *
     * @param boolean $personalCustomerEmailOptIn
     * @return PersonalCustomer
     */
    public function setPersonalCustomerEmailOptIn($personalCustomerEmailOptIn)
    {
        $this->personal_customer_email_opt_in = $personalCustomerEmailOptIn;

        return $this;
    }

    /**
     * Get personal_customer_email_opt_in
     *
     * @return boolean 
     */
    public function getPersonalCustomerEmailOptIn()
    {
        return $this->personal_customer_email_opt_in;
    }

    /**
     * Set personal_customer_phone_opt_in
     *
     * @param boolean $personalCustomerPhoneOptIn
     * @return PersonalCustomer
     */
    public function setPersonalCustomerPhoneOptIn($personalCustomerPhoneOptIn)
    {
        $this->personal_customer_phone_opt_in = $personalCustomerPhoneOptIn;

        return $this;
    }

    /**
     * Get personal_customer_phone_opt_in
     *
     * @return boolean 
     */
    public function getPersonalCustomerPhoneOptIn()
    {
        return $this->personal_customer_phone_opt_in;
    }

    /**
     * Set personal_customer_post_opt_in
     *
     * @param boolean $personalCustomerPostOptIn
     * @return PersonalCustomer
     */
    public function setPersonalCustomerPostOptIn($personalCustomerPostOptIn)
    {
        $this->personal_customer_post_opt_in = $personalCustomerPostOptIn;

        return $this;
    }

    /**
     * Get personal_customer_post_opt_in
     *
     * @return boolean 
     */
    public function getPersonalCustomerPostOptIn()
    {
        return $this->personal_customer_post_opt_in;
    }

    /**
     * Set personal_customer_text_opt_in
     *
     * @param boolean $personalCustomerTextOptIn
     * @return PersonalCustomer
     */
    public function setPersonalCustomerTextOptIn($personalCustomerTextOptIn)
    {
        $this->personal_customer_text_opt_in = $personalCustomerTextOptIn;

        return $this;
    }

    /**
     * Get personal_customer_text_opt_in
     *
     * @return boolean 
     */
    public function getPersonalCustomerTextOptIn()
    {
        return $this->personal_customer_text_opt_in;
    }

    /**
     * Set personal_customer_terms_agreed
     *
     * @param boolean $personalCustomerTermsAgreed
     * @return PersonalCustomer
     */
    public function setPersonalCustomerTermsAgreed($personalCustomerTermsAgreed)
    {
        $this->personal_customer_terms_agreed = $personalCustomerTermsAgreed;

        return $this;
    }

    /**
     * Get personal_customer_terms_agreed
     *
     * @return boolean 
     */
    public function getPersonalCustomerTermsAgreed()
    {
        return $this->personal_customer_terms_agreed;
    }

    
    

    /**
     * Set fos_user
     *
     * @param \MilesApart\AdminBundle\Entity\FosUser $fosUser
     * @return PersonalCustomer
     */
    public function setFosUser(\MilesApart\AdminBundle\Entity\FosUser $fosUser = null)
    {
        $this->fos_user = $fosUser;

        return $this;
    }

    /**
     * Get fos_user
     *
     * @return \MilesApart\AdminBundle\Entity\FosUser 
     */
    public function getFosUser()
    {
        return $this->fos_user;
    }

    /**
     * Set customer
     *
     * @param \MilesApart\AdminBundle\Entity\Customer $customer
     * @return PersonalCustomer
     */
    public function setCustomer(\MilesApart\AdminBundle\Entity\Customer $customer = null)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * Get customer
     *
     * @return \MilesApart\AdminBundle\Entity\Customer 
     */
    public function getCustomer()
    {
        return $this->customer;
    }
}
