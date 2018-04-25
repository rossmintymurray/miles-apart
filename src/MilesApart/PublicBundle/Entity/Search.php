<?php
// src/MilesApart/PublicBundle/Entity/ContactUsMessage.php -- Defines the contact us message object

namespace MilesApart\PublicBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;




class Search
{
   
    protected $search_string;

    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //Contact us message name
        $metadata->addPropertyConstraint('search_string', new Assert\NotBlank());
        $metadata->addPropertyConstraint('search_string', new Assert\Length(array(
            'min'        => 1,
            'max'        => 200,
            'minMessage' => 'The \'Search\' field must be at least {{ limit }} characters length',
            'maxMessage' => 'The \'Search\' field cannot be longer than {{ limit }} characters length',
        )));

    }
    
    

    /**
     * Set search_string
     *
     * @param string $searchString
     * @return searchString
     */
    public function setSearchString($search_string)
    {
        $this->search_string = $search_string;
    
        return $this;
    }

    /**
     * Get search_string
     *
     * @return string 
     */
    public function getSearchString()
    {
        return $this->search_string;
    }
}