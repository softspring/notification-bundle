<?php

namespace Softspring\NotificationBundle\Twig;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Softspring\NotificationBundle\Model\NotificationInterface;
use Softspring\UserBundle\Model\UserInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Attribute\AsTwigFilter;
use Twig\Attribute\AsTwigFunction;

class NotificationsExtension
{
    protected TokenStorageInterface $tokenStorage;
    protected EntityManagerInterface $em;
    protected TranslatorInterface $translator;
    protected string $notificationClass;
    protected RouterInterface $router;

    public function __construct(TokenStorageInterface $tokenStorage, EntityManagerInterface $em, TranslatorInterface $translator, string $notificationClass, RouterInterface $router)
    {
        $this->tokenStorage = $tokenStorage;
        $this->em = $em;
        $this->translator = $translator;
        $this->notificationClass = $notificationClass;
        $this->router = $router;
    }

    #[AsTwigFunction(name: 'getUserNotifications')]
    public function getUserNotifications(?int $limit = 4, bool $onlyUnread = false): Collection
    {
        if (!($token = $this->tokenStorage->getToken()) instanceof TokenInterface) {
            return new ArrayCollection([]);
        }

        /** @var ?UserInterface $user */
        $user = $token->getUser();

        if (!$user) {
            return new ArrayCollection([]);
        }

        $repo = $this->em->getRepository($this->notificationClass);

        $criteria = [
            'user' => $user,
        ];

        if ($onlyUnread) {
            $criteria['read'] = false;
        }

        $result = $repo->findBy($criteria, ['createdAt' => 'desc'], $limit);

        return new ArrayCollection($result);
    }

    #[AsTwigFunction(name: 'notificationMessage', isSafe: ['html'])]
    public function notificationMessage(NotificationInterface $notification): string
    {
        $message = $notification->getMessage();

        if (isset($message['raw'])) {
            return $message['raw'];
        }

        if (isset($message['domain']) && isset($message['id'])) {
            $messageId = "{$message['id']}";

            $data = is_array($message['data']) ? $message['data'] : [];
            $data['%markAsReadUrl%'] = $this->router->generate('sfs_notification_mark_read_notification', ['notification' => $notification->getId()]);

            return $this->translator->trans($messageId, $data, $message['domain']);
        }

        return '';
    }

    /**
     * @param array|Collection $collection
     */
    #[AsTwigFilter(name: 'unreadNotifications')]
    public function unreadNotifications($collection): bool
    {
        $filterCallback = function (NotificationInterface $notification): bool {
            return !$notification->isRead();
        };

        if (is_array($collection)) {
            $unreadNotifications = array_filter($collection, $filterCallback);

            return [] !== $unreadNotifications;
        } elseif ($collection instanceof Collection) {
            $unreadNotifications = $collection->filter($filterCallback);

            return (bool) $unreadNotifications->count();
        }

        return false;
    }

    #[AsTwigFunction(name: 'notificationMarkAsRead')]
    public function notificationMarkAsRead(NotificationInterface $notification): void
    {
        if ($notification->isRead()) {
            return;
        }

        $notification->setReadAt(new DateTime('now'));
        $notification->setRead(true);

        /* @phpstan-ignore-next-line */
        $this->em->flush($notification);
    }
}
