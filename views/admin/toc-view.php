<?php
$class = ' jtoc-v-highlight';
?>
<div class="jtoc-v-wrap">
    <span><?= __('Visualize', 'joli-table-of-contents') ?></span>
    <span class="joli-field-info dashicons dashicons-info-outline"></span>
    <div class="joli-info-bubble">
        <div class="jtoc-v-main<?= $highlight === 'main' ? $class : '' ?>">
            <div class="jtoc-v-header<?= $highlight === 'header' ? $class : '' ?>">
                <span class="jtoc-v-title<?= $highlight === 'title' ? $class : '' ?>">TABLE OF CONTENTS TITLE</span>
                <div class="jtoc-v-toggle<?= $highlight === 'toggle' ? $class : '' ?>">[toggle]</div>
            </div>
            <div class="jtoc-v-body<?= $highlight === 'body' ? $class : '' ?>">
                <ol class="jtoc-v-headings">
                    <?php for ($i = 1; $i <= 4; $i++) : ?>
                        <li class="<?= $highlight === 'headings' ? $class : '' ?>">
                            <span class="jtoc-v-text-link<?= $highlight === 'links' ? $class : '' ?>">HEADINGS TEXT LINK</span>
                        </li>
                    <?php endfor; ?>
                </ol>
            </div>
        </div>
    </div>
</div>