<div hx-get="<?= env("ext_url") ?>/api.php?type=user&value=<?= $user->getUsername() ?>" hx-swap="innerHTML" hx-indicator="#spinner" hx-trigger="every 120s">
    <?php include_once("back_button.php") ?>

    <section class="container">
        <div class="user">
            <div class="user-info">
                <div class="user-info-presentation">
                    <img alt="<?= $user->getUsername() ?>" src="<?= env("ext_url") ?>/<?= $user->getAvatar() ?>.thumb.jpg" decoding="async" data-nimg="intrinsic" class="user-info-avatar" srcset="<?= env("ext_url") ?>/<?= $user->getAvatar() ?>.thumb.jpg">

                    <span class="user-info-desc">
                        <p><?= $user->getDisplayName() ?></p>
                        <p class="user-info-username">@<?= $user->getUsername() ?></p>
                    </span>
                    <?php if ($streak->getStreak() != null) { ?>
                        <span class="user-info-streak" title="Série de publications journalières niveau <?= $streak->getStreak() ?>">
                            <img alt="Série de publications journalières niveau <?= $streak->getStreak() ?>" src="<?= env("ext_url") ?>/shared/levels/electrogram_level_<?= $streak->getStreak() ?>.png" decoding="async" data-nimg="intrinsic" class="user-info-streak-img" srcset="<?= env("ext_url") ?>/shared/levels/electrogram_level_<?= $streak->getStreak() ?>.png">
                        </span>
                    <?php } ?>
                </div>
                <span class="posts-stats">
                    <p>Nombre de publications : <?= $totalMessages ?></p>
                    <p>Date de la première publication : <?= $firstMessageDate ?></p>
                    <p>Date de la dernière publication : <?= $lastMessageDate ?></p>
                    <p>Plus longue série de publications journalières : <?= $streak->getMaxStreak() ?></p>
                </span>
            </div>
            <div class="heatmap-parent">
                <div id="heatmap"></div>
                <div class="legend-parent">
                    <span class="legend-info">Moins</span>
                    <div id="legend"></div>
                    <span class="legend-info">Plus</span>
                </div>
            </div>
        </div>
    </section>
    <script>
        var counts = <?= preg_replace('/"date":/i', 'date:', preg_replace('/"value":/i', 'value:', json_encode($heatmapData))) ?>;
        var data = Object.entries(counts).map(([date, count]) => ({
            date,
            count
        }));
        var startDate = new Date(new Date().getFullYear(), new Date().getMonth() - 4, 1);
        var endDate = new Date();
        var cal = new CalHeatmap();
        cal.paint({
            itemSelector: "#heatmap",
            domain: {
                type: "month",

            },
            subDomain: {
                type: "day",
                radius: 1
            },
            date: {
                start: startDate,
                locale: "fr",
            },
            range: 6,
            data: {
                source: counts,
                type: "json",
                x: "date",
                y: d => +d["value"],
                groupY: "max"
            },
            scale: {
                color: {
                    type: "threshold",
                    range: ["#14432a", "#166b34", "#37a446", "#4dd05a"],
                    domain: [1, 2, 3, 4, 5],
                },
            },
            animationDuration: 0,
        }, [
            [
                Tooltip,
                {
                    text: function(date, value, dayjsDate) {
                        return (
                            (value ? value : "0") +
                            " publication(s) le " +
                            dayjsDate.format("dddd D MMMM YYYY")
                        );
                    },
                },
            ],
            [
                LegendLite,
                {
                    includeBlank: true,
                    itemSelector: "#legend",
                    radius: 2,
                    width: 11,
                    height: 11,
                    gutter: 4,
                },
            ],
            [
                CalendarLabel,
                {
                    width: 30,
                    textAlign: "start",
                    text: () => dayjs.weekdaysShort().map((d, i) => (i % 2 == 0 ? "" : d)),
                    padding: [-10, 0, 0, 0],
                },
            ],
        ]);
    </script>