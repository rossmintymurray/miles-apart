<?php
// src/MilesApart/AdminBundle/Entity/EmployeeWageRate.php -- Defines the employee work week object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\EmployeeWageRateRepository")
 * @ORM\Table(name="employee_wage_rate")
 * @ORM\HasLifecycleCallbacks()
 */

class EmployeeWageRate
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
     * @ORM\Column(type="decimal", precision=4, scale=2, nullable=false)
     */
    protected $employee_wage_rate_hourly_rate;

    /**
     * @ORM\Column(type="datetime", unique=false, nullable=false)
     */
    protected $employee_wage_rate_date_created;

    /**
     * @ORM\OneToMany(targetEntity="EmployeeWageRateJobRole", mappedBy="employee_wage_rate")
     */
    protected $employee_wage_rate_job_role;

    /**
     * @ORM\OneToMany(targetEntity="EmployeePayment", mappedBy="employee_wage_rate")
     */
    protected $employee_payment;



    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {

        if($this->getEmployeeWageRateDateCreated() == null)
        {
            $this->setEmployeeWageRateDateCreated(new \DateTime(date('Y-m-d H:i:s')));
        }
    }



    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //Employee wage rate hourly rate
        $metadata->addPropertyConstraint('employee_wage_rate_hourly_rate', new Assert\NotBlank());

        

    }


    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->employee_wage_rate_job_role = new \Doctrine\Common\Collections\ArrayCollection();
        $this->employee_payment = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set employee_wage_rate_hourly_rate
     *
     * @param float $employeeWageRateHourlyRate
     * @return EmployeeWageRate
     */
    public function setEmployeeWageRateHourlyRate($employeeWageRateHourlyRate)
    {
        $this->employee_wage_rate_hourly_rate = $employeeWageRateHourlyRate;
    
        return $this;
    }

    /**
     * Get employee_wage_rate_hourly_rate
     *
     * @return float 
     */
    public function getEmployeeWageRateHourlyRate()
    {
        return $this->employee_wage_rate_hourly_rate;
    }

    /**
     * Set employee_wage_rate_date_created
     *
     * @param \DateTime $employeeWageRateDateCreated
     * @return EmployeeWageRate
     */
    public function setEmployeeWageRateDateCreated($employeeWageRateDateCreated)
    {
        $this->employee_wage_rate_date_created = $employeeWageRateDateCreated;
    
        return $this;
    }

    /**
     * Get employee_wage_rate_date_created
     *
     * @return \DateTime 
     */
    public function getEmployeeWageRateDateCreated()
    {
        return $this->employee_wage_rate_date_created;
    }


    /**
     * Add employee_wage_rate_job_role
     *
     * @param \MilesApart\AdminBundle\Entity\EmployeeWageRateJobRole $employeeWageRateJobRole
     * @return EmployeeWageRate
     */
    public function addEmployeeWageRateJobRole(\MilesApart\AdminBundle\Entity\EmployeeWageRateJobRole $employeeWageRateJobRole)
    {
        $this->employee_wage_rate_job_role[] = $employeeWageRateJobRole;
    
        return $this;
    }

    /**
     * Remove employee_wage_rate_job_role
     *
     * @param \MilesApart\AdminBundle\Entity\EmployeeWageRateJobRole $employeeWageRateJobRole
     */
    public function removeEmployeeWageRateJobRole(\MilesApart\AdminBundle\Entity\EmployeeWageRateJobRole $employeeWageRateJobRole)
    {
        $this->employee_wage_rate_job_role->removeElement($employeeWageRateJobRole);
    }

    /**
     * Get employee_wage_rate_job_role
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEmployeeWageRateJobRole()
    {
        return $this->employee_wage_rate_job_role;
    }

    /**
     * Add employee_payment
     *
     * @param \MilesApart\AdminBundle\Entity\EmployeePayment $employeePayment
     * @return EmployeeWageRate
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
}