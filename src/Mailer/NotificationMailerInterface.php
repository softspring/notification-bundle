<?php

namespace Softspring\NotificationBundle\Mailer;

use FOS\UserBundle\Model\UserInterface;

interface NotificationMailerInterface
{
    public function sendNotification(string $templateName, UserInterface $user, array $context = []);
}