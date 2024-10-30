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
            <div class="joli-version">
                <div class="joli-submit joli-submit-inline">
                    <div class="joli-save-info">
                        <?php submit_button(__('Save settings', 'joli-table-of-contents'), 'primary joli-do-submit', 'submit-menu', false); ?>
                    </div>
                </div>

                <p>v<?= $version; ?></p>
                <div class="joli-toc-notice-v2" style="text-align:center;">
                    <button class="button button-primary" data-method="go" data-action="v2">Switch to v2 now</button>
                    <p>v1 support will be dropped on Jul 31st, 2024 (extended from Jan 31st 2024).</p>
                    <p>You can go back and forth between v1 & v2, don't worry, your settings will not be lost !</p>
                </div>
            </div>
        </header>
        <section class="joli-content">
            <form id="jtoc-settings" method="post" action="options.php">
                <div class="tab-content joli-tab-content">
                    <p class="joli-title"><span class="joli-styling">Joli</span> Table Of Contents</p>
                    <?php if (jtoc_xy()->is_free_plan()) : ?>
                        <p class="joli-gopro-notice">
                            <?= __('Want more cool features such as the', 'joli-table-of-contents'); ?> <strong><a target="_blank" href="<?= $pro_url_v; ?>"><?= __('Floating Table Of Contents Widget', 'joli-table-of-contents'); ?></strong></a> ?
                            <a href="<?= sprintf('%sadmin.php?page=joli_toc_settings-pricing', get_admin_url()); ?>" class="button button-primary"><?= __('Go Pro now', 'joli-table-of-contents'); ?></a>
                        </p>
                    <?php endif; ?>
                    <div class="joli-quickstart-notice">
                        <p>
                            <?= __('Quick start guide: How to display the Table Of Contents ?', 'joli-table-of-contents'); ?>
                            (<?= sprintf('<a href="%sadmin.php?page=joli_toc_user_guide">', get_admin_url()) . __('View full documentation', 'joli-table-of-contents') . '</a>)'; ?>
                        </p>
                        <ol>
                            <li><?= __('Shortcode :', 'joli-table-of-contents'); ?><code>[joli-toc]</code> <?= __('(paste shortcode anywhere in your posts/pages)', 'joli-table-of-contents'); ?></li>
                            <li><?= __('Auto-insert: Check the post type(s) you would like the TOC to auto-display in the "Auto-insert" tab of the settings and save', 'joli-table-of-contents'); ?></li>
                        </ol>
                    </div>
                    <div id="tab-settings" class="joli-settings-tab-pane">
                        <?php
                        $option_group = JTOC()::SLUG . '_settings';
                        settings_fields($option_group);
                        do_settings_sections($option_group);
                        ?>
                    </div>
                </div>
                <div class="joli-submit">
                    <div class="joli-save-info">
                        <div class="joli-info-text"><?= __('Changes unsaved', 'joli-table-of-contents'); ?></div>
                        <?php submit_button(__('Save settings', 'joli-table-of-contents'), 'primary', 'submit-float', false); ?>
                    </div>
                </div>
            </form>
            <form action="<?= $_SERVER['REQUEST_URI']; ?>" method="post">
                <p>
                    <input type="submit" id="jtoc_reset_settings" name="jtoc_reset_settings" class="button button-secondary button-small" value="<?= __("Reset settings", "joli-table-of-contents"); ?>" data-prompt="<?= __("Are you sure you want to reset settings ? All current settings will be lost.", "joli-table-of-contents"); ?>">
                </p>
            </form>
        </section>
    </div>

</div>