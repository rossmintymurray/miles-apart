<?php
// src/MilesApart/AdminBundle/Entity/Employee.php -- Defines the employee object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\EmployeeRepository")
 * @ORM\Table(name="employee")
 * @ORM\HasLifecycleCallbacks()
 */

class Employee
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
    protected $employee_first_name;

    /**
     * @ORM\Column(type="string", length=100, unique=false, nullable=false)
     */
    protected $employee_surname;

    protected $employee_full_name;

    /**
     * @ORM\Column(type="string", length=100, unique=false, nullable=false)
     */
    protected $employee_address_1;

    /**
     * @ORM\Column(type="string", length=100, unique=false, nullable=true)
     */
    protected $employee_address_2;

    /**
     * @ORM\Column(type="string", length=100, unique=false, nullable=false)
     */
    protected $employee_town;

    /**
     * @ORM\Column(type="string", length=100, unique=false, nullable=false)
     */
    protected $employee_county;

    /**
     * @ORM\Column(type="string", length=10, unique=false, nullable=false)
     */
    protected $employee_postcode;

    /**
     * @ORM\Column(type="string", length=15, unique=false, nullable=true)
     */
    protected $employee_landline_phone;

      /**
     * @ORM\Column(type="string", length=15, unique=false, nullable=true)
     */
    protected $employee_mobile_phone;

    /**
     * @ORM\Column(type="string", length=100, unique=false, nullable=true)
     */
    protected $employee_email;

    /**
     * @ORM\Column(type="string", length=20, unique=false, nullable=true)
     */
    protected $employee_national_insurance_number;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    protected $employee_date_of_birth;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    protected $employee_starting_date;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    protected $employee_leaving_date;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $employee_tax_code;

    /**
     * @ORM\OneToMany(targetEntity="EmployeeWorkWeek", mappedBy="employee")
     */
    protected $employee_work_week;

    /**
     * @ORM\OneToMany(targetEntity="AdminUser", mappedBy="employee")
     */
    protected $admin_user;

    /**
     * @ORM\OneToMany(targetEntity="EmployeeJobRoleEmployee", mappedBy="employee", cascade={"persist"})
     */
    protected $employee_job_role_employee;

    /**
     * @ORM\OneToMany(targetEntity="SupplierDelivery", mappedBy="employee")
     */
    protected $supplier_delivery;

    /**
     * @ORM\Column(type="datetime", unique=false, nullable=true)
     */
    protected $employee_date_created;

    /**
     * @ORM\Column(type="datetime", unique=false, nullable=false)
     */
    protected $employee_date_modified;

    /**
     * @ORM\OneToMany(targetEntity="EmployeeHoliday", mappedBy="employee")
     */
    protected $employee_holiday;

    /**
     * @ORM\OneToMany(targetEntity="EmployeePayment", mappedBy="employee")
     */
    protected $employee_payment;

    /**
     * @ORM\OneToMany(targetEntity="EmployeeStatutoryPayment", mappedBy="employee")
     */
    protected $employee_statutory_payment;

    /**
     * @ORM\OneToMany(targetEntity="EmployeeContractedHours", mappedBy="employee", cascade={"persist"})
     */
    protected $employee_contracted_hours;

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        $this->setEmployeeDateModified(new \DateTime(date('Y-m-d H:i:s')));

        if($this->getEmployeeDateCreated() == null)
        {
            $this->setEmployeeDateCreated(new \DateTime(date('Y-m-d H:i:s')));
        }
    }
   


    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //Employee first name
        $metadata->addPropertyConstraint('employee_first_name', new Assert\NotBlank());
        $metadata->addPropertyConstraint('employee_first_name', new Assert\Length(array(
            'min'        => 1,
            'max'        => 100,
            'minMessage' => 'The first name must be at least {{ limit }} characters length',
            'maxMessage' => 'The first name cannot be longer than {{ limit }} characters length',
        )));

        //Employee surname
        $metadata->addPropertyConstraint('employee_surname', new Assert\NotBlank());
        $metadata->addPropertyConstraint('employee_surname', new Assert\Length(array(
            'min'        => 1,
            'max'        => 100,
            'minMessage' => 'The surname must be at least {{ limit }} characters length',
            'maxMessage' => 'The surname cannot be longer than {{ limit }} characters length',
        )));

        //Employee address 1
        $metadata->addPropertyConstraint('employee_address_1', new Assert\NotBlank());
        $metadata->addPropertyConstraint('employee_address_1', new Assert\Length(array(
            'min'        => 1,
            'max'        => 100,
            'minMessage' => 'The address line 1 must be at least {{ limit }} characters length',
            'maxMessage' => 'The address line 1 cannot be longer than {{ limit }} characters length',
        )));

        //Employee town
        $metadata->addPropertyConstraint('employee_town', new Assert\NotBlank());
        $metadata->addPropertyConstraint('employee_town', new Assert\Length(array(
            'min'        => 2,
            'max'        => 100,
            'minMessage' => 'The town must be at least {{ limit }} characters length',
            'maxMessage' => 'The town cannot be longer than {{ limit }} characters length',
        )));

        //Employee county
        $metadata->addPropertyConstraint('employee_county', new Assert\NotBlank());
        $metadata->addPropertyConstraint('employee_county', new Assert\Length(array(
            'min'        => 2,
            'max'        => 100,
            'minMessage' => 'The county must be at least {{ limit }} characters length',
            'maxMessage' => 'The county cannot be longer than {{ limit }} characters length',
        )));


        //Employee postcode
        $metadata->addPropertyConstraint('employee_postcode', new Assert\NotBlank());
        $metadata->addPropertyConstraint('employee_postcode', new Assert\Length(array(
            'min'        => 5,
            'max'        => 10,
            'minMessage' => 'The postcode must be at least {{ limit }} characters length',
            'maxMessage' => 'The postcode cannot be longer than {{ limit }} characters length',
        )));

        //Employee landline phone
        $metadata->addPropertyConstraint('employee_landline_phone', new Assert\Length(array(
            'min'        => 1,
            'max'        => 14,
            'minMessage' => 'The landline phone number must be at least {{ limit }} characters length',
            'maxMessage' => 'The landline phone number cannot be longer than {{ limit }} characters length',
        )));

        //Employee mobile phone
        $metadata->addPropertyConstraint('employee_mobile_phone', new Assert\Length(array(
            'min'        => 11,
            'max'        => 12,
            'minMessage' => 'The mobile phone number must be at least {{ limit }} characters length',
            'maxMessage' => 'The mobile phone number cannot be longer than {{ limit }} characters length',
        )));

        //Employee email
        $metadata->addPropertyConstraint('employee_email', new Assert\Email());

        //Employee national insurance number
        $metadata->addPropertyConstraint('employee_national_insurance_number', new Assert\Length(array(
            'min'        => 9,
            'max'        => 20,
            'minMessage' => 'The national insurance number must be at least {{ limit }} characters length',
            'maxMessage' => 'The national insurance number cannot be longer than {{ limit }} characters length',
        )));
    }

    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->employee_work_week = new \Doctrine\Common\Collections\ArrayCollection();
        $this->admin_user = new \Doctrine\Common\Collections\ArrayCollection();
        $this->employee_job_role_employee = new \Doctrine\Common\Collections\ArrayCollection();
        $this->supplier_delivery = new \Doctrine\Common\Collections\ArrayCollection();
        $this->employee_holiday = new \Doctrine\Common\Collections\ArrayCollection();
        $this->employee_payment = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set employee_first_name
     *
     * @param string $employeeFirstName
     * @return Employee
     */
    public function setEmployeeFirstName($employeeFirstName)
    {
        $this->employee_first_name = $employeeFirstName;
    
        return $this;
    }

    /**
     * Get employee_first_name
     *
     * @return string 
     */
    public function getEmployeeFirstName()
    {
        return $this->employee_first_name;
    }

    /**
     * Set employee_surname
     *
     * @param string $employeeSurname
     * @return Employee
     */
    public function setEmployeeSurname($employeeSurname)
    {
        $this->employee_surname = $employeeSurname;
    
        return $this;
    }

    /**
     * Get employee_surname
     *
     * @return string 
     */
    public function getEmployeeSurname()
    {
        return $this->employee_surname;
    }

    /**
    * Get employee full name
    *
    * @return string 
     */
    public function getEmployeeFullName()
    {
        return   $this->employee_first_name ." ". $this->employee_surname;
    }

    /**
     * Set employee_address_1
     *
     * @param string $employeeAddress1
     * @return Employee
     */
    public function setEmployeeAddress1($employeeAddress1)
    {
        $this->employee_address_1 = $employeeAddress1;
    
        return $this;
    }

    /**
     * Get employee_address_1
     *
     * @return string 
     */
    public function getEmployeeAddress1()
    {
        return $this->employee_address_1;
    }

    /**
     * Set employee_address_2
     *
     * @param string $employeeAddress2
     * @return Employee
     */
    public function setEmployeeAddress2($employeeAddress2)
    {
        $this->employee_address_2 = $employeeAddress2;
    
        return $this;
    }

    /**
     * Get employee_address_2
     *
     * @return string 
     */
    public function getEmployeeAddress2()
    {
        return $this->employee_address_2;
    }

    /**
     * Set employee_town
     *
     * @param string $employeeTown
     * @return Employee
     */
    public function setEmployeeTown($employeeTown)
    {
        $this->employee_town = $employeeTown;
    
        return $this;
    }

    /**
     * Get employee_town
     *
     * @return string 
     */
    public function getEmployeeTown()
    {
        return $this->employee_town;
    }

    /**
     * Set employee_county
     *
     * @param string $employeeCounty
     * @return Employee
     */
    public function setEmployeeCounty($employeeCounty)
    {
        $this->employee_county = $employeeCounty;
    
        return $this;
    }

    /**
     * Get employee_county
     *
     * @return string 
     */
    public function getEmployeeCounty()
    {
        return $this->employee_county;
    }

    /**
     * Set employee_postcode
     *
     * @param string $employeePostcode
     * @return Employee
     */
    public function setEmployeePostcode($employeePostcode)
    {
        $this->employee_postcode = $employeePostcode;
    
        return $this;
    }

    /**
     * Get employee_postcode
     *
     * @return string 
     */
    public function getEmployeePostcode()
    {
        return $this->employee_postcode;
    }

    /**
     * Set employee_landline_phone
     *
     * @param string $employeeLandlinePhone
     * @return Employee
     */
    public function setEmployeeLandlinePhone($employeeLandlinePhone)
    {
        $this->employee_landline_phone = $employeeLandlinePhone;
    
        return $this;
    }

    /**
     * Get employee_landline_phone
     *
     * @return string 
     */
    public function getEmployeeLandlinePhone()
    {
        return $this->employee_landline_phone;
    }

    /**
     * Set employee_mobile_phone
     *
     * @param string $employeeMobilePhone
     * @return Employee
     */
    public function setEmployeeMobilePhone($employeeMobilePhone)
    {
        $this->employee_mobile_phone = $employeeMobilePhone;
    
        return $this;
    }

    /**
     * Get employee_mobile_phone
     *
     * @return string 
     */
    public function getEmployeeMobilePhone()
    {
        return $this->employee_mobile_phone;
    }

    /**
     * Set employee_email
     *
     * @param string $employeeEmail
     * @return Employee
     */
    public function setEmployeeEmail($employeeEmail)
    {
        $this->employee_email = $employeeEmail;
    
        return $this;
    }

    /**
     * Get employee_email
     *
     * @return string 
     */
    public function getEmployeeEmail()
    {
        return $this->employee_email;
    }

    /**
     * Set employee_national_insurance_number
     *
     * @param string $employeeNationalInsuranceNumber
     * @return Employee
     */
    public function setEmployeeNationalInsuranceNumber($employeeNationalInsuranceNumber)
    {
        $this->employee_national_insurance_number = $employeeNationalInsuranceNumber;
    
        return $this;
    }

    /**
     * Get employee_national_insurance_number
     *
     * @return string 
     */
    public function getEmployeeNationalInsuranceNumber()
    {
        return $this->employee_national_insurance_number;
    }

    /**
     * Set employee_date_of_birth
     *
     * @param \DateTime $employeeDateOfBirth
     * @return Employee
     */
    public function setEmployeeDateOfBirth($employeeDateOfBirth)
    {
        $this->employee_date_of_birth = $employeeDateOfBirth;
    
        return $this;
    }

    /**
     * Get employee_date_of_birth
     *
     * @return \DateTime 
     */
    public function getEmployeeDateOfBirth()
    {
        return $this->employee_date_of_birth;
    }

    /**
     * Set employee_starting_date
     *
     * @param \DateTime $employeeStartingDate
     * @return Employee
     */
    public function setEmployeeStartingDate($employeeStartingDate)
    {
        $this->employee_starting_date = $employeeStartingDate;
    
        return $this;
    }

    /**
     * Get employee_starting_date
     *
     * @return \DateTime 
     */
    public function getEmployeeStartingDate()
    {
        return $this->employee_starting_date;
    }

    /**
     * Set employee_leaving_date
     *
     * @param \DateTime $employeeLeavingDate
     * @return Employee
     */
    public function setEmployeeLeavingDate($employeeLeavingDate)
    {
        $this->employee_leaving_date = $employeeLeavingDate;
    
        return $this;
    }

    /**
     * Get employee_leaving_date
     *
     * @return \DateTime 
     */
    public function getEmployeeLeavingDate()
    {
        return $this->employee_leaving_date;
    }

    /**
     * Set employee_tax_code
     *
     * @param string $employeeTaxCode
     * @return Employee
     */
    public function setEmployeeTaxCode($employeeTaxCode)
    {
        $this->employee_tax_code = $employeeTaxCode;
    
        return $this;
    }

    /**
     * Get employee_tax_code
     *
     * @return string 
     */
    public function getEmployeeTaxCode()
    {
        return $this->employee_tax_code;
    }

    /**
     * Set employee_date_created
     *
     * @param \DateTime $employeeDateCreated
     * @return Employee
     */
    public function setEmployeeDateCreated($employeeDateCreated)
    {
        $this->employee_date_created = $employeeDateCreated;
    
        return $this;
    }

    /**
     * Get employee_date_created
     *
     * @return \DateTime 
     */
    public function getEmployeeDateCreated()
    {
        return $this->employee_date_created;
    }

    /**
     * Set employee_date_modified
     *
     * @param \DateTime $employeeDateModified
     * @return Employee
     */
    public function setEmployeeDateModified($employeeDateModified)
    {
        $this->employee_date_modified = $employeeDateModified;
    
        return $this;
    }

    /**
     * Get employee_date_modified
     *
     * @return \DateTime 
     */
    public function getEmployeeDateModified()
    {
        return $this->employee_date_modified;
    }

    /**
     * Add employee_work_week
     *
     * @param \MilesApart\AdminBundle\Entity\EmployeeWorkWeek $employeeWorkWeek
     * @return Employee
     */
    public function addEmployeeWorkWeek(\MilesApart\AdminBundle\Entity\EmployeeWorkWeek $employeeWorkWeek)
    {
        $this->employee_work_week[] = $employeeWorkWeek;
    
        return $this;
    }

    /**
     * Remove employee_work_week
     *
     * @param \MilesApart\AdminBundle\Entity\EmployeeWorkWeek $employeeWorkWeek
     */
    public function removeEmployeeWorkWeek(\MilesApart\AdminBundle\Entity\EmployeeWorkWeek $employeeWorkWeek)
    {
        $this->employee_work_week->removeElement($employeeWorkWeek);
    }

    /**
     * Get employee_work_week
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEmployeeWorkWeek()
    {
        return $this->employee_work_week;
    }

    /**
     * Add admin_user
     *
     * @param \MilesApart\AdminBundle\Entity\AdminUser $adminUser
     * @return Employee
     */
    public function addAdminUser(\MilesApart\AdminBundle\Entity\AdminUser $adminUser)
    {
        $this->admin_user[] = $adminUser;
    
        return $this;
    }

    /**
     * Remove admin_user
     *
     * @param \MilesApart\AdminBundle\Entity\AdminUser $adminUser
     */
    public function removeAdminUser(\MilesApart\AdminBundle\Entity\AdminUser $adminUser)
    {
        $this->admin_user->removeElement($adminUser);
    }

    /**
     * Get admin_user
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAdminUser()
    {
        return $this->admin_user;
    }

    /**
     * Add employee_job_role_employee
     *
     * @param \MilesApart\AdminBundle\Entity\EmployeeJobRoleEmployee $employeeJobRoleEmployee
     * @return Employee
     */
    public function addEmployeeJobRoleEmployee(\MilesApart\AdminBundle\Entity\EmployeeJobRoleEmployee $employeeJobRoleEmployee)
    {
        $this->employee_job_role_employee[] = $employeeJobRoleEmployee;
    
        return $this;
    }

    /**
     * Remove employee_job_role_employee
     *
     * @param \MilesApart\AdminBundle\Entity\EmployeeJobRoleEmployee $employeeJobRoleEmployee
     */
    public function removeEmployeeJobRoleEmployee(\MilesApart\AdminBundle\Entity\EmployeeJobRoleEmployee $employeeJobRoleEmployee)
    {
        $this->employee_job_role_employee->removeElement($employeeJobRoleEmployee);
    }

    /**
     * Get employee_job_role_employee
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEmployeeJobRoleEmployee()
    {
        return $this->employee_job_role_employee;
    }

    /**
     * Add supplier_delivery
     *
     * @param \MilesApart\AdminBundle\Entity\SupplierDelivery $supplierDelivery
     * @return Employee
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
     * Add employee_holiday
     *
     * @param \MilesApart\AdminBundle\Entity\EmployeeHoliday $employeeHoliday
     * @return Employee
     */
    public function addEmployeeHoliday(\MilesApart\AdminBundle\Entity\EmployeeHoliday $employeeHoliday)
    {
        $this->employee_holiday[] = $employeeHoliday;
    
        return $this;
    }

    /**
     * Remove employee_holiday
     *
     * @param \MilesApart\AdminBundle\Entity\EmployeeHoliday $employeeHoliday
     */
    public function removeEmployeeHoliday(\MilesApart\AdminBundle\Entity\EmployeeHoliday $employeeHoliday)
    {
        $this->employee_holiday->removeElement($employeeHoliday);
    }

    /**
     * Get employee_holiday
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEmployeeHoliday()
    {
        return $this->employee_holiday;
    }

    /**
     * Add employee_payment
     *
     * @param \MilesApart\AdminBundle\Entity\EmployeePayment $employeePayment
     * @return Employee
     */
    public function addEmployeePayment(\MilesApart\AdminBundle\Entity\EmployeePayment $employeePayment)
    {
        $this->employee_payment[] = $employeePayment;
    
        return $this;
    }

    /**
     * Remove employee_payment
     *
     * @param \MilesApart\AdminBundle\Entity\EmployeePayment $employeePayment
     */
    public function removeEmployeePayment(\MilesApart\AdminBundle\Entity\EmployeePayment $employeePayment)
    {
        $this->employee_payment->removeElement($employeePayment);
    }

    /**
     * Get employee_payment
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEmployeePayment()
    {
        return $this->employee_payment;
        
    }

    /**
     * Add employee_contracted_hours
     *
     * @param \MilesApart\AdminBundle\Entity\EmployeeContractedHours $employeeContractedHours
     * @return Employee
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
     * Get employee_statutory_payment
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEmployeeStatutoryPayment()
    {
        return $this->employee_statutory_payment;
    }

    /**
     * Get current_job_role
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCurrentJobRole()
    {
        
       $most_recent = 0;
       $most_recent_job_role = false;
       $date = null;
       if (count($this->getEmployeeJobRoleEmployee()) > 0) {
            foreach($this->getEmployeeJobRoleEmployee() as $key => $value) {
                //$most_recent_job_role .= "-1";
                $date = $value->getEmployeeJobRoleDateCommenced();
                $curDate = strtotime($date->format('Y-m-d H:i:s'));
                if ($curDate > $most_recent) {
                    $most_recent = $curDate;
                    $most_recent_job_role = $value->getEmployeeJobRole();
                }
            } 
        } else {
            $most_recent_job_role = false;
        }

        
        
            
        return $most_recent_job_role;
    
    }

    /**
     * Get current_job_role_date_commenced
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCurrentJobRoleDateCommenced()
    {
        
       $most_recent = 0;
       //$most_recent_job_role = "1";
       $date = null;
       if (count($this->getEmployeeJobRoleEmployee()) > 0) {
            foreach($this->getEmployeeJobRoleEmployee() as $key => $value) {
                //$most_recent_job_role .= "-1";
                $date = $value->getEmployeeJobRoleDateCommenced();
                $curDate = strtotime($date->format('Y-m-d H:i:s'));
                if ($curDate > $most_recent) {
                    $most_recent = $curDate;
                    $most_recent_job_role = $value->getEmployeeJobRole();
                }
            } 
        } else {
            $most_recent_job_role = "Not set";
        }

        
        if ($date != null) {
            
            return $most_recent;
        }
    }

    /**
     * Get job_role_by_date
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getJobRoleByDate($payment_date)
    {
        
        //Set most recent date to zero
        $most_recent_date = 0;
        
        $most_recent_job_role = "1";
       
        if (count($this->getEmployeeJobRoleEmployee()) > 0) {
            foreach($this->getEmployeeJobRoleEmployee() as $key => $value) {
                //$most_recent_job_role .= "-1";
                $commenced_date = $value->getEmployeeJobRoleDateCommenced();
                
                //$curDate = strtotime($commenced_date->format('Y-m-d H:i:s'));
                //$payment_date = strtotime($payment_date->format('Y-m-d H:i:s'));
                if ($commenced_date < $payment_date) {
                    //Check if the job role date is before the payment date.
                   
                    $most_recent_job_role = $value->getEmployeeJobRole();
                }
            } 
        } else {
            $most_recent_job_role = "Not set";
        }

        
        if ($payment_date != null) {
            
            return $most_recent_job_role;
        }
    }

    
    /**
     * Get current_job_role
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    /*
    public function getCurrentWageRate()
    {
        
       $most_recent = 0;
       $most_recent_job_role = "1";
       $date = null;
       if (count($this->getEmployeeJobRoleEmployee()) > 0) {
            foreach($this->getEmployeeJobRoleEmployee() as $key => $value) {
                $most_recent_job_role .= "-1";
                $date = $value->getEmployeeJobRoleDateCommenced();
                $curDate = strtotime($date->format('Y-m-d H:i:s'));
                if ($curDate > $most_recent) {
                    $most_recent = $curDate;
                    $most_recent_job_role = $value->getEmployeeJobRole();
                }
            } 
        } else {
            $most_recent_job_role = "Not set";
        }

        
        if ($date != null) {
            
            return $most_recent_job_role;
        }
    }
*/

    /**
     * Get current_contracted_hours
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCurrentContractedHours()
    {
        
       $most_recent = 0;
       //$most_recent_job_role = "1";
       $date = null;
       if (count($this->getEmployeeContractedHours()) > 0) {
            foreach($this->getEmployeeContractedHours() as $key => $value) {
                //$most_recent_job_role .= "-1";
                $date = $value->getEmployeeContractedHoursValidFrom();
                $curDate = strtotime($date->format('Y-m-d H:i:s'));
                if ($curDate > $most_recent) {
                    $most_recent = $curDate;
                    $current_contracted_hours = $value;
                }
            } 
        } else {
            $current_contracted_hours = "Not set";
        }

        
        if ($date != null) {
            
            return $current_contracted_hours;
        }
    }

    /**
     * Get current_contracted_hours_display
     *
     * @return string 
     */
    public function getCurrentContractedHoursDisplay()
    {
        if($this->getCurrentContractedHours()) {
        
            $hours = $this->getCurrentContractedHours()->getEmployeeContractedHours();
            $hours = preg_replace("/\.?0*$/",'',$hours); 

            return $hours;
        } else {
            return false;
        }
    }

    /**
     * Get weighted_holiday_hours
     *
     * @return \Doctrine\Common\Collections\Collection 
     *
     */
    public function getWeightedHolidayHoursEarnedToDate()
    {
        //Find the year that todays date belongs to it (now).
        $current_date = new \DateTime('now');
        $current_year = $current_date->format('Y');

        //Calculate the start and end date of the current year.
        //Check if the current fate is before new year starts.
        if (strtotime($current_date->format('Y-m-d')) <  strtotime($current_year . "-11-01")) {
            $holiday_year_start_date = new \DateTime(($current_year - 1) . "-11-01");
            $holiday_year_end_date = new \DateTime($current_year . "-10-31");
        } else {
            $holiday_year_start_date = new \DateTime(($current_year) . "-11-01");
            $holiday_year_end_date = new \DateTime(($current_year + 1) . "-11-01");
        }

        //Check if employee has finished work, use their last day as the holiday end date
        if($this->getEmployeeLeavingDate() != null) {
            $holiday_calc_end_date = $this->getEmployeeLeavingDate();
        } else {
            $holiday_calc_end_date = new \DateTime('now');
        }

        //Set up the number of days in this holiday year for division
        $holiday_year_days = ($holiday_year_start_date->diff($holiday_year_end_date)->days) +1;

        //Set up weighted hours array.
        $weighted_hours_array = array();
        $duration = 0;


        //Get all employee contracted hours for that period (maybe from a get to the employee contracted hours, giving a weighted figure)
        //Check if employee contracted hours are set.
        if (count($this->getEmployeeContractedHours()) > 0) {

            //For each contracted hours
            foreach($this->getEmployeeContractedHours() as $key => $value) {

                //If contracted hours end is after holiday year start date & before holiday year end date (contract ends part way through the holiday year)
                if($value->getEmployeeContractedHoursValidUntil() > $holiday_year_start_date && $value->getEmployeeContractedHoursValidUntil() < $holiday_year_end_date) {
                    
                    

                    //If contracted hours start is after the holiday year start date (contracted start -> contracted end)
                    if($value->getEmployeeContractedHoursValidFrom() > $holiday_year_start_date) {
                        
                        //Contracted hours started after holiday year started so entire duration of contracted hours should be considered.
                        $duration = $value->getEmployeeContractedHoursValidFrom()->diff($value->getEmployeeContractedHoursValidUntil());
                        $weighted = (($duration->days + 1) / $holiday_year_days);

                        $weighted_hours = $weighted * $value->getEmployeeContractedHours();

                        array_push($weighted_hours_array, $weighted_hours);

                       

                    
                    //If contracted hours start is before or equal to the holiday year start (holiday start -> contracted end)
                    } else if ($value->getEmployeeContractedHoursValidFrom() <= $holiday_year_start_date) {

                        //Yes, so start of hours should be period start date.
                        $duration = $holiday_year_start_date->diff($value->getEmployeeContractedHoursValidUntil());
                        $weighted = (($duration->days + 1) / $holiday_year_days);

                        $weighted_hours = $weighted * $value->getEmployeeContractedHours();

                        array_push($weighted_hours_array, $weighted_hours);

                       

                    }

                
                //If contracted hours start before holiday year end date & contracted hours end is after holiday year end date
                } else if ($value->getEmployeeContractedHoursValidFrom() < $holiday_year_end_date && $value->getEmployeeContractedHoursValidUntil() > $holiday_year_end_date)  {
                    
                    //If contracted hours start is before or equal to the holiday start date (holiday start -> holiday end)
                    if ($value->getEmployeeContractedHoursValidFrom() <=  $holiday_year_start_date) {
                        
                        $weighted = 1;

                        $weighted_hours = $weighted * $value->getEmployeeContractedHours();

                        array_push($weighted_hours_array, $weighted_hours);

                        
                    //If contracted hours start is after the holiday start date (contracted start -> holiday end)
                    } else if ($value->getEmployeeContractedHoursValidFrom() > $holiday_year_start_date) {

                        //Start of hours should be period start date.
                        $duration = $holiday_year_end_date->diff($value->getEmployeeContractedHoursValidFrom());
                        $weighted = (($duration->days + 1) / $holiday_year_days);


                        $weighted_hours = $weighted * $value->getEmployeeContractedHours();

                        array_push($weighted_hours_array, $weighted_hours);
                         

                    }
                
                //If contracted hours end is not set.
                } else if($value->getEmployeeContractedHoursValidUntil() == NULL) {

                    //If contracted hours start is before or same as holiday start date (holiday start date -> today)
                    if ($value->getEmployeeContractedHoursValidFrom() <=  $holiday_year_start_date) {
                       
                        //Whole period 
                        $duration = $holiday_year_start_date->diff($holiday_calc_end_date);
                        $weighted = (($duration->days + 1) / $holiday_year_days);

                        $weighted_hours = $weighted * $value->getEmployeeContractedHours();
            
                        array_push($weighted_hours_array, $weighted_hours);

                        
                    //If contracted hours start is after holiday year start date (contracted hours start -> today)
                    } else if ($value->getEmployeeContractedHoursValidFrom() > $holiday_year_start_date) {

                        //Start of hours should be period start date.
                        $duration = $value->getEmployeeContractedHoursValidFrom()->diff($holiday_calc_end_date);
                        $weighted = (($duration->days + 1) / $holiday_year_days);

                        $weighted_hours = $weighted * $value->getEmployeeContractedHours();
                        

                        array_push($weighted_hours_array, $weighted_hours);
                        

                    }
                }
            }
        }

        

        //ADD PROJECTED HOURS BELOW TO SHOW EXPECTED ANNUAL HOLIDAY (THIS FUNCTION WILL SHOW EARNED SO FAR THIS YEAR).
        
        $holiday_total_hours = 0;
        //Add all the hours and multiply by 5.6.
        foreach ($weighted_hours_array as $value) {
            $holiday_total_hours = $holiday_total_hours + $value;

        }

        //$holiday_total_hours = $holiday_total_hours / count($weighted_hours_array);

        $holiday_total_hours = $holiday_total_hours * 5.6;



       
        return number_format($holiday_total_hours, 2, '.', ' ');
    }
  

    /**
     * Get projected_holiday_hours
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProjectedHolidayHours()
    {
        if($this->getCurrentContractedHours() != null) {
            //Find the year that todays date belongs to it (now).
            $current_date = new \DateTime('now');
            $current_year = $current_date->format('Y');

            //Calculate the start and end date of the current year.
            //Check if the current date is before new year starts.
            if (strtotime($current_date->format('Y-m-d')) <  strtotime($current_year . "-11-01")) {
                $holiday_year_start_date = new \DateTime(($current_year - 1) . "-11-01");
                $holiday_year_end_date = new \DateTime($current_year . "-10-31");
            } else {
                $holiday_year_start_date = new \DateTime(($current_year) . "-11-01");
                $holiday_year_end_date = new \DateTime(($current_year + 1) . "-10-31");
            }

            //Set up the number of days in this holiday year for division
            $holiday_year_days = ($holiday_year_start_date->diff($holiday_year_end_date)->days) +1;

            //Duration - days from today to last day of holiday year
            $duration = $current_date->diff($holiday_year_end_date);
            

            $weighted = (($duration->days +1) / $holiday_year_days);

            $weighted_hours = $weighted * $this->getCurrentContractedHours()->getEmployeeContractedHours();

            $remaining_holiday = $weighted_hours * 5.6;


            return ceil($this->getWeightedHolidayHoursEarnedToDate() + $remaining_holiday) ;
        } else {
            return false;
        }
    }


    /**
     * Get taken_holiday_hours
     *
     * @return string 
     */
    public function getTakenHolidayHours()
    {
        //Find the year that todays date belongs to it (now).
        $current_date = new \DateTime('now');
        $current_year = $current_date->format('Y');

        //Calculate the start and end date of the current year.
        //Check if the current fate is before new year starts.
        if (strtotime($current_date->format('Y-m-d')) <  strtotime($current_year . "-11-01")) {
            $holiday_year_start_date = new \DateTime(($current_year - 1) . "-11-01");
            $holiday_year_end_date = new \DateTime($current_year . "-10-31");
        } else {
            $holiday_year_start_date = new \DateTime(($current_year) . "-11-01");
            $holiday_year_end_date = new \DateTime(($current_year + 1) . "-11-01");
        }

       
        $total = "0.00";
        foreach ($this->getEmployeePayment() as $key => $value) {
            if ($value->getEmployeePaymentWeekEndDate() <= $holiday_year_end_date &&  $value->getEmployeePaymentWeekEndDate() >= $holiday_year_start_date) {
                if ($value->getIsEmployeePaymentHoliday()) {
                    
                    $total = $total + number_format($value->getEmployeePaymentTotalHours(), 2, '.', ' ');
                    
                }
            }
            
        }
        
        return $total; 
    }

    /**
     * Get remaining_holiday_hours_earned
     *
     * @return string 
     */
    public function getRemainingHolidayHoursEarned()
    {
        $total = $this->getWeightedHolidayHoursEarnedToDate() - $this->getTakenHolidayHours() ;

        
        return $total;
    }

    /**
     * Get remaining_holiday_hours_projected
     *
     * @return string 
     */
    public function getRemainingHolidayHoursProjected()
    {
        if($this->getProjectedHolidayHours() == true) {
            $total = $this->getProjectedHolidayHours() - $this->getTakenHolidayHours() ;
            
            
            return $total;
        } else {
            return "Unknown";
        }
    }

    /**
     * Get period_hours
     *
     * @return string 
     */
    public function getPeriodStandardRateHours()
    {
        $total = "0.00";
        foreach ($this->getEmployeePayment() as $key => $value) {
            if (!$value->getIsEmployeePaymentHoliday() && $value->getEmployeeWageRate() == NULL) {
                $total = $total + $value->getEmployeePaymentTotalHours();
            }
        }
        
        return $total;
    }

    /**
     * Get period_hours
     *
     * @return string 
     */
    public function getPeriodAlternateRateHours()
    {
        $total = "0.00";
        foreach ($this->getEmployeePayment() as $key => $value) {
            if (!$value->getIsEmployeePaymentHoliday() && $value->getEmployeeWageRate() != NULL) {
                $total = $total + $value->getEmployeePaymentTotalHours();
            }
        }
        
        return $total;
    }

     /**
     * Get period_wage_payment
     *
     * @return string 
     */
    public function getPeriodWagePayment()
    {
        $total = "0.00";
        foreach ($this->getEmployeePayment() as $key => $value) {
            if (!$value->getIsEmployeePaymentHoliday()) {
                $total = $total + $value->getEmployeePaymentTotal();
            }
        }
        
        return $total;
    }

    /**
     * Get period_holiday_hours
     *
     * @return string 
     */
    public function getPeriodStandardRateHolidayHours()
    {
        $total = "0.00";
        foreach ($this->getEmployeePayment() as $key => $value) {
            if ($value->getIsEmployeePaymentHoliday() && $value->getEmployeeWageRate() == NULL) {
                $total = $total + $value->getEmployeePaymentTotalHours();
            }
        }
        
        return $total;
    }

    /**
     * Get period_holiday_hours
     *
     * @return string 
     */
    public function getPeriodAlternateRateHolidayHours()
    {
        $total = "0.00";
        foreach ($this->getEmployeePayment() as $key => $value) {
            if ($value->getIsEmployeePaymentHoliday() && $value->getEmployeeWageRate() != NULL) {
                $total = $total + $value->getEmployeePaymentTotalHours();
            }
        }
        
        return $total;
    }

    /**
     * Get period_holiday_payment
     *
     * @return string 
     */
    public function getPeriodHolidayPayment()
    {
        $total = "0.00";
        foreach ($this->getEmployeePayment() as $key => $value) {
            if ($value->getIsEmployeePaymentHoliday()) {
                $total = $total + $value->getEmployeePaymentTotal();
            }
        }
        
        return $total;
    }

    /**
     * Get period_alternate_rate_holiday_payment
     *
     * @return string 
     */
    public function getPeriodAlternateRateHolidayPayment()
    {
        $total = "0.00";
        foreach ($this->getEmployeePayment() as $key => $value) {
            if ($value->getIsEmployeePaymentHoliday() && $value->getEmployeeWageRate() != NULL ) {
                $total = $total + $value->getEmployeePaymentTotal();
            }
        }
        
        return $total;
    }

    /**
     * Get period_alternate_rate_payment
     *
     * @return string 
     */
    public function getPeriodAlternateRatePayment()
    {
        $total = "0.00";
        foreach ($this->getEmployeePayment() as $key => $value) {
            if (!$value->getIsEmployeePaymentHoliday() && $value->getEmployeeWageRate() != NULL ) {
                $total = $total + $value->getEmployeePaymentTotal();
            }
        }
        
        return $total;
    }

    /**
     * Get period_statutory_payment
     *
     * @return string 
     */
    public function getPeriodStatutoryPayment()
    {
        $total = "0.00";
        foreach ($this->getEmployeeStatutoryPayment() as $key => $value) {
            
            $total = $total + $value->getEmployeeStatutoryPaymentValue();
            
        }
        
        return $total;
    }

}