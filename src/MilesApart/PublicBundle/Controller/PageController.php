<?php
// src/MilesApart/PublicBundle/Controller/PageController.php

namespace MilesApart\PublicBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use EWZ\Bundle\RecaptchaBundle\Validator\Constraints\IsTrue;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;

use MilesApart\AdminBundle\Entity\Category;
use MilesApart\AdminBundle\Entity\PersonalCustomer;
use MilesApart\PublicBundle\Entity\ContactUsMessage;
use MilesApart\PublicBundle\Entity\Feedback;

use MilesApart\PublicBundle\Form\FeedbackFormType;
use MilesApart\PublicBundle\Form\ContactUsMessageType;
use MilesApart\PublicBundle\Form\SignInFormType;
use MilesApart\PublicBundle\Form\PersonalCustomerRegistrationFormType;


class PageController extends Controller
{
    public function indexAction()
    {
        if( $this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') ){
            // authenticated (NON anonymous)
            $user = $this->getUser()->getUsername();
        } else {
            $user = false;
        }

        ob_start();
        phpinfo();
        $phpinfo = ob_get_clean();

        return $this->render('MilesApartPublicBundle:Page:index.html.twig', array(
            'user' => $user,
            'phpinfo' => $phpinfo
        ));
    }

    public function termsandconditionsAction()
    {
    	 //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_public_homepage"));
        $breadcrumbs->addItem("Terms & Conditions", $this->get("router")->generate("miles_apart_public_terms_and_conditions"));
        
        return $this->render('MilesApartPublicBundle:Page:terms-and-conditions.html.twig');
    }

    public function privacyandcookiepolicyAction()
    {
    	//Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_public_homepage"));
        $breadcrumbs->addItem("Privacy & Cookie Policy", $this->get("router")->generate("miles_apart_public_privacy_and_cookie_policy"));

        return $this->render('MilesApartPublicBundle:Page:privacy-and-cookie-policy.html.twig');
    }

    public function returnspolicyAction()
    {
    	//Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_public_homepage"));
        $breadcrumbs->addItem("Returns Policy", $this->get("router")->generate("miles_apart_public_returns_policy"));

        return $this->render('MilesApartPublicBundle:Page:returns-policy.html.twig');
    }

    public function accessibilityAction()
    {
    	//Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_public_homepage"));
        $breadcrumbs->addItem("Accessibility", $this->get("router")->generate("miles_apart_public_accessibility"));

        return $this->render('MilesApartPublicBundle:Page:accessibility.html.twig');
    }

    public function aboutusAction()
    {
    	//Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_public_homepage"));
        $breadcrumbs->addItem("About Us", $this->get("router")->generate("miles_apart_public_about_us"));

        return $this->render('MilesApartPublicBundle:Page:about-us.html.twig');
    }

    public function deliveryinformationAction()
    {
    	//Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_public_homepage"));
        $breadcrumbs->addItem("Delivery Information", $this->get("router")->generate("miles_apart_public_delivery_information"));

        //Get the delivery options
        $em = $this->getDoctrine()->getManager();
        $postage_bands = $em->getRepository('MilesApartAdminBundle:PostageBand')->findAll();
        

        return $this->render('MilesApartPublicBundle:Page:delivery-information.html.twig', array(
            'postage_bands' => $postage_bands
        ));

    }

    public function ourstoresAction()
    {
    	//Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_public_homepage"));
        $breadcrumbs->addItem("Our Stores", $this->get("router")->generate("miles_apart_public_our_stores"));

        return $this->render('MilesApartPublicBundle:Page:our-stores.html.twig');
    }

    public function ourstaffAction()
    {
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_public_homepage"));
        $breadcrumbs->addItem("Our Staff", $this->get("router")->generate("miles_apart_public_our_staff"));

        return $this->render('MilesApartPublicBundle:Page:our-staff.html.twig');
    }
    
    public function helpandsupportAction()
    {
    	//Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_public_homepage"));
        $breadcrumbs->addItem("Help & Support", $this->get("router")->generate("miles_apart_public_help_and_support"));

        return $this->render('MilesApartPublicBundle:Page:help_and_support.html.twig');
    }

    public function faqsAction()
    {
    	//Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_public_homepage"));
        $breadcrumbs->addItem("Help & Support", $this->get("router")->generate("miles_apart_public_help_and_support"));
        $breadcrumbs->addItem("FAQs", $this->get("router")->generate("miles_apart_public_faqs"));

        return $this->render('MilesApartPublicBundle:Page:faqs.html.twig');
    }

