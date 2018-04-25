<?php
// src/MilesApart/AdminBundle/Entity/CategoryType.php -- Defines the category type object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\CategoryTypeRepository")
 * @ORM\Table(name="category_type")
 * @ORM\HasLifecycleCallbacks()
 */

class CategoryType
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
     * @ORM\Column(type="string", length=50, unique=true, nullable=false)
     */
    protected $category_type_name;

    /**
    * @ORM\OneToMany(targetEntity="Category", mappedBy="category_type")
     */
    protected $category;
    


    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //Category type name
        $metadata->addPropertyConstraint('category_type_name', new Assert\NotBlank());
        $metadata->addPropertyConstraint('category_type_name', new Assert\Length(array(
            'min'        => 2,
            'max'        => 100,
            'minMessage' => 'The category type name must be at least {{ limit }} characters length',
            'maxMessage' => 'The category type name cannot be longer than {{ limit }} characters length',
        )));
    }

       

       
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->category = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set category_type_name
     *
     * @param string $categoryTypeName
     * @return CategoryType
     */
    public function setCategoryTypeName($categoryTypeName)
    {
        $this->category_type_name = $categoryTypeName;
    
        return $this;
    }

    /**
     * Get category_type_name
     *
     * @return string 
     */
    public function getCategoryTypeName()
    {
        return $this->category_type_name;
    }

    /**
     * Add category
     *
     * @param \MilesApart\AdminBundle\Entity\Category $category
     * @return CategoryType
     */
    public function addCategory(\MilesApart\AdminBundle\Entity\Category $category)
    {
        $this->category[] = $category;
    
        return $this;
    }

    /**
     * Remove category
     *
     * @param \MilesApart\AdminBundle\Entity\Category $category
     */
    public function removeCategory(\MilesApart\AdminBundle\Entity\Category $category)
    {
        $this->category->removeElement($category);
    }

    /**
     * Get category
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCategory()
    {
        return $this->category;
    }
}