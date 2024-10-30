<?php

$has_custom_styles = $toc_styles_general || $toc_styles || $toc_styles_root || $custom_css;
$preserve_styles = ( $preserve_theme_styles ? '' : '.--jtoc-has-custom-styles' );
$is_in_the_content = current_filter() === 'the_content';
$in_the_content = ( $is_in_the_content ? ' --jtoc-the-content' : '' );
$toc_inline_styles = null;
$toc_style = '';
$hidden_main_toc = '';
$is_hidden_class = '';
$all_styles = null;
$toc_inline_styles_str = ( $toc_inline_styles ? jtoc_attrify( [
    'style' => $toc_inline_styles,
] ) : '' );
if ( $has_custom_styles ) {
    ?>
    <?php 
    ob_start();
    ?>
    <?php 
    if ( $toc_styles_root ) {
        ?>
        :root {
        <?php 
        echo $toc_styles_root;
        ?>
        }
    <?php 
    }
    ?>
    <?php 
    if ( $toc_styles_general ) {
        ?>
        .wpj-jtoc.<?php 
        echo $theme_class;
        echo $preserve_styles;
        ?> {
        <?php 
        echo $toc_styles_general;
        ?>
        }
    <?php 
    }
    ?>
    <?php 
    if ( $toc_styles && !$preserve_theme_styles ) {
        ?>
        .wpj-jtoc.<?php 
        echo $theme_class;
        echo $preserve_styles;
        ?> {
        <?php 
        echo $toc_styles;
        ?>
        }
    <?php 
    }
    ?>
    <?php 
    if ( $custom_css ) {
        echo $custom_css;
    }
    ?>
    <?php 
    $all_styles = trim( ob_get_contents() );
    ob_end_clean();
}
?>

<?php 
if ( $all_styles ) {
    ?>
    <style>
        <?php 
    echo $all_styles;
    ?>
    </style>
<?php 
}
?>

<?php 
?>

<?php 
?>

<?php 
do_action( 'joli_toc_before_table_of_contents', $data );
?>
<div id="wpj-jtoc" class="wpj-jtoc wpj-jtoc--main<?php 
echo $in_the_content;
echo $toc_wrapper_shared_classes;
echo $toc_wrapper_main_classes;
echo $hidden_main_toc;
?>" <?php 
echo $toc_style;
?>>
    <?php 
?>

    <!-- TOC -->
    <div class="wpj-jtoc--toc<?php 
echo $toc_classes;
echo $is_hidden_class;
?>"<?php 
echo $toc_inline_styles_str;
?>>
        <?php 
do_action( 'joli_toc_before_header', $data );
?>
        <?php 
if ( $show_header ) {
    ?>
            <div class="wpj-jtoc--header">
                <div class="wpj-jtoc--header-main">
                    <?php 
    do_action( 'joli_toc_before_title', $data );
    ?>
                    <div class="wpj-jtoc--title">
                        <span class="wpj-jtoc--title-label"><?php 
    echo $title;
    ?></span>
                    </div>
                    <?php 
    do_action( 'joli_toc_after_title', $data );
    ?>
                    <?php 
    if ( $show_toggle ) {
        ?>
                        <div class="wpj-jtoc--toggle-wrap">
                            <?php 
        if ( $toggle_type === 'text' ) {
            ?>
                                <!-- toggle icon instead of the toggle box if any custom icon -->
                                <div class="wpj-jtoc--toggle-text">
                                    <div class="wpj-jtoc--toggle-opened">
                                        <span class="--jtoc-bracket">[</span><?php 
            echo $toggle_button_text_opened;
            ?><span class="--jtoc-bracket">]</span>
                                    </div>
                                    <div class="wpj-jtoc--toggle-closed">
                                        <span class="--jtoc-bracket">[</span><?php 
            echo $toggle_button_text_closed;
            ?><span class="--jtoc-bracket">]</span>
                                    </div>
                                </div>
                            <?php 
        }
        ?>
                            <?php 
        if ( $toggle_type === 'icon-std' ) {
            ?>
                                <div class="wpj-jtoc--toggle-icon">
                                    <div class="wpj-jtoc--toggle-opened"><?php 
            echo $toggle_button_icon_opened;
            ?></div>
                                    <div class="wpj-jtoc--toggle-closed"><?php 
            echo $toggle_button_icon_closed;
            ?></div>
                                </div>
                            <?php 
        }
        ?>
                            <?php 
        if ( $toggle_type === 'icon' ) {
            ?>
                                <div class="wpj-jtoc--toggle-box">
                                    <div class="wpj-jtoc--toggle"></div>
                                </div>
                            <?php 
        }
        ?>
                        </div>
                    <?php 
    }
    ?>
                </div>
            </div>
            <?php 
}
?>
            <?php 
do_action( 'joli_toc_after_header', $data );
?>
        <div class="wpj-jtoc--body">
            <?php 
do_action( 'joli_toc_before_headings', $data );
?>
            <nav class="wpj-jtoc--nav">
                <?php 
echo $toc;
?>
            </nav>
            <?php 
do_action( 'joli_toc_after_headings', $data );
?>
            <?php 
?>
        </div>
        <?php 
do_action( 'joli_toc_after_body', $data );
?>
    </div>
</div>
<?php 
do_action( 'joli_toc_after_table_of_contents', $data );