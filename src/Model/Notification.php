<?php

namespace Softspring\NotificationBundle\Model;

use Symfony\Component\Security\Core\User\UserInterface;

abstract class Notification implements NotificationInterface
{
    /**
     * @var UserInterface|null
     */
    protected $user;

    /**
     * @var \DateTime|null
     */
    protected $createdAt;

    /**
     * @var boolean
     */
    protected $new = true;

    /**
     * @var boolean
     */
    protected $read = false;

    /**
     * @var \DateTime|null
     */
    protected $readAt;

    /**
     * @var integer|null
     */
    protected $messageCode = self::CODE_UNDEFINED;

    /**
     * @var integer|null
     */
    protected $messageLevel = self::LEVEL_NOTICE;

    /**
     * @var array|null
     *
     * Options:
     *     {"raw":"This is a raw message"}
     *     {"domain":"translations_domain", "id":"test.message"}
     *     {"domain":"translations_domain", "id":"test.message", "data":{"plant": "Plant name"}}
     */
    protected $message;

    /**
     * @return null|UserInterface
     */
    public function getUser(): ?UserInterface
    {
        return $this->user;
    }

    /**
     * @param null|UserInterface $user
     */
    public function setUser(?UserInterface $user): void
    {
        $this->user = $user;
    }

    /**
     * @inheritdoc
     */
    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    /**
     * Auto set created at
     */
    public function autoSetCreatedAt()
    {
        $this->createdAt = new \DateTime('now');
    }

    /**
     * @return bool
     */
    public function isNew(): bool
    {
        return $this->new;
    }

    /**
     * @param bool $new
     */
    public function setNew(bool $new): void
    {
        $this->new = $new;
    }

    /**
     * @inheritdoc
     */
    public function isRead(): bool
    {
        return $this->read;
    }

    /**
     * @inheritdoc
     */
    public function isUnread(): bool
    {
        return !$this->isRead();
    }

    /**
     * @inheritdoc
     */
    public function setRead(bool $read): void
    {
        $this->read = $read;

        if (!$this->getReadAt()) {
            $this->setReadAt(new \DateTime('now'));
        }
    }

    /**
     * @inheritdoc
     */
    public function getReadAt(): ?\DateTime
    {
        return $this->readAt;
    }

    /**
     * @inheritdoc
     */
    public function setReadAt(\DateTime $readAt): void
    {
        $this->readAt = $readAt;

        if (!$this->isRead()) {
            $this->setRead(true);
        }
    }

    /**
     * @inheritdoc
     */
    public function getMessageCode(): ?int
    {
        return $this->messageCode;
    }

    /**
     * @inheritdoc
     */
    public function setMessageCode(int $messageCode): void
    {
        $this->messageCode = $messageCode;
    }

    /**
     * @inheritdoc
     */
    public function getMessageLevel(): ?int
    {
        return $this->messageLevel;
    }

    /**
     * @inheritdoc
     */
    public function setMessageLevel(int $messageLevel): void
    {
        $this->messageLevel = $messageLevel;
    }

    /**
     * @inheritdoc
     */
    public function getMessage(): ?array
    {
        return $this->message;
    }

    /**
     * @inheritdoc
     */
    public function setMessage(array $message): void
    {
        $this->message = $message;
    }
}