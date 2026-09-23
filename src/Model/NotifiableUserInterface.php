<?php

declare(strict_types=1);

namespace Softspring\NotificationBundle\Model;

use Doctrine\Common\Collections\Collection;

interface NotifiableUserInterface
{
    public function getNotifications(): Collection;

    public function hasUnreadNotifications(): bool;

    public function hasNewNotifications(): bool;

    public function getLastNotifications(): Collection;
}
