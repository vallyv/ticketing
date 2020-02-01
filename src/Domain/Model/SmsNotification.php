<?php
namespace Domain\Model;

use Domain\User\Model\User;

class SmsNotification implements NotificationInterface
{
    private $text;
    private $created_at;
    private $receiver;

    public static function create(User $receiver, string $text): NotificationInterface
    {
        $notification = new self();
        $notification->text = $text;
        $notification->receiver = $receiver;
        $notification->created_at = new \DateTime('now');

        return $notification;
    }

    public function supports(): bool
    {
        return true;
    }

    public function send()
    {
        $this->sendSms($this->receiver, $this->text);
    }

    private function sendSms(User $receiver, string $text)
    {
        //operazioni che servono per spedire un sms
    }

}