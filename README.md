# Notification Bundle

![Latest Stable](https://img.shields.io/packagist/v/softspring/notification-bundle?label=stable&style=flat-square)
![Latest Unstable](https://img.shields.io/packagist/v/softspring/notification-bundle?label=unstable&style=flat-square&include_prereleases)
![License](https://img.shields.io/packagist/l/softspring/notification-bundle?style=flat-square)
![PHP Version](https://img.shields.io/packagist/dependency-v/softspring/notification-bundle/php?style=flat-square)
![Downloads](https://img.shields.io/packagist/dt/softspring/notification-bundle?style=flat-square)
[![CI](https://img.shields.io/github/actions/workflow/status/softspring/notification-bundle/ci.yml?branch=5.3&style=flat-square&label=CI)](https://github.com/softspring/notification-bundle/actions/workflows/ci.yml)
![Coverage](https://raw.githubusercontent.com/softspring/notification-bundle/5.3/.github/badges/coverage.svg)

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
    use Softspring\Component\DoctrineTemplates\Entity\Traits\AutoId;
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
