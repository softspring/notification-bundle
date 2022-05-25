# SfsNotificationBundle

[![Latest Stable Version](https://poser.pugx.org/softspring/notification-bundle/v/stable.svg)](https://packagist.org/packages/softspring/notification-bundle)
[![Latest Unstable Version](https://poser.pugx.org/softspring/notification-bundle/v/unstable.svg)](https://packagist.org/packages/softspring/notification-bundle)
[![License](https://poser.pugx.org/softspring/notification-bundle/license.svg)](https://packagist.org/packages/softspring/notification-bundle)
[![Total Downloads](https://poser.pugx.org/softspring/notification-bundle/downloads)](https://packagist.org/packages/softspring/notification-bundle)
[![Build status](https://github.com/softspring/notification-bundle/actions/workflows/php.yml/badge.svg?branch=5.0)](https://github.com/softspring/notification-bundle/actions/workflows/php.yml)

## Installation

### Configure Bundle

If you use flex, you should not need to do this. But if you don't or something goes wrong, you must add include
the bundle in config/bundles.php file:

    <?php
    
    return [
        ...
        Softspring\NotificationBundle\SfsNotificationBundle::class => ['all' => true],
    ];

### Configure ORM

Create your Notification entity:

    <?php
    
    namespace App\Entity;
    
    use Doctrine\ORM\Mapping as ORM;
    use Softspring\DoctrineTemplates\Entity\Traits\AutoId;
    use Softspring\NotificationBundle\Model\Notification as SfsNotification;
    
    /**
     * @ORM\Entity()
     * @ORM\Table(name="notification")
     */
    class Notification extends SfsNotification
    {
        use AutoId;
        
        /**
         * @var User|null
         * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="notifications")
         * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
         */
        protected $user;
        
        /**
         * @inheritdoc
         */
        public function getUser(): ?UserInterface
        {
            return $this->user;
        }
    
        /**
         * @inheritdoc
         */
        public function setUser(UserInterface $user): void
        {
            $this->user = $user;
        }
    }


Create config/packages/sfs_notification.yaml file with your entity configuration

    sfs_notification:
        notification_class: App\Entity\Notification
