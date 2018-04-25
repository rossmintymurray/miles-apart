<?php

namespace MilesApart\AdminBundle\Utils;

class MilesApart
{
    static public function slugify($text)
    {
    	$text = str_replace("'", '', $text);

        // replace all non letters or digits by -
        $text = preg_replace('/\W+/', '-', $text);
 
        // trim and lowercase
        $text = strtolower(trim($text, '-'));
 
        return $text;
    }

    
}

