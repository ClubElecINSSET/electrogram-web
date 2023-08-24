<?php

namespace Electrogram\src\Manager;

use Electrogram\src\Entity\Tag;
use PDO;

class TagManager
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getTagsByMessageId($messageId)
    {
        $sql = "SELECT * FROM tags WHERE message_id = :message_id ORDER BY id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([":message_id" => $messageId]);

        $tags = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $tag = new Tag();
            $tag->setId($row["id"]);
            $tag->setMessageId($row["message_id"]);
            $tag->setEmoji($row["emoji"]);
            $tag->setDescription($row["description"]);
            $tag->setFilename($row["filename"]);
            $tags[] = $tag;
        }

        return $tags;
    }

    public function getTagDataByMessageId($messageId)
    {
        $tags = $this->getTagsByMessageId($messageId);

        $tagData = [];
        foreach ($tags as $tag) {
            $tagInfo = new Tag();
            $tagInfo->setEmoji($tag->getEmoji());
            $tagInfo->setDescription($tag->getDescription());
            $tagInfo->setFilename($tag->getFilename());

            $tagData[] = $tagInfo;
        }

        return $tagData;
    }


    public function getTagCount()
    {
        $query = "SELECT COUNT(*) FROM tags";
        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt->fetchColumn();
    }

    public function countPostsByEmoji($emoji)
    {
        $query = "SELECT COUNT(*) FROM tags WHERE emoji = :emoji";
        $stmt = $this->db->prepare($query);
        $stmt->execute([":emoji" => $emoji]);

        return $stmt->fetchColumn();
    }

    public function getEmojiAndDescriptionByEmoji($emoji)
    {
        $query = "SELECT emoji, description, filename FROM tags WHERE emoji = :emoji";
        $stmt = $this->db->prepare($query);
        $stmt->execute([":emoji" => $emoji]);

        $tagData = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($tagData) {
            $tag = new Tag();
            $tag->setEmoji($tagData["emoji"]);
            $tag->setDescription($tagData["description"]);
            $tag->setFilename($tagData["filename"]);
            return $tag;
        }

        return null;
    }

    public function getTopUsedTags($limit)
    {
        $sql = "SELECT emoji, COUNT(*) as count FROM tags GROUP BY emoji ORDER BY count DESC LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
        $stmt->execute();

        $tags = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $tag = new Tag();
            $tag->setEmoji($row["emoji"]);

            $tags[] = $tag;
        }

        return $tags;
    }
}
