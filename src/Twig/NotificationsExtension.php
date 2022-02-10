<?php

namespace Softspring\NotificationBundle\Twig;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Softspring\NotificationBundle\Model\NotificationInterface;
use Softspring\UserBundle\Model\UserInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class NotificationsExtension extends AbstractExtension
{
    /**
     * @var TokenStorageInterface
     */
    protected $tokenStorage;

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var string
     */
    protected $notificationClass;

    /**
     * @var RouterInterface
     */
    protected $router;

    /**
     * NotificationsExtension constructor.
     */
    public function __construct(TokenStorageInterface $tokenStorage, EntityManagerInterface $em, TranslatorInterface $translator, string $notificationClass, RouterInterface $router)
    {
        $this->tokenStorage = $tokenStorage;
        $this->em = $em;
        $this->translator = $translator;
        $this->notificationClass = $notificationClass;
        $this->router = $router;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('unreadNotifications', [$this, 'unreadNotifications']),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('getUserNotifications', [$this, 'getUserNotifications']),
            new TwigFunction('notificationMessage', [$this, 'notificationMessage'], ['is_safe' => ['html']]),
            new TwigFunction('notificationMarkAsRead', [$this, 'notificationMarkAsRead']),
        ];
    }

    public function getUserNotifications(?int $limit = 4, bool $onlyUnread = false): Collection
    {
        if (!$token = $this->tokenStorage->getToken()) {
            return new ArrayCollection([]);
        }

        /** @var UserInterface $user */
        if (!$user = $token->getUser()) {
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
    public function unreadNotifications($collection): bool
    {
        $filterCallback = function (NotificationInterface $notification) {
            return !$notification->isRead();
        };

        if (is_array($collection)) {
            $unreadNotifications = array_filter($collection, $filterCallback);

            return !empty($unreadNotifications);
        } elseif ($collection instanceof Collection) {
            $unreadNotifications = $collection->filter($filterCallback);

            return (bool) $unreadNotifications->count();
        }

        return false;
    }

    public function notificationMarkAsRead(NotificationInterface $notification)
    {
        if ($notification->isRead()) {
            return;
        }

        $notification->setReadAt(new \DateTime('now'));
        $notification->setRead(true);

        $this->em->flush($notification);
    }
}
