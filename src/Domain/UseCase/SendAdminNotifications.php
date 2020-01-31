<?php

namespace Domain\UseCase;

use Domain\Model\EmailNotification;
use Domain\Model\PushNotification;
use Domain\Model\SmsNotification;
use Domain\User\Repository\UserRepository;

class SendAdminNotifications
{
    private $admins;

    public function __construct(UserRepository $repo)
    {
        $this->admins = $repo->loadAdmins();
    }

    public function execute(string $text)
    {
        foreach ($this->admins as $admin) {
            $this->sendEmail($admin, $text);
            $this->sendPushNotification($admin, $text);
            $this->sendSms($admin, $text);
        }

        return true;
    }

    private function sendEmail($user, $text)
    {
        $notification = EmailNotification::create($user, $text);
        $notification->send();
    }

    private function sendPushNotification($user, $text)
    {
        if($user->hasPushNotification()){
            $notification = PushNotification::create($user, $text);
            $notification->send();
        }
    }

    private function sendSms($user, $text)
    {
        if($user->hasSMSNotification()) {
            $notification = SmsNotification::create($user, $text);
            $notification->send();
        }
    }
}