<?php
// src/MilesApart/AdminBundle/Entity/EmployeeHoliday.php -- Defines the customer type object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\EmployeeHolidayRepository")
 * @ORM\Table(name="employee_holiday")
 * @ORM\HasLifecycleCallbacks()
 */

class EmployeeHoliday
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
     * @ORM\ManyToOne(targetEntity="Employee", inversedBy="employee_holiday")
     * @ORM\JoinTable(name="employee")
     * @ORM\JoinColumn(name="employee_id", referencedColumnName="id")
     */
    protected $employee;

    /**
     * @ORM\Column(type="date", unique=false, nullable=false)
     */
    protected $employee_holiday_start_date;

    /**
     * @ORM\Column(type="date", unique=false, nullable=false)
     */
    protected $employee_holiday_end_date;

    /**
     * @ORM\Column(type="date", unique=false, nullable=false)
     */
    protected $employee_holiday_date_created;

    /**
     * @ORM\Column(type="date", unique=false, nullable=false)
     */
    protected $employee_holiday_date_modified;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $employee_holiday_authorised;

    /**
     * @ORM\ManyToOne(targetEntity="AdminUser", inversedBy="employee_holiday")
     * @ORM\JoinTable(name="admin_user")
     * @ORM\JoinColumn(name="employee_holiday_authorised_by_id", referencedColumnName="id")
     */
    protected $employee_holiday_authorised_by;
   


    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        $this->setEmployeeHolidayDateModified(new \DateTime(date('Y-m-d H:i:s')));

        if($this->getEmployeeHolidayDateCreated() == null)
        {
            $this->setEmployeeHolidayDateCreated(new \DateTime(date('Y-m-d H:i:s')));
        }
    }



    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {

        //Employee holiday start date
        $metadata->addPropertyConstraint('employee_holiday_start_date', new Assert\NotBlank());
        $metadata->addPropertyConstraint('employee_holiday_start_date', new Assert\Date());

        //Employee holiday end date
        $metadata->addPropertyConstraint('employee_holiday_end_date', new Assert\NotBlank());
        $metadata->addPropertyConstraint('employee_holiday_end_date', new Assert\Date());

        
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
     * Set employee_holiday_start_date
     *
     * @param \DateTime $employeeHolidayStartDate
     * @return EmployeeHoliday
     */
    public function setEmployeeHolidayStartDate($employeeHolidayStartDate)
    {
        $this->employee_holiday_start_date = $employeeHolidayStartDate;
    
        return $this;
    }

    /**
     * Get employee_holiday_start_date
     *
     * @return \DateTime 
     */
    public function getEmployeeHolidayStartDate()
    {
        return $this->employee_holiday_start_date;
    }

    /**
     * Set employee_holiday_end_date
     *
     * @param \DateTime $employeeHolidayEndDate
     * @return EmployeeHoliday
     */
    public function setEmployeeHolidayEndDate($employeeHolidayEndDate)
    {
        $this->employee_holiday_end_date = $employeeHolidayEndDate;
    
        return $this;
    }

    /**
     * Get employee_holiday_end_date
     *
     * @return \DateTime 
     */
    public function getEmployeeHolidayEndDate()
    {
        return $this->employee_holiday_end_date;
    }

    /**
     * Set employee_holiday_date_created
     *
     * @param \DateTime $employeeHolidayDateCreated
     * @return EmployeeHoliday
     */
    public function setEmployeeHolidayDateCreated($employeeHolidayDateCreated)
    {
        $this->employee_holiday_date_created = $employeeHolidayDateCreated;
    
        return $this;
    }

    /**
     * Get employee_holiday_date_created
     *
     * @return \DateTime 
     */
    public function getEmployeeHolidayDateCreated()
    {
        return $this->employee_holiday_date_created;
    }

    /**
     * Set employee_holiday_date_modified
     *
     * @param \DateTime $employeeHolidayDateModified
     * @return EmployeeHoliday
     */
    public function setEmployeeHolidayDateModified($employeeHolidayDateModified)
    {
        $this->employee_holiday_date_modified = $employeeHolidayDateModified;
    
        return $this;
    }

    /**
     * Get employee_holiday_date_modified
     *
     * @return \DateTime 
     */
    public function getEmployeeHolidayDateModified()
    {
        return $this->employee_holiday_date_modified;
    }

    /**
     * Set employee_holiday_authorised
     *
     * @param boolean $employeeHolidayAuthorised
     * @return EmployeeHoliday
     */
    public function setEmployeeHolidayAuthorised($employeeHolidayAuthorised)
    {
        $this->employee_holiday_authorised = $employeeHolidayAuthorised;
    
        return $this;
    }

    /**
     * Get employee_holiday_authorised
     *
     * @return boolean 
     */
    public function getEmployeeHolidayAuthorised()
    {
        return $this->employee_holiday_authorised;
    }

    /**
     * Set employee
     *
     * @param \MilesApart\AdminBundle\Entity\Employee $employee
     * @return EmployeeHoliday
     */
    public function setEmployee(\MilesApart\AdminBundle\Entity\Employee $employee = null)
    {
        $this->employee = $employee;
    
        return $this;
    }

    /**
     * Get employee
     *
     * @return \MilesApart\AdminBundle\Entity\Employee 
     */
    public function getEmployee()
    {
        return $this->employee;
    }

    /**
     * Set employee_holiday_authorised_by
     *
     * @param \MilesApart\AdminBundle\Entity\AdminUser $employeeHolidayAuthorisedBy
     * @return EmployeeHoliday
     */
    public function setEmployeeHolidayAuthorisedBy(\MilesApart\AdminBundle\Entity\AdminUser $employeeHolidayAuthorisedBy = null)
    {
        $this->employee_holiday_authorised_by = $employeeHolidayAuthorisedBy;
    
        return $this;
    }

    /**
     * Get employee_holiday_authorised_by
     *
     * @return \MilesApart\AdminBundle\Entity\AdminUser 
     */
    public function getEmployeeHolidayAuthorisedBy()
    {
        return $this->employee_holiday_authorised_by;
    }
}