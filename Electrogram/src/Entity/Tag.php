<?php

namespace Electrogram\src\Entity;

class Tag
{
    private $id;
    private $messageId;
    private $emoji;
    private $description;
    private $filename;

    public function getId()
    {
        return $this->id;
    }

    public function getMessageId()
    {
        return $this->messageId;
    }

    public function getEmoji()
    {
        return $this->emoji;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getFilename()
    {
        return $this->filename;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setMessageId($messageId)
    {
        $this->messageId = $messageId;
    }

    public function setEmoji($emoji)
    {
        $this->emoji = $emoji;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function setFilename($filename)
    {
        $this->filename = $filename;
    }
}
