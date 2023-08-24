<?php

namespace Electrogram\src\Manager;

use Electrogram\src\Entity\Attachment;
use PDO;

class AttachmentManager
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAttachmentsByMessageId($messageId)
    {
        $query = "SELECT * FROM attachments WHERE message_id = :messageId";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":messageId", $messageId);
        $stmt->execute();

        $attachments = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $attachment = new Attachment();
            $attachment->setId($row["id"]);
            $attachment->setMessageId($row["message_id"]);
            $attachment->setFilename($row["filename"]);
            $attachment->setType($row["type"]);
            $attachments[] = $attachment;
        }

        return $attachments;
    }
}
