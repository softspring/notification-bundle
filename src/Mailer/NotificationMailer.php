<?php

namespace Softspring\NotificationBundle\Mailer;

use FOS\UserBundle\Model\UserInterface;

class NotificationMailer implements NotificationMailerInterface
{
    /**
     * @var \Swift_Mailer
     */
    protected $mailer;

    /**
     * @var \Twig_Environment
     */
    protected $twig;

    /**
     * @var string
     */
    protected $fromEmail;

    /**
     * @var string
     */
    protected $fromName;

    /**
     * NotificationMailer constructor.
     */
    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $twig, string $fromEmail, string $fromName = null)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->fromEmail = $fromEmail;
        $this->fromName = $fromName;
    }

    /**
     * @throws \Throwable
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function sendNotification(string $templateName, UserInterface $user, array $context = [], string $locale = null)
    {
        $context['_locale'] = $locale;

        $this->sendMessage($templateName, $context, (string) $user->getEmail());
    }

    /**
     * @param string $templateName
     * @param array  $context
     * @param string $toEmail
     *
     * @throws \Throwable
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    protected function sendMessage($templateName, $context, $toEmail)
    {
        $template = $this->twig->load($templateName);
        $subject = $template->renderBlock('subject', $context);
        $textBody = $template->renderBlock('body_text', $context);

        $htmlBody = '';

        if ($template->hasBlock('body_html', $context)) {
            $htmlBody = $template->renderBlock('body_html', $context);
        }

        $message = (new \Swift_Message())
            ->setSubject($subject)
            ->setFrom($this->fromEmail, $this->fromName)
            ->setTo($toEmail);

        if (!empty($htmlBody)) {
            $message->setBody($htmlBody, 'text/html')
                ->addPart($textBody, 'text/plain');
        } else {
            $message->setBody($textBody);
        }

        $this->mailer->send($message);
    }
}
