<?php

declare(strict_types=1);

namespace Softspring\NotificationBundle\Tests\Fixtures;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Softspring\NotificationBundle\Model\NotifiableUserInterface;
use Softspring\NotificationBundle\Model\NotifiableUserTrait;

final class NotifiableUser implements NotifiableUserInterface
{
    use NotifiableUserTrait;

    private Collection $notifications;

    public function __construct()
    {
        $this->notifications = new ArrayCollection();
    }
}
