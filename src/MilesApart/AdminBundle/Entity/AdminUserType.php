<?php
// src/MilesApart/AdminBundle/Entity/AdminUserType.php -- Defines the admin user type object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\AdminUserTypeRepository")
 * @ORM\Table(name="admin_user_type")
 * @ORM\HasLifecycleCallbacks()
 */

class AdminUserType
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
    protected $admin_user_type_name;

    /**
     * @ORM\OneToMany(targetEntity="AdminUser", mappedBy="admin_user_type")
     */
    protected $admin_user;

    /**
     * @ORM\OneToMany(targetEntity="AdminUserTypeAccessRight", mappedBy="admin_user_type")
     */
    protected $admin_user_type_access_right;



    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //Admin user type name
        $metadata->addPropertyConstraint('admin_user_type_name', new Assert\NotBlank());
        $metadata->addPropertyConstraint('admin_user_type_name', new Assert\Length(array(
            'min'        => 4,
            'max'        => 100,
            'minMessage' => 'The user type must be at least {{ limit }} characters length',
            'maxMessage' => 'The user type cannot be longer than {{ limit }} characters length',
        )));
    }

    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->admin_user = new \Doctrine\Common\Collections\ArrayCollection();
        $this->admin_user_type_access_right = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set admin_user_type_name
     *
     * @param string $adminUserTypeName
     * @return AdminUserType
     */
    public function setAdminUserTypeName($adminUserTypeName)
    {
        $this->admin_user_type_name = $adminUserTypeName;
    
        return $this;
    }

    /**
     * Get admin_user_type_name
     *
     * @return string 
     */
    public function getAdminUserTypeName()
    {
        return $this->admin_user_type_name;
    }

    /**
     * Add admin_user
     *
     * @param \MilesApart\AdminBundle\Entity\AdminUser $adminUser
     * @return AdminUserType
     */
    public function addAdminUser(\MilesApart\AdminBundle\Entity\AdminUser $adminUser)
    {
        $this->admin_user[] = $adminUser;
    
        return $this;
    }

    /**
     * Remove admin_user
     *
     * @param \MilesApart\AdminBundle\Entity\AdminUser $adminUser
     */
    public function removeAdminUser(\MilesApart\AdminBundle\Entity\AdminUser $adminUser)
    {
        $this->admin_user->removeElement($adminUser);
    }

    /**
     * Get admin_user
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAdminUser()
    {
        return $this->admin_user;
    }

    /**
     * Add admin_user_type_access_right
     *
     * @param \MilesApart\AdminBundle\Entity\AdminUserTypeAccessRight $adminUserTypeAccessRight
     * @return AdminUserType
     */
    public function addAdminUserTypeAccessRight(\MilesApart\AdminBundle\Entity\AdminUserTypeAccessRight $adminUserTypeAccessRight)
    {
        $this->admin_user_type_access_right[] = $adminUserTypeAccessRight;
    
        return $this;
    }

    /**
     * Remove admin_user_type_access_right
     *
     * @param \MilesApart\AdminBundle\Entity\AdminUserTypeAccessRight $adminUserTypeAccessRight
     */
    public function removeAdminUserTypeAccessRight(\MilesApart\AdminBundle\Entity\AdminUserTypeAccessRight $adminUserTypeAccessRight)
    {
        $this->admin_user_type_access_right->removeElement($adminUserTypeAccessRight);
    }

    /**
     * Get admin_user_type_access_right
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAdminUserTypeAccessRight()
    {
        return $this->admin_user_type_access_right;
    }
}