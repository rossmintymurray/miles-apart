<?php
// src/MilesApart/AdminBundle/Entity/SupplierRepresentative.php  -- Defines the supplier representative object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\SupplierRepresentativeRepository")
 * @ORM\Table(name="supplier_representative")
 */

class SupplierRepresentative
{
    //Define the values

    /**
     * @var integer $id
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=50, unique=false, nullable=false)
     */
    protected $supplier_representative_first_name;

    /**
     * @ORM\Column(type="string", length=50, unique=false, nullable=false)
     */
    protected $supplier_representative_surname;

    /**
     * @ORM\Column(type="string", length=100, unique=false, nullable=true)
     */
    protected $supplier_representative_address_1;

    /**
     * @ORM\Column(type="string", length=100, unique=false, nullable=true)
     */
    protected $supplier_representative_address_2;

    /**
     * @ORM\Column(type="string", length=100, unique=false, nullable=true)
     */
    protected $supplier_representative_town;

    /**
     * @ORM\Column(type="string", length=50, unique=false, nullable=true)
     */
    protected $supplier_representative_county;

    /**
     * @ORM\Column(type="string", length=10, unique=false, nullable=true)
     */
    protected $supplier_representative_postcode;

    /**
     * @ORM\Column(type="string", length=50, unique=false, nullable=true)
     */
    protected $supplier_representative_country;

    /**
     * @ORM\Column(type="string", length=20, unique=false, nullable=true)
     */
    protected $supplier_representative_mobile_number;

    /**
     * @ORM\Column(type="string", length=20, unique=false, nullable=true)
     */
    protected $supplier_representative_landline_number;

    /**
     * @ORM\Column(type="string", length=100, unique=false, nullable=true)
     */
    protected $supplier_representative_email;

    /**
     * @ORM\ManyToOne(targetEntity="Supplier", inversedBy="supplier_representative")
     * @ORM\JoinTable(name="supplier")
     * @ORM\JoinColumn(name="supplier_id", referencedColumnName="id")
     */
    protected $supplier;

    /**
     * @ORM\Column(type="date", unique=false, nullable=true)
     */
    protected $supplier_representative_start_date;

    /**
     * @ORM\Column(type="date", unique=false, nullable=true)
     */
    protected $supplier_representative_end_date;

    /**
     * @ORM\Column(type="datetime", unique=false, nullable=true)
     */
    protected $supplier_representative_date_created;

    /**
     * @ORM\Column(type="datetime", unique=false, nullable=true)
     */
    protected $supplier_representative_date_modified;

    protected $supplier_representative_full_name;

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        $this->setSupplierRepresentativeDateModified(new \DateTime(date('Y-m-d H:i:s')));

