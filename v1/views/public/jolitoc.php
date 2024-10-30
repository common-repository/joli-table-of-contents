<?= $custom_css; ?>
<?php do_action('joli_toc_before_table_of_contents'); ?>
<div id="joli-toc-filler"></div>
<div id="joli-toc-wrapper" class="<?= $visibility_classes; ?> <?= $smoothscroll; ?>"<?= $hide_at_load; ?>>
    <nav id="joli-toc">
        <<?= apply_filters( 'joli_toc_header_tag', 'header' ); ?> id="joli-toc-header">
            <?php if ($visibility !== 'unfolded-incontent') : ?>
            <div id="joli-toc-toggle-box" class="toggle-align-<?= $togglealign ? $togglealign : 'left'; ?>">
                <div id="joli-toc-toggle"><?= $expand; ?></div>
                <div id="joli-toc-collapse"><?= $collapse; ?></div>
            </div>
            <?php endif; ?>
            <?php do_action('joli_toc_before_title'); ?>
            <?php if ($title) : ?>
            <div class="title">
                <span id="title-label"><?= $title; ?></span>
            </div>
            <?php endif; ?>
        </<?= apply_filters( 'joli_toc_header_tag', 'header' ); ?>>
        <?php do_action('joli_toc_after_title'); ?>
        <?= $toc; ?>
        <?php do_action('joli_toc_after_headings'); ?>
        <?php if ($logo) : ?>
        <div class="joli-div">
            <a href="https://wpjoli.com?utm_source=<?= $domain; ?>&utm_medium=watermark" target="_blank" class="joli-credits">
                <span>Powered by</span><img src="<?= esc_attr( $logo ); ?>" alt="wpjoli.com" title="wpjoli.com">
            </a>
        </div>
        <?php endif; ?>
    </nav>
</div>
<?php do_action('joli_toc_after_table_of_contents'); ?>
