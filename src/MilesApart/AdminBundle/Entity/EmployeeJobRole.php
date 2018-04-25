<?php
// src/MilesApart/AdminBundle/Entity/EmployeeJobRole.php -- Defines the employee work week object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\EmployeeJobRoleRepository")
 * @ORM\Table(name="employee_job_role")
 * @ORM\HasLifecycleCallbacks()
 */

class EmployeeJobRole
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
    protected $employee_job_role_title;

    /**
     * @ORM\Column(type="string", length=2000, unique=false, nullable=false)
     */
    protected $employee_job_role_overview;

    /**
     * @ORM\OneToMany(targetEntity="EmployeeJobRoleEmployee", mappedBy="employee_job_role")
     */
    protected $employee_job_role_employee;

    /**
     * @ORM\OneToMany(targetEntity="EmployeeWageRateJobRole", mappedBy="employee_job_role")
     */
    protected $employee_wage_rate_job_role;




    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //Employee job role title
        $metadata->addPropertyConstraint('employee_job_role_title', new Assert\NotBlank());
        $metadata->addPropertyConstraint('employee_job_role_title', new Assert\Length(array(
            'min'        => 4,
            'max'        => 100,
            'minMessage' => 'The employee job role title must be at least {{ limit }} characters length',
            'maxMessage' => 'The employee job role title cannot be longer than {{ limit }} characters length',
        )));

        //Employee job role overview
        $metadata->addPropertyConstraint('employee_job_role_overview', new Assert\NotBlank());
        $metadata->addPropertyConstraint('employee_job_role_overview', new Assert\Length(array(
            'min'        => 4,
            'max'        => 2000,
            'minMessage' => 'Your employee job role overview must be at least {{ limit }} characters length',
            'maxMessage' => 'Your employee job role overview cannot be longer than {{ limit }} characters length',
        )));
    }




    /**
     * Constructor
     */
    public function __construct()
    {
        $this->employee_job_role_employee = new \Doctrine\Common\Collections\ArrayCollection();
        $this->employee_wage_rate_job_role = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set employee_job_role_title
     *
     * @param string $employeeJobRoleTitle
     * @return EmployeeJobRole
     */
    public function setEmployeeJobRoleTitle($employeeJobRoleTitle)
    {
        $this->employee_job_role_title = $employeeJobRoleTitle;
    
        return $this;
    }

    /**
     * Get employee_job_role_title
     *
     * @return string 
     */
    public function getEmployeeJobRoleTitle()
    {
        return $this->employee_job_role_title;
    }

    /**
     * Set employee_job_role_overview
     *
     * @param string $employeeJobRoleOverview
     * @return EmployeeJobRole
     */
    public function setEmployeeJobRoleOverview($employeeJobRoleOverview)
    {
        $this->employee_job_role_overview = $employeeJobRoleOverview;
    
        return $this;
    }

    /**
     * Get employee_job_role_overview
     *
     * @return string 
     */
    public function getEmployeeJobRoleOverview()
    {
        return $this->employee_job_role_overview;
    }

    /**
     * Add employee_wage_rate_job_role
     *
     * @param \MilesApart\AdminBundle\Entity\EmployeeWageRateJobRole $employeeWageRateJobRole
     * @return EmployeeJobRole
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
     * Get current_wage_rate_object
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCurrentWageRateObject()
    {
        
       $most_recent = 0;
       
       $date = null;
       if (count($this->getEmployeeWageRateJobRole()) > 0) {
            foreach($this->getEmployeeWageRateJobRole() as $key => $value) {
                
                $date = $value->getEmployeeWageRateDateCommenced();
                $curDate = strtotime($date->format('Y-m-d H:i:s'));
                if ($curDate > $most_recent) {
                    $most_recent = $curDate;
                    $most_recent_wage_rate = $value->getEmployeeWageRate();
                }
            } 
        } else {
            $most_recent_wage_rate = "Not set";
        }

        
        if ($date != null) {
            
            return $most_recent_wage_rate;
        }
    }

    /**
     * Get current_wage_rate
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCurrentWageRate()
    {
        
       $most_recent = 0;
       $most_recent_wage_rate = "1";
       $date = null;
       if (count($this->getEmployeeWageRateJobRole()) > 0) {
            foreach($this->getEmployeeWageRateJobRole() as $key => $value) {
                $most_recent_wage_rate .= "-1";
                $date = $value->getEmployeeWageRateDateCommenced();
                $curDate = strtotime($date->format('Y-m-d H:i:s'));
                if ($curDate > $most_recent) {
                    $most_recent = $curDate;
                    $most_recent_wage_rate = $value->getEmployeeWageRate()->getEmployeeWageRateHourlyRate();
                }
            } 
        } else {
            $most_recent_wage_rate = "Not set";
        }

        
        if ($date != null) {
            
            return $most_recent_wage_rate;
        }
    }

    /**
     * Get wage_rate_by_date
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getWageRateByDate($payment_date)
    {
        
        //Set most recent date to zero
        $most_recent_date = 0;
        
        $most_recent_wage_rate = "1";
       
        if (count($this->getEmployeeWageRateJobRole()) > 0) {
            foreach($this->getEmployeeWageRateJobRole() as $key => $value) {
                //$most_recent_job_role .= "-1";
                $commenced_date = $value->getEmployeeWageRateDateCommenced();
                
                //$curDate = strtotime($commenced_date->format('Y-m-d H:i:s'));
                //$payment_date = strtotime($payment_date->format('Y-m-d H:i:s'));

                if ($commenced_date < $payment_date) {
                    //Check if the job role date is before the payment date.
                    
                    $most_recent_wage_rate = $value->getEmployeeWageRate()->getEmployeeWageRateHourlyRate();
                }
            } 
        } else {
            $most_recent_wage_rate = "Not set";
        }

        
        if ($payment_date != null) {
            
            return $most_recent_wage_rate;
        }
    }
}