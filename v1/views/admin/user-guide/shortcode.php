<h2><?= __('How to use the shortcode ?', 'joli-table-of-contents'); ?></h2>
<p><?= __('Use the following shortcode within your content to have the table of contents display where you wish to:', 'joli-table-of-contents'); ?></p>
<p><code>[joli-toc]</code></p>
<h2><?= __('How to modify the shortcode tag ?', 'joli-table-of-contents'); ?></h2>
<p><?= __('If you were using another table of contents plugin and were using the associated shortcode, good news, you don\'t have to change all of the shortcodes!', 'joli-table-of-contents'); ?></p>
<p><?= __('Just change the shortcode tag to match the one you have used until now.', 'joli-table-of-contents'); ?></p>
<p><?= __('Copy & paste the following code into your theme\'s function.php file:', 'joli-table-of-contents'); ?></p>
<p><code>add_filter('jolitoc_shortcode_tag', function(){ return '<strong style="color: red;">custom-tag</strong>'; });</code></p>
<h2><?= __('Can I use the shortcode in a widget?', 'joli-table-of-contents'); ?></h2>
<p><?= __('Yes, you can.', 'joli-table-of-contents'); ?></p>
<p><?= __('However, if you are using the floating display on scroll, this may result in unexpected behaviour on some websites.', 'joli-table-of-contents'); ?></p>

