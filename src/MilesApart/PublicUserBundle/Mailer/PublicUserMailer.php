<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MilesApart\PublicUserBundle\Mailer;

use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Mailer\MailerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @author Christophe Coevoet <stof@notk.org>
 */
class PublicUserMailer implements MailerInterface
{
    protected $mailer;
    protected $router;
    protected $twig;


    public function __construct(\Swift_Mailer $mailer, UrlGeneratorInterface $router, \Twig_Environment $twig)
    {
        $this->mailer = $mailer;
        $this->router = $router;
        $this->twig = $twig;

    }

    public function sendConfirmationEmailMessage(UserInterface $user)
    {
        $template = "MilesApartPublicBundle:Emails:registration_confirmation.email.twig";
        $url = $this->router->generate('fos_user_registration_confirm', array('token' => $user->getConfirmationToken()), true);
        $context = array(
            'user' => $user,
            'confirmationUrl' => $url
        );

        $this->sendMessage($template, $context, array('useraccounts@miles-apart.com' => 'Miles Apart User Accounts'), $user->getEmail());
    }

    public function sendResettingEmailMessage(UserInterface $user)
    {
        $template = "MilesApartPublicBundle:Emails:password_resetting.email.twig";
        $url = $this->router->generate('fos_user_resetting_reset', array('token' => $user->getConfirmationToken()), true);
        $context = array(
            'user' => $user,
            'confirmationUrl' => $url
        );
        $this->sendMessage($template, $context, array('useraccounts@miles-apart.com' => 'Miles Apart User Accounts'), $user->getEmail());
    }

    /**
     * @param string $templateName
     * @param array  $context
     * @param string $fromEmail
     * @param string $toEmail
     */
    protected function sendMessage($templateName, $context, $fromEmail, $toEmail)
    {
        $template = $this->twig->loadTemplate($templateName);
        $subject = $template->renderBlock('subject', $context);
        $textBody = $template->renderBlock('body_text', $context);
        $htmlBody = $template->renderBlock('body_html', $context);

        //Set up the mailer
        $mailer = $this->mailer;

        //Get the supplier email address.
        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($fromEmail)
            ->setTo($toEmail);
        if (!empty($htmlBody)) {
            $message->setContentType('text/html');
            $message->setBody($htmlBody);
        } else {
            $message->setBody($textBody);
        }

        //Avoid localhost issues
        $mailer->getTransport()->setLocalDomain('[127.0.0.1]');

        //Send the email
        $mailer->send($message);

        return true;

    }
}