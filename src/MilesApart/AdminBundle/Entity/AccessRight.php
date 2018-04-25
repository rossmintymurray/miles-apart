<?php
// src/MilesApart/AdminBundle/Entity/AccessRight.php -- Defines the admin user object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;



use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\AccessRightRepository")
 * @ORM\Table(name="access_right")
 * @ORM\HasLifecycleCallbacks()
 */

class AccessRight
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
    protected $access_right_action;

    /**
     * @ORM\OneToMany(targetEntity="AdminUserTypeAccessRight", mappedBy="access_right")
     */
    protected $admin_user_type_access_right;




	//Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
    	//Access right action
        $metadata->addPropertyConstraint('access_right_action', new Assert\NotBlank());
        $metadata->addPropertyConstraint('access_right_action', new Assert\Length(array(
            'min'        => 2,
            'max'        => 100,
            'minMessage' => 'The access right action must be at least {{ limit }} characters length',
            'maxMessage' => 'The access right action cannot be longer than {{ limit }} characters length',
        )));
       
    }



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->admin_user_access_right = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set access_right_action
     *
     * @param string $accessRightAction
     * @return AccessRight
     */
    public function setAccessRightAction($accessRightAction)
    {
        $this->access_right_action = $accessRightAction;
    
        return $this;
    }

    /**
     * Get access_right_action
     *
     * @return string 
     */
    public function getAccessRightAction()
    {
        return $this->access_right_action;
    }

    /**
     * Add admin_user_type_access_right
     *
     * @param \MilesApart\AdminBundle\Entity\AdminUserTypeAccessRight $adminUserTypeAccessRight
     * @return AccessRight
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