    public function loginorregisterAction($registration_form = null)
    {
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_public_homepage"));
        $breadcrumbs->addItem("Login or Register", $this->get("router")->generate("miles_apart_public_login_or_register"));

        
        $logger = $this->get('logger');
        $logger->info('I just got the logger add update price pre');
        if($registration_form == null) {
            $logger->info("reg is null");
        } else {
            $logger->info("reg is not null");
        }
        $logger->info('I just got the logger add update price');

        //Render the page
        return $this->render('MilesApartPublicBundle:Page:login_or_register.html.twig', array(
            'registration_form' => $registration_form
            ));
    }

    public function returnsproceedureAction()
    {
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_public_homepage"));
        $breadcrumbs->addItem("Returns Proceedure", $this->get("router")->generate("miles_apart_public_returns_proceedure"));

        //Render the page
        return $this->render('MilesApartPublicBundle:Page:returns_proceedure.html.twig');
    }

    public function contactusAction()
    {
    	//Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_public_homepage"));
        $breadcrumbs->addItem("Help & Support", $this->get("router")->generate("miles_apart_public_help_and_support"));
        $breadcrumbs->addItem("Contact Us", $this->get("router")->generate("miles_apart_public_contact_us"));

        $entity = new ContactUsMessage();

        //Check if the user is logged in, if so set the name and email address.
         if( $this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') ){
            // authenticated (NON anonymous)
            $entity->setContactUsMessageName($this->getUser()->getFullName());
            $entity->setContactUsMessageEmailAddress($this->getUser()->getEmail());
        } 
        $form = $this->createCreateContactUsMessageForm($entity);


        return $this->render('MilesApartPublicBundle:Page:contact-us.html.twig', array(
            'submitted' => false,
            'entity' => $entity,
            'form'   => $form->createView(),
            ));
    }

