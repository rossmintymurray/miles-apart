<?php
// src/MilesApart/PublicBundle/Controller/PageController.php

namespace MilesApart\PublicBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use MilesApart\AdminBundle\Entity\Category;
use MilesApart\PublicBundle\Entity\Search;
use MilesApart\PublicBundle\Form\MainSearchType;

use Symfony\Component\HttpFoundation\Session\Session;

class PageComponentsController extends Controller
{
    public function mainNavigationAction($main_cat_slug=null, $sub_cat_slug=null, $specific_cat_slug=null)
    {
        //Get the main category from the session

        //Create the main navigation.
        //Get categories from the database.
        $em = $this->getDoctrine()->getManager();

        $navigation_categories = $em->getRepository('MilesApartAdminBundle:Category')->findBy(array('category_navigation_display' => 1 ), array('category_display_order' => 'ASC'));

        //Get the season depending on currenty dates
        $current_season = $em->getRepository('MilesApartAdminBundle:Season')->findByCurrentDate();

        return $this->render(
            'MilesApartPublicBundle:PageComponents:main_navigation_foundation.html.twig', array(
                'navigation_categories' => $navigation_categories,
                'main_cat_slug'=> $main_cat_slug,
                'sub_cat_slug'=> $sub_cat_slug,
                'specific_cat_slug'=> $specific_cat_slug,
                'current_season' => $current_season

            )
        );
    }

    public function infoLinksAction()
    {
        //Create the info links bar.
        return $this->render(
            'MilesApartPublicBundle:PageComponents:info_links.html.twig'
           
        );
    }


    public function mobileheaderAction(Request $request, $search_string, $active_page = null)
    {
        $em = $this->getDoctrine()->getManager();

        $session = $request->getSession();

        if ($session->has('basket')) {
            //Get the basket from the session
            $basket = $session->get('basket');
            $basket = $em->merge($basket);
            
        } else {
            $basket = null;
        }

        //Create the search form.

        $search = new Search();
        $form = $this->createForm('milesapart_publicbundle_main_search_form', $search);

        //Create the main header.
        return $this->render(
            'MilesApartPublicBundle:PageComponents:mobile_header.html.twig', array(
                'form' => $form->createView(),
                'search_string' => $search_string,
                'basket' => $basket,
                'active_page' => $active_page
                )
           
        );
    }

    public function mainHeaderAction($search_string)
    {
        //Create the search form.

        $search = new Search();
        $form = $this->createForm('milesapart_publicbundle_main_search_form', $search);


        //Create the main header.
        return $this->render(
            'MilesApartPublicBundle:PageComponents:main_header.html.twig', array(
                'form' => $form->createView(),
                'search_string' => $search_string
                )
           
        );
    }

   



    public function basketBarAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $session = $request->getSession();

        if ($session->has('basket')) {
            //Get the basket from the session
            $basket = $session->get('basket');
            $basket = $em->merge($basket);
            
        } else {
            $basket = null;
        }

        

        //Create the basket bar.
        return $this->render(
            'MilesApartPublicBundle:PageComponents:basket_bar.html.twig', array(
                'basket' => $basket,
        ));
    }

    public function basketContentsPopupAction()
    {

        //$session = new Session();
        $removed = "false";
        if ($session->has('basket')) {
            //Get the basket from the session
            $basket = $session->get('basket');
        } else {
            $basket = null;
        }

        //Create the basket bar.
        return $this->render(
            'MilesApartPublicBundle:PageComponents:basket_contents_popup.html.twig', array(
                'basket' => $basket
            )
           
        );
    }

    public function newProductsAction()
    {
        $em = $this->getDoctrine()->getManager();

        //Get the new products from the database
        $new_products = $em->getRepository('MilesApartAdminBundle:Product')->findNewProducts();

        //Create the footer.
        return $this->render(
            'MilesApartPublicBundle:PageComponents:new_products.html.twig', array(
                'new_products' => $new_products,
            )
           
        );
    }

    public function staffPickProductsAction()
    {
        $em = $this->getDoctrine()->getManager();

        //Get the new products from the database
        $staff_picks = $em->getRepository('MilesApartAdminBundle:StaffPickProduct')->findStaffPickProducts();

        //Create the footer.
        return $this->render(
            'MilesApartPublicBundle:PageComponents:staff_picks.html.twig', array(
                'staff_picks' => $staff_picks,
            )
           
        );
    }

    public function footerAction()
    {
        //Create the footer.
        return $this->render(
            'MilesApartPublicBundle:PageComponents:footer.html.twig'
           
        );
    }
}