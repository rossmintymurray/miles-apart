<?php
// src/MilesApart/PublicBundle/Controller/FunctionsController.php

namespace MilesApart\PublicBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use MilesApart\AdminBundle\Entity\ProductImage;


class FunctionsController extends Controller
{
    public function replaceproductpageproductimageAction($id)
    {
    	//Set up entity manager
    	$em = $this->getDoctrine()->getManager();

    	//Get proudtc umage for this id
        $product_image = $em->getRepository('MilesApartAdminBundle:ProductImage')->findOneById($id);

        //Return the template with the new image path.
        return $this->render(
            'MilesApartPublicBundle:Functions:replace_product_page_product_image.html.twig', array(
                'product_image' => $product_image
            )
           
        );
    }
}