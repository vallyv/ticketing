<?php
namespace Domain\Model;

use Domain\User\Model\User;

interface NotificationInterface
{
    public static function create(User $user, string $text): self;
    public function supports(): bool;
    public function send();

}