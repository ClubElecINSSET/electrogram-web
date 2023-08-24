<?php

namespace Electrogram\src\Entity;

class User
{
    private $id;
    private $username;
    private $displayName;
    private $avatar;

    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getDisplayName()
    {
        return $this->displayName;
    }

    public function getAvatar()
    {
        return $this->avatar;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
    }

    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
    }
}
