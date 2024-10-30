<h2><?= __('Understanding the Behaviour > Visibility option', 'joli-table-of-contents'); ?></h2>
<p><?= __('Joli Table Of Contents can be displayed in various manners from a simple, to a modern floating element.', 'joli-table-of-contents'); ?></p>
<p><?= __('It has a unique feature the allows it to show the current heading section while scrolling', 'joli-table-of-contents'); ?></p>
<p><?= __('Here is a description of each behaviour:', 'joli-table-of-contents'); ?></p>

<h3>1. <?= __('Invisible, floating on scroll', 'joli-table-of-contents'); ?><?= jtoc_pro_only(); ?></h3>
<p><?= __('Table Of Contents is invisible after page is loaded. It only shows on scroll through a floating widget', 'joli-table-of-contents'); ?></p>
<details class="primer" style="display: inline-block; width: 100%;">
    <summary title="View demo">Click here to view demo</summary>
    <section>
        <p><img src="<?= JTOC()->url('assets/admin/img/' . 'invisible-floating' . '.gif') ?>" alt=""></p>
    </section>
</details>

<h3>2. <?= __('Unfolded, in-content', 'joli-table-of-contents'); ?></h3>
<p><?= __('Table Of Contents is unfolded after page is loaded. It remains in its position and no widget shows on scroll.', 'joli-table-of-contents'); ?></p>
<details class="primer" style="display: inline-block; width: 100%;">
    <summary title="View demo">Click here to view demo</summary>
    <section>
        <p><img src="<?= JTOC()->url('assets/admin/img/' . 'unfolded-incontent' . '.gif') ?>" alt=""></p>
    </section>
</details>

<h3>3. <?= __('Unfolded, folded & floating on scroll', 'joli-table-of-contents'); ?><?= jtoc_pro_only(); ?></h3>
<p><?= __('Table Of Contents is unfolded after page is loaded. It also shows on scroll through a floating widget.', 'joli-table-of-contents'); ?></p>
<details class="primer" style="display: inline-block; width: 100%;">
    <summary title="View demo">Click here to view demo</summary>
    <section>
        <p><img src="<?= JTOC()->url('assets/admin/img/' . 'unfolded-floating' . '.gif') ?>" alt=""></p>
    </section>
</details>

<h3>4. <?= __('Unfolded, unfolded & floating on scroll', 'joli-table-of-contents'); ?><?= jtoc_pro_only(); ?></h3>
<p><?= __('Table Of Contents is unfolded after page is loaded. It remains unfolded and floating on scroll.', 'joli-table-of-contents'); ?></p>
<details class="primer" style="display: inline-block; width: 100%;">
    <summary title="View demo">Click here to view demo</summary>
    <section>
        <!-- <p><img src="<?= JTOC()->url('assets/admin/img/' . 'invisible-floating' . '.gif') ?>" alt=""></p> -->
    </section>
</details>

<h3>5. <?= __('Folded, in-content', 'joli-table-of-contents'); ?></h3>
<p><?= __('Table Of Contents is folded after page is loaded. It can be unfolded at will. It remains in its position and no widget shows on scroll.', 'joli-table-of-contents'); ?></p>
<details class="primer" style="display: inline-block; width: 100%;">
    <summary title="View demo">Click here to view demo</summary>
    <section>
        <p><img src="<?= JTOC()->url('assets/admin/img/' . 'folded-incontent' . '.gif') ?>" alt=""></p>
      </section>
</details>

<h3>6. <?= __('Folded, folded & floating on scroll', 'joli-table-of-contents'); ?><?= jtoc_pro_only(); ?></h3>
<p><?= __('Table Of Contents is folded after page is loaded. It can be unfolded at will. It also shows on scroll through a floating widget.', 'joli-table-of-contents'); ?></p>
<details class="primer" style="display: inline-block; width: 100%;">
    <summary title="View demo">Click here to view demo</summary>
    <section>
        <p><img src="<?= JTOC()->url('assets/admin/img/' . 'folded-floating' . '.gif') ?>" alt=""></p>
    </section>
</details>

<h2><?= __('Globally disabling the Table of Contents', 'joli-table-of-contents'); ?></h2>
<p><?= __('For some reason, you may need to deactivate the table of contents without touching your content. To do so:', 'joli-table-of-contents'); ?></p>
<p><?= __('Copy & paste the following code into your theme\'s function.php file:', 'joli-table-of-contents'); ?></p>
<p><code>add_filter('joli_toc_disable_autoinsert', function(){ return true; });</code></p>
<p><?= __('This will disable both shortcodes and auto-insert from running.', 'joli-table-of-contents'); ?></p>

<h2><?= __('Disable Javascript', 'joli-table-of-contents'); ?></h2>
<p><?= __('This filter will prevent the javascript from loading. It is not recommanded unless you know exactly what you are doing.', 'joli-table-of-contents'); ?></p>
<p><?= __('By doing so, you may break some of the plugin\'s functionalities including smooth scrolling and jump-to offset', 'joli-table-of-contents'); ?></p>
<p><?= __('Copy & paste the following code into your theme\'s function.php file:', 'joli-table-of-contents'); ?></p>
<p><code>add_filter('joli_toc_disable_js', function(){ return true; });</code></p>