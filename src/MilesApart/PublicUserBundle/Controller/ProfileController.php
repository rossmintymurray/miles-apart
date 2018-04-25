<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MilesApart\PublicUserBundle\Controller;

use FOS\UserBundle\Controller\ProfileController as BaseController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Model\UserInterface;

/**
 * Controller managing the user profile
 *
 * @author Christophe Coevoet <stof@notk.org>
 */
class ProfileController extends BaseController
{
    /**
     * Show the user
     */
    public function showAction()
    {
         //Set up the breadcrumb
        $breadcrumbs = $this->container->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->container->get("router")->generate("miles_apart_public_homepage"));
        $breadcrumbs->addItem("Profile", $this->container->get("router")->generate("fos_user_profile_show"));
        

        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $em = $this->container->get('doctrine')->getManager();
        //Get the current orders and the previous orders and limit to 5
        $current_orders = $em->getRepository('MilesApartAdminBundle:CustomerOrder')->getCurrentOrdersByCustomerId($user->getCustomer()->getId());
        $previous_orders = $em->getRepository('MilesApartAdminBundle:CustomerOrder')->getPreviousOrdersByCustomerId($user->getCustomer()->getId());

        return $this->container->get('templating')->renderResponse('FOSUserBundle:Profile:show.html.'.$this->container->getParameter('fos_user.template.engine'), array(
            'user' => $user,
            'current_orders' => $current_orders,
            'previous_orders' => $previous_orders
        ));
    }

    /**
     * Edit the user
     */
    public function editAction()
    {   
        //Set up the breadcrumb
        $breadcrumbs = $this->container->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->container->get("router")->generate("miles_apart_public_homepage"));
        $breadcrumbs->addItem("Profile", $this->container->get("router")->generate("fos_user_profile_show"));
        $breadcrumbs->addItem("Edit Details", $this->container->get("router")->generate("fos_user_profile_edit"));

        //Get user from security token
        $user = $this->container->get('security.context')->getToken()->getUser();

        //Check that the user has access 
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        //Get the form 
        $form = $this->container->get('fos_user.profile.form');

        //Process the form 
        $formHandler = $this->container->get('fos_user.profile.form.handler');
        $process = $formHandler->process($user);

        //If form has been processed 
        if ($process) {
            //Set flash message
            $this->setFlash('public-success', 'Your details have been updated.');

            //Redirect the user
            return new RedirectResponse($this->getRedirectionUrl($user));
        }

        //If the form hasn't been processed
        return $this->container->get('templating')->renderResponse(
            'FOSUserBundle:Profile:edit.html.'.$this->container->getParameter('fos_user.template.engine'),
            array('form' => $form->createView(),
                'user' => $user)
        );
    }

    /**
     * Generate the redirection url when editing is completed.
     *
     * @param \FOS\UserBundle\Model\UserInterface $user
     *
     * @return string
     */
    protected function getRedirectionUrl(UserInterface $user)
    {
        return $this->container->get('router')->generate('fos_user_profile_show');
    }

    /**
     * @param string $action
     * @param string $value
     */
    protected function setFlash($action, $value)
    {
        $this->container->get('session')->getFlashBag()->set($action, $value);
    }
}
