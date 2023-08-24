<?php

namespace Electrogram\src\Entity;

class Streak
{
    private $userId;
    private $streak;
    private $maxStreak;
    private $lastMessageDate;

    public function getUserId()
    {
        return $this->userId;
    }

    public function getStreak()
    {
        return $this->streak;
    }

    public function getMaxStreak()
    {
        return $this->maxStreak;
    }

    public function getLastMessageDate()
    {
        return $this->lastMessageDate;
    }

    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    public function setStreak($streak)
    {
        $this->streak = $streak;
    }

    public function setMaxStreak($maxStreak)
    {
        $this->maxStreak = $maxStreak;
    }

    public function setLastMessageDate($lastMessageDate)
    {
        $this->lastMessageDate = $lastMessageDate;
    }
}
