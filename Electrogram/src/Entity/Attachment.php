<?php

namespace Electrogram\src\Entity;

class Attachment
{
    private $id;
    private $messageId;
    private $filename;
    private $type;

    public function getId()
    {
        return $this->id;
    }

    public function getMessageId()
    {
        return $this->messageId;
    }

    public function getFilename()
    {
        return $this->filename;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setMessageId($messageId)
    {
        $this->messageId = $messageId;
    }

    public function setFilename($filename)
    {
        $this->filename = $filename;
    }

    public function setType($type)
    {
        $this->type = $type;
    }
}
