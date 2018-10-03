<?php

namespace Softspring\NotificationBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Softspring\NotificationBundle\Model\NotificationInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class NotificationController extends Controller
{
    public function viewAll(Request $request): Response
    {
        $notifications = $this->getDoctrine()->getRepository($this->getParameter('sfs_notification.model.notification.class'))->findAll();

        return $this->render('@SfsNotification/Notification/view_all.html.twig', [
            'notifications' => $notifications,
        ]);
    }

    /**
     * @ Security("notification.user == user")
     */
    public function markAsRead(NotificationInterface $notification, Request $request): Response
    {
        return new Response();
    }

    public function markAllAsRead(Request $request): Response
    {
        return new Response();
    }
}