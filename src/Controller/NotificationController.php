<?php

namespace Softspring\NotificationBundle\Controller;

use Doctrine\ORM\EntityRepository;
use Softspring\NotificationBundle\Model\NotificationInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class NotificationController extends Controller
{
    public function viewAll(Request $request): Response
    {
        $notifications = $this->getRepository()->findBy([
            'user' => $this->getUser(),
        ], ['createdAt' => 'desc']);

        $this->markAllAsNotNewAjax($request);

        return $this->render('@SfsNotification/Notification/view_all.html.twig', [
            'notifications' => $notifications,
        ]);
    }

    public function markAsRead(string $notification, Request $request): RedirectResponse
    {
        $notification = $this->getNotification($notification);

        $notification->markRead();

        $this->getDoctrine()->getManager()->flush();

        return $this->redirect($request->query->get('continue', '/'));
    }

    public function markAsReadAjax(string $notification): Response
    {
        $notification = $this->getNotification($notification);

        $notification->markRead();

        $this->getDoctrine()->getManager()->flush();

        return new Response('', Response::HTTP_NO_CONTENT);
    }

    public function markAsUnreadAjax(string $notification): Response
    {
        $notification = $this->getNotification($notification);

        $notification->markUnread();

        $this->getDoctrine()->getManager()->flush();

        return new Response('', Response::HTTP_NO_CONTENT);
    }

    public function markAllAsReadAjax(): Response
    {
        $notifications = $this->getDoctrine()->getRepository($this->getParameter('sfs_notification.model.notification.class'))->findBy([
            'user' => $this->getUser(),
            'read' => false,
        ]);

        /** @var NotificationInterface $notification */
        foreach ($notifications as $notification) {
            $notification->setReadAt(new \DateTime('now'));
            $notification->setRead(true);
        }

        $this->getDoctrine()->getManager()->flush();

        return new Response('', Response::HTTP_NO_CONTENT);
    }

    public function markAllAsNotNewAjax(Request $request): Response
    {
        $notifications = $this->getDoctrine()->getRepository($this->getParameter('sfs_notification.model.notification.class'))->findBy([
            'user' => $this->getUser(),
            'new' => true,
        ]);

        // TODO $request->query->get('date');

        /** @var NotificationInterface $notification */
        foreach ($notifications as $notification) {
            $notification->setNew(false);
        }

        $this->getDoctrine()->getManager()->flush();

        return new Response('', Response::HTTP_NO_CONTENT);
    }

    protected function getRepository(): EntityRepository
    {
        return $this->getDoctrine()->getRepository($this->getParameter('sfs_notification.model.notification.class'));
    }

    /**
     * @param string $notificationId
     *
     * @return NotificationInterface
     * @throws \Doctrine\ORM\ORMException
     * @throws AccessDeniedException
     */
    protected function getNotification(string $notificationId): NotificationInterface
    {
        $notification = $this->getRepository()->findOneById($notificationId);

        if ($notification->getUser() != $this->getUser()) {
            throw $this->createAccessDeniedException('Notification is not owned by user');
        }

        return $notification;
    }
}