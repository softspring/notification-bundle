<?php

namespace Softspring\NotificationBundle\Twig;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Softspring\NotificationBundle\Model\NotificationInterface;
use Softspring\UserBundle\Model\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Twig\Extension\AbstractExtension;
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
     * NotificationsExtension constructor.
     *
     * @param TokenStorageInterface  $tokenStorage
     * @param EntityManagerInterface $em
     * @param TranslatorInterface    $translator
     * @param string                 $notificationClass
     */
    public function __construct(TokenStorageInterface $tokenStorage, EntityManagerInterface $em, TranslatorInterface $translator, string $notificationClass)
    {
        $this->tokenStorage = $tokenStorage;
        $this->em = $em;
        $this->translator = $translator;
        $this->notificationClass = $notificationClass;
    }

    /**
     * @inheritdoc
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('getUserNotifications', [$this, 'getUserNotifications']),
            new TwigFunction('notificationMessage', [$this, 'notificationMessage'], ['is_safe'=>['html']]),
        ];
    }

    /**
     * @param int|null $limit
     *
     * @return Collection
     */
    public function getUserNotifications(?int $limit = 4): Collection
    {
        if (!$token = $this->tokenStorage->getToken()) {
            return new ArrayCollection([]);
        }

        /** @var UserInterface $user */
        if (!$user = $token->getUser()) {
            return new ArrayCollection([]);
        }

        $repo = $this->em->getRepository($this->notificationClass);

        $result = $repo->findBy([
            'user' => $user,
            'read' => false,
        ], ['createdAt' => 'desc'], $limit);

        return new ArrayCollection($result);
    }

    public function notificationMessage(NotificationInterface $notification): string
    {
        $message = $notification->getMessage();

        if (isset($message['raw'])) {
            return $message['raw'];
        }

        if (isset($message['domain']) && isset($message['id'])) {
            return $this->translator->trans($message['id'], is_array($message['data']) ? $message['data'] : [], $message['domain']);
        }

        return '';
    }
}