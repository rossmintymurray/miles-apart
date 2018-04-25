<?php
// src/MilesApart/AdminBundle/Entity/EmployeeJobRoleEmployee.php -- Defines the employee work week object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\EmployeeJobRoleEmployeeRepository")
 * @ORM\Table(name="employee_job_role_employee")
 * @ORM\HasLifecycleCallbacks()
 */

class EmployeeJobRoleEmployee
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
     * @ORM\ManyToOne(targetEntity="EmployeeJobRole", inversedBy="employee_job_role_employee", cascade={"persist"})
     * @ORM\JoinTable(name="employee_job_role")
     * @ORM\JoinColumn(name="employee_job_role_id", referencedColumnName="id")
     */
    protected $employee_job_role;

    /**
     * @ORM\ManyToOne(targetEntity="Employee", inversedBy="employee_job_role_employee")
     * @ORM\JoinTable(name="employee")
     * @ORM\JoinColumn(name="employee_id", referencedColumnName="id")
     */
    protected $employee;

    /**
     * @ORM\Column(type="date", unique=false, nullable=false)
     */
    protected $employee_job_role_date_commenced;



    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {

        //Employee job role date commenced
        $metadata->addPropertyConstraint('employee_job_role_date_commenced', new Assert\NotBlank());
        $metadata->addPropertyConstraint('employee_job_role_date_commenced', new Assert\Date());
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
     * Set employee_job_role_date_commenced
     *
     * @param \DateTime $employeeJobRoleDateCommenced
     * @return EmployeeJobRoleEmployee
     */
    public function setEmployeeJobRoleDateCommenced($employeeJobRoleDateCommenced)
    {
        $this->employee_job_role_date_commenced = $employeeJobRoleDateCommenced;
    
        return $this;
    }

    /**
     * Get employee_job_role_date_commenced
     *
     * @return \DateTime 
     */
    public function getEmployeeJobRoleDateCommenced()
    {
        return $this->employee_job_role_date_commenced;
    }

    /**
     * Set employee_job_role
     *
     * @param \MilesApart\AdminBundle\Entity\EmployeeJobRole $employeeJobRole
     * @return EmployeeJobRoleEmployee
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
     * Set employee
     *
     * @param \MilesApart\AdminBundle\Entity\Employee $employee
     * @return EmployeeJobRoleEmployee
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