        if($this->getSupplierRepresentativeDateCreated() == null)
        {
            $this->setSupplierRepresentativeDateCreated(new \DateTime(date('Y-m-d H:i:s')));
        }
    }



    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //Supplier representative first name
        $metadata->addPropertyConstraint('supplier_representative_first_name', new Assert\NotBlank());
        $metadata->addPropertyConstraint('supplier_representative_first_name', new Assert\Length(array(
            'min'        => 2,
            'max'        => 50,
            'minMessage' => 'The supplier representative first name must be at least {{ limit }} characters length',
            'maxMessage' => 'The supplier representative first name cannot be longer than {{ limit }} characters length',
        )));

        //Supplier representative surname
        $metadata->addPropertyConstraint('supplier_representative_surname', new Assert\NotBlank());
        $metadata->addPropertyConstraint('supplier_representative_surname', new Assert\Length(array(
            'min'        => 2,
            'max'        => 50,
            'minMessage' => 'The supplier representative surname must be at least {{ limit }} characters length',
            'maxMessage' => 'The supplier representative surname cannot be longer than {{ limit }} characters length',
        )));

        //Supplier representative address 1
        $metadata->addPropertyConstraint('supplier_representative_address_1', new Assert\Length(array(
            'min'        => 2,
            'max'        => 100,
            'minMessage' => 'The supplier representative address line 1 must be at least {{ limit }} characters length',
            'maxMessage' => 'The supplier representative address line 1 cannot be longer than {{ limit }} characters length',
        )));

        //Supplier representative address 2
        $metadata->addPropertyConstraint('supplier_representative_address_2', new Assert\Length(array(
            'min'        => 2,
            'max'        => 100,
            'minMessage' => 'The supplier representative address line 2 must be at least {{ limit }} characters length',
            'maxMessage' => 'The supplier representative address line 2 cannot be longer than {{ limit }} characters length',
        )));

        //Supplier representative town
        $metadata->addPropertyConstraint('supplier_representative_town', new Assert\Length(array(
            'min'        => 2,
            'max'        => 100,
            'minMessage' => 'The supplier representative town must be at least {{ limit }} characters length',
            'maxMessage' => 'The supplier representative town cannot be longer than {{ limit }} characters length',
        )));

        //Supplier representative county
        $metadata->addPropertyConstraint('supplier_representative_county', new Assert\Length(array(
            'min'        => 2,
            'max'        => 50,
            'minMessage' => 'The supplier representative county must be at least {{ limit }} characters length',
            'maxMessage' => 'The supplier representative county cannot be longer than {{ limit }} characters length',
        )));

        //Supplier representative postcode
        $metadata->addPropertyConstraint('supplier_representative_postcode', new Assert\Length(array(
            'min'        => 2,
            'max'        => 10,
            'minMessage' => 'The supplier representative postcode must be at least {{ limit }} characters length',
            'maxMessage' => 'The supplier representative postcode cannot be longer than {{ limit }} characters length',
        )));

        //Supplier representative country
        $metadata->addPropertyConstraint('supplier_representative_country', new Assert\Length(array(
            'min'        => 2,
            'max'        => 20,
            'minMessage' => 'The supplier representative country must be at least {{ limit }} characters length',
            'maxMessage' => 'The supplier representative country cannot be longer than {{ limit }} characters length',
        )));

        //Supplier representative mobile number
        $metadata->addPropertyConstraint('supplier_representative_mobile_number', new Assert\Length(array(
            'min'        => 2,
            'max'        => 20,
            'minMessage' => 'The supplier representative mobile number must be at least {{ limit }} characters length',
            'maxMessage' => 'The supplier representative mobile number cannot be longer than {{ limit }} characters length',
        )));

        //Supplier representative landline number
        $metadata->addPropertyConstraint('supplier_representative_landline_number', new Assert\Length(array(
            'min'        => 2,
            'max'        => 100,
            'minMessage' => 'The supplier representative landline number must be at least {{ limit }} characters length',
            'maxMessage' => 'The supplier representative landline number cannot be longer than {{ limit }} characters length',
        )));
        
        //Supplier representative email
        $metadata->addPropertyConstraint('supplier_representative_email', new Assert\Email());

        //Supplier representative start date
        $metadata->addPropertyConstraint('supplier_representative_start_date', new Assert\Date());

        //Supplier representative end date
        $metadata->addPropertyConstraint('supplier_representative_end_date', new Assert\Date());
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
     * Set supplier_representative_first_name
     *
     * @param string $supplierRepresentativeFirstName
     * @return SupplierRepresentative
     */
    public function setSupplierRepresentativeFirstName($supplierRepresentativeFirstName)
    {
        $this->supplier_representative_first_name = $supplierRepresentativeFirstName;
    
        return $this;
    }

    /**
     * Get supplier_representative_first_name
     *
     * @return string 
     */
    public function getSupplierRepresentativeFirstName()
    {
        return $this->supplier_representative_first_name;
    }

    /**
     * Set supplier_representative_surname
     *
     * @param string $supplierRepresentativeSurname
     * @return SupplierRepresentative
     */
    public function setSupplierRepresentativeSurname($supplierRepresentativeSurname)
    {
        $this->supplier_representative_surname = $supplierRepresentativeSurname;
    
        return $this;
    }

    /**
     * Get supplier_representative_surname
     *
     * @return string 
     */
    public function getSupplierRepresentativeSurname()
    {
        return $this->supplier_representative_surname;
    }

    /**
     * Get supplier_representative_full_name
     *
     * @return string 
     */
    public function getSupplierRepresentativeFullName()
    {
        return $this->getSupplierRepresentativeFirstName() . " " .$this->getSupplierRepresentativeSurname();
    }

    /**
     * Set supplier_representative_address_1
     *
     * @param string $supplierRepresentativeAddress1
     * @return SupplierRepresentative
     */
    public function setSupplierRepresentativeAddress1($supplierRepresentativeAddress1)
    {
        $this->supplier_representative_address_1 = $supplierRepresentativeAddress1;
    
        return $this;
    }

    /**
     * Get supplier_representative_address_1
     *
     * @return string 
     */
    public function getSupplierRepresentativeAddress1()
    {
        return $this->supplier_representative_address_1;
    }

    /**
     * Set supplier_representative_address_2
     *
     * @param string $supplierRepresentativeAddress2
     * @return SupplierRepresentative
     */
    public function setSupplierRepresentativeAddress2($supplierRepresentativeAddress2)
    {
        $this->supplier_representative_address_2 = $supplierRepresentativeAddress2;
    
        return $this;
    }

    /**
     * Get supplier_representative_address_2
     *
     * @return string 
     */
    public function getSupplierRepresentativeAddress2()
    {
        return $this->supplier_representative_address_2;
    }

    /**
     * Set supplier_representative_town
     *
     * @param string $supplierRepresentativeTown
     * @return SupplierRepresentative
     */
    public function setSupplierRepresentativeTown($supplierRepresentativeTown)
    {
        $this->supplier_representative_town = $supplierRepresentativeTown;
    
        return $this;
    }

    /**
     * Get supplier_representative_town
     *
     * @return string 
     */
    public function getSupplierRepresentativeTown()
    {
        return $this->supplier_representative_town;
    }

    /**
     * Set supplier_representative_county
     *
     * @param string $supplierRepresentativeCounty
     * @return SupplierRepresentative
     */
    public function setSupplierRepresentativeCounty($supplierRepresentativeCounty)
    {
        $this->supplier_representative_county = $supplierRepresentativeCounty;
    
        return $this;
    }

    /**
     * Get supplier_representative_county
     *
     * @return string 
     */
    public function getSupplierRepresentativeCounty()
    {
        return $this->supplier_representative_county;
    }

    /**
     * Set supplier_representative_postcode
     *
     * @param string $supplierRepresentativePostcode
     * @return SupplierRepresentative
     */
    public function setSupplierRepresentativePostcode($supplierRepresentativePostcode)
    {
        $this->supplier_representative_postcode = $supplierRepresentativePostcode;
    
        return $this;
    }

    /**
     * Get supplier_representative_postcode
     *
     * @return string 
     */
    public function getSupplierRepresentativePostcode()
    {
        return $this->supplier_representative_postcode;
    }

    /**
     * Set supplier_representative_country
     *
     * @param string $supplierRepresentativeCountry
     * @return SupplierRepresentative
     */
    public function setSupplierRepresentativeCountry($supplierRepresentativeCountry)
    {
        $this->supplier_representative_country = $supplierRepresentativeCountry;
    
        return $this;
    }

    /**
     * Get supplier_representative_country
     *
     * @return string 
     */
    public function getSupplierRepresentativeCountry()
    {
        return $this->supplier_representative_country;
    }

    /**
     * Set supplier_representative_mobile_number
     *
     * @param string $supplierRepresentativeMobileNumber
     * @return SupplierRepresentative
     */
    public function setSupplierRepresentativeMobileNumber($supplierRepresentativeMobileNumber)
    {
        $this->supplier_representative_mobile_number = $supplierRepresentativeMobileNumber;
    
        return $this;
    }

    /**
     * Get supplier_representative_mobile_number
     *
     * @return string 
     */
    public function getSupplierRepresentativeMobileNumber()
    {
        return $this->supplier_representative_mobile_number;
    }

    /**
     * Set supplier_representative_landline_number
     *
     * @param string $supplierRepresentativeLandlineNumber
     * @return SupplierRepresentative
     */
    public function setSupplierRepresentativeLandlineNumber($supplierRepresentativeLandlineNumber)
    {
        $this->supplier_representative_landline_number = $supplierRepresentativeLandlineNumber;
    
        return $this;
    }

    /**
     * Get supplier_representative_landline_number
     *
     * @return string 
     */
    public function getSupplierRepresentativeLandlineNumber()
    {
        return $this->supplier_representative_landline_number;
    }

    /**
     * Set supplier_representative_email
     *
     * @param string $supplierRepresentativeEmail
     * @return SupplierRepresentative
     */
    public function setSupplierRepresentativeEmail($supplierRepresentativeEmail)
    {
        $this->supplier_representative_email = $supplierRepresentativeEmail;
    
        return $this;
    }

    /**
     * Get supplier_representative_email
     *
     * @return string 
     */
    public function getSupplierRepresentativeEmail()
    {
        return $this->supplier_representative_email;
    }

    /**
     * Set supplier_representative_start_date
     *
     * @param \DateTime $supplierRepresentativeStartDate
     * @return SupplierRepresentative
     */
    public function setSupplierRepresentativeStartDate($supplierRepresentativeStartDate)
    {
        $this->supplier_representative_start_date = $supplierRepresentativeStartDate;
    
        return $this;
    }

    /**
     * Get supplier_representative_start_date
     *
     * @return \DateTime 
     */
    public function getSupplierRepresentativeStartDate()
    {
        return $this->supplier_representative_start_date;
    }

    /**
     * Set supplier_representative_end_date
     *
     * @param \DateTime $supplierRepresentativeEndDate
     * @return SupplierRepresentative
     */
    public function setSupplierRepresentativeEndDate($supplierRepresentativeEndDate)
    {
        $this->supplier_representative_end_date = $supplierRepresentativeEndDate;
    
        return $this;
    }

    /**
     * Get supplier_representative_end_date
     *
     * @return \DateTime 
     */
    public function getSupplierRepresentativeEndDate()
    {
        return $this->supplier_representative_end_date;
    }

    /**
     * Set supplier_representative_date_created
     *
     * @param \DateTime $supplierRepresentativeDateCreated
     * @return SupplierRepresentative
     */
    public function setSupplierRepresentativeDateCreated($supplierRepresentativeDateCreated)
    {
        $this->supplier_representative_date_created = $supplierRepresentativeDateCreated;
    
        return $this;
    }

    /**
     * Get supplier_representative_date_created
     *
     * @return \DateTime 
     */
    public function getSupplierRepresentativeDateCreated()
    {
        return $this->supplier_representative_date_created;
    }

    /**
     * Set supplier_representative_date_modified
     *
     * @param \DateTime $supplierRepresentativeDateModified
     * @return SupplierRepresentative
     */
    public function setSupplierRepresentativeDateModified($supplierRepresentativeDateModified)
    {
        $this->supplier_representative_date_modified = $supplierRepresentativeDateModified;
    
        return $this;
    }

    /**
     * Get supplier_representative_date_modified
     *
     * @return \DateTime 
     */
    public function getSupplierRepresentativeDateModified()
    {
        return $this->supplier_representative_date_modified;
    }

    /**
     * Set supplier
     *
     * @param \MilesApart\AdminBundle\Entity\Supplier $supplier
     * @return SupplierRepresentative
     */
    public function setSupplier(\MilesApart\AdminBundle\Entity\Supplier $supplier = null)
    {
        $this->supplier = $supplier;
    
        return $this;
    }

    /**
     * Get supplier
     *
     * @return \MilesApart\AdminBundle\Entity\Supplier 
     */
    public function getSupplier()
    {
        return $this->supplier;
    }
}