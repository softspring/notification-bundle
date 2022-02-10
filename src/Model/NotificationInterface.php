<?php

namespace Softspring\NotificationBundle\Model;

use Symfony\Component\Security\Core\User\UserInterface;

interface NotificationInterface
{
    public const CODE_UNDEFINED = 1000;

    public const LEVEL_NOTICE = 10;
    public const LEVEL_WARNING = 20;
    public const LEVEL_ERROR = 30;
    public const LEVEL_CRITICAL = 40;
    public const LEVEL_EMERGENCY = 50;

    public function getCreatedAt(): ?\DateTime;

    public function getUser(): ?UserInterface;

    public function setUser(UserInterface $user): void;

    public function isNew(): bool;

    public function setNew(bool $new): void;

    public function isRead(): bool;

    public function isUnread(): bool;

    /**
     * @param bool
     */
    public function setRead(bool $read): void;

    public function getReadAt(): ?\DateTime;

    public function setReadAt(?\DateTime $readAt): void;

    public function getMessageCode(): ?int;

    public function setMessageCode(int $messageCode): void;

    public function getMessageLevel(): ?int;

    public function setMessageLevel(int $messageLevel): void;

    public function getMessage(): ?array;

    public function setMessage(array $message): void;

    /**
     * Set read notification.
     */
    public function markRead(): void;

    /**
     * Set unread notification.
     */
    public function markUnread(): void;
}
