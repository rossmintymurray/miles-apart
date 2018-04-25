<?php
// src/MilesApart/AdminBundle/Entity/ShopDepartment.php -- Defines the customer type object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\ShopDepartmentRepository")
 * @ORM\Table(name="shop_department")
 * @ORM\HasLifecycleCallbacks()
 */

class ShopDepartment
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
     * @ORM\Column(type="string", length=100, unique=true, nullable=false)
     */
    protected $shop_department_name;

   /**
     * @ORM\OneToMany(targetEntity="DailyTakeBusinessPremisesShopDepartment", mappedBy="shop_department")
     */
    protected $daily_take_business_premises_shop_department;

    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //Business Customer Representative Date Of Birth
        $metadata->addPropertyConstraint('shop_department_name', new Assert\NotBlank());
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->daily_take_business_premises_shop_department = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set shop_department_name
     *
     * @param string $shopDepartmentName
     * @return ShopDepartment
     */
    public function setShopDepartmentName($shopDepartmentName)
    {
        $this->shop_department_name = $shopDepartmentName;
    
        return $this;
    }

    /**
     * Get shop_department_name
     *
     * @return string 
     */
    public function getShopDepartmentName()
    {
        return $this->shop_department_name;
    }

    /**
     * Add daily_take_business_premises_shop_department
     *
     * @param \MilesApart\AdminBundle\Entity\DailyTakeBusinessPremisesShopDepartment $dailyTakeBusinessPremisesShopDepartment
     * @return ShopDepartment
     */
    public function addDailyTakeBusinessPremisesShopDepartment(\MilesApart\AdminBundle\Entity\DailyTakeBusinessPremisesShopDepartment $dailyTakeBusinessPremisesShopDepartment)
    {
        $this->daily_take_business_premises_shop_department[] = $dailyTakeBusinessPremisesShopDepartment;
    
        return $this;
    }

    /**
     * Remove daily_take_business_premises_ShopDepartment
     *
     * @param \MilesApart\AdminBundle\Entity\DailyTakeBusinessPremisesShopDepartment $dailyTakeBusinessPremisesShopDepartment
     */
    public function removeDailyTakeBusinessPremisesShopDepartment(\MilesApart\AdminBundle\Entity\DailyTakeBusinessPremisesShopDepartment $dailyTakeBusinessPremisesShopDepartment)
    {
        $this->daily_take_business_premises_shop_department->removeElement($dailyTakeBusinessPremisesShopDepartment);
    }

    /**
     * Get daily_take_business_premises_shop_department
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDailyTakeBusinessPremisesShopDepartment()
    {
        return $this->daily_take_business_premises_shop_department;
    }
}