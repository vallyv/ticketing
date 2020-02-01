<?php

namespace Domain\UseCase;

use Domain\Model\NotificationContext;
use Domain\User\Repository\UserRepository;

class SendAdminNotifications
{
    private $admins;
    private $notificationContext;

    public function __construct(UserRepository $repo, NotificationContext $notificationContext)
    {
        $this->admins = $repo->loadAdmins();
        $this->notificationContext = $notificationContext;
    }

    public function execute(string $text)
    {
        foreach ($this->admins as $admin) {
            $this->notificationContext->send($admin, $text);
        }

        return true;
    }
}