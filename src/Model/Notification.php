<?php

namespace Softspring\NotificationBundle\Model;

use Symfony\Component\Security\Core\User\UserInterface;

abstract class Notification implements NotificationInterface
{
    protected ?UserInterface $user = null;

    protected ?\DateTime $createdAt = null;

    protected bool $new = true;

    protected bool $read = false;

    protected ?\DateTime $readAt = null;

    protected int $messageCode = self::CODE_UNDEFINED;

    protected int $messageLevel = self::LEVEL_NOTICE;

    /**
     * Options:
     *     {"raw":"This is a raw message"}
     *     {"domain":"translations_domain", "id":"test.message"}
     *     {"domain":"translations_domain", "id":"test.message", "data":{"plant": "Plant name"}}
     */
    protected ?array $message = null;

    public function getUser(): ?UserInterface
    {
        return $this->user;
    }

    public function setUser(?UserInterface $user): void
    {
        $this->user = $user;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function autoSetCreatedAt()
    {
        $this->createdAt = new \DateTime('now');
    }

    public function isNew(): bool
    {
        return $this->new;
    }

    public function setNew(bool $new): void
    {
        $this->new = $new;
    }

    public function isRead(): bool
    {
        return $this->read;
    }

    public function isUnread(): bool
    {
        return !$this->isRead();
    }

    public function setRead(bool $read): void
    {
        $this->read = $read;

        if (!$this->getReadAt()) {
            $this->setReadAt(new \DateTime('now'));
        }
    }

    public function getReadAt(): ?\DateTime
    {
        return $this->readAt;
    }

    public function setReadAt(?\DateTime $readAt): void
    {
        $this->readAt = $readAt;

        if (!$this->isRead()) {
            $this->setRead(true);
        }
    }

    public function getMessageCode(): ?int
    {
        return $this->messageCode;
    }

    public function setMessageCode(int $messageCode): void
    {
        $this->messageCode = $messageCode;
    }

    public function getMessageLevel(): ?int
    {
        return $this->messageLevel;
    }

    public function setMessageLevel(int $messageLevel): void
    {
        $this->messageLevel = $messageLevel;
    }

    public function getMessage(): ?array
    {
        return $this->message;
    }

    public function setMessage(array $message): void
    {
        $this->message = $message;
    }

    public function markRead(): void
    {
        $this->read = true;
        $this->readAt = new \DateTime('now');
    }

    public function markUnread(): void
    {
        $this->read = false;
        $this->readAt = null;
    }
}
