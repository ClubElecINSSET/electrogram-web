<?php

namespace Electrogram\src\Entity;

class Message
{
    private $id;
    private $content;
    private $timestamp;
    private $userId;

    public function getId()
    {
        return $this->id;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getTimestamp()
    {
        return $this->timestamp;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }

    public function setUserId($userId)
    {
        $this->userId = $userId;
    }
}
