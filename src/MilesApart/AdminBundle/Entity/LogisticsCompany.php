<?php
// src/MilesApart/AdminBundle/Entity/LogisticsCompany.php -- Defines the customer type object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\LogisticsCompanyRepository")
 * @ORM\Table(name="logistics_company")
 * @ORM\HasLifecycleCallbacks()
 */

class LogisticsCompany
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
    protected $logistics_company_name;

    /**
     * @ORM\Column(type="string", length=100, unique=false, nullable=true)
     */
    protected $logistics_company_address_line_1;

    /**
     * @ORM\Column(type="string", length=100, unique=false, nullable=true)
     */
    protected $logistics_company_address_line_2;

    /**
     * @ORM\Column(type="string", length=100, unique=false, nullable=true)
     */
    protected $logistics_company_address_town;

    /**
     * @ORM\Column(type="string", length=100, unique=false, nullable=true)
     */
    protected $logistics_company_address_county;

    /**
     * @ORM\Column(type="string", length=100, unique=false, nullable=true)
     */
    protected $logistics_company_address_country;

    /**
     * @ORM\Column(type="string", length=20, unique=false, nullable=true)
     */
    protected $logistics_company_address_postcode;

    /**
     * @ORM\Column(type="string", length=20, unique=false, nullable=false)
     */
    protected $logistics_company_phone;

    /**
     * @ORM\Column(type="string", length=20, unique=false, nullable=true)
     */
    protected $logistics_company_fax;

    /**
     * @ORM\Column(type="string", length=100, unique=false, nullable=true)
     */
    protected $logistics_company_email;

    /**
     * @ORM\OneToMany(targetEntity="PostageBandDispatchLogistics", mappedBy="logistics_company")
     */
    protected $postage_band_dispatch_logistics;

    /**
     * @ORM\OneToMany(targetEntity="SupplierDelivery", mappedBy="logistics_company")
     */
    protected $supplier_delivery;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $logistics_company_date_created;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $logistics_company_date_modified;



    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        $this->setLogisticsCompanyDateModified(new \DateTime(date('Y-m-d H:i:s')));

        if($this->getLogisticsCompanyDateCreated() == null)
        {
            $this->setLogisticsCompanyDateCreated(new \DateTime(date('Y-m-d H:i:s')));
        }
    }



    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //Logistics company name
        $metadata->addPropertyConstraint('logistics_company_name', new Assert\NotBlank());
        $metadata->addPropertyConstraint('logistics_company_name', new Assert\Length(array(
            'min'        => 1,
            'max'        => 100,
            'minMessage' => 'The logistics company name must be at least {{ limit }} characters length',
            'maxMessage' => 'The logistics company name cannot be longer than {{ limit }} characters length',
        )));

        //Logistics company address line 1
        $metadata->addPropertyConstraint('logistics_company_address_line_1', new Assert\Length(array(
            'min'        => 1,
            'max'        => 100,
            'minMessage' => 'The logistics company address line 1 must be at least {{ limit }} characters length',
            'maxMessage' => 'The logistics company address line 1 cannot be longer than {{ limit }} characters length',
        )));

        //Logistics company address line 2
        $metadata->addPropertyConstraint('logistics_company_address_line_2', new Assert\Length(array(
            'min'        => 1,
            'max'        => 100,
            'minMessage' => 'The logistics company address line 2 must be at least {{ limit }} characters length',
            'maxMessage' => 'The logistics company address line 2 cannot be longer than {{ limit }} characters length',
        )));

        //Logistics company address town
        $metadata->addPropertyConstraint('logistics_company_address_town', new Assert\Length(array(
            'min'        => 2,
            'max'        => 100,
            'minMessage' => 'The logistics company address town must be at least {{ limit }} characters length',
            'maxMessage' => 'The logistics company name cannot be longer than {{ limit }} characters length',
        )));

        //Logistics company address county
        $metadata->addPropertyConstraint('logistics_company_address_county', new Assert\Length(array(
            'min'        => 3,
            'max'        => 100,
            'minMessage' => 'The logistics company address county must be at least {{ limit }} characters length',
            'maxMessage' => 'The logistics company address county cannot be longer than {{ limit }} characters length',
        )));

        //Logistics company address country
        $metadata->addPropertyConstraint('logistics_company_address_country', new Assert\Length(array(
            'min'        => 2,
            'max'        => 100,
            'minMessage' => 'The logistics company address country must be at least {{ limit }} characters length',
            'maxMessage' => 'The logistics company address country cannot be longer than {{ limit }} characters length',
        )));

        //Logistics company address postcode
        $metadata->addPropertyConstraint('logistics_company_address_postcode', new Assert\Length(array(
            'min'        => 1,
            'max'        => 20,
            'minMessage' => 'The logistics company address postcode must be at least {{ limit }} characters length',
            'maxMessage' => 'The logistics company address postcode cannot be longer than {{ limit }} characters length',
        )));

        //Logistics company phone
        $metadata->addPropertyConstraint('logistics_company_phone', new Assert\NotBlank());
        $metadata->addPropertyConstraint('logistics_company_phone', new Assert\Length(array(
            'min'        => 1,
            'max'        => 20,
            'minMessage' => 'The logistics company phone must be at least {{ limit }} characters length',
            'maxMessage' => 'The logistics company phone cannot be longer than {{ limit }} characters length',
        )));

        //Logistics company fax
        $metadata->addPropertyConstraint('logistics_company_fax', new Assert\Length(array(
            'min'        => 1,
            'max'        => 20,
            'minMessage' => 'The logistics company fax must be at least {{ limit }} characters length',
            'maxMessage' => 'The logistics company fax cannot be longer than {{ limit }} characters length',
        )));

        //Logistics company email
        $metadata->addPropertyConstraint('logistics_company_email', new Assert\Email());
    }


   
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->postage_band_dispatch_logistics = new \Doctrine\Common\Collections\ArrayCollection();
        $this->supplier_delivery = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set logistics_company_name
     *
     * @param string $logisticsCompanyName
     * @return LogisticsCompany
     */
    public function setLogisticsCompanyName($logisticsCompanyName)
    {
        $this->logistics_company_name = $logisticsCompanyName;
    
        return $this;
    }

    /**
     * Get logistics_company_name
     *
     * @return string 
     */
    public function getLogisticsCompanyName()
    {
        return $this->logistics_company_name;
    }

    /**
     * Set logistics_company_address_line_1
     *
     * @param string $logisticsCompanyAddressLine1
     * @return LogisticsCompany
     */
    public function setLogisticsCompanyAddressLine1($logisticsCompanyAddressLine1)
    {
        $this->logistics_company_address_line_1 = $logisticsCompanyAddressLine1;
    
        return $this;
    }

    /**
     * Get logistics_company_address_line_1
     *
     * @return string 
     */
    public function getLogisticsCompanyAddressLine1()
    {
        return $this->logistics_company_address_line_1;
    }

    /**
     * Set logistics_company_address_line_2
     *
     * @param string $logisticsCompanyAddressLine2
     * @return LogisticsCompany
     */
    public function setLogisticsCompanyAddressLine2($logisticsCompanyAddressLine2)
    {
        $this->logistics_company_address_line_2 = $logisticsCompanyAddressLine2;
    
        return $this;
    }

    /**
     * Get logistics_company_address_line_2
     *
     * @return string 
     */
    public function getLogisticsCompanyAddressLine2()
    {
        return $this->logistics_company_address_line_2;
    }

    /**
     * Set logistics_company_address_town
     *
     * @param string $logisticsCompanyAddressTown
     * @return LogisticsCompany
     */
    public function setLogisticsCompanyAddressTown($logisticsCompanyAddressTown)
    {
        $this->logistics_company_address_town = $logisticsCompanyAddressTown;
    
        return $this;
    }

    /**
     * Get logistics_company_address_town
     *
     * @return string 
     */
    public function getLogisticsCompanyAddressTown()
    {
        return $this->logistics_company_address_town;
    }

    /**
     * Set logistics_company_address_county
     *
     * @param string $logisticsCompanyAddressCounty
     * @return LogisticsCompany
     */
    public function setLogisticsCompanyAddressCounty($logisticsCompanyAddressCounty)
    {
        $this->logistics_company_address_county = $logisticsCompanyAddressCounty;
    
        return $this;
    }

    /**
     * Get logistics_company_address_county
     *
     * @return string 
     */
    public function getLogisticsCompanyAddressCounty()
    {
        return $this->logistics_company_address_county;
    }

    /**
     * Set logistics_company_address_country
     *
     * @param string $logisticsCompanyAddressCountry
     * @return LogisticsCompany
     */
    public function setLogisticsCompanyAddressCountry($logisticsCompanyAddressCountry)
    {
        $this->logistics_company_address_country = $logisticsCompanyAddressCountry;
    
        return $this;
    }

    /**
     * Get logistics_company_address_country
     *
     * @return string 
     */
    public function getLogisticsCompanyAddressCountry()
    {
        return $this->logistics_company_address_country;
    }

    /**
     * Set logistics_company_address_postcode
     *
     * @param string $logisticsCompanyAddressPostcode
     * @return LogisticsCompany
     */
    public function setLogisticsCompanyAddressPostcode($logisticsCompanyAddressPostcode)
    {
        $this->logistics_company_address_postcode = $logisticsCompanyAddressPostcode;
    
        return $this;
    }

    /**
     * Get logistics_company_address_postcode
     *
     * @return string 
     */
    public function getLogisticsCompanyAddressPostcode()
    {
        return $this->logistics_company_address_postcode;
    }

    /**
     * Set logistics_company_phone
     *
     * @param string $logisticsCompanyPhone
     * @return LogisticsCompany
     */
    public function setLogisticsCompanyPhone($logisticsCompanyPhone)
    {
        $this->logistics_company_phone = $logisticsCompanyPhone;
    
        return $this;
    }

    /**
     * Get logistics_company_phone
     *
     * @return string 
     */
    public function getLogisticsCompanyPhone()
    {
        return $this->logistics_company_phone;
    }

    /**
     * Set logistics_company_fax
     *
     * @param string $logisticsCompanyFax
     * @return LogisticsCompany
     */
    public function setLogisticsCompanyFax($logisticsCompanyFax)
    {
        $this->logistics_company_fax = $logisticsCompanyFax;
    
        return $this;
    }

    /**
     * Get logistics_company_fax
     *
     * @return string 
     */
    public function getLogisticsCompanyFax()
    {
        return $this->logistics_company_fax;
    }

    /**
     * Set logistics_company_email
     *
     * @param string $logisticsCompanyEmail
     * @return LogisticsCompany
     */
    public function setLogisticsCompanyEmail($logisticsCompanyEmail)
    {
        $this->logistics_company_email = $logisticsCompanyEmail;
    
        return $this;
    }

    /**
     * Get logistics_company_email
     *
     * @return string 
     */
    public function getLogisticsCompanyEmail()
    {
        return $this->logistics_company_email;
    }

    /**
     * Set logistics_company_date_created
     *
     * @param \DateTime $logisticsCompanyDateCreated
     * @return LogisticsCompany
     */
    public function setLogisticsCompanyDateCreated($logisticsCompanyDateCreated)
    {
        $this->logistics_company_date_created = $logisticsCompanyDateCreated;
    
        return $this;
    }

    /**
     * Get logistics_company_date_created
     *
     * @return \DateTime 
     */
    public function getLogisticsCompanyDateCreated()
    {
        return $this->logistics_company_date_created;
    }

    /**
     * Set logistics_company_date_modified
     *
     * @param \DateTime $logisticsCompanyDateModified
     * @return LogisticsCompany
     */
    public function setLogisticsCompanyDateModified($logisticsCompanyDateModified)
    {
        $this->logistics_company_date_modified = $logisticsCompanyDateModified;
    
        return $this;
    }

    /**
     * Get logistics_company_date_modified
     *
     * @return \DateTime 
     */
    public function getLogisticsCompanyDateModified()
    {
        return $this->logistics_company_date_modified;
    }

    /**
     * Add postage_band_dispatch_logistics
     *
     * @param \MilesApart\AdminBundle\Entity\PostageBandDispatchLogistics $postageBandDispatchLogistics
     * @return LogisticsCompany
     */
    public function addPostageBandDispatchLogistic(\MilesApart\AdminBundle\Entity\PostageBandDispatchLogistics $postageBandDispatchLogistics)
    {
        $this->postage_band_dispatch_logistics[] = $postageBandDispatchLogistics;
    
        return $this;
    }

    /**
     * Remove postage_band_dispatch_logistics
     *
     * @param \MilesApart\AdminBundle\Entity\PostageBandDispatchLogistics $postageBandDispatchLogistics
     */
    public function removePostageBandDispatchLogistic(\MilesApart\AdminBundle\Entity\PostageBandDispatchLogistics $postageBandDispatchLogistics)
    {
        $this->postage_band_dispatch_logistics->removeElement($postageBandDispatchLogistics);
    }

    /**
     * Get postage_band_dispatch_logistics
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPostageBandDispatchLogistics()
    {
        return $this->postage_band_dispatch_logistics;
    }

    /**
     * Add supplier_delivery
     *
     * @param \MilesApart\AdminBundle\Entity\SupplierDelivery $supplierDelivery
     * @return LogisticsCompany
     */
    public function addSupplierDelivery(\MilesApart\AdminBundle\Entity\SupplierDelivery $supplierDelivery)
    {
        $this->supplier_delivery[] = $supplierDelivery;
    
        return $this;
    }

    /**
     * Remove supplier_delivery
     *
     * @param \MilesApart\AdminBundle\Entity\SupplierDelivery $supplierDelivery
     */
    public function removeSupplierDelivery(\MilesApart\AdminBundle\Entity\SupplierDelivery $supplierDelivery)
    {
        $this->supplier_delivery->removeElement($supplierDelivery);
    }

    /**
     * Get supplier_delivery
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSupplierDelivery()
    {
        return $this->supplier_delivery;
    }
}