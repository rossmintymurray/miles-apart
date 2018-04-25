<?php
// src/MilesApart/AdminBundle/Entity/BusinessPremises.php -- Defines the stock location object (for storage of products)

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use MilesApart\AdminBundle\Utils\MilesApart as MilesApart;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\BusinessPremisesRepository")
 * @ORM\Table(name="business_premises")
 * @ORM\HasLifecycleCallbacks()
 *
 * @ORM\NamedNativeQueries({
 *      @ORM\NamedNativeQuery(
 *          name            = "bus_prem_in_dt",
 *          resultSetMapping= "map_bus_prem_in_dt",
 *          query           = "SELECT bp.*
 *                        FROM MilesApartAdminBundle:BusinessPremises AS bp
 *                         WHERE bp.id NOT IN (
 *                               SELECT premises FROM daily_take_business_premises dtbp 
 *                               LEFT JOIN business_premises busp ON busp.id = dtbp.business_premises_id 
 *                               LEFT JOIN daily_take dt ON dt.id = dtbp.daily_take_id
 *                               WHERE dt.daily_take_date = :daily_take_date
 *
 *                           )
 *                           
 *                           AND bp.business_premises_type = :businessTypes"
 *      ),
 * })
 * @ORM\SqlResultSetMappings({
 *      @ORM\SqlResultSetMapping(
 *          name    = "map_bus_prem_in_dt",
 *          entities= {
 *              @ORM\EntityResult(
 *                  entityClass = "BusinessPremises",
 *                  fields      = {
 *                      @ORM\FieldResult(name = "id",       column="bp_id"),
 *                      @ORM\FieldResult(name = "business_premises_name",     column="bp_business_premises_name"),
 *                      
 *                  }
 *              ),
 *          },
 *       
 *      )
 *})
 */

class BusinessPremises
{
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
    protected $business_premises_name;

    /**
     * @ORM\Column(type="string", length=4, unique=true, nullable=false)
     */
    protected $business_premises_code;

    /**
     * @Gedmo\Slug(fields={"business_premises_name"}, separator="-")
     * @ORM\Column(type="string", length=200, unique=true, nullable=false)
     */
    protected $business_premises_slug;

    /**
     * @ORM\Column(type="string", length=100, unique=false, nullable=false)
     */
    protected $business_premises_address_line_1;

    /**
     * @ORM\Column(type="string", length=100, unique=false, nullable=true)
     */
    protected $business_premises_address_line_2;

    /**
     * @ORM\Column(type="string", length=100, unique=false, nullable=false)
     */
    protected $business_premises_town;

    /**
     * @ORM\Column(type="string", length=100, unique=false, nullable=false)
     */
    protected $business_premises_county;

    /**
     * @ORM\Column(type="string", length=10, unique=false, nullable=false)
     */
    protected $business_premises_postcode;

    /**
     * @ORM\Column(type="string", length=20, unique=true, nullable=false)
     */
    protected $business_premises_phone;

    /**
     * @ORM\Column(type="string", length=100, unique=true, nullable=false)
     */
    protected $business_premises_email;

    /**
     * @ORM\ManyToOne(targetEntity="BusinessPremisesType", inversedBy="business_premises")
     * @ORM\JoinTable(name="business_premises_type")
     * @ORM\JoinColumn(name="business_premises_type_id", referencedColumnName="id")
     */
    protected $business_premises_type;

    /**
     * @ORM\ManyToOne(targetEntity="Business", inversedBy="business_premises")
     * @ORM\JoinTable(name="business")
     * @ORM\JoinColumn(name="business_id", referencedColumnName="id")
     */
    protected $business;

    /**
     * @ORM\OneToMany(targetEntity="StockLocation", mappedBy="business_premises")
     */
    protected $stock_location;

    /**
     * @ORM\OneToMany(targetEntity="DailyTakeBusinessPremises", mappedBy="business_premises")
     */
    protected $daily_take_business_premises;

