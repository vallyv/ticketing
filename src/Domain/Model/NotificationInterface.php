<?php
namespace Domain\Model;

use Domain\User\Model\User;

interface NotificationInterface
{
    public static function create(User $user, string $text);
    public function send();

}