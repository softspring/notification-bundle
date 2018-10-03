<?php

namespace Softspring\NotificationBundle\Model;

use Softspring\UserBundle\Model\UserInterface;

interface NotificationInterface
{
    const CODE_UNDEFINED = 1000;

    const LEVEL_NOTICE = 10;
    const LEVEL_WARNING = 20;
    const LEVEL_ERROR = 30;
    const LEVEL_CRITICAL = 40;
    const LEVEL_EMERGENCY = 50;

    /**
     * @return \DateTime|null
     */
    public function getCreatedAt(): ?\DateTime;

    /**
     * @return null|UserInterface
     */
    public function getUser(): ?UserInterface;

    /**
     * @param UserInterface $user
     */
    public function setUser(UserInterface $user): void;

    /**
     * @return bool
     */
    public function isRead(): bool;

    /**
     * @param bool
     */
    public function setRead(bool $read): void;

    /**
     * @return \DateTime|null
     */
    public function getReadAt(): ?\DateTime;

    /**
     * @param \DateTime|null $readAt
     */
    public function setReadAt(\DateTime $readAt): void;

    /**
     * @return int|null
     */
    public function getMessageCode(): ?int;

    /**
     * @param int $messageCode
     */
    public function setMessageCode(int $messageCode): void;

    /**
     * @return int|null
     */
    public function getMessageLevel(): ?int;

    /**
     * @param int $messageLevel
     */
    public function setMessageLevel(int $messageLevel): void;

    /**
     * @return array|null
     */
    public function getMessage(): ?array;

    /**
     * @param array $message
     */
    public function setMessage(array $message): void;
}