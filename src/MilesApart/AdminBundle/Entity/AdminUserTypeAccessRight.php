<?php
// src/MilesApart/AdminBundle/Entity/AdminUserTypeAccessRight.php -- Defines the attribute object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\AdminUserTypeAccessRightRepository")
 * @ORM\Table(name="admin_user_type_access_right")
 * @ORM\HasLifecycleCallbacks()
 */

class AdminUserTypeAccessRight
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
     * @ORM\ManyToOne(targetEntity="AdminUserType", inversedBy="admin_user_type_access_right")
     * @ORM\JoinTable(name="admin_user_type")
     * @ORM\JoinColumn(name="admin_user_type_id", referencedColumnName="id")
     */
    protected $admin_user_type;

    /**
     * @ORM\ManyToOne(targetEntity="AccessRight", inversedBy="admin_user_type_access_right")
     * @ORM\JoinTable(name="access_right")
     * @ORM\JoinColumn(name="access_right_id", referencedColumnName="id")
     */
    protected $access_right;

    

    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
       
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
     * Set admin_user_type
     *
     * @param \MilesApart\AdminBundle\Entity\AdminUserType $adminUserType
     * @return AdminUserTypeAccessRight
     */
    public function setAdminUserType(\MilesApart\AdminBundle\Entity\AdminUserType $adminUserType = null)
    {
        $this->admin_user_type = $adminUserType;
    
        return $this;
    }

    /**
     * Get admin_user_type
     *
     * @return \MilesApart\AdminBundle\Entity\AdminUserType 
     */
    public function getAdminUserType()
    {
        return $this->admin_user_type;
    }

    /**
     * Set access_right
     *
     * @param \MilesApart\AdminBundle\Entity\AccessRight $accessRight
     * @return AdminUserTypeAccessRight
     */
    public function setAccessRight(\MilesApart\AdminBundle\Entity\AccessRight $accessRight = null)
    {
        $this->access_right = $accessRight;
    
        return $this;
    }

    /**
     * Get access_right
     *
     * @return \MilesApart\AdminBundle\Entity\AccessRight 
     */
    public function getAccessRight()
    {
        return $this->access_right;
    }
}