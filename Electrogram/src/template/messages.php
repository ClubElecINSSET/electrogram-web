<div class="card">
   <a class="post-header" href="<?= env("ext_url") ?>/user/<?= $user->getUsername() ?>" hx-get="<?= env("ext_url") ?>/api.php?type=user&value=<?= $user->getUsername() ?>" hx-replace-url="<?= env("ext_url") ?>/user/<?= $user->getUsername() ?>" hx-target="#htmx">
      <img alt="<?= $user->getUsername() ?>" src="<?= env("ext_url") ?>/<?= $user->getAvatar() ?>.thumb.jpg" decoding="async" data-nimg="intrinsic" class="post-header-avatar" srcset="<?= env("ext_url") ?>/<?= $user->getAvatar() ?>.thumb.jpg">
      <section class="post-header-container">
         <span class="post-header-name">
            <strong>@<?= $user->getUsername() ?></strong>
            <?php if ($streak->getStreak() != null) { ?>
               <span class="post-header-streak" title="Série de publications journalières niveau <?= $streak->getStreak() ?>">
                  <img alt="Série de publications journalières niveau <?= $streak->getStreak() ?>" src="<?= env("ext_url") ?>/shared/levels/electrogram_level_<?= $streak->getStreak() ?>.png" decoding="async" data-nimg="intrinsic" class="post-header-streak-img" srcset="<?= env("ext_url") ?>/shared/levels/electrogram_level_<?= $streak->getStreak() ?>.png">
               </span>
            <?php } ?>
         </span>
         <time class="post-header-date" datetime="<?= $message->getTimestamp() ?>"><?= $message->getTimestamp() ?></time>
      </section>
   </a>
   <article class="post-text"><?= $message->getContent() ?></article>
   <div class="post-attachments">
      <?php foreach ($attachments as $attachment) { ?>

         <?php if ($attachment->getType() === "picture" || $attachment->getType() === "video") { ?>

            <a href="<?= env("ext_url") ?>/<?= $attachment->getFilename() ?>" class="post-attachment glightbox" data-gallery="<?= $message->getId(); ?>">
               <img alt="<?= $attachment->getFilename() ?>" src="<?= env("ext_url") ?>/<?= $attachment->getFilename() ?>.thumb.jpg" loading="lazy" title="<?= $attachment->getFilename() ?>">
            </a>
         <?php } elseif ($attachment->getType() === "audio") { ?>
            <audio controls>
               <source src="<?= env("ext_url") ?>/<?= $attachment->getFilename() ?>" type="audio/mp3">
               Votre navigateur ne prend pas en charge la balise audio.
            </audio>
         <?php } ?>
      <?php } ?>
   </div>
   <footer class="post-tags" aria-label="Tags">
      <?php foreach ($tags as $tag) {
         if (!empty($tag->getFilename())) { ?>
            <a class="post-tag" title="<?= $tag->getDescription() ?>" href="<?= env("ext_url") ?>/tag/<?= $tag->getEmoji() ?>" hx-get="<?= env("ext_url") ?>/api.php?type=tag&value=<?= $tag->getEmoji() ?>" hx-replace-url="<?= env("ext_url") ?>/tag/<?= $tag->getEmoji() ?>" hx-target="#htmx">
               <div style="height:24px;vertical-align:middle">
                  <span style="box-sizing:border-box;display:block;overflow:hidden;width:initial;height:initial;background:none;opacity:1;border:0;margin:0;padding:0;position:relative">
                     <span style="box-sizing:border-box;display:block;width:initial;height:initial;background:none;opacity:1;border:0;margin:0;padding:0;padding-top:100%">
                     </span>
                     <img alt="<?= $tag->getDescription() ?>" src="<?= env("ext_url") ?>/<?= $tag->getFilename() ?>" decoding="async" data-nimg="responsive" class="post-emoji" style="position:absolute;top:0;left:0;bottom:0;right:0;box-sizing:border-box;padding:0;border:none;margin:auto;display:block;width:0;height:0;min-width:100%;max-width:100%;min-height:100%;max-height:100%">
               </div>
               <span style="transform:translateY(2px)"></span>
            </a>
         <?php } else {
         ?>
            <a class="post-tag" title="<?= $tag->getDescription() ?>" href="<?= env("ext_url") ?>/tag/<?= $tag->getEmoji() ?>" hx-get="<?= env("ext_url") ?>/api.php?type=tag&value=<?= $tag->getEmoji() ?>" hx-replace-url="<?= env("ext_url") ?>/tag/<?= $tag->getEmoji() ?>" hx-target="#htmx"><span style="transform:translateY(2px)"><?= $tag->getEmoji() ?></span></a>
         <?php } ?>
      <?php } ?>
   </footer>
</div>

<?php if ($hasNextPage && $isLast) { ?>
   <?php if ($type == "user") { ?>
      <div class="infinite-scrolling" hx-get="<?= env("ext_url") ?>/api.php?type=user&value=<?= $user->getUsername() ?>&page=<?= $nextPage ?>" hx-trigger="intersect once" hx-swap="outerHTML" hx-indicator="#spinner"></div>
   <?php } else if ($type == "tag") { ?>
      <div class="infinite-scrolling" hx-get="<?= env("ext_url") ?>/api.php?type=tag&value=<?= $tag->getEmoji() ?>&page=<?= $nextPage ?>" hx-trigger="intersect once" hx-swap="outerHTML" hx-indicator="#spinner"></div>
   <?php } else { ?>
      <div class="infinite-scrolling" hx-get="<?= env("ext_url") ?>/api.php?page=<?= $nextPage ?>" hx-trigger="intersect once" hx-swap="outerHTML" hx-indicator="#spinner"></div>
   <?php } ?>
<?php } ?>

<?php if ($isLast) { ?>
   <script>
      var grid = document.querySelector(".cards");

      imagesLoaded(grid, function() {
         var msnry = new Masonry(grid, {
            itemSelector: ".card",
            columnWidth: ".grid-sizer",
            percentPosition: true,
            gutter: ".gutter-sizer"
         });
      });

      var lightbox = GLightbox({
         openEffect: "lightboxFade",
         closeEffect: "lightboxFade",
         cssEfects: {
            lightboxFade: {
               in: "fadeIn",
               out: "fadeOut"
            },
         },
         touchNavigation: true,
         loop: true,
         autoplayVideos: true
      });
   </script>
<?php } ?>