<?php

namespace Domain\UseCase;

use Domain\Model\EmailNotification;
use Domain\Model\PushNotification;
use Domain\Model\SmsNotification;
use Domain\User\Model\User;

class SendUserNotifications
{

    public function execute(User $user, string $text)
    {
        $this->sendEmail($user, $text);
        $this->sendPushNotification($user, $text);
        $this->sendSms($user, $text);
    }

    private function sendEmail($user): EmailNotification
    {
        $notification = EmailNotification::create($user, text);
        $notification->send();
    }

    private function sendPushNotification($user): PushNotification
    {
        if($user->hasPushNotification()){
            $notification = PushNotification::create($user, text);
            $notification->send();
        }
    }

    private function sendSms($user)
    {
        if($user->hasSMSNotification()) {
            $notification = SmsNotification::create($user, text);
            $notification->send();
        }
    }
}