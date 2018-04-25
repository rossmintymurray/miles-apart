<?php
// src/MilesApart/AdminBundle/Entity/EmployeeStatutoryPayment.php -- Defines the admin user object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;



use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\EmployeeStatutoryPaymentRepository")
 * @ORM\Table(name="employee_statutory_payment")
 * @ORM\HasLifecycleCallbacks()
 */

class EmployeeStatutoryPayment
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
     * @ORM\ManyToOne(targetEntity="EmployeeStatutoryPaymentType", inversedBy="employee_statutory_payment")
     * @ORM\JoinTable(name="employee_statutory_payment_type")
     * @ORM\JoinColumn(name="employee_statutory_payment_type_id", referencedColumnName="id")
     */
    protected $employee_statutory_payment_type;

    /**
     * @ORM\ManyToOne(targetEntity="Employee", inversedBy="employee_statutory_payment")
     * @ORM\JoinTable(name="employee")
     * @ORM\JoinColumn(name="employee_id", referencedColumnName="id")
     */
    protected $employee;

    /**
     * @ORM\ManyToOne(targetEntity="DailyTakeBusinessPremises", inversedBy="employee_statutory_payment", cascade={"persist"})
     * @ORM\JoinTable(name="daily_take_business_premises")
     * @ORM\JoinColumn(name="daily_take_business_premises_id", referencedColumnName="id")
     */
    protected $daily_take_business_premises;

    /**
     * @ORM\Column(type="date", unique=false, nullable=true)
     */
    protected $employee_statutory_payment_week_end_date;

    /**
     * @ORM\Column(type="decimal", precision=6, scale=2, nullable=false)
     */
    protected $employee_statutory_payment_value;

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
     * Set employee_statutory_payment_week_end_date
     *
     * @param \DateTime $employeeStatutoryPaymentWeekEndDate
     * @return EmployeeStatutoryPayment
     */
    public function setEmployeeStatutoryPaymentWeekEndDate($employeeStatutoryPaymentWeekEndDate)
    {
        $this->employee_statutory_payment_week_end_date = $employeeStatutoryPaymentWeekEndDate;
    
        return $this;
    }

    /**
     * Get employee_statutory_payment_week_end_date
     *
     * @return \DateTime 
     */
    public function getEmployeeStatutoryPaymentWeekEndDate()
    {
        return $this->employee_statutory_payment_week_end_date;
    }

    /**
     * Set employee_statutory_payment_value
     *
     * @param string $employeeStatutoryPaymentValue
     * @return EmployeeStatutoryPayment
     */
    public function setEmployeeStatutoryPaymentValue($employeeStatutoryPaymentValue)
    {
        $this->employee_statutory_payment_value = $employeeStatutoryPaymentValue;
    
        return $this;
    }

    /**
     * Get employee_statutory_payment_value
     *
     * @return string 
     */
    public function getEmployeeStatutoryPaymentValue()
    {
        return $this->employee_statutory_payment_value;
    }

    /**
     * Set employee_statutory_payment_type
     *
     * @param \MilesApart\AdminBundle\Entity\EmployeeStatutoryPaymentType $employeeStatutoryPaymentType
     * @return EmployeeStatutoryPayment
     */
    public function setEmployeeStatutoryPaymentType(\MilesApart\AdminBundle\Entity\EmployeeStatutoryPaymentType $employeeStatutoryPaymentType = null)
    {
        $this->employee_statutory_payment_type = $employeeStatutoryPaymentType;
    
        return $this;
    }

    /**
     * Get employee_statutory_payment_type
     *
     * @return \MilesApart\AdminBundle\Entity\EmployeeStatutoryPaymentType 
     */
    public function getEmployeeStatutoryPaymentType()
    {
        return $this->employee_statutory_payment_type;
    }

    /**
     * Set employee
     *
     * @param \MilesApart\AdminBundle\Entity\Employee $employee
     * @return EmployeeStatutoryPayment
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
     * Set daily_take_business_premises
     *
     * @param \MilesApart\AdminBundle\Entity\DailyTakeBusinessPremises $dailyTakeBusinessPremises
     * @return EmployeeStatutoryPayment
     */
    public function setDailyTakeBusinessPremises(\MilesApart\AdminBundle\Entity\DailyTakeBusinessPremises $dailyTakeBusinessPremises = null)
    {
        $this->daily_take_business_premises = $dailyTakeBusinessPremises;
    
        return $this;
    }

    /**
     * Get daily_take_business_premises
     *
     * @return \MilesApart\AdminBundle\Entity\DailyTakeBusinessPremises 
     */
    public function getDailyTakeBusinessPremises()
    {
        return $this->daily_take_business_premises;
    }
}