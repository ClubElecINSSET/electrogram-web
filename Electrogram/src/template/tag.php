<div hx-get="api.php?type=tag&value=<?= $tag->getEmoji() ?>" hx-indicator="#spinner" hx-swap="innerHTML" hx-trigger="every 120s">
    <?php include_once("back_button.php") ?>
    <section class="container">
        <div class="tag">

            <?php if ($tagManager->countPostsByEmoji($tag->getEmoji()) > 1) { ?>
                <p><?= $tagManager->countPostsByEmoji($tag->getEmoji()) ?> publications avec l’étiquette </p>

            <?php } else { ?>
                <p><?= $tagManager->countPostsByEmoji($tag->getEmoji()) ?> publication avec l’étiquette </p>

            <?php } ?>


            <?php if (!empty($tag->getFilename())) { ?>

                <img alt="<?= $tag->getDescription() ?>" src="<?= env("ext_url") ?>/<?= $tag->getFilename() ?>" decoding="async" data-nimg="responsive" class="intro-tag-img">

            <?php } else {
            ?>
                <span class="intro-tag-text"><?= $tag->getEmoji() ?></span>
            <?php } ?>
        </div>
    </section>