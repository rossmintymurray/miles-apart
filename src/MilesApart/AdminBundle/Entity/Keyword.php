<?php
// src/MilesApart/AdminBundle/Entity/Keyword.php -- Defines the keyword object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use MilesApart\AdminBundle\Utils\MilesApart as MilesApart;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\KeywordRepository")
 * @ORM\Table(name="keyword")
 * @ORM\HasLifecycleCallbacks()
 */

class Keyword
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
     * @ORM\Column(type="string", length=200, unique=true, nullable=false)
     */
    protected $keyword_word;

    /**
     * @ORM\ManyToMany(targetEntity="Category", mappedBy="keyword", cascade={"persist"})
     */
     protected $category;

    /**
     * @ORM\ManyToMany(targetEntity="Product", mappedBy="keyword", cascade={"persist"})
     */
     protected $product;

    /**
     * @Gedmo\Slug(fields={"keyword_word"}, separator="-")
     * @ORM\Column(type="string", length=100, unique=true, nullable=false)
     */
    protected $keyword_slug;

    /**
     * @ORM\ManyToMany(targetEntity="Promotion", mappedBy="keyword", cascade={"persist"})
     */
     protected $promotion;



    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //Employee first name
        $metadata->addPropertyConstraint('keyword_word', new Assert\NotBlank());
        $metadata->addPropertyConstraint('keyword_word', new Assert\Length(array(
            'min'        => 2,
            'max'        => 200,
            'minMessage' => 'The keyword word must be at least {{ limit }} characters length',
            'maxMessage' => 'The keyword word cannot be longer than {{ limit }} characters length',
        )));
    }



    /**
     * Get keyword_slug
     *
     * @return string 
     */
    public function getKeywordSlug()
    {
        return MilesApart::slugify($this->getKeywordWord());
    }



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->category = new \Doctrine\Common\Collections\ArrayCollection();
        $this->product = new \Doctrine\Common\Collections\ArrayCollection();
        $this->promotion = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set keyword_word
     *
     * @param string $keywordWord
     * @return Keyword
     */
    public function setKeywordWord($keywordWord)
    {
        $this->keyword_word = $keywordWord;
    
        return $this;
    }

    /**
     * Get keyword_word
     *
     * @return string 
     */
    public function getKeywordWord()
    {
        return $this->keyword_word;
    }

    /**
     * Set keyword_slug
     *
     * @param string $keywordSlug
     * @return Keyword
     */
    public function setKeywordSlug($keywordSlug)
    {
        $this->keyword_slug = $keywordSlug;
    
        return $this;
    }

    /**
     * Add category
     *
     * @param \MilesApart\AdminBundle\Entity\Category $category
     * @return Keyword
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

    /**
     * Add product
     *
     * @param \MilesApart\AdminBundle\Entity\Product $product
     * @return Keyword
     */
    public function addProduct(\MilesApart\AdminBundle\Entity\Product $product)
    {
        $this->product[] = $product;
    
        return $this;
    }

    /**
     * Remove product
     *
     * @param \MilesApart\AdminBundle\Entity\Product $product
     */
    public function removeProduct(\MilesApart\AdminBundle\Entity\Product $product)
    {
        $this->product->removeElement($product);
    }

    /**
     * Get product
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Add promotion
     *
     * @param \MilesApart\AdminBundle\Entity\Promotion $promotion
     * @return Keyword
     */
    public function addPromotion(\MilesApart\AdminBundle\Entity\Promotion $promotion)
    {
        $this->promotion[] = $promotion;
    
        return $this;
    }

    /**
     * Remove promotion
     *
     * @param \MilesApart\AdminBundle\Entity\Promotion $promotion
     */
    public function removePromotion(\MilesApart\AdminBundle\Entity\Promotion $promotion)
    {
        $this->promotion->removeElement($promotion);
    }

    /**
     * Get promotion
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPromotion()
    {
        return $this->promotion;
    }
}