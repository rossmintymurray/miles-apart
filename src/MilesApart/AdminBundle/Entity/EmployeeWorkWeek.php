<?php
// src/MilesApart/AdminBundle/Entity/EmployeeWorkWeek.php -- Defines the employee work week object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\EmployeeWorkWeekRepository")
 * @ORM\Table(name="employee_work_week")
 * @ORM\HasLifecycleCallbacks()
 */

class EmployeeWorkWeek
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
     * @ORM\ManyToOne(targetEntity="WorkWeek", inversedBy="employee_work_week")
     * @ORM\JoinTable(name="work_week")
     * @ORM\JoinColumn(name="work_week_id", referencedColumnName="id")
     */
    protected $work_week;

    /**
     * @ORM\ManyToOne(targetEntity="Employee", inversedBy="employee_work_week")
     * @ORM\JoinTable(name="employee")
     * @ORM\JoinColumn(name="employee_id", referencedColumnName="id")
     */
    protected $employee;

    /**
     * @ORM\Column(type="integer", length=2, unique=false, nullable=false)
     */
    protected $employee_hours_worked;

    /**
     * @ORM\Column(type="integer", length=2, unique=false, nullable=false)
     */
    protected $employee_minutes_worked;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $employee_work_week_date_created;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $employee_work_week_date_modified;


    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        $this->setEmployeeWorkWeekDateModified(new \DateTime(date('Y-m-d H:i:s')));

        if($this->getEmployeeWorkWeekDateCreated() == null)
        {
            $this->setEmployeeWorkWeekDateCreated(new \DateTime(date('Y-m-d H:i:s')));
        }
    }



    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
         //Work week
        $metadata->addPropertyConstraint('work_week', new Assert\NotBlank());
        $metadata->addPropertyConstraint('work_week', new Assert\Choice(array(
            'callback' => 'getWorkWeek',
        )));

        //Employee
        $metadata->addPropertyConstraint('employee', new Assert\NotBlank());
        $metadata->addPropertyConstraint('employee', new Assert\Choice(array(
            'callback' => 'getEmployee',
        )));

        //Employee hours worked
        $metadata->addPropertyConstraint('employee_hours_worked', new Assert\NotBlank());
        $metadata->addPropertyConstraint('employee_hours_worked', new Assert\Range(array(
            'min'        => 1,
            'max'        => 60,
            'minMessage' => 'The employee must work a minimum of {{ limit }} hour',
            'maxMessage' => 'The employee cannot work more than {{ limit }} hours per week',
        )));

        //Employee minutes worked
        $metadata->addPropertyConstraint('employee_minutes_worked', new Assert\NotBlank());
        $metadata->addPropertyConstraint('employee_minutes_worked', new Assert\Range(array(
            'min'        => 0,
            'max'        => 59,
            'minMessage' => 'The employee must work a minimum of {{ limit }} minute',
            'maxMessage' => 'Your username cannot be longer than {{ limit }} minutes, please increase the hours',
        )));
    }
    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->employee_payement = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set employee_hours_worked
     *
     * @param integer $employeeHoursWorked
     * @return EmployeeWorkWeek
     */
    public function setEmployeeHoursWorked($employeeHoursWorked)
    {
        $this->employee_hours_worked = $employeeHoursWorked;
    
        return $this;
    }

    /**
     * Get employee_hours_worked
     *
     * @return integer 
     */
    public function getEmployeeHoursWorked()
    {
        return $this->employee_hours_worked;
    }

    /**
     * Set employee_minutes_worked
     *
     * @param integer $employeeMinutesWorked
     * @return EmployeeWorkWeek
     */
    public function setEmployeeMinutesWorked($employeeMinutesWorked)
    {
        $this->employee_minutes_worked = $employeeMinutesWorked;
    
        return $this;
    }

    /**
     * Get employee_minutes_worked
     *
     * @return integer 
     */
    public function getEmployeeMinutesWorked()
    {
        return $this->employee_minutes_worked;
    }

    /**
     * Set employee_work_week_date_created
     *
     * @param \DateTime $employeeWorkWeekDateCreated
     * @return EmployeeWorkWeek
     */
    public function setEmployeeWorkWeekDateCreated($employeeWorkWeekDateCreated)
    {
        $this->employee_work_week_date_created = $employeeWorkWeekDateCreated;
    
        return $this;
    }

    /**
     * Get employee_work_week_date_created
     *
     * @return \DateTime 
     */
    public function getEmployeeWorkWeekDateCreated()
    {
        return $this->employee_work_week_date_created;
    }

    /**
     * Set employee_work_week_date_modified
     *
     * @param \DateTime $employeeWorkWeekDateModified
     * @return EmployeeWorkWeek
     */
    public function setEmployeeWorkWeekDateModified($employeeWorkWeekDateModified)
    {
        $this->employee_work_week_date_modified = $employeeWorkWeekDateModified;
    
        return $this;
    }

    /**
     * Get employee_work_week_date_modified
     *
     * @return \DateTime 
     */
    public function getEmployeeWorkWeekDateModified()
    {
        return $this->employee_work_week_date_modified;
    }

    /**
     * Set work_week
     *
     * @param \MilesApart\AdminBundle\Entity\WorkWeek $workWeek
     * @return EmployeeWorkWeek
     */
    public function setWorkWeek(\MilesApart\AdminBundle\Entity\WorkWeek $workWeek = null)
    {
        $this->work_week = $workWeek;
    
        return $this;
    }

    /**
     * Get work_week
     *
     * @return \MilesApart\AdminBundle\Entity\WorkWeek 
     */
    public function getWorkWeek()
    {
        return $this->work_week;
    }

    /**
     * Set employee
     *
     * @param \MilesApart\AdminBundle\Entity\Employee $employee
     * @return EmployeeWorkWeek
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

}