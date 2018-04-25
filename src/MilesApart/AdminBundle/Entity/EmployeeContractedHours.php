<?php
// src/MilesApart/AdminBundle/Entity/EmployeeContractedHours.php -- Defines the customer type object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\EmployeeContractedHoursRepository")
 * @ORM\Table(name="employee_contracted_hours")
 * @ORM\HasLifecycleCallbacks()
 */

class EmployeeContractedHours
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
     * @ORM\Column(type="decimal", precision=6, scale=2, nullable=false)
     */
    protected $employee_contracted_hours;


    /**
     * @ORM\ManyToOne(targetEntity="Employee", inversedBy="employee_contracted_hours")
     * @ORM\JoinTable(name="employee")
     * @ORM\JoinColumn(name="employee_id", referencedColumnName="id")
     */
    protected $employee;

    /**
     * @ORM\ManyToOne(targetEntity="BusinessPremises", inversedBy="employee_contracted_hours")
     * @ORM\JoinTable(name="business_premises")
     * @ORM\JoinColumn(name="business_premises_id", referencedColumnName="id")
     */
    protected $business_premises;

    /**
     * @ORM\Column(type="date", unique=false, nullable=true)
     */
    protected $employee_contracted_hours_valid_from;

    /**
     * @ORM\Column(type="date", unique=false, nullable=true)
     */
    protected $employee_contracted_hours_valid_until;

    /**
     * @ORM\Column(type="datetime", unique=false, nullable=true)
     */
    protected $employee_contracted_hours_date_created;
    
    /**
     * @ORM\Column(type="datetime", unique=false, nullable=true)
     */
    protected $employee_contracted_hours_date_modified;


   /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        $this->setEmployeeContractedHoursDateModified(new \DateTime(date('Y-m-d H:i:s')));

        if($this->getEmployeeContractedHoursDateCreated() == null)
        {
            $this->setEmployeeContractedHoursDateCreated(new \DateTime(date('Y-m-d H:i:s')));
        }
    }

    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //Business Customer Representative Date Of Birth
        $metadata->addPropertyConstraint('employee_contracted_hours', new Assert\NotBlank());

       
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        
        $this->employee_contracted_hours_valid_from = new \DateTime('now');
        
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
     * Set employee_contracted_hours
     *
     * @param string $employeeContractedHours
     * @return EmployeeContractedHours
     */
    public function setEmployeeContractedHours($employeeContractedHours)
    {
        $this->employee_contracted_hours = $employeeContractedHours;
    
        return $this;
    }

    /**
     * Get employee_contracted_hours
     *
     * @return string 
     */
    public function getEmployeeContractedHours()
    {
        return $this->employee_contracted_hours;
    }

    /**
     * Set employee_contracted_hours_valid_from
     *
     * @param \DateTime $employeeContractedHoursValidFrom
     * @return EmployeeContractedHours
     */
    public function setEmployeeContractedHoursValidFrom($employeeContractedHoursValidFrom)
    {
        $this->employee_contracted_hours_valid_from = $employeeContractedHoursValidFrom;
    
        return $this;
    }

    /**
     * Get employee_contracted_hours_valid_from
     *
     * @return \DateTime 
     */
    public function getEmployeeContractedHoursValidFrom()
    {
        return $this->employee_contracted_hours_valid_from;
    }

    /**
     * Set employee_contracted_hours_valid_until
     *
     * @param \DateTime $employeeContractedHoursValidUntil
     * @return EmployeeContractedHours
     */
    public function setEmployeeContractedHoursValidUntil($employeeContractedHoursValidUntil)
    {
        $this->employee_contracted_hours_valid_until = $employeeContractedHoursValidUntil;
    
        return $this;
    }

    /**
     * Get employee_contracted_hours_valid_until
     *
     * @return \DateTime 
     */
    public function getEmployeeContractedHoursValidUntil()
    {
        return $this->employee_contracted_hours_valid_until;
    }

    /**
     * Set employee_contracted_hours_date_created
     *
     * @param \DateTime $employeeContractedHoursDateCreated
     * @return EmployeeContractedHours
     */
    public function setEmployeeContractedHoursDateCreated($employeeContractedHoursDateCreated)
    {
        $this->employee_contracted_hours_date_created = $employeeContractedHoursDateCreated;
    
        return $this;
    }

    /**
     * Get employee_contracted_hours_date_created
     *
     * @return \DateTime 
     */
    public function getEmployeeContractedHoursDateCreated()
    {
        return $this->employee_contracted_hours_date_created;
    }

    /**
     * Set employee_contracted_hours_date_modified
     *
     * @param \DateTime $employeeContractedHoursDateModified
     * @return EmployeeContractedHours
     */
    public function setEmployeeContractedHoursDateModified($employeeContractedHoursDateModified)
    {
        $this->employee_contracted_hours_date_modified = $employeeContractedHoursDateModified;
    
        return $this;
    }

    /**
     * Get employee_contracted_hours_date_modified
     *
     * @return \DateTime 
     */
    public function getEmployeeContractedHoursDateModified()
    {
        return $this->employee_contracted_hours_date_modified;
    }

    /**
     * Set employee
     *
     * @param \MilesApart\AdminBundle\Entity\Employee $employee
     * @return EmployeeContractedHours
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
     * Set business_premises
     *
     * @param \MilesApart\AdminBundle\Entity\BusinessPremises $businessPremises
     * @return EmployeeContractedHours
     */
    public function setBusinessPremises(\MilesApart\AdminBundle\Entity\BusinessPremises $businessPremises = null)
    {
        $this->business_premises = $businessPremises;
    
        return $this;
    }

    /**
     * Get business_premises
     *
     * @return \MilesApart\AdminBundle\Entity\BusinessPremises 
     */
    public function getBusinessPremises()
    {
        return $this->business_premises;
    }
}