    /**
     * @ORM\OneToMany(targetEntity="Stocktake", mappedBy="business_premises")
     */
    protected $stocktake;

    /**
     * @ORM\OneToMany(targetEntity="SupplierDelivery", mappedBy="business_premises")
     */
    protected $supplier_delivery;

    /**
     * @ORM\OneToMany(targetEntity="TransferRequest", mappedBy="business_premises")
     */
    protected $transfer_request;

    /**
     * @ORM\OneToMany(targetEntity="SeasonalStorageBox", mappedBy="business_premises")
     */
    protected $seasonal_storage_box;

    /**
     * @ORM\OneToMany(targetEntity="PrintRequest", mappedBy="business_premises", cascade={"persist"})
     */
    protected $print_request;

    /**
     * @ORM\OneToMany(targetEntity="EmployeeContractedHours", mappedBy="business_premises", cascade={"persist"})
     */
    protected $employee_contracted_hours;


    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //Business premises name
        $metadata->addPropertyConstraint('business_premises_name', new Assert\NotBlank());
        $metadata->addPropertyConstraint('business_premises_name', new Assert\Length(array(
            'min'        => 2,
            'max'        => 100,
            'minMessage' => 'The business premises name must be at least {{ limit }} characters length',
            'maxMessage' => 'The business premises name cannot be longer than {{ limit }} characters length',
        )));

        //Business premises code
        $metadata->addPropertyConstraint('business_premises_code', new Assert\NotBlank());
        $metadata->addPropertyConstraint('business_premises_code', new Assert\Length(array(
            'min'        => 4,
            'max'        => 4,
            'minMessage' => 'The business premises code must be at least {{ limit }} characters length',
            'maxMessage' => 'The business premises code cannot be longer than {{ limit }} characters length',
        )));

        //Business premises address line 1
        $metadata->addPropertyConstraint('business_premises_address_line_1', new Assert\NotBlank());
        $metadata->addPropertyConstraint('business_premises_address_line_1', new Assert\Length(array(
            'min'        => 1,
            'max'        => 100,
            'minMessage' => 'The business premises address line 1 must be at least {{ limit }} characters length',
            'maxMessage' => 'The business premises address line 1 cannot be longer than {{ limit }} characters length',
        )));
        
        //Business premises town
        $metadata->addPropertyConstraint('business_premises_town', new Assert\NotBlank());
        $metadata->addPropertyConstraint('business_premises_town', new Assert\Length(array(
            'min'        => 1,
            'max'        => 100,
            'minMessage' => 'The business premises town must be at least {{ limit }} characters length',
            'maxMessage' => 'The business premises town cannot be longer than {{ limit }} characters length',
        )));

        //Business premises county
        $metadata->addPropertyConstraint('business_premises_county', new Assert\NotBlank());
        $metadata->addPropertyConstraint('business_premises_county', new Assert\Length(array(
            'min'        => 1,
            'max'        => 100,
            'minMessage' => 'The business premises county must be at least {{ limit }} characters length',
            'maxMessage' => 'The business premises county cannot be longer than {{ limit }} characters length',
        )));

        //Business premises postcode
        $metadata->addPropertyConstraint('business_premises_postcode', new Assert\NotBlank());
        $metadata->addPropertyConstraint('business_premises_postcode', new Assert\Length(array(
            'min'        => 5,
            'max'        => 10,
            'minMessage' => 'The business premises postcode must be at least {{ limit }} characters length',
            'maxMessage' => 'The business premises postcode cannot be longer than {{ limit }} characters length',
        )));

        //Business premises phone
        $metadata->addPropertyConstraint('business_premises_phone', new Assert\NotBlank());
        $metadata->addPropertyConstraint('business_premises_phone', new Assert\Length(array(
            'min'        => 11,
            'max'        => 20,
            'minMessage' => 'The business premises phone number must be at least {{ limit }} characters length',
            'maxMessage' => 'The business premises phone number cannot be longer than {{ limit }} characters length',
        )));
     
