<?php

namespace Softspring\NotificationBundle\Entity\Embeddable;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable
 */
class UserNotificationPreference
{
    /**
     * @var bool
     * @ORM\Column(name="screen", type="boolean", nullable=false, options={"default": 0})
     */
    protected $screen = true;

    /**
     * @var bool
     * @ORM\Column(name="email", type="boolean", nullable=false, options={"default": 0})
     */
    protected $email = true;

    /**
     * @var bool
     * @ORM\Column(name="push", type="boolean", nullable=false, options={"default": 0})
     */
    protected $push = true;

    public function isScreen(): bool
    {
        return $this->screen;
    }

    public function setScreen(bool $screen): void
    {
        $this->screen = $screen;
    }

    public function isEmail(): bool
    {
        return $this->email;
    }

    public function setEmail(bool $email): void
    {
        $this->email = $email;
    }

    public function isPush(): bool
    {
        return $this->push;
    }

    public function setPush(bool $push): void
    {
        $this->push = $push;
    }
}
