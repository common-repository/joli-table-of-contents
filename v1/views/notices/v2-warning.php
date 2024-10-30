<div class="notice notice-warning joli-toc-notice-v2" style="background-color: #ffecc9;">
    <h3 style="font-size: 24px;color: orangered;">⚠️ ACTION REQUIRED</h3>
    <p>
        <?= __('Joli Table of contents has been updated to version 2.', 'joli-table-of-contents'); ?> <strong><a target="_blank" href="<?= $v2_what_new_url; ?>"><?= __('What is changing ?', 'joli-table-of-contents'); ?></strong></a>
    </p>
    <p>This new major version comes with <strong>BREAKING CHANGES</strong>. It has been entirely redesigned and improved on many aspects.</p>
    <p>Because you are using Joli TOC v1 (or used it before), the v1 is still running right now.</p>
    <p>It is highly recommanded to switch to v2 ASAP but this will break any custom CSS (related to Joli TOC) and some details may change.</p>
    <p>You can switch back and forth between v1 & v2 without losing your settings on either version.</p>
    <p>Support for v1 will be definitely dropped on January 31st, 2024.</p>
    <p>Your current settings will be converted for v2 but some minor details may change.</p>
    <p>
        <a href="#" target="_blank" class="button button-primary" data-method="go" data-action="v2"><?= __('I understand, activate v2 now', 'joli-table-of-contents'); ?></a>
        <a href="" class="button button-secondary" data-method="remind" data-action="v2"><?= __('I am not sure, keep the v1 running for now and remind me later', 'joli-table-of-contents'); ?></a>
    </p>
</div>