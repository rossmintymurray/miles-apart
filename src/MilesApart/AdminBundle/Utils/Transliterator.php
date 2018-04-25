<?php
namespace Khepin\MyBundle\Listener;
class Transliterator {
    public static function transliterate($text, $separator, $object){
        $text =  my_transliteration_function($text);
        return \Gedmo\Sluggable\Util\Urlizer::urlize($text, $separator);
    }
}