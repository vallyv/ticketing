<?php
namespace Domain\Model;

use Domain\User\Model\User;
use phpDocumentor\Reflection\Types\This;

class EmailNotification implements NotificationInterface
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
        $this->sendMail($this->receiver, $this->text);
    }

    private function sendMail(User $receiver, string $text)
    {
        //operazioni che servono per spedire la mail

    }

}