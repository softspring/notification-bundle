<?php

namespace Softspring\NotificationBundle\Model;

use Doctrine\Common\Collections\Collection;

interface NotifiableUserInterface
{
    /**
     * @return Collection|NotificationInterface[]
     */
    public function getNotifications(): Collection;

    public function hasUnreadNotifications(): bool;

    public function hasNewNotifications(): bool;

    /**
     * @return Collection|NotificationInterface[]
     */
    public function getLastNotifications(): Collection;
}
