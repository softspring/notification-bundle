<?php

namespace Softspring\NotificationBundle\Entity\Embeddable;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable
 */
class UserNotificationPreference
{
    /**
     * @ORM\Column(name="screen", type="boolean", nullable=false, options={"default": 0})
     */
    protected bool $screen = true;

    /**
     * @ORM\Column(name="email", type="boolean", nullable=false, options={"default": 0})
     */
    protected bool $email = true;

    /**
     * @ORM\Column(name="push", type="boolean", nullable=false, options={"default": 0})
     */
    protected bool $push = true;

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
