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

    /**
     * @return bool
     */
    public function hasUnreadNotifications(): bool
    {
        return (bool) $this->getNotifications()->filter(function (NotificationInterface $notification) {
            return $notification->isUnread();
        })->count();
    }

    /**
     * @return bool
     */
    public function hasNewNotifications(): bool
    {
        return (bool) $this->getNotifications()->filter(function (NotificationInterface $notification) {
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
            $notifications = $notifications->filter(function (NotificationInterface $notification) {
                return $notification->isUnread();
            });
        }

        return $notifications;
    }
}