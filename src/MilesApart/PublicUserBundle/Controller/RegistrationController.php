<?php
// src/Acme/UserBundle/Controller/RegistrationController.php

namespace MilesApart\PublicUserBundle\Controller;

use EWZ\Bundle\RecaptchaBundle\Validator\Constraints\IsTrue;

use MilesApart\AdminBundle\Entity\PersonalCustomer;
use MilesApart\AdminBundle\Entity\BusinessCustomerRepresentative;
use MilesApart\AdminBundle\Entity\BusinessCustomer;
use MilesApart\AdminBundle\Entity\Customer;

use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\UserBundle\Controller\RegistrationController as BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class RegistrationController extends BaseController
{
    public function registerAction()
    {
       
        //Chec session for form 
        $session = new Session();
        if($session->get('form')) {
            $form = $session->get('form');
            $session->remove('form');

            return $this->container->get('templating')->renderResponse('FOSUserBundle:Registration:register.html.'.$this->getEngine(), array(
            'form' => $form,
        ));
        } else {

            $form = $this->container->get('fos_user.registration.form');
            $formHandler = $this->container->get('fos_user.registration.form.handler');
            $confirmationEnabled = $this->container->getParameter('fos_user.registration.confirmation.enabled');

            $process = $formHandler->process($confirmationEnabled);
           
            if ($process['proc']) {
                if ($process['valid']) {
                   
                    $user = $form->getData();

                   
                  

                    $session = $this->container->get('session');

                        //If business, create new business customer and new business customer representative.
                        if($form->get('business_customer_representative')->get('business_customer_representative_first_name')->getData()) {

                            $business_customer_representative = new BusinessCustomerRepresentative();
                            $business_customer_representative = $form->get('business_customer_representative')->getData();
                           
                            $business_customer_representative->setFosUser($user);
                            $user->setBusinessCustomerRepresentative($business_customer_representative);

                            //Set the personal customer email address
                            $business_customer_representative->setBusinessCustomerRepresentativeEmailAddress($user->getEmailCanonical());

                            //Create business customer and set  id
                            $business_customer = new BusinessCustomer();
                            $business_customer = $form->get('business_customer_representative')->get('business_customer')->getData();
                            $business_customer_representative->setBusinessCustomer($business_customer);

                            //Set first name for email
                            $session->set('first_name', $business_customer_representative->getBusinessCustomerRepresentativeFirstName());
                            //Create business customer and set  id
                            $customer = new Customer();
                            $business_customer->setCustomer($customer);

                            //Set vat invoice option to true
                            $customer->setVatInvoiceOption(true);

                            $em = $this->container->get('doctrine')->getManager();
                            $em->persist($business_customer_representative);
                            $em->persist($user);
                            $em->flush();
                            

                        }

                        //If personal, create new personal customer.
                        if($form->get('personal_customer')->get('personal_customer_first_name')->getData()) {

                            $personal_customer = new PersonalCustomer();
                            $personal_customer = $form->get('personal_customer')->getData();
                           
                            $personal_customer->setFosUser($user);
                            $user->setPersonalCustomer($personal_customer);

                            //Set the personal customer email address
                            $personal_customer->setPersonalCustomerEmailAddress($user->getEmailCanonical());

                            //Set first name for email
                            $session->set('first_name', $personal_customer->getPersonalCustomerFirstName());

                            //Create customer and set customer id
                            $customer = new Customer();
                            $personal_customer->setCustomer($customer);

                            $em = $this->container->get('doctrine')->getManager();
                            $em->persist($personal_customer);
                            $em->persist($user);
                            $em->flush();
                            
                        }

                    
                    $authUser = false;
                    if ($confirmationEnabled) {
                        $this->container->get('session')->set('fos_user_send_confirmation_email/email', $user->getEmail());
                        $route = 'fos_user_registration_check_email';
                    } else {
                        $authUser = true;
                        $route = 'fos_user_registration_confirmed';
                    }
                   

                    $this->setFlash('fos_registration_success', 'registration.flash.user_created');
                    $url = $this->container->get('router')->generate($route);
                    $response = new RedirectResponse($url);

                    if ($authUser) {
                        $this->authenticateUser($user, $response);
                    }

                    return $response;


                } else {


                    $this->setFlash('fos_registration_error', 'There are some issues with the form');

                    //Redirect to login register page
                    $logger = $this->container->get('logger');
                    $logger->info('I just got the logger add update price444 pre');
                    if($form == null) {
                        $logger->info("reg is null44");
                    } else {
                        $logger->info("reg is not null44");
                    }
                    $logger->info('I just got the logger add update price444');

                    //Set the form in the session
                    $session = new Session();
                    $session->set('form', $form->createView());
                    $url = $this->container->get('router')->generate('miles_apart_public_login_or_register');
                    return new RedirectResponse($url);
                }
            }
        }
    

        return $this->container->get('templating')->renderResponse('FOSUserBundle:Registration:register.html.'.$this->getEngine(), array(
            'form' => $form->createView(),
        ));
    }
}