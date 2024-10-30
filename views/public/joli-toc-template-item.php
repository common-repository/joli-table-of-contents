<?php $href = $args['url'] ? $args['url'] : '#' . $args['id'] ?>
<li class="wpj-jtoc--item --jtoc-h<?= $args['depth'] ?>">
    <?php do_action('joli_toc_before_begin_item_content', $args) ?>
    <div class="wpj-jtoc--item-content" data-depth="<?= $args['depth'] ?>">
        <?php do_action('joli_toc_after_begin_item_content', $args) ?>
        <?php if ($args['bullet']) : ?><div class="jtoc--bullet"><div class="--bullet-inner"></div></div><?php endif; ?>
        <a href="<?= $href ?>" title="<?= $args['title'] ?>" data-numeration="<?= $args['counter'] ?>" <?php echo jtoc_process_attrs(apply_filters('joli_toc_item_link_attributes', $args['attrs'], $args)) ?>><?php do_action('joli_toc_after_begin_item_link', $args) ?><?= $args['title'] ?><?php do_action('joli_toc_before_end_item_link', $args) ?></a>
        <?php if ($args['smart']) : ?><span class="wpj-jtoc--item-indicator"><?= $args['smart'] ?></span><?php endif; ?>
        <?php do_action('joli_toc_before_end_item_content', $args) ?>
    </div> <?php do_action('joli_toc_after_end_item_content', $args) ?>