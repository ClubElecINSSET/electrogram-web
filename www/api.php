<?php

ob_start("ob_gzhandler");

require_once "../Electrogram/src/Entity/Attachment.php";
require_once "../Electrogram/src/Entity/Message.php";
require_once "../Electrogram/src/Entity/Tag.php";
require_once "../Electrogram/src/Entity/Streak.php";
require_once "../Electrogram/src/Entity/User.php";
require_once "../Electrogram/src/Manager/AttachmentManager.php";
require_once "../Electrogram/src/Manager/MessageManager.php";
require_once "../Electrogram/src/Manager/TagManager.php";
require_once "../Electrogram/src/Manager/StreakManager.php";
require_once "../Electrogram/src/Manager/UserManager.php";

use Electrogram\src\Manager\AttachmentManager;
use Electrogram\src\Manager\MessageManager;
use Electrogram\src\Manager\TagManager;
use Electrogram\src\Manager\StreakManager;
use Electrogram\src\Manager\UserManager;

$success = include "../Electrogram/env.php";

if (!$success) {
    echo "Missing env.php, copy env.example.php to env.php\n";
    die();
}

if (!function_exists("env")) {
    function env($key, $default = null)
    {
        $value = getenv($key);
        if ($value === false) {
            return $default;
        }
        return $value;
    }
}

$dsn = "mysql:host=" . env("mysql_address") . ";dbname=" . env("mysql_database") . ";port=" . env("mysql_port");
$db = new PDO($dsn, env("mysql_username"), env("mysql_password"));

$userManager = new UserManager($db);
$messageManager = new MessageManager($db);
$tagManager = new TagManager($db);
$attachmentManager = new AttachmentManager($db);
$streakManager = new StreakManager($db);

$type = isset($_GET["type"]) ? $_GET["type"] : null;
$value = isset($_GET["value"]) ? $_GET["value"] : null;
$page = isset($_GET["page"]) ? max(1, intval($_GET["page"])) : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

$user = $userManager->getUserDataByUsername($value);
$emoji = $value;
$tag = $messageManager->getMessagesByTag($emoji);

if ($type) {
    if (($type && !($user || $tag)) && !($type == "streak")) {
        include_once("../Electrogram/src/template/not_found.php");
        die();
    }
}

if ($user) {
    $userId = $user->getId();
    $messages = $messageManager->getMessagesByUserId($userId, $limit, $offset);
    $totalMessages = $messageManager->getTotalMessagesByUserId($userId);
    $hasNextPage = ($offset + $limit) < $totalMessages;
    $nextPage = $page + 1;
    $streak = $streakManager->getStreakByUserId($userId);
    $firstMessageDate = $messageManager->getFirstPostDateByUserId($userId);
    $lastMessageDate = $messageManager->getLastPostDateByUserId($userId);
    $endDate = date("Y-m-d");
    $startDate = date("Y-m-d", strtotime("-1 month", strtotime($endDate)));
    $heatmapData = $messageManager->getHeatmapDataByUserIdAndDate($userId, $startDate, $endDate);

    if ($page == 1) {
        include_once("../Electrogram/src/template/user.php");
        include_once("../Electrogram/src/template/message_top.php");
    }
    foreach ($messages as $key => $message) {
        if ($key === array_key_last($messages)) {
            $isLast = true;
        } else {
            $isLast = false;
        }
        $attachments = $attachmentManager->getAttachmentsByMessageId($message->getId());
        $tags = $tagManager->getTagDataByMessageId($message->getId());
        include("../Electrogram/src/template/messages.php");
    }
    if ($page == 1) {
        include_once("../Electrogram/src/template/message_bottom.php");
    }
} else if ($tag) {
    $messages = $messageManager->getMessagesByTag($emoji, $limit, $offset);
    $totalMessages = $messageManager->getTotalMessagesByTag($emoji);
    $hasNextPage = ($offset + $limit) < $totalMessages;
    $nextPage = $page + 1;
    $tag = $tagManager->getEmojiAndDescriptionByEmoji($emoji);
    if ($page == 1) {
        include_once("../Electrogram/src/template/tag.php");
        include_once("../Electrogram/src/template/message_top.php");
    }
    foreach ($messages as $key => $message) {
        if ($key === array_key_last($messages)) {
            $isLast = true;
        } else {
            $isLast = false;
        }
        $attachments = $attachmentManager->getAttachmentsByMessageId($message->getId());
        $user = $userManager->getUserDataById($message->getUserId());
        $streak = $streakManager->getStreakByUserId($user->getId());
        $tags = $tagManager->getTagDataByMessageId($message->getId());
        include("../Electrogram/src/template/messages.php");
    }
    if ($page == 1) {
        include_once("../Electrogram/src/template/message_bottom.php");
    }
} else if ($type == "streak") {
    $topUsersByStreak = $streakManager->getTopUsersByStreak();
    $topUsersByMaxStreak = $streakManager->getTopUsersByMaxStreak();
    include_once("../Electrogram/src/template/streak.php");
} else {
    $messages = $messageManager->getAllMessages($limit, $offset);
    $totalMessages = $messageManager->getTotalAllMessages();
    $hasNextPage = ($offset + $limit) < $totalMessages;
    $nextPage = $page + 1;
    $topUsedTags = $tagManager->getTopUsedTags(5);
    $nbUsers = $userManager->getUserCount();
    $nbTags = $tagManager->getTagCount();

    if ($page == 1) {
        include_once("../Electrogram/src/template/intro.php");
        include_once("../Electrogram/src/template/message_top.php");
    }
    foreach ($messages as $key => $message) {
        if ($key === array_key_last($messages)) {
            $isLast = true;
        } else {
            $isLast = false;
        }
        $attachments = $attachmentManager->getAttachmentsByMessageId($message->getId());
        $user = $userManager->getUserDataById($message->getUserId());
        $streak = $streakManager->getStreakByUserId($user->getId());
        $tags = $tagManager->getTagDataByMessageId($message->getId());
        include("../Electrogram/src/template/messages.php");
    }
    if ($page == 1) {
        include_once("../Electrogram/src/template/message_bottom.php");
    }
}

http_response_code(200);
