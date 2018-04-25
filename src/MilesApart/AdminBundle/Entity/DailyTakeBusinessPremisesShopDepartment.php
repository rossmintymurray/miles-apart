<?php
// src/MilesApart/AdminBundle/Entity/DailyTakeBusinessPremisesShopDepartment.php -- Defines the customer type object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\DailyTakeBusinessPremisesShopDepartmentRepository")
 * @ORM\Table(name="daily_take_business_premises_shop_department")
 * @ORM\HasLifecycleCallbacks()
 */

class DailyTakeBusinessPremisesShopDepartment
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
    protected $shop_department_value;

    /**
     * @ORM\ManyToOne(targetEntity="ShopDepartment", inversedBy="daily_take_business_premises_shop_department")
     * @ORM\JoinTable(name="shop_department")
     * @ORM\JoinColumn(name="shop_department_id", referencedColumnName="id")
     */
    protected $shop_department;

    /**
     * @ORM\ManyToOne(targetEntity="DailyTakeBusinessPremises", inversedBy="daily_take_business_premises_shop_department", cascade={"persist"})
     * @ORM\JoinTable(name="daily_take_business_premises")
     * @ORM\JoinColumn(name="daily_take_business_premises_id", referencedColumnName="id")
     */
    protected $daily_take_business_premises;
   

    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //Business Customer Representative Date Of Birth
        $metadata->addPropertyConstraint('shop_department_value', new Assert\NotBlank());
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
     * Set shop_department_value
     *
     * @param string $shopDepartmentValue
     * @return DailyTakeBusinessPremisesShopDepartment
     */
    public function setShopDepartmentValue($shopDepartmentValue)
    {
        $this->shop_department_value = $shopDepartmentValue;
    
        return $this;
    }

    /**
     * Get shop_department_value
     *
     * @return string 
     */
    public function getShopDepartmentValue()
    {
        return $this->shop_department_value;
    }

    /**
     * Set shop_department
     *
     * @param \MilesApart\AdminBundle\Entity\ShopDepartment $shopDepartment
     * @return DailyTakeBusinessPremisesShopDepartment
     */
    public function setShopDepartment(\MilesApart\AdminBundle\Entity\ShopDepartment $shopDepartment = null)
    {
        $this->shop_department = $shopDepartment;
    
        return $this;
    }

    /**
     * Get shop_department
     *
     * @return \MilesApart\AdminBundle\Entity\ShopDepartment 
     */
    public function getShopDepartment()
    {
        return $this->shop_department;
    }

    /**
     * Set daily_take_business_premises
     *
     * @param \MilesApart\AdminBundle\Entity\DailyTakeBusinessPremises $dailyTakeBusinessPremises
     * @return DailyTakeBusinessPremisesShopDepartment
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