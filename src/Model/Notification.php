<?php

namespace Softspring\NotificationBundle\Model;

abstract class Notification implements NotificationInterface
{
    /**
     * @var \DateTime|null
     */
    protected $createdAt;

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
     * @inheritdoc
     */
    public function isRead(): bool
    {
        return $this->read;
    }

    /**
     * @inheritdoc
     */
    public function setRead(bool $read): void
    {
        $this->read = $read;
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