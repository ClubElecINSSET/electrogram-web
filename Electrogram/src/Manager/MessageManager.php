<?php

namespace Electrogram\src\Manager;

use Electrogram\src\Entity\Message;
use PDO;

class MessageManager
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAllMessages($limit = null, $offset = null)
    {
        $query = "SELECT * FROM messages ORDER BY timestamp DESC";
        if ($limit) {
            $query .= " LIMIT :limit";
        }
        if ($offset) {
            $query .= " OFFSET :offset";
        }
        $stmt = $this->db->prepare($query);
        if ($limit) {
            $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
        }
        if ($offset) {
            $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);
        }
        $stmt->execute();

        $messages = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $message = new Message();
            $message->setId($row["id"]);
            $message->setContent($this->replaceUserIdWithUsername($row["content"]));
            $timestamp = strtotime($row["timestamp"]);
            $currentDate = date("Y-m-d");
            $messageDate = date("Y-m-d", $timestamp);

            if ($messageDate == $currentDate) {
                $message->setTimestamp(date("H:i", $timestamp));
            } elseif ($messageDate == date("Y-m-d", strtotime("-1 day", strtotime($currentDate)))) {
                $message->setTimestamp("hier à " . date("H:i", $timestamp));
            } else {
                $message->setTimestamp(date("d/m/Y H:i", $timestamp));
            }
            $message->setUserId($row["user_id"]);
            $messages[] = $message;
        }

        return $messages;
    }

    public function getMessagesByUserId($userId, $limit = null, $offset = null)
    {
        $query = "SELECT * FROM messages WHERE user_id = :userId ORDER BY timestamp DESC";
        if ($limit) {
            $query .= " LIMIT :limit";
        }
        if ($offset) {
            $query .= " OFFSET :offset";
        }
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":userId", $userId);
        if ($limit) {
            $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
        }
        if ($offset) {
            $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);
        }
        $stmt->execute();

        $messages = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $message = new Message();
            $message->setId($row["id"]);
            $message->setContent($this->replaceUserIdWithUsername($row["content"]));
            $timestamp = strtotime($row["timestamp"]);
            $currentDate = date("Y-m-d");
            $messageDate = date("Y-m-d", $timestamp);

            if ($messageDate == $currentDate) {
                $message->setTimestamp(date("H:i", $timestamp));
            } elseif ($messageDate == date("Y-m-d", strtotime("-1 day", strtotime($currentDate)))) {
                $message->setTimestamp("hier à " . date("H:i", $timestamp));
            } else {
                $message->setTimestamp(date("d/m/Y H:i", $timestamp));
            }
            $message->setUserId($row["user_id"]);
            $messages[] = $message;
        }

        return $messages;
    }

    public function getMessagesByTag($emoji, $limit = null, $offset = null)
    {
        $query = "SELECT m.* FROM messages m
              INNER JOIN tags t ON m.id = t.message_id
              WHERE t.emoji = :emoji
              ORDER BY m.timestamp DESC";
        if ($limit) {
            $query .= " LIMIT :limit";
        }
        if ($offset) {
            $query .= " OFFSET :offset";
        }
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":emoji", $emoji);
        if ($limit) {
            $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
        }
        if ($offset) {
            $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);
        }
        $stmt->execute();

        $messages = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $message = new Message();
            $message->setId($row["id"]);
            $message->setContent($this->replaceUserIdWithUsername($row["content"]));
            $message->setTimestamp($row["timestamp"]);
            $message->setUserId($row["user_id"]);
            $messages[] = $message;
        }

        return $messages;
    }

    public function getPostCountByTag($emoji)
    {
        $query = "SELECT t.emoji as tag_id, COUNT(*) as count FROM messages m
              INNER JOIN tags t ON m.id = t.message_id";
        if ($emoji !== null) {
            $query .= " WHERE t.emoji = :emoji";
        }
        $query .= " GROUP BY t.emoji";
        $stmt = $this->db->prepare($query);
        if ($emoji !== null) {
            $stmt->bindParam(":emoji", $emoji);
        }
        $stmt->execute();

        $postCounts = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $postCounts[$row["tag_id"]] = $row["count"];
        }

        return $postCounts;
    }

    public function getHeatmapDataByUserIdAndDate($userId, $startDate, $endDate)
    {
        $query = "SELECT DATE(timestamp) AS message_date, COUNT(*) AS message_count FROM messages WHERE user_id = :userId AND timestamp >= :startDate AND timestamp <= :endDate GROUP BY DATE(timestamp)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":userId", $userId);
        $stmt->bindParam(":startDate", $startDate);
        $stmt->bindParam(":endDate", $endDate);
        $stmt->execute();

        $messageCounts = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $messageCounts[] = [
                "date" => $row["message_date"],
                "value" => intval($row["message_count"])
            ];
        }

        return $messageCounts;
    }

    public function getFirstPostDateByUserId($userId)
    {
        $query = "SELECT MIN(timestamp) AS first_post_date FROM messages WHERE user_id = :userId";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":userId", $userId);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $firstPostDate = $row["first_post_date"];

        if ($firstPostDate) {
            return date("d/m/Y H:i", strtotime($firstPostDate));
        } else {
            return null;
        }
    }

    public function getLastPostDateByUserId($userId)
    {
        $query = "SELECT MAX(timestamp) AS last_post_date FROM messages WHERE user_id = :userId";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":userId", $userId);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $lastPostDate = $row["last_post_date"];

        if ($lastPostDate) {
            return date("d/m/Y H:i", strtotime($lastPostDate));
        } else {
            return null;
        }
    }

    public function getTotalMessagesByUserId($userId)
    {
        $query = "SELECT COUNT(*) FROM messages WHERE user_id = :userId";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":userId", $userId);
        $stmt->execute();

        return $stmt->fetchColumn();
    }

    public function getTotalMessagesByTag($emoji)
    {
        $query = "SELECT COUNT(*) FROM messages m
              INNER JOIN tags t ON m.id = t.message_id
              WHERE t.emoji = :emoji";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":emoji", $emoji);
        $stmt->execute();

        return $stmt->fetchColumn();
    }

    public function getTotalAllMessages()
    {
        $query = "SELECT COUNT(*) FROM messages";
        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt->fetchColumn();
    }

    public function replaceUserIdWithUsername($content)
    {
        $pattern = "/&lt;@(\\d+)&gt;/";
        $userManager = new UserManager($this->db);

        preg_match_all($pattern, $content, $matches);
        if (!empty($matches[1])) {
            foreach ($matches[1] as $userId) {
                $user = $userManager->getUserDataById($userId);
                if ($user) {
                    $username = $user->getUsername();
                    $content = str_replace("&lt;@" . $userId . "&gt;", "<a href=\"" . env("ext_url") . "/user/" . $username . "\" hx-get=\"" . env("ext_url") . "/api.php?type=user&value=" . $username . "\" hx-replace-url=\"" . env("ext_url") . "/user/" . $username . "\" hx-target=\"#htmx\" class=\"post-text-mention\">" . "@" . $username . "</a>", $content);
                }
            }
        }

        return $content;
    }
}
