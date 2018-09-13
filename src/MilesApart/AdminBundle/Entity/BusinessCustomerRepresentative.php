<?php
// src/MilesApart/AdminBundle/Entity/BusinessCustomerRepresentative.php -- Defines the customer type object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\BusinessCustomerRepresentativeRepository")
 * @ORM\Table(name="business_customer_representative")
 * @ORM\HasLifecycleCallbacks()
 */

class BusinessCustomerRepresentative
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
    protected $business_customer_representative_first_name;

    /**
     * @ORM\Column(type="string", length=100, unique=false, nullable=false)
     */
    protected $business_customer_representative_surname;

    /**
     * @ORM\Column(type="string", length=200, unique=false, nullable=false)
     */
    protected $business_customer_representative_email_address;

    /**
     * @ORM\ManyToOne(targetEntity="BusinessCustomer", inversedBy="business_customer_representative", cascade={"persist"})
     * @ORM\JoinTable(name="business_customer")
     * @ORM\JoinColumn(name="business_customer_id", referencedColumnName="id")
     */
    protected $business_customer;

    /**
     * @ORM\OneToOne(targetEntity="FosUser", mappedBy="business_customer_representative")
     */
    protected $fos_user;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $business_customer_representative_terms_agreed;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    protected $business_customer_representative_email_opt_in;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    protected $business_customer_representative_phone_opt_in;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    protected $business_customer_representative_post_opt_in;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    protected $business_customer_representative_text_opt_in;

    /**
     * @ORM\OneToMany(targetEntity="BusinessCustomerRepresentativeCustomerOrder", mappedBy="business_customer_representative", cascade={"persist"})
     */
    protected $business_customer_representative_customer_order;

    /**
     * @ORM\Column(type="datetime", unique=false, nullable=false)
     */
    protected $business_customer_representative_date_created;

    /**
     * @ORM\Column(type="datetime", unique=false, nullable=true)
     */
    protected $business_customer_representative_date_modified;

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        $this->setBusinessCustomerRepresentativeDateModified(new \DateTime(date('Y-m-d H:i:s')));

        if($this->getBusinessCustomerRepresentativeDateCreated() == null)
        {
            $this->setBusinessCustomerRepresentativeDateCreated(new \DateTime(date('Y-m-d H:i:s')));
        }
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //Business customer name
        $metadata->addPropertyConstraint('business_customer_representative_first_name', new Assert\NotBlank());
        $metadata->addPropertyConstraint('business_customer_representative_first_name', new Assert\Length(array(
            'min'        => 1,
            'max'        => 100,
            'minMessage' => 'The business customer first name must be at least {{ limit }} characters length',
            'maxMessage' => 'The business customer first name cannot be longer than {{ limit }} characters length',
        )));

        //Business customer vat number
        $metadata->addPropertyConstraint('business_customer_representative_surname', new Assert\NotBlank());
        $metadata->addPropertyConstraint('business_customer_representative_surname', new Assert\Length(array(
            'min'        => 2,
            'max'        => 15,
            'minMessage' => 'The business customer representative surname name must be at least {{ limit }} characters length',
            'maxMessage' => 'The business customer representative surname name cannot be longer than {{ limit }} characters length',
        )));

        
    }



    /**
     * Constructor
     */
    public function __construct()
    {

        $this->business_customer_representative_email_opt_in = FALSE;
        $this->business_customer_representative_phone_opt_in = FALSE;
        $this->business_customer_representative_post_opt_in = FALSE;
        $this->business_customer_representative_text_opt_in = FALSE;

        
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
     * Set business_customer_representative_date_created
     *
     * @param \DateTime $businessCustomerRepresentativeDateCreated
     * @return BusinessCustomerRepresentative
     */
    public function setBusinessCustomerRepresentativeDateCreated($businessCustomerRepresentativeDateCreated)
    {
        $this->business_customer_representative_date_created = $businessCustomerRepresentativeDateCreated;

        return $this;
    }

    /**
     * Get business_customer_representative_date_created
     *
     * @return \DateTime
     */
    public function getBusinessCustomerRepresentativeDateCreated()
    {
        return $this->business_customer_representative_date_created;
    }

    /**
     * Set business_customer_representative_date_modified
     *
     * @param \DateTime $businessCustomerRepresentativeDateModified
     * @return BusinessCustomerRepresentative
     */
    public function setBusinessCustomerRepresentativeDateModified($businessCustomerRepresentativeDateModified)
    {
        $this->business_customer_representative_date_modified = $businessCustomerRepresentativeDateModified;

        return $this;
    }

    /**
     * Get business_customer_representative_date_modified
     *
     * @return \DateTime
     */
    public function getBusinessCustomerRepresentativeDateModified()
    {
        return $this->business_customer_representative_date_modified;
    }

    /**
     * Set business_customer_representative_first_name
     *
     * @param string $businessCustomerRepresentativeFirstName
     * @return BusinessCustomerRepresentative
     */
    public function setBusinessCustomerRepresentativeFirstName($businessCustomerRepresentativeFirstName)
    {
        $this->business_customer_representative_first_name = $businessCustomerRepresentativeFirstName;

        return $this;
    }

    /**
     * Get business_customer_representative_first_name
     *
     * @return string 
     */
    public function getBusinessCustomerRepresentativeFirstName()
    {
        return $this->business_customer_representative_first_name;
    }

    /**
     * Set business_customer_representative_surname
     *
     * @param string $businessCustomerRepresentativeSurname
     * @return BusinessCustomerRepresentative
     */
    public function setBusinessCustomerRepresentativeSurname($businessCustomerRepresentativeSurname)
    {
        $this->business_customer_representative_surname = $businessCustomerRepresentativeSurname;

        return $this;
    }

    /**
     * Get business_customer_representative_surname
     *
     * @return string 
     */
    public function getBusinessCustomerRepresentativeSurname()
    {
        return $this->business_customer_representative_surname;
    }

    /**
     * Get business_customer_representative_full_name
     *
     * @return string 
     */
    public function getBusinessCustomerRepresentativeFullName()
    {
        return $this->getBusinessCustomerRepresentativeFirstName() . " " . $this->getBusinessCustomerRepresentativeSurname();
    }

    /**
     * Set business_customer_representative_email_address
     *
     * @param string $businessCustomerRepresentativeEmailAddress
     * @return BusinessCustomerRepresentative
     */
    public function setBusinessCustomerRepresentativeEmailAddress($businessCustomerRepresentativeEmailAddress)
    {
        $this->business_customer_representative_email_address = $businessCustomerRepresentativeEmailAddress;

        return $this;
    }

    /**
     * Get business_customer_representative_email_address
     *
     * @return string 
     */
    public function getBusinessCustomerRepresentativeEmailAddress()
    {
        return $this->business_customer_representative_email_address;
    }

    /**
     * Set business_customer_representative_terms_agreed
     *
     * @param boolean $businessCustomerRepresentativeTermsAgreed
     * @return BusinessCustomerRepresentative
     */
    public function setBusinessCustomerRepresentativeTermsAgreed($businessCustomerRepresentativeTermsAgreed)
    {
        $this->business_customer_representative_terms_agreed = $businessCustomerRepresentativeTermsAgreed;

        return $this;
    }

    /**
     * Get business_customer_representative_terms_agreed
     *
     * @return boolean 
     */
    public function getBusinessCustomerRepresentativeTermsAgreed()
    {
        return $this->business_customer_representative_terms_agreed;
    }

    /**
     * Set business_customer_representative_email_opt_in
     *
     * @param boolean $businessCustomerRepresentativeEmailOptIn
     * @return BusinessCustomerRepresentative
     */
    public function setBusinessCustomerRepresentativeEmailOptIn($businessCustomerRepresentativeEmailOptIn)
    {
        $this->business_customer_representative_email_opt_in = $businessCustomerRepresentativeEmailOptIn;

        return $this;
    }

    /**
     * Get business_customer_representative_email_opt_in
     *
     * @return boolean 
     */
    public function getBusinessCustomerRepresentativeEmailOptIn()
    {
        return $this->business_customer_representative_email_opt_in;
    }

    /**
     * Set business_customer_representative_phone_opt_in
     *
     * @param boolean $businessCustomerRepresentativePhoneOptIn
     * @return BusinessCustomerRepresentative
     */
    public function setBusinessCustomerRepresentativePhoneOptIn($businessCustomerRepresentativePhoneOptIn)
    {
        $this->business_customer_representative_phone_opt_in = $businessCustomerRepresentativePhoneOptIn;

        return $this;
    }

    /**
     * Get business_customer_representative_phone_opt_in
     *
     * @return boolean 
     */
    public function getBusinessCustomerRepresentativePhoneOptIn()
    {
        return $this->business_customer_representative_phone_opt_in;
    }

    /**
     * Set business_customer_representative_post_opt_in
     *
     * @param boolean $businessCustomerRepresentativePostOptIn
     * @return BusinessCustomerRepresentative
     */
    public function setBusinessCustomerRepresentativePostOptIn($businessCustomerRepresentativePostOptIn)
    {
        $this->business_customer_representative_post_opt_in = $businessCustomerRepresentativePostOptIn;

        return $this;
    }

    /**
     * Get business_customer_representative_post_opt_in
     *
     * @return boolean 
     */
    public function getBusinessCustomerRepresentativePostOptIn()
    {
        return $this->business_customer_representative_post_opt_in;
    }

    /**
     * Set business_customer_representative_text_opt_in
     *
     * @param boolean $businessCustomerRepresentativeTextOptIn
     * @return BusinessCustomerRepresentative
     */
    public function setBusinessCustomerRepresentativeTextOptIn($businessCustomerRepresentativeTextOptIn)
    {
        $this->business_customer_representative_text_opt_in = $businessCustomerRepresentativeTextOptIn;

        return $this;
    }

    /**
     * Get business_customer_representative_text_opt_in
     *
     * @return boolean 
     */
    public function getBusinessCustomerRepresentativeTextOptIn()
    {
        return $this->business_customer_representative_text_opt_in;
    }

    /**
     * Set business_customer
     *
     * @param \MilesApart\AdminBundle\Entity\BusinessCustomer $businessCustomer
     * @return BusinessCustomerRepresentative
     */
    public function setBusinessCustomer(\MilesApart\AdminBundle\Entity\BusinessCustomer $businessCustomer = null)
    {
        $this->business_customer = $businessCustomer;

        return $this;
    }

    /**
     * Get business_customer
     *
     * @return \MilesApart\AdminBundle\Entity\BusinessCustomer 
     */
    public function getBusinessCustomer()
    {
        return $this->business_customer;
    }



    /**
     * Set business_customer_representative_customer_order
     *
     * @param \MilesApart\AdminBundle\Entity\BusinessCustomerRepresentativeCustomerOrder $businessCustomerRepresentativeCustomerOrder
     * @return BusinessCustomerRepresentative
     */
    public function setBusinessCustomerRepresentativeCustomerOrder(\MilesApart\AdminBundle\Entity\BusinessCustomerRepresentativeCustomerOrder $businessCustomerRepresentativeCustomerOrder = null)
    {
        $this->business_customer_representative_customer_order = $businessCustomerRepresentativeCustomerOrder;

        return $this;
    }

    /**
     * Get business_customer_representative_customer_order
     *
     * @return \MilesApart\AdminBundle\Entity\BusinessCustomerRepresentativeCustomerOrder 
     */
    public function getBusinessCustomerRepresentativeCustomerOrder()
    {
        return $this->business_customer_representative_customer_order;
    }

    /**
     * Set fos_user
     *
     * @param \MilesApart\AdminBundle\Entity\FosUser $fosUser
     * @return BusinessCustomerRepresentative
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
}
