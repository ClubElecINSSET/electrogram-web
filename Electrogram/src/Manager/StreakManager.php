<?php

namespace Electrogram\src\Manager;

use Electrogram\src\Entity\Streak;
use PDO;
use DateTime;

class StreakManager
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getStreakByUserId($userId)
    {
        $query = "SELECT * FROM streaks WHERE user_id = :userId";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":userId", $userId);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $streak = new Streak();
            $streak->setUserId($row["user_id"]);

            $lastMessageDate = new DateTime($row["last_message_date"]);
            $now = new DateTime();

            $interval = $lastMessageDate->diff($now);

            if ($interval->days > 2) {
                $streak->setStreak(null);
                if ($row["streak"] > $row["max_streak"]) {
                    $streak->setMaxStreak($row["streak"]);
                } else {
                    $streak->setMaxStreak($row["max_streak"]);
                }
            } else {
                $streak->setStreak($row["streak"]);
                $streak->setMaxStreak($row["max_streak"]);
            }

            $streak->setLastMessageDate($row["last_message_date"]);

            return $streak;
        }

        return null;
    }

    public function getTopUsersByStreak($limit = 10)
    {
        $query = "SELECT s.user_id, s.streak, s.max_streak, u.username, u.display_name, u.avatar FROM streaks s
              INNER JOIN users u ON s.user_id = u.id
              WHERE s.streak IS NOT NULL AND s.last_message_date >= DATE_SUB(NOW(), INTERVAL 2 DAY)
              ORDER BY s.streak DESC LIMIT :limit";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":limit", $limit, PDO::PARAM_INT);
        $stmt->execute();

        $ranking = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $streak = new Streak();
            $streak->setUserId($row["user_id"]);
            $streak->setStreak($row["streak"]);
            $streak->setMaxStreak($row["max_streak"]);

            $ranking[] = $streak;
        }

        return $ranking;
    }
    public function getTopUsersByMaxStreak($limit = 10)
    {
        $query = "SELECT s.user_id, s.streak, s.max_streak, u.username, u.display_name, u.avatar FROM streaks s
              INNER JOIN users u ON s.user_id = u.id
              ORDER BY s.max_streak DESC LIMIT :limit";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":limit", $limit, PDO::PARAM_INT);
        $stmt->execute();

        $ranking = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $streak = new Streak();
            $streak->setUserId($row["user_id"]);
            $streak->setStreak($row["streak"]);
            $streak->setMaxStreak($row["max_streak"]);

            $ranking[] = $streak;
        }

        return $ranking;
    }
}
