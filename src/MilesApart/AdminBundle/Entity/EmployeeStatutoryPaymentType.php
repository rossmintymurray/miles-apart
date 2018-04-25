<?php
// src/MilesApart/AdminBundle/Entity/EmployeeStatutoryPaymentType.php -- Defines the admin user object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;



use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\EmployeeStatutoryPaymentTypeRepository")
 * @ORM\Table(name="employee_statutory_payment_type")
 * @ORM\HasLifecycleCallbacks()
 */

class EmployeeStatutoryPaymentType
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
    protected $employee_statutory_payment_type_name;

    /**
     * @ORM\OneToMany(targetEntity="EmployeeStatutoryPayment", mappedBy="employee_statutory_payment_type")
     */
    protected $employee_statutory_payment;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->employee_statutory_payment = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set employee_statutory_payment_type_name
     *
     * @param string $employeeStatutoryPaymentTypeName
     * @return EmployeeStatutoryPaymentType
     */
    public function setEmployeeStatutoryPaymentTypeName($employeeStatutoryPaymentTypeName)
    {
        $this->employee_statutory_payment_type_name = $employeeStatutoryPaymentTypeName;
    
        return $this;
    }

    /**
     * Get employee_statutory_payment_type_name
     *
     * @return string 
     */
    public function getEmployeeStatutoryPaymentTypeName()
    {
        return $this->employee_statutory_payment_type_name;
    }

    /**
     * Add employee_statutory_payment
     *
     * @param \MilesApart\AdminBundle\Entity\EmployeeStatutoryPayment $employeeStatutoryPayment
     * @return EmployeeStatutoryPaymentType
     */
    public function addEmployeeStatutoryPayment(\MilesApart\AdminBundle\Entity\EmployeeStatutoryPayment $employeeStatutoryPayment)
    {
        $this->employee_statutory_payment[] = $employeeStatutoryPayment;
    
        return $this;
    }

    /**
     * Remove employee_statutory_payment
     *
     * @param \MilesApart\AdminBundle\Entity\EmployeeStatutoryPayment $employeeStatutoryPayment
     */
    public function removeEmployeeStatutoryPayment(\MilesApart\AdminBundle\Entity\EmployeeStatutoryPayment $employeeStatutoryPayment)
    {
        $this->employee_statutory_payment->removeElement($employeeStatutoryPayment);
    }

    /**
     * Get employee_statutory_payment
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEmployeeStatutoryPayment()
    {
        return $this->employee_statutory_payment;
    }
}