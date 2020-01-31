<?php
namespace Domain\User\Model;

use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface, \Serializable
{
    const ROLE_ADMIN = 'ROLE_ADMIN';

    const ROLE_USER =  'ROLE_USER';

    private $id;

    private $username;

    private $password;

    private $email;

    private $roles;

    private $isActive;

    private $isAdmin;

    public static function create($username, $password, $email, $role = null): User
    {
        $user = new self();
        $user->username = $username;
        $user->password = $password;
        $user->email = $email;
        $user->roles[] = self::ROLE_USER;
        $user->isAdmin = false;

        if ($role == 'admin'){
            $user->roles[] = self::ROLE_ADMIN;
            $user->isAdmin = true;
        }

        return $user;
    }

    public function __construct()
    {
        $this->isActive = true;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getSalt()
    {
        return null;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function isAdmin(): bool
    {
        return $this->isAdmin;
    }

    public function hasSMSNotification(): bool
    {
        return true;
    }

    public function hasPushNotification(): bool
    {
        return true;
    }


    public function eraseCredentials()
    {
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize([
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt,
        ]);
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt
            ) = unserialize($serialized, ['allowed_classes' => false]);
    }
}