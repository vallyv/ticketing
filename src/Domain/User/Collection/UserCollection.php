<?php
namespace Domain\User\Collection;

class UserCollection
{
    private $users;

    public function __construct(Array $users)
    {
        $this->users = $users;
    }

    public function getUsers(): array
    {
        return $this->users;
    }
}