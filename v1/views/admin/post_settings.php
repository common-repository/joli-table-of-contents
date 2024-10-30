<div class="jtoc-wrap jtoc-post-edit">
    <header class="joli-header">
        <div class="joli-logo">
            <a href="https://wpjoli.com" title="WPJoli" target="_blank">
                <img src="<?= $logo_url; ?>" alt="">
            </a>
        </div>
    </header>
    <section class="joli-content">
        <div class="tab-content joli-tab-content post-settings">
            <div id="tab-settings" class="joli-settings-tab-pane">

                <?php
                global $post;
                //custom settings
                $setting_name = JTOC()::SLUG . '_post_settings';
                $data = get_post_meta( $post->ID, $setting_name, true );
                
                ?>
                <!-- OPTION -->
                <input type="checkbox" name="<?= $setting_name; ?>[disable_toc]" id="<?= $setting_name; ?>[disable_toc]"
                <?= isset($data['disable_toc']) ? ($data['disable_toc'] == 'on' ? 'checked' : '') : ''; ?>>
                <label for="<?= $setting_name; ?>[disable_toc]">
                    <?= esc_html__( 'Disable Auto-insert Table of contents', 'joli-table-of-contents') ?>
                </label>
                <p class="description">
                    <?= esc_html__( 'This will deactivate the table of contents auto-insert on this post. Shortcode will still work if in the content.', 'joli-table-of-contents') ?>
                </p>
                <!-- /OPTION -->
                
                <!-- OPTION -->
                <input type="checkbox" name="<?= $setting_name; ?>[enable_toc]" id="<?= $setting_name; ?>[enable_toc]"
                <?= isset($data['enable_toc']) ? ($data['enable_toc'] == 'on' ? 'checked' : '') : ''; ?>>
                <label for="<?= $setting_name; ?>[enable_toc]">
                    <?= esc_html__( 'Enable Auto-insert Table of contents', 'joli-table-of-contents') ?>
                </label>
                <p class="description">
                    <?= esc_html__( 'This will force the table of contents auto-insert on this post.', 'joli-table-of-contents') ?>
                </p>
                <!-- /OPTION -->
            </div>
        </div>
    </section>
</div>
