<div hx-get="<?= env("ext_url") ?>/api.php?type=stats" hx-indicator="#spinner" hx-swap="innerHTML" hx-trigger="every 120s">
    <?php include_once("back_button.php") ?>
    <section class="container">
        <div class="stats">
            <p class="stats-header">Classement des utilisateurs avec les plus longues séries de publications journalières</p>
            <div class="stats-parent">
                <div>
                    <p class="stats-title">Actuel</p>
                    <?php foreach ($topUsersByStreak as $userStreak) {
                        $user = $userManager->getUserDataById($userStreak->getUserId());
                        $streak = $userStreak->getStreak();
                    ?>
                        <div class="stats-item">
                            <a class="stats-item-user" href="<?= env("ext_url") ?>/user/<?= $user->getUsername() ?>" hx-get="<?= env("ext_url") ?>/api.php?type=user&value=<?= $user->getUsername() ?>" hx-replace-url="<?= env("ext_url") ?>/user/<?= $user->getUsername() ?>" hx-target="#htmx">
                                <img alt="<?= $user->getUsername() ?>" src="<?= env("ext_url") ?>/<?= $user->getAvatar() ?>.thumb.jpg" decoding="async" data-nimg="intrinsic" class="stats-item-user-avatar" srcset="<?= env("ext_url") ?>/<?= $user->getAvatar() ?>.thumb.jpg">
                                <span class="stats-item-user-name">
                                    <strong>@<?= $user->getUsername() ?></strong>
                                </span>
                            </a>
                                <span class="stats-item-user stats-item-user-streak" title="Série de publications journalières niveau <?= $streak ?>">
                                    <img alt="Série de publications journalières niveau <?= $streak ?>" src="<?= env("ext_url") ?>/shared/levels/electrogram_level_<?= $streak ?>.png" decoding="async" data-nimg="intrinsic" class="stats-item-user-streak-img" srcset="<?= env("ext_url") ?>/shared/levels/electrogram_level_<?= $streak ?>.png">
                                </span>
                        </div>
                    <?php } ?>
                </div>
                <div>
                    <p class="stats-title">Depuis le début</p>
                    <?php foreach ($topUsersByMaxStreak as $userStreak) {
                        $user = $userManager->getUserDataById($userStreak->getUserId());
                        $streak = $userStreak->getMaxStreak();
                    ?>
                        <div class="stats-item">
                            <a class="stats-item-user" href="<?= env("ext_url") ?>/user/<?= $user->getUsername() ?>" hx-get="<?= env("ext_url") ?>/api.php?type=user&value=<?= $user->getUsername() ?>" hx-replace-url="<?= env("ext_url") ?>/user/<?= $user->getUsername() ?>" hx-target="#htmx">
                                <img alt="<?= $user->getUsername() ?>" src="<?= env("ext_url") ?>/<?= $user->getAvatar() ?>.thumb.jpg" decoding="async" data-nimg="intrinsic" class="stats-item-user-avatar" srcset="<?= env("ext_url") ?>/<?= $user->getAvatar() ?>.thumb.jpg">
                                <span class="stats-item-user-name">
                                    <strong>@<?= $user->getUsername() ?></strong>
                                </span>
                            </a>
                                <span class="stats-item-user stats-item-user-streak" title="Série de publications journalières niveau <?= $streak ?>">
                                    <img alt="Série de publications journalières niveau <?= $streak ?>" src="<?= env("ext_url") ?>/shared/levels/electrogram_level_<?= $streak ?>.png" decoding="async" data-nimg="intrinsic" class="stats-item-user-streak-img" srcset="<?= env("ext_url") ?>/shared/levels/electrogram_level_<?= $streak ?>.png">
                                </span>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </section>