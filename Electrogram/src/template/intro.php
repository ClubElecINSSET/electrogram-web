<div hx-get="api.php" hx-swap="innerHTML" hx-indicator="#spinner" hx-trigger="every 120s">
    <section class="container">
        <div class="info">
            <p>club elec electrogram est un moyen simple pour permettre aux étudiants et au personnel de l’UPJV de mettre en avant ce qu’ils font <a href="<?= env("ext_url") ?>/streak" hx-get="<?= env("ext_url") ?>/api.php?type=streak" hx-replace-url="<?= env("ext_url") ?>/streak" hx-target="#htmx">tous les jours</a> dans le domaine des sciences et technologies.
                <br>Rejoignez notre <a href="https://discord.clubelec.org">serveur Discord</a> pour prendre part à l’aventure !
            </p>
        </div>
    </section>
    <section class="container">

        <div class="explore-tags">
            <p>Explorez club elec electrogram avec les étiquettes les plus utilisées :</p>
            <div class="explore-tags-parent">
                <?php foreach ($topUsedTags as $topTag) {
                    $tag = $tagManager->getEmojiAndDescriptionByEmoji($topTag->getEmoji());
                ?>
                    <?php if (!empty($tag->getFilename())) { ?>
                        <a class="explore-tags-tag" title="<?= $tag->getDescription() ?>" href="<?= env("ext_url") ?>/tag/<?= $tag->getEmoji() ?>" hx-get="<?= env("ext_url") ?>/api.php?type=tag&value=<?= $tag->getEmoji() ?>" hx-replace-url="<?= env("ext_url") ?>/tag/<?= $tag->getEmoji() ?>" hx-target="#htmx">
                            <div style="height:24px;vertical-align:middle">
                                <span style="box-sizing:border-box;display:block;overflow:hidden;width:initial;height:initial;background:none;opacity:1;border:0;margin:0;padding:0;position:relative">
                                    <span style="box-sizing:border-box;display:block;width:initial;height:initial;background:none;opacity:1;border:0;margin:0;padding:0;padding-top:100%">
                                    </span>
                                    <img alt="<?= $tag->getDescription() ?>" src="<?= env("ext_url") ?>/<?= $tag->getFilename() ?>" decoding="async" data-nimg="responsive" class="explore-tags-emoji" style="position:absolute;top:0;left:0;bottom:0;right:0;box-sizing:border-box;padding:0;border:none;margin:auto;display:block;width:0;height:0;min-width:100%;max-width:100%;min-height:100%;max-height:100%">
                            </div>
                            <span style="transform:translateY(2px)"></span>
                        </a>
                    <?php } else {
                    ?>
                        <a class="explore-tags-tag" title="<?= $tag->getDescription() ?>" href="<?= env("ext_url") ?>/tag/<?= $tag->getEmoji() ?>" hx-get="<?= env("ext_url") ?>/api.php?type=tag&value=<?= $tag->getEmoji() ?>" hx-replace-url="<?= env("ext_url") ?>/tag/<?= $tag->getEmoji() ?>" hx-target="#htmx"><span style="transform:translateY(2px)"><?= $tag->getEmoji() ?></span></a>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
    </section>