        //Business premises email address
        $metadata->addPropertyConstraint('business_premises_email', new Assert\Email());
       
    }



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->stock_location = new \Doctrine\Common\Collections\ArrayCollection();
        $this->daily_take_business_premises = new \Doctrine\Common\Collections\ArrayCollection();
        $this->stocktake = new \Doctrine\Common\Collections\ArrayCollection();
        $this->supplier_delivery = new \Doctrine\Common\Collections\ArrayCollection();
        $this->transfer_request = new \Doctrine\Common\Collections\ArrayCollection();
        $this->seasonal_storage_box = new \Doctrine\Common\Collections\ArrayCollection();
        $this->print_request = new \Doctrine\Common\Collections\ArrayCollection();
        $this->employee_contracted_hours = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set business_premises_name
     *
     * @param string $businessPremisesName
     * @return BusinessPremises
     */
    public function setBusinessPremisesName($businessPremisesName)
    {
        $this->business_premises_name = $businessPremisesName;
    
        return $this;
    }

    /**
     * Get business_premises_name
     *
     * @return string 
     */
    public function getBusinessPremisesName()
    {
        return $this->business_premises_name;
    }

    /**
     * Set business_premises_code
     *
     * @param string $businessPremisesCode
     * @return BusinessPremises
     */
    public function setBusinessPremisesCode($businessPremisesCode)
    {
        $this->business_premises_code = $businessPremisesCode;
    
        return $this;
    }

    /**
     * Get business_premises_code
     *
     * @return string 
     */
    public function getBusinessPremisesCode()
    {
        return $this->business_premises_code;
    }

    /**
     * Set business_premises_slug
     *
     * @param string $businessPremisesSlug
     * @return BusinessPremises
     */
    public function setBusinessPremisesSlug($businessPremisesSlug)
    {
        $this->business_premises_slug = $businessPremisesSlug;
    
        return $this;
    }

    /**
     * Get business_premises_slug
     *
     * @return string 
     */
    public function getBusinessPremisesSlug()
    {
        return MilesApart::slugify($this->getBusinessPremisesName());
    }

    /**
     * Set business_premises_address_line_1
     *
     * @param string $businessPremisesAddressLine1
     * @return BusinessPremises
     */
    public function setBusinessPremisesAddressLine1($businessPremisesAddressLine1)
    {
        $this->business_premises_address_line_1 = $businessPremisesAddressLine1;
    
        return $this;
    }

    /**
     * Get business_premises_address_line_1
     *
     * @return string 
     */
    public function getBusinessPremisesAddressLine1()
    {
        return $this->business_premises_address_line_1;
    }

    /**
     * Set business_premises_address_line_2
     *
     * @param string $businessPremisesAddressLine2
     * @return BusinessPremises
     */
    public function setBusinessPremisesAddressLine2($businessPremisesAddressLine2)
    {
        $this->business_premises_address_line_2 = $businessPremisesAddressLine2;
    
        return $this;
    }

    /**
     * Get business_premises_address_line_2
     *
     * @return string 
     */
    public function getBusinessPremisesAddressLine2()
    {
        return $this->business_premises_address_line_2;
    }

    /**
     * Set business_premises_town
     *
     * @param string $businessPremisesTown
     * @return BusinessPremises
     */
    public function setBusinessPremisesTown($businessPremisesTown)
    {
        $this->business_premises_town = $businessPremisesTown;
    
        return $this;
    }

    /**
     * Get business_premises_town
     *
     * @return string 
     */
    public function getBusinessPremisesTown()
    {
        return $this->business_premises_town;
    }

    /**
     * Set business_premises_county
     *
     * @param string $businessPremisesCounty
     * @return BusinessPremises
     */
    public function setBusinessPremisesCounty($businessPremisesCounty)
    {
        $this->business_premises_county = $businessPremisesCounty;
    
        return $this;
    }

    /**
     * Get business_premises_county
     *
     * @return string 
     */
    public function getBusinessPremisesCounty()
    {
        return $this->business_premises_county;
    }

    /**
     * Set business_premises_postcode
     *
     * @param string $businessPremisesPostcode
     * @return BusinessPremises
     */
    public function setBusinessPremisesPostcode($businessPremisesPostcode)
    {
        $this->business_premises_postcode = $businessPremisesPostcode;
    
        return $this;
    }

    /**
     * Get business_premises_postcode
     *
     * @return string 
     */
    public function getBusinessPremisesPostcode()
    {
        return $this->business_premises_postcode;
    }

    /**
     * Set business_premises_phone
     *
     * @param string $businessPremisesPhone
     * @return BusinessPremises
     */
    public function setBusinessPremisesPhone($businessPremisesPhone)
    {
        $this->business_premises_phone = $businessPremisesPhone;
    
        return $this;
    }

    /**
     * Get business_premises_phone
     *
     * @return string 
     */
    public function getBusinessPremisesPhone()
    {
        return $this->business_premises_phone;
    }

    /**
     * Set business_premises_email
     *
     * @param string $businessPremisesEmail
     * @return BusinessPremises
     */
    public function setBusinessPremisesEmail($businessPremisesEmail)
    {
        $this->business_premises_email = $businessPremisesEmail;
    
        return $this;
    }

    /**
     * Get business_premises_email
     *
     * @return string 
     */
    public function getBusinessPremisesEmail()
    {
        return $this->business_premises_email;
    }

    /**
     * Set business_premises_type
     *
     * @param \MilesApart\AdminBundle\Entity\BusinessPremisesType $businessPremisesType
     * @return BusinessPremises
     */
    public function setBusinessPremisesType(\MilesApart\AdminBundle\Entity\BusinessPremisesType $businessPremisesType = null)
    {
        $this->business_premises_type = $businessPremisesType;
    
        return $this;
    }

    /**
     * Get business_premises_type
     *
     * @return \MilesApart\AdminBundle\Entity\BusinessPremisesType 
     */
    public function getBusinessPremisesType()
    {
        return $this->business_premises_type;
    }

    /**
     * Set business
     *
     * @param \MilesApart\AdminBundle\Entity\Business $business
     * @return BusinessPremises
     */
    public function setBusiness(\MilesApart\AdminBundle\Entity\Business $business = null)
    {
        $this->business = $business;
    
        return $this;
    }

    /**
     * Get business
     *
     * @return \MilesApart\AdminBundle\Entity\Business 
     */
    public function getBusiness()
    {
        return $this->business;
    }

    /**
     * Add stock_location
     *
     * @param \MilesApart\AdminBundle\Entity\StockLocation $stockLocation
     * @return BusinessPremises
     */
    public function addStockLocation(\MilesApart\AdminBundle\Entity\StockLocation $stockLocation)
    {
        $this->stock_location[] = $stockLocation;
    
        return $this;
    }

    /**
     * Remove stock_location
     *
     * @param \MilesApart\AdminBundle\Entity\StockLocation $stockLocation
     */
    public function removeStockLocation(\MilesApart\AdminBundle\Entity\StockLocation $stockLocation)
    {
        $this->stock_location->removeElement($stockLocation);
    }

    /**
     * Get stock_location
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getStockLocation()
    {
        return $this->stock_location;
    }

    /**
     * Add daily_take_business_premises
     *
     * @param \MilesApart\AdminBundle\Entity\DailyTakeBusinessPremises $dailyTakeBusinessPremises
     * @return BusinessPremises
     */
    public function addDailyTakeBusinessPremises(\MilesApart\AdminBundle\Entity\DailyTakeBusinessPremises $dailyTakeBusinessPremises)
    {
        $this->daily_take_business_premises[] = $dailyTakeBusinessPremises;
    
        return $this;
    }

    /**
     * Remove daily_take_business_premises
     *
     * @param \MilesApart\AdminBundle\Entity\DailyTakeBusinessPremises $dailyTakeBusinessPremises
     */
    public function removeDailyTakeBusinessPremises(\MilesApart\AdminBundle\Entity\DailyTakeBusinessPremises $dailyTakeBusinessPremises)
    {
        $this->daily_take_business_premises->removeElement($dailyTakeBusinessPremises);
    }

    /**
     * Get daily_take_business_premises
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDailyTakeBusinessPremises()
    {
        return $this->daily_take_business_premises;
    }

    /**
     * Add stocktake
     *
     * @param \MilesApart\AdminBundle\Entity\Stocktake $stocktake
     * @return BusinessPremises
     */
    public function addStocktake(\MilesApart\AdminBundle\Entity\Stocktake $stocktake)
    {
        $this->stocktake[] = $stocktake;
    
        return $this;
    }

    /**
     * Remove stocktake
     *
     * @param \MilesApart\AdminBundle\Entity\Stocktake $stocktake
     */
    public function removeStocktake(\MilesApart\AdminBundle\Entity\Stocktake $stocktake)
    {
        $this->stocktake->removeElement($stocktake);
    }

    /**
     * Get stocktake
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getStocktake()
    {
        return $this->stocktake;
    }

    /**
     * Add supplier_delivery
     *
     * @param \MilesApart\AdminBundle\Entity\SupplierDelivery $supplierDelivery
     * @return BusinessPremises
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

    /**
     * Add transfer_request
     *
     * @param \MilesApart\AdminBundle\Entity\TransferRequest $transferRequest
     * @return BusinessPremises
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

    /**
     * Add daily_take_business_premises
     *
     * @param \MilesApart\AdminBundle\Entity\DailyTakeBusinessPremises $dailyTakeBusinessPremises
     * @return BusinessPremises
     */
    public function addDailyTakeBusinessPremise(\MilesApart\AdminBundle\Entity\DailyTakeBusinessPremises $dailyTakeBusinessPremises)
    {
        $this->daily_take_business_premises[] = $dailyTakeBusinessPremises;
    
        return $this;
    }

    /**
     * Remove daily_take_business_premises
     *
     * @param \MilesApart\AdminBundle\Entity\DailyTakeBusinessPremises $dailyTakeBusinessPremises
     */
    public function removeDailyTakeBusinessPremise(\MilesApart\AdminBundle\Entity\DailyTakeBusinessPremises $dailyTakeBusinessPremises)
    {
        $this->daily_take_business_premises->removeElement($dailyTakeBusinessPremises);
    }


    /**
     * Add seasonal_storage_box
     *
     * @param \MilesApart\AdminBundle\Entity\SeasonalStorageBox $seasonalStorageBox
     * @return BusinessPremises
     */
    public function addSeasonalStorageBox(\MilesApart\AdminBundle\Entity\SeasonalStorageBox $seasonalStorageBox)
    {
        $this->seasonal_storage_box[] = $seasonalStorageBox;
    
        return $this;
    }

    /**
     * Remove seasonal_storage_box
     *
     * @param \MilesApart\AdminBundle\Entity\SeasonalStorageBox $seasonalStorageBox
     */
    public function removeSeasonalStorageBox(\MilesApart\AdminBundle\Entity\SeasonalStorageBox $seasonalStorageBox)
    {
        $this->seasonal_storage_box->removeElement($seasonalStorageBox);
    }

    /**
     * Get seasonal_storage_box
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSeasonalStorageBox()
    {
        return $this->seasonal_storage_box;
    }

    /**
     * Add employee_contracted_hours
     *
     * @param \MilesApart\AdminBundle\Entity\EmployeeContractedHours $employeeContractedHours
     * @return BusinessPremises
     */
    public function addEmployeeContractedHours(\MilesApart\AdminBundle\Entity\EmployeeContractedHours $employeeContractedHours)
    {
        $this->employee_contracted_hours[] = $employeeContractedHours;
    
        return $this;
    }

    /**
     * Remove employee_contracted_hours
     *
     * @param \MilesApart\AdminBundle\Entity\EmployeeContractedHours $employeeContractedHours
     */
    public function removeEmployeeContractedHours(\MilesApart\AdminBundle\Entity\EmployeeContractedHours $employeeContractedHours)
    {
        $this->employee_contracted_hours->removeElement($employeeContractedHours);
    }

    /**
     * Get employee_contracted_hours
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEmployeeContractedHours()
    {
        return $this->employee_contracted_hours;
    }

    /**
     * Add print_request
     *
     * @param \MilesApart\AdminBundle\Entity\PrintRequest $printRequest
     * @return BusinessPremises
     */
    public function addPrintRequest(\MilesApart\AdminBundle\Entity\PrintRequest $printRequest)
    {
        $this->print_request[] = $printRequest;
    
        return $this;
    }

    /**
     * Remove print_request
     *
     * @param \MilesApart\AdminBundle\Entity\PrintRequest $printRequest
     */
    public function removePrintRequest(\MilesApart\AdminBundle\Entity\PrintRequest $printRequest)
    {
        $this->print_request->removeElement($printRequest);
    }

    /**
     * Get print_request
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPrintRequest()
    {
        return $this->print_request;
    }

    /**
     * Get period_z_reading_cash
     *
     * @return string 
     */
    public function getPeriodZReadingCash()
    {
        $total = "0.00";
        foreach ($this->getDailyTakeBusinessPremises() as $key => $value) {
            $total = $total + $value->getZReadingCash();
        }
        
        return $total;
    }

    /**
     * Get period_z_reading_card
     *
     * @return string 
     */
    public function getPeriodZReadingCard()
    {
        $total = "0.00";
        foreach ($this->getDailyTakeBusinessPremises() as $key => $value) {
            $total = $total + $value->getZReadingCard();
        }
        
        return $total;
    }

    /**
     * Get period_total_z
     *
     * @return string 
     */
    public function getPeriodTotalZ()
    {
        $total = "0.00";
        foreach ($this->getDailyTakeBusinessPremises() as $key => $value) {
            $total = $total + $value->getTotalZ();
        }
        
        return $total;
    }

    /**
     * Get period_total_renumeration
     *
     * @return string 
     */
    public function getPeriodTotalRenumeration()
    {
        $total = "0.00";
        foreach ($this->getDailyTakeBusinessPremises() as $key => $value) {
            $total = $total + $value->getTotalRenumeration();
        }
        
        return $total;
    }

    
    
    /**
     * Get period_total_petty_cash
     *
     * @return string 
     */
    public function getPeriodTotalPettyCash()
    {
        $total = "0.00";
        foreach ($this->getDailyTakeBusinessPremises() as $key => $value) {
            foreach ($value->getDailyTakeBusinessPremisesPettyCash() as $key => $value2) {
                $total = $total + $value2->getPettyCashValue();
            }
        }
        
        return $total;
    }

    /**
     * Get period_total_renumeration_cash
     *
     * @return string 
     */
    public function getPeriodRenumerationCash()
    {
        $total = "0.00";
        foreach ($this->getDailyTakeBusinessPremises() as $key => $value) {
            $total = $total + $value->getRenumerationCash();
        }
        
        return $total;

    }


    /**
     * Get period_total_renumeration_card
     *
     * @return string 
     */
    public function getPeriodRenumerationCard()
    {
        $total = "0.00";
        foreach ($this->getDailyTakeBusinessPremises() as $key => $value) {
            $total = $total + $value->getRenumerationCard();    
        }
        
        return $total;

    }

   


}