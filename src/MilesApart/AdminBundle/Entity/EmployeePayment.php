<?php
// src/MilesApart/AdminBundle/Entity/EmployeePayment.php -- Defines the customer type object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\EmployeePaymentRepository")
 * @ORM\Table(name="employee_payment")
 * @ORM\HasLifecycleCallbacks()
 */

class EmployeePayment
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
    protected $employee_payment_total_hours;

    /**
     * @ORM\ManyToOne(targetEntity="Employee", inversedBy="employee_payment")
     * @ORM\JoinTable(name="employee")
     * @ORM\JoinColumn(name="employee_id", referencedColumnName="id")
     */
    protected $employee;

    /**
     * @ORM\ManyToOne(targetEntity="DailyTakeBusinessPremises", inversedBy="employee_payment", cascade={"persist"})
     * @ORM\JoinTable(name="daily_take_business_premises")
     * @ORM\JoinColumn(name="daily_take_business_premises_id", referencedColumnName="id")
     */
    protected $daily_take_business_premises;

    /**
     * @ORM\ManyToOne(targetEntity="EmployeeWageRate", inversedBy="employee_payment", cascade={"persist"})
     * @ORM\JoinTable(name="employee_wage_rate")
     * @ORM\JoinColumn(name="employee_wage_rate_id", referencedColumnName="id")
     */
    protected $employee_wage_rate;

    /**
     * @ORM\Column(type="date", unique=false, nullable=true)
     */
    protected $employee_payment_week_end_date;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $is_employee_payment_holiday;


    //Change EMPLOYEE PAYMENT TOTAL TO THIS
    protected $employee_payment_value;


    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        

       
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        
        $this->employee_payment_week_end_date = new \DateTime();
        
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
     * Set employee_payment_total_hours
     *
     * @param string $employeePaymentTotalHours
     * @return EmployeePayment
     */
    public function setEmployeePaymentTotalHours($employeePaymentTotalHours)
    {
        $this->employee_payment_total_hours = $employeePaymentTotalHours;
    
        return $this;
    }

    /**
     * Get employee_payment_total
     *
     * @return string 
     */
    public function getEmployeePaymentTotal()
    {
        
        //Check if employee wage rate is set.
        if($this->getEmployeeWageRate() != NULL) {
            //Rate is set so use the set rate.

            //ladybug_dump($this->employee_payment_total_hours);
            //ladybug_dump($this->getEmployeeWageRate()->getEmployeeWageRateHourlyRate());
            return round($this->employee_payment_total_hours * $this->getEmployeeWageRate()->getEmployeeWageRateHourlyRate(), 2) ;
        
        } else {
            //Rate is not set so use the default
            return round($this->employee_payment_total_hours * $this->getEmployee()->getJobRoleByDate($this->getEmployeePaymentWeekEndDate())->getWageRateByDate($this->getEmployeePaymentWeekEndDate()), 2) ;
    
        }
            
    }

    /**
     * Get employee_payment_total_display
     *
     * @return string 
     */
    public function getEmployeePaymentTotalDisplay()
    {
        if ($this->is_employee_payment_holiday == true) {
            return $this->getEmployeePaymentTotal() ." - Hol";
        } else {

            return $this->getEmployeePaymentTotal();
        }
    }

    /**
     * Get employee_payment_total_hours
     *
     * @return string 
     */
    public function getEmployeePaymentTotalHours()
    {
        return $this->employee_payment_total_hours;
    }

    /**
     * Get employee_payment_total_hours_display
     *
     * @return string 
     */
    public function getEmployeePaymentTotalHoursDisplay()
    {
        $hours = $this->employee_payment_total_hours;
        $hours = preg_replace("/\.?0*$/",'',$hours); 

        return $hours;
    }

    /**
     * Set employee_payment_week_end_date
     *
     * @param \DateTime $employeePaymentWeekEndDate
     * @return EmployeePayment
     */
    public function setEmployeePaymentWeekEndDate($employeePaymentWeekEndDate)
    {
        $this->employee_payment_week_end_date = $employeePaymentWeekEndDate;
    
        return $this;
    }

    /**
     * Get employee_payment_week_end_date
     *
     * @return \DateTime 
     */
    public function getEmployeePaymentWeekEndDate()
    {
        return $this->employee_payment_week_end_date;
    }

    /**
     * Set is_employee_payment_holiday
     *
     * @param boolean $isEmployeePaymentHoliday
     * @return EmployeePayment
     */
    public function setIsEmployeePaymentHoliday($isEmployeePaymentHoliday)
    {
        $this->is_employee_payment_holiday = $isEmployeePaymentHoliday;
    
        return $this;
    }

    /**
     * Get is_employee_payment_holiday
     *
     * @return boolean 
     */
    public function getIsEmployeePaymentHoliday()
    {
        return $this->is_employee_payment_holiday;
    }

    /**
     * Set employee
     *
     * @param \MilesApart\AdminBundle\Entity\Employee $employee
     * @return EmployeePayment
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
     * @return EmployeePayment
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

    /**
     * Set employee_wage_rate
     *
     * @param \MilesApart\AdminBundle\Entity\EmployeeWageRate $employeeWageRate
     * @return EmployeePayment
     */
    public function setEmployeeWageRate(\MilesApart\AdminBundle\Entity\EmployeeWageRate $employeeWageRate = null)
    {
        $this->employee_wage_rate = $employeeWageRate;
    
        return $this;
    }

    /**
     * Get employee_wage_rate
     *
     * @return \MilesApart\AdminBundle\Entity\EmployeeWageRate 
     */
    public function getEmployeeWageRate()
    {
        return $this->employee_wage_rate;
    }
}