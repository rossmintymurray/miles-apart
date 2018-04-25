<?php
// src/MilesApart/AdminBundle/Entity/WorkWeek.php -- Defines the work week object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\WorkWeekRepository")
 * @ORM\Table(name="work_week")
 * @ORM\HasLifecycleCallbacks()
 */

class WorkWeek
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
     * @ORM\Column(type="integer", length=2, unique=false, nullable=false)
     */
    protected $work_week_number;

    /**
     * @ORM\Column(type="date", unique=true, nullable=false)
     */
    protected $work_week_start_date;

    /**
     * @ORM\Column(type="date", unique=true, nullable=false)
     */
    protected $work_week_end_date;

    /**
     * @ORM\OneToMany(targetEntity="EmployeeWorkWeek", mappedBy="work_week")
     */
    protected $employee_work_week;



    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //Work week number
        $metadata->addPropertyConstraint('work_week_number', new Assert\NotBlank());
        $metadata->addPropertyConstraint('work_week_number', new Assert\Length(array(
            'min'        => 1,
            'max'        => 52,
            'minMessage' => 'The work week number must be at least {{ limit }} characters length',
            'maxMessage' => 'The work week number cannot be longer than {{ limit }} characters length',
        )));

        //Work week start date
        $metadata->addPropertyConstraint('work_week_start_date', new Assert\Date());

        //Work week end date
        $metadata->addPropertyConstraint('work_week_end_date', new Assert\Date());
    }



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->employee_work_week = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set work_week_number
     *
     * @param integer $workWeekNumber
     * @return WorkWeek
     */
    public function setWorkWeekNumber($workWeekNumber)
    {
        $this->work_week_number = $workWeekNumber;
    
        return $this;
    }

    /**
     * Get work_week_number
     *
     * @return integer 
     */
    public function getWorkWeekNumber()
    {
        return $this->work_week_number;
    }

    /**
     * Set work_week_start_date
     *
     * @param \DateTime $workWeekStartDate
     * @return WorkWeek
     */
    public function setWorkWeekStartDate($workWeekStartDate)
    {
        $this->work_week_start_date = $workWeekStartDate;
    
        return $this;
    }

    /**
     * Get work_week_start_date
     *
     * @return \DateTime 
     */
    public function getWorkWeekStartDate()
    {
        return $this->work_week_start_date;
    }

    /**
     * Set work_week_end_date
     *
     * @param \DateTime $workWeekEndDate
     * @return WorkWeek
     */
    public function setWorkWeekEndDate($workWeekEndDate)
    {
        $this->work_week_end_date = $workWeekEndDate;
    
        return $this;
    }

    /**
     * Get work_week_end_date
     *
     * @return \DateTime 
     */
    public function getWorkWeekEndDate()
    {
        return $this->work_week_end_date;
    }

    /**
     * Add employee_work_week
     *
     * @param \MilesApart\AdminBundle\Entity\EmployeeWorkWeek $employeeWorkWeek
     * @return WorkWeek
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
}