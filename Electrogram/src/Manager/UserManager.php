<?php

namespace Electrogram\src\Manager;

use Electrogram\src\Entity\User;
use PDO;

class UserManager
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAllUsers()
    {
        $query = "SELECT * FROM users";
        $stmt = $this->db->prepare($query);
        $stmt->execute();

        $users = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $user = new User();
            $user->setId($row["id"]);
            $user->setUsername($row["username"]);
            $user->setDisplayName($row["display_name"]);
            $user->setAvatar($row["avatar"]);
            $users[] = $user;
        }

        return $users;
    }

    public function getUserDataById($userId)
    {
        $query = "SELECT * FROM users WHERE id = :userId";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":userId", $userId);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $user = new User();
            $user->setId($row["id"]);
            $user->setUsername($row["username"]);
            $user->setDisplayName($row["display_name"]);
            $user->setAvatar($row["avatar"]);

            return $user;
        }

        return null;
    }

    public function getUserDataByUsername($username)
    {
        $query = "SELECT * FROM users WHERE username = :username";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":username", $username);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $user = new User();
            $user->setId($row["id"]);
            $user->setUsername($row["username"]);
            $user->setDisplayName($row["display_name"]);
            $user->setAvatar($row["avatar"]);

            return $user;
        }

        return null;
    }

    public function getUserCount()
    {
        $query = "SELECT COUNT(*) as count FROM users";
        $stmt = $this->db->prepare($query);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row["count"];
    }
}
