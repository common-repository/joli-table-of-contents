<div class="wrap">
    <h1 class="h1-title">Joli Table Of Contents</h1>
    <?php settings_errors(); ?>

    <div class="jtoc-wrap">
        <header class="joli-header">
            <div class="joli-logo">
                <a href="https://wpjoli.com" title="WPJoli" target="_blank">
                    <img src="<?= $logo_url; ?>" alt="">
                </a>
            </div>
            <div class="joli-nav">
                <?php foreach ($tabs as $id => $title) : ?>
                <a id="tab-<?= $id; ?>" class="joli-nav-item" href="#<?= $id; ?>">
                    <div class="joli-nav-title">
                        <?= $title; ?>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
        </header>
        <section class="joli-content">
            <form method="post" action="options.php">
                <div class="tab-content joli-tab-content">
                    <p class="joli-title"><span class="joli-styling">Joli</span> Table Of Contents</p>
                    <?php foreach ($tabs as $id => $title) : ?>
                    <div data-key="tab-<?= $id; ?>" class="joli-tab-pane">
                        <!-- <h2><?= $title; ?></h2> -->
                        <?php JTOC()->render( [ 'admin/' . basename(__FILE__, '.php') => $id ] ) ?>
                    </div>
                    <?php endforeach; ?>

                </div>
            </form>
        </section>
    </div>

</div>