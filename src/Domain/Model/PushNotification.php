<?php
namespace Domain\Model;

use Domain\User\Model\User;

class PushNotification implements NotificationInterface
{
    private $text;
    private $created_at;
    private $receiver;

    public static function create(User $receiver, string $text)
    {
        $notification = new self();
        $notification->text = $text;
        $notification->receiver = $receiver;
        $notification->created_at = new \DateTime('now');

        return $notification;
    }

    public function send()
    {
        $this->sendPushNotification($this->receiver, $this->text);
    }

    private function sendPushNotification(User $receiver, string $text)
    {
        //operazioni che servono per spedire la notifica push

    }

}