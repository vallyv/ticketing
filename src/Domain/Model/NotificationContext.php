<?php

namespace Domain\Model;

use Domain\User\Model\User;
use iterable;

class NotificationContext
{
    private $notificationSystems = [];

    public function __construct(iterable $notifications)
    {
        foreach ($notifications as $notification) {
            if ($notification->supports()) {
                $this->notificationSystems[] = $notification;
            }
        }
    }

    public function send(User $user, string $text)
    {
        foreach ($this->notificationSystems as $notification){
            $notification->send($user, $text);
        }
    }

}