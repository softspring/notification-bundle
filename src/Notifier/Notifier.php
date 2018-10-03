<?php

namespace Softspring\NotificationBundle\Notifier;

use Doctrine\ORM\EntityManagerInterface;
use Softspring\NotificationBundle\Model\NotificationInterface;
use Softspring\UserBundle\Model\UserInterface;

class Notifier
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var string
     */
    protected $notificationClass;

    /**
     * Notifier constructor.
     *
     * @param string                 $notificationClass
     * @param EntityManagerInterface $em
     */
    public function __construct(string $notificationClass, EntityManagerInterface $em)
    {
        $this->notificationClass = $notificationClass;
        $this->em = $em;
    }

    /**
     * @param UserInterface  $user
     * @param array $message
     * @param int   $messageLevel
     * @param int   $messageCode
     *
     * @return NotificationInterface
     */
    public function createNotification(UserInterface $user, array $message, $messageLevel = NotificationInterface::LEVEL_NOTICE, int $messageCode = NotificationInterface::CODE_UNDEFINED): NotificationInterface
    {
        $notificationClass = $this->notificationClass;

        /** @var NotificationInterface $notification */
        $notification = new $notificationClass();

        $notification->setUser($user);
        $notification->setMessageCode($messageCode);
        $notification->setMessageLevel($messageLevel);
        $notification->setMessage($message);
        // $notification->setClient();
        // $notification->setPlant();

        return $notification;
    }


    public function notifyUser(UserInterface $user, array $message, $messageLevel = NotificationInterface::LEVEL_NOTICE, int $messageCode = NotificationInterface::CODE_UNDEFINED): void
    {
        $notification = $this->createNotification($user, $message, $messageLevel, $messageCode);
        $this->em->persist($notification);
        $this->em->flush($notification);
    }

    public function noticeUserRaw(UserInterface $user, string $messageRaw, int $messageCode = NotificationInterface::CODE_UNDEFINED): void
    {
        $this->notifyUser($user, ['raw' => $messageRaw], NotificationInterface::LEVEL_NOTICE, $messageCode);
    }

    public function warningUserRaw(UserInterface $user, string $messageRaw, int $messageCode = NotificationInterface::CODE_UNDEFINED): void
    {
        $this->notifyUser($user, ['raw' => $messageRaw], NotificationInterface::LEVEL_WARNING, $messageCode);
    }

    public function errorUserRaw(UserInterface $user, string $messageRaw, int $messageCode = NotificationInterface::CODE_UNDEFINED): void
    {
        $this->notifyUser($user, ['raw' => $messageRaw], NotificationInterface::LEVEL_ERROR, $messageCode);
    }

    public function criticalUserRaw(UserInterface $user, string $messageRaw, int $messageCode = NotificationInterface::CODE_UNDEFINED): void
    {
        $this->notifyUser($user, ['raw' => $messageRaw], NotificationInterface::LEVEL_CRITICAL, $messageCode);
    }

    public function emergencyUserRaw(UserInterface $user, string $messageRaw, int $messageCode = NotificationInterface::CODE_UNDEFINED): void
    {
        $this->notifyUser($user, ['raw' => $messageRaw], NotificationInterface::LEVEL_EMERGENCY, $messageCode);
    }

    public function noticeUser(UserInterface $user, string $messageId, int $messageCode = NotificationInterface::CODE_UNDEFINED, string $translationDomain = 'notification', array $messageData = []): void
    {
        $this->notifyUser($user, ['id' => $messageId, 'domain' => $translationDomain, 'data' => $messageData], NotificationInterface::LEVEL_NOTICE, $messageCode);
    }

    public function warningUser(UserInterface $user, string $messageId, int $messageCode = NotificationInterface::CODE_UNDEFINED, string $translationDomain = 'notification', array $messageData = []): void
    {
        $this->notifyUser($user, ['id' => $messageId, 'domain' => $translationDomain, 'data' => $messageData], NotificationInterface::LEVEL_WARNING, $messageCode);
    }

    public function errorUser(UserInterface $user, string $messageId, int $messageCode = NotificationInterface::CODE_UNDEFINED, string $translationDomain = 'notification', array $messageData = []): void
    {
        $this->notifyUser($user, ['id' => $messageId, 'domain' => $translationDomain, 'data' => $messageData], NotificationInterface::LEVEL_ERROR, $messageCode);
    }

    public function criticalUser(UserInterface $user, string $messageId, int $messageCode = NotificationInterface::CODE_UNDEFINED, string $translationDomain = 'notification', array $messageData = []): void
    {
        $this->notifyUser($user, ['id' => $messageId, 'domain' => $translationDomain, 'data' => $messageData], NotificationInterface::LEVEL_CRITICAL, $messageCode);
    }

    public function emergencyUser(UserInterface $user, string $messageId, int $messageCode = NotificationInterface::CODE_UNDEFINED, string $translationDomain = 'notification', array $messageData = []): void
    {
        $this->notifyUser($user, ['id' => $messageId, 'domain' => $translationDomain, 'data' => $messageData], NotificationInterface::LEVEL_EMERGENCY, $messageCode);
    }
}