<?php
// src/MilesApart/AdminBundle/Entity/EmployeeWageRateJobRole.php -- Defines the employee work week object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\EmployeeWageRateJobRoleRepository")
 * @ORM\Table(name="employee_wage_rate_job_role")
 * @ORM\HasLifecycleCallbacks()
 */

class EmployeeWageRateJobRole
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
     * @ORM\ManyToOne(targetEntity="EmployeeJobRole", inversedBy="employee_wage_rate_job_role")
     * @ORM\JoinTable(name="employee_job_role")
     * @ORM\JoinColumn(name="employee_job_role_id", referencedColumnName="id")
     */
    protected $employee_job_role;

    /**
     * @ORM\ManyToOne(targetEntity="EmployeeWageRate", inversedBy="employee_wage_rate_job_role")
     * @ORM\JoinTable(name="employee_wage_rate")
     * @ORM\JoinColumn(name="employee_wage_rate_id", referencedColumnName="id")
     */
    protected $employee_wage_rate;

    /**
     * @ORM\Column(type="datetime", unique=false, nullable=false)
     */
    protected $employee_wage_rate_date_commenced;


    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {

        //Employee job role
        $metadata->addPropertyConstraint('employee_job_role', new Assert\NotBlank());
        $metadata->addPropertyConstraint('employee_job_role', new Assert\Choice(array(
            'callback' => 'getEmployeeJobRole',
        )));

        //Employee wage rate
        $metadata->addPropertyConstraint('employee_wage_rate', new Assert\NotBlank());
        $metadata->addPropertyConstraint('employee_wage_rate', new Assert\Choice(array(
            'callback' => 'getEmployeeWageRate',
        )));
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
     * Set employee_job_role
     *
     * @param \MilesApart\AdminBundle\Entity\EmployeeJobRole $employeeJobRole
     * @return EmployeeWageRateJobRole
     */
    public function setEmployeeJobRole(\MilesApart\AdminBundle\Entity\EmployeeJobRole $employeeJobRole = null)
    {
        $this->employee_job_role = $employeeJobRole;
    
        return $this;
    }

    /**
     * Get employee_job_role
     *
     * @return \MilesApart\AdminBundle\Entity\EmployeeJobRole 
     */
    public function getEmployeeJobRole()
    {
        return $this->employee_job_role;
    }

    /**
     * Set employee_wage_rate
     *
     * @param \MilesApart\AdminBundle\Entity\EmployeeWageRate $employeeWageRate
     * @return EmployeeWageRateJobRole
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

    /**
     * Set employee_wage_rate_date_commenced
     *
     * @param \DateTime $employeeWageRateDateCommenced
     * @return EmployeeWageRateJobRole
     */
    public function setEmployeeWageRateDateCommenced($employeeWageRateDateCommenced)
    {
        $this->employee_wage_rate_date_commenced = $employeeWageRateDateCommenced;
    
        return $this;
    }

    /**
     * Get employee_wage_rate_date_commenced
     *
     * @return \DateTime 
     */
    public function getEmployeeWageRateDateCommenced()
    {
        return $this->employee_wage_rate_date_commenced;
    }

}