<?php
namespace Khepin\MyBundle\Listener;
class SluggableListener extends \Gedmo\Sluggable\SluggableListener{
    
    public function __construct(){
        $this->setTransliterator(array('\MilesApart\AdminBundle\Utils\Transliterator', 'transliterate'));
    }
}