<?php

namespace Softspring\NotificationBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

trait NotifiableUserTrait
{
    /**
     * @return Collection|NotificationInterface[]
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function hasUnreadNotifications(): bool
    {
        return (bool) $this->getNotifications()->filter(function (NotificationInterface $notification): bool {
            return $notification->isUnread();
        })->count();
    }

    public function hasNewNotifications(): bool
    {
        return (bool) $this->getNotifications()->filter(function (NotificationInterface $notification): bool {
            return $notification->isNew();
        })->count();
    }

    /**
     * @return Collection|NotificationInterface[]
     */
    public function getLastNotifications(?int $limit = 4, bool $onlyUnread = true): Collection
    {
        $notifications = $this->getNotifications();

        if (null !== $limit) {
            $notifications = new ArrayCollection($notifications->slice(0, $limit));
        }

        if ($onlyUnread) {
            $notifications = $notifications->filter(function (NotificationInterface $notification): bool {
                return $notification->isUnread();
            });
        }

        return $notifications;
    }
}