    /**
    * Creates a form to create a new contact us message.
    *
    * @param Contact Us Message $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateContactUsMessageForm(ContactUsMessage $entity)
    {
        $form = $this->createForm(new ContactUsMessageType(), $entity, array(
            'action' => $this->generateUrl('miles_apart_public_contact_us_submit'),
            'method' => 'POST',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

        $form->add('submit', 'submit', array('label' => 'Send Message', 'attr' => array(
                        'class' => 'btn btn-primary large-offset-3 large-6 small-12')));

        return $form;
    }

    //Need to figure out how to make this ajax
     public function contactussubmitAction(Request $request)
    {
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_public_homepage"));
        $breadcrumbs->addItem("Help & Support", $this->get("router")->generate("miles_apart_public_help_and_support"));
        $breadcrumbs->addItem("Contact Us", $this->get("router")->generate("miles_apart_public_contact_us"));

        $entity = new ContactUsMessage();

        $form = $this->createCreateContactUsMessageForm($entity);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            //Create and send the email.
            $email_send = $this->sendContactUsEmail($entity);

            //Show the flash message with success
            $this->get('session')->getFlashBag()->set('public-success', 'Thank you. Your message has been sent successfully.');

            return $this->redirect($this->generateUrl('miles_apart_public_contact_us'));

        } else {
            //Validation has failed
            //Set submit to true for error messages
            $submitted = true;
            //Show the flash message notifying of an error
            $this->get('session')->getFlashBag()->set('public-error', 'Sorry, there has been a problem sending your message. Please check the fields highlighted red.');

            return $this->render('MilesApartPublicBundle:Page:contact-us.html.twig', array(
                'submitted' => $submitted,
                'entity' => $entity,
                'form'   => $form->createView(),
            ));
        }
    }

    function sendContactUsEmail($contact_us)
    {
        //Set up the mailer
        $mailer = $this->container->get('swiftmailer.mailer.system_mailer');

        $message = \Swift_Message::newInstance()
            ->setContentType("text/html")
            ->setSubject('Contact Us Message From - ' . $contact_us->getContactUsMessageName())
            ->setFrom(array($contact_us->getContactUsMessageEmailAddress() => $contact_us->getContactUsMessageName()))
            ->setTo('customersupport@miles-apart.com')
            ->setBody(
                $this->renderView(
                    'MilesApartPublicBundle:Emails:contact_us_email.html.twig',
                    array('contact_us' => $contact_us)
                )
            )
        ;

        //Avoid localhost issues
        $mailer->getTransport()->setLocalDomain('[127.0.0.1]');

        $mailer->send($message);

        return true;
    }


    public function leavefeedbackAction()
    {
    	//Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_public_homepage"));
        $breadcrumbs->addItem("Help & Support", $this->get("router")->generate("miles_apart_public_help_and_support"));
        $breadcrumbs->addItem("Leave Feedback", $this->get("router")->generate("miles_apart_public_leave_feedback"));

        $entity = new Feedback();

        //Check if the user is logged in, if so set the name and email address.
         if( $this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') ){
            // authenticated (NON anonymous)
            $entity->setFeedbackName($this->getUser()->getFullName());
            $entity->setFeedbackEmailAddress($this->getUser()->getEmail());
        } 
        $form = $this->createFeedbackForm($entity);

        return $this->render('MilesApartPublicBundle:Page:leave-feedback.html.twig', array(
            'submitted' => false,
            'entity' => $entity,
            'form'   => $form->createView(),
            ));
    }

    /**
    * Creates a form to create a new contact us message.
    *
    * @param Contact Us Message $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createFeedbackForm(Feedback $entity)
    {
        $form = $this->createForm(new FeedbackFormType(), $entity, array(
            'action' => $this->generateUrl('miles_apart_public_feedback_submit'),
            'method' => 'POST',
            'attr' => array('class' => 'admin_form form-horizontal')
        ));

        $form->add('submit', 'submit', array('label' => 'Send Feedback', 'attr' => array(
                        'class' => 'btn btn-primary large-offset-3 large-6')));

        return $form;
    }

    //Need to figure out how to make this ajax
     public function leavefeedbacksubmitAction(Request $request)
    {
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_public_homepage"));
        $breadcrumbs->addItem("Help & Support", $this->get("router")->generate("miles_apart_public_help_and_support"));
         $breadcrumbs->addItem("Leave Feedback", $this->get("router")->generate("miles_apart_public_leave_feedback"));

        $entity = new Feedback();

        $form = $this->createFeedbackForm($entity);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            //Create and send the email.
            $email_send = $this->sendFeedbackEmail($entity);

            //Show the flash message with success
            $this->get('session')->getFlashBag()->set('public-success', 'Thank you. Your feedback has been received successfully.');

            return $this->redirect($this->generateUrl('miles_apart_public_leave_feedback'));

        } else {
            //Validation has failed
            //Set submit to true for error messages
            $submitted = true;
            //Show the flash message notifying of an error
            $this->get('session')->getFlashBag()->set('public-error', 'Sorry, there has been a problem leaving your feedback. Please check the fields highlighted red.');

            return $this->render('MilesApartPublicBundle:Page:leave-feedback.html.twig', array(
                'submitted' => $submitted,
                'entity' => $entity,
                'form'   => $form->createView(),
            ));
        }
    }

    function sendFeedbackEmail($feedback)
    {
        //Set up the mailer
        $mailer = $this->container->get('swiftmailer.mailer.system_mailer');

        $message = \Swift_Message::newInstance()
            ->setContentType("text/html")
            ->setSubject('Feedback Message From - ' . $feedback->getFeedbackName())
            ->setFrom(array($feedback->getFeedbackEmailAddress() => $feedback->getFeedbackName()))
            ->setTo('customersupport@miles-apart.com')
            ->setBody(
                $this->renderView(
                    'MilesApartPublicBundle:Emails:feedback_email.html.twig',
                    array('feedback' => $feedback)
                )
            )
        ;

        //Avoid localhost issues
        $mailer->getTransport()->setLocalDomain('[127.0.0.1]');

        $mailer->send($message);

        return true;
    }


	public function sitemapAction()
    {
    	//Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Add pages to breadcrumb.
        $breadcrumbs->addItem("Home", $this->get("router")->generate("miles_apart_public_homepage"));
        $breadcrumbs->addItem("Help & Support", $this->get("router")->generate("miles_apart_public_help_and_support"));
        $breadcrumbs->addItem("Sitemap", $this->get("router")->generate("miles_apart_public_sitemap"));


        return $this->render('MilesApartPublicBundle:Page:sitemap.html.twig');
    }

    public function vanityurlAction($vanity_url)
    {
        //Set up the breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        
        //Set up entity manager.
        $em = $this->getDoctrine()->getManager();

        //Get the promotion that matches the vanity url
        $promotion = $em->getRepository('MilesApartAdminBundle:Promotion')->findCurrentPromotionByVanityUrl($vanity_url);

        if($promotion != NULL) {
            return $this->redirect($promotion[0]->getTrackingURL());
        } else {
            throw new \Symfony\Component\HttpKernel\Exception\HttpException(404, "Not found");
        }
    }


/*************** Create functions for retrieving categories ***************/

 
}
?>