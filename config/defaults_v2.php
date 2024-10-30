<?php
$vars = [
    'dontaddpx' => '<span style="color:orange;">' . __('Do not add "px".', 'joli-table-of-contents') . '</span>',
    'dontaddem' => '<span style="color:orange;">' . __('Do not add "em".', 'joli-table-of-contents') . '</span>',
];

$font_weight_list = [
    'none' => __('[Inherit from theme]', 'joli-table-of-contents'),
    '100' => '100 (lightest)',
    '200' => '200',
    '300' => '300',
    '400' => '400 (normal)',
    '500' => '500',
    '600' => '600',
    '700' => '700 (bold)',
    '800' => '800',
    '900' => '900 (boldest)',
    'lighter' => __('Lighter (relative to parent)', 'joli-table-of-contents'),
    'bolder' => __('Bolder (relative to parent)', 'joli-table-of-contents'),
];

$font_style_list = [
    'none' => __('[Inherit from theme]', 'joli-table-of-contents'),
    'italic' => __('Italic', 'joli-table-of-contents'),
    'normal' => __('Normal', 'joli-table-of-contents'),
    'oblique' => __('Oblique', 'joli-table-of-contents'),
];

return [
    // GROUP: General ********************************************************
    [
        'group' => 'general',
        'label' => __('General', 'joli-table-of-contents'),
        'sections' => [
            // Themes ----------
            [
                'name' => 'appearance',
                'title' => __('Appearance', 'joli-table-of-contents'),
                'fields' => [

                    [
                        'id' => 'toc-width-incontent',
                        'title' => __('Width (in-content)', 'joli-table-of-contents'),
                        'type' => 'select',
                        'args' => [
                            'desc' => __('Auto will adapt to the content, "100%" will result in a full width table of contents', 'joli-table-of-contents'),
                            'values' => [
                                'width-auto' => __('Auto', 'joli-table-of-contents'),
                                'width-100' => '100%',
                            ],

                        ],
                        'default' => 'width-auto',
                    ],

                    [
                        'id' => 'toc-min-width',
                        'title' => __('Minimum width', 'joli-table-of-contents'),
                        'type' => 'unitinput',
                        'args' => [
                            'desc' => __('Define a minimum width value to prevent the TOC from shrinking too much.', 'joli-table-of-contents'),
                            // 'class' => 'tab-appearance'
                            'values' => [
                                'px' => 'px',
                                'em' => 'em',
                                'rem' => 'rem',
                                'percent' => '%',
                            ],
                        ],
                        'sanitize' => 'unit',
                    ],
                    [
                        'id' => 'toc-max-width',
                        'title' => __('Maximum width', 'joli-table-of-contents'),
                        'type' => 'unitinput',
                        'args' => [
                            'desc' => __('Maximum width of the table of contents.', 'joli-table-of-contents'),
                            // 'class' => 'tab-appearance'
                            'values' => [
                                'px' => 'px',
                                'em' => 'em',
                                'rem' => 'rem',
                                'percent' => '%',
                            ],
                        ],
                        'sanitize' => 'unit',
                    ],
                ],
            ],

            // Table of contents ----------
            [
                'name' => 'table-of-contents',
                'title' => __('Table of contents', 'joli-table-of-contents'),
                'fields' => [

                    [
                        'id' => 'hierarchy-offset',
                        'type' => 'unitinput',
                        'title' => __('Hierarchy offset', 'joli-table-of-contents'),
                        'args' => [
                            'placeholder' => '20',
                            'desc' => __('Empty space per level of title depth. Set to "0" to have all the titles vertically inline.', 'joli-table-of-contents'),
                            // 'classes' => 'joli-color-picker',//adds color picker
                            'custom' => jtoc_tagify('p', __('Set to "0" to prevent hierarchical view', 'joli-table-of-contents'), ['class' => 'description']),
                            'values' => [
                                'px' => 'px',
                            ],
                        ],
                        'default' => '16|px',
                        'sanitize' => 'unit',
                    ],

                    [
                        'id' => 'min-headings',
                        'title' => __('Minimal headings count', 'joli-table-of-contents'),
                        'type' => 'text',
                        'args' => [
                            'desc' => __('Table of contents will not be displayed if the number of headings of the current post is below this number', 'joli-table-of-contents'),
                            'placeholder' => '3',
                        ],
                        'default' => 3,
                        'sanitize' => 'number',
                    ],

                    [
                        'id' => 'max-headings',
                        'title' => __('Maximal headings count', 'joli-table-of-contents'),
                        'type' => 'text',
                        'args' => [
                            'new' => true,
                            'desc' => __('Table of contents will not be displayed if the number of headings of the current post is over this number', 'joli-table-of-contents'),
                            'placeholder' => '3',
                        ],
                        // 'default' => 3,
                        'sanitize' => 'number',
                    ],

                    [
                        'id' => 'animate-on-fold',
                        'title' => __('Animate on fold', 'joli-table-of-contents'),
                        'type' => 'switch',
                        'args' => [
                            'new' => true,
                            'desc' => __('Shows an animation upon folding/unfoldind the table of contents', 'joli-table-of-contents'),
                            // 'class' => 'tab-general'
                        ],
                        'default' => 0,
                        'sanitize' => 'checkbox',
                    ],

                    [
                        'id' => 'smooth-scroll',
                        'title' => __('Smooth scroll', 'joli-table-of-contents'),
                        'type' => 'switch',
                        'args' => [
                            'desc' => __('Enables smooth scrolling when clicking an element of the table of contents', 'joli-table-of-contents'),
                            // 'class' => 'tab-general'
                            'custom' => jtoc_tagify(
                                'p',
                                __('Some themes have built-in smooth scrolling for links. This may interfere with Joli TOC\'s smooth scrolling if both are activated.', 'joli-table-of-contents'),
                                ['class' => ['description', 'danger']]
                            ),
                        ],
                        'default' => 1,
                        'sanitize' => 'checkbox',
                    ],

                    [
                        'id' => 'headings-full-row-clickable',
                        'title' => __('Heading full row clickable', 'joli-table-of-contents'),
                        'type' => 'switch',
                        'args' => [
                            'new' => true,
                            'desc' => __('Lets the whole row including empty space and padding to be clickable, not only the text link', 'joli-table-of-contents'),
                            // 'class' => 'tab-general'
                        ],
                        'default' => 0,
                        'sanitize' => 'checkbox',
                    ],

                    [
                        'id' => 'headings-overflow',
                        'title' => __('Headings overflow', 'joli-table-of-contents'),
                        'type' => 'select',
                        'args' => [
                            'desc' => __('How to handle headings that are longer than the table of content (especially for mobile devices).', 'joli-table-of-contents'),
                            'values' => [
                                'wrap' => __('Wrap (overflowing content will show on a new line)', 'joli-table-of-contents'),
                                'hidden-ellipsis' => __('Hidden, with ellipsis (\'...\')', 'joli-table-of-contents'),
                                // 'hidden-gradient' => __('Hidden, with fading gradient', 'joli-table-of-contents'),
                                'hidden' => __('Hidden', 'joli-table-of-contents'),
                            ],
                            // 'media' => [
                            //     'unfolded-incontent' => 'unfolded-incontent.gif',
                            //     'folded-incontent' => 'folded-incontent.gif',
                            // ],
                        ],
                        'default' => 'wrap',
                    ],

                    [
                        'id' => 'jump-to-offset',
                        'title' => __('Jump-to offset (in pixels)', 'joli-table-of-contents'),
                        'type' => 'unitinput',
                        'args' => [
                            'placeholder' => '50',
                            'desc' => __('Offset between the top of the viewport and the clicked heading.', 'joli-table-of-contents'),
                            // 'classes' => 'joli-color-picker',//adds color picker
                            'values' => [
                                'px' => 'px',
                            ],
                        ],
                        'default' => '50|px',
                        'sanitize' => 'unit',
                    ],

                    [

                        'id' => 'jump-to-offset-mobile',
                        'title' => __('Jump-to offset (mobile)', 'joli-table-of-contents'),
                        'type' => 'unitinput',
                        'args' => [
                            'placeholder' => '50',
                            'desc' => __('Offset between the top of the viewport and the clicked heading.', 'joli-table-of-contents'),
                            // 'classes' => 'joli-color-picker',//adds color picker
                            'values' => [
                                'px' => 'px',
                            ],
                        ],
                        'default' => '50|px',
                        'sanitize' => 'unit',
                    ],

                    [
                        'id' => 'fold-on-load',
                        'title' => __('Fold on load', 'joli-table-of-contents'),
                        'type' => 'select',
                        'args' => [
                            'new' => true,
                            'desc' => __('Keeps the table of contents folded upon page load', 'joli-table-of-contents'),
                            // 'class' => 'tab-general'
                            'values' => [
                                'no' => __('No', 'joli-table-of-contents'),
                                'yes' => __('Yes', 'joli-table-of-contents'),
                                'responsive' => __('Yes on mobile, No on desktop', 'joli-table-of-contents'),
                                'partial' => __('Partial (view more button)', 'joli-table-of-contents'),
                            ],
                            'values_pro' => [
                                'partial',
                            ],
                            'values_disabled' => [
                                'partial',
                            ],
                        ],
                        'default' => 'no',
                        'sanitize' => 'text',
                    ],

                    [

                        'id' => 'partial-fold-max-height',
                        'title' => __('Partial fold max height', 'joli-table-of-contents'),
                        'type' => 'unitinput',
                        'args' => [
                            'pro' => true,
                            // 'new' => true,
                            'placeholder' => '250',
                            'desc' => __('Controls the max height of the TOC when on partial fold mode.', 'joli-table-of-contents'),
                            // 'classes' => 'joli-color-picker',//adds color picker
                            'values' => [
                                'px' => 'px',
                            ],
                        ],
                        'default' => '250|px',
                        'sanitize' => 'unit',
                    ],

                    [
                        'id' => 'fold-if-headings-count',
                        'title' => __('Fold if headings count exceeds', 'joli-table-of-contents'),
                        'type' => 'text',
                        'args' => [
                            'new' => true,
                            'desc' => __('Table of contents will not be folded upon page load if the number of headings exceed the specified amount.', 'joli-table-of-contents'),
                            'placeholder' => '3',
                        ],
                        // 'default' => 0,
                        'sanitize' => 'number',
                    ],
                    [
                        'id' => 'hide-main-toc',
                        'title' => __('Hide main table of contents', 'joli-table-of-contents'),
                        'type' => 'switch',
                        'args' => [
                            // 'new' => true,
                            'pro' => true,
                            'desc' => __('Hides the main TOC from the content while keeping the floating widget (activate to replicate the deprecated "Invisible, floating" mode)', 'joli-table-of-contents'),
                            'custom' => jtoc_tagify(
                                'p',
                                __('Activate to replicate the deprecated "Invisible, floating" mode.', 'joli-table-of-contents'),
                                ['class' => 'description']
                            ),
                        ],
                        'default' => 0,
                        'sanitize' => 'checkbox',
                    ],
                ],
            ],

            // Table of contents header ----------
            [
                'name' => 'table-of-contents-header',
                'title' => __('Table of contents header', 'joli-table-of-contents'),
                'fields' => [

                    [
                        'id' => 'show-header',
                        'title' => __('Show header', 'joli-table-of-contents'),
                        'type' => 'switch',
                        'args' => [
                            'desc' => __('Show the table of contents header (title & toggle button)', 'joli-table-of-contents'),
                            // 'class' => 'tab-general'
                            'children_sections' => [
                                'table-of-contents-toggle',
                            ],
                            'children' => [
                                'toc-title',
                                'toc-title-alignment',
                                'header-as-toggle',
                            ],
                        ],
                        'default' => 1,
                        'sanitize' => 'checkbox',
                    ],

                    [
                        'id' => 'toc-title',
                        'title' => __('Table of contents title', 'joli-table-of-contents'),
                        'type' => 'text',
                        'args' => [
                            'desc' => __('Title of the Table of contents.', 'joli-table-of-contents'),
                            'placeholder' => __('Table of contents', 'joli-table-of-contents'),
                        ],
                        'default' => __('Table of contents', 'joli-table-of-contents'),
                        'sanitize' => 'text',
                    ],

                    [
                        'id' => 'toc-title-alignment',
                        'title' => __('Title alignement', 'joli-table-of-contents'),
                        'type' => 'select',
                        'args' => [
                            'desc' => __('Alignement of the "Table of contents" title.', 'joli-table-of-contents'),
                            'values' => [ //value =>display
                                'left' => __('Left', 'joli-table-of-contents'),
                                'center' => __('Center', 'joli-table-of-contents'),
                                'right' => __('Right', 'joli-table-of-contents'),
                            ]
                        ],
                        'default' => 'left',
                        'sanitize' => 'text'
                    ],

                    [
                        'id' => 'header-as-toggle',
                        'title' => __('Header as toggle', 'joli-table-of-contents'),
                        'type' => 'switch',
                        'args' => [
                            'new' => true,
                            'desc' => __('A click anywhere on the header will toggle/collapse the toc', 'joli-table-of-contents'),
                            // 'class' => 'tab-general'
                        ],
                        'default' => 0,
                        'sanitize' => 'checkbox',
                    ],
                ],
            ],

            // Table of contents header ----------
            [
                'name' => 'table-of-contents-toggle',
                'title' => __('Table of contents toggle', 'joli-table-of-contents'),
                'fields' => [

                    [
                        'id' => 'show-toggle',
                        'title' => __('Show toggle', 'joli-table-of-contents'),
                        'type' => 'switch',
                        'args' => [
                            'desc' => __('Show the TOC toggle', 'joli-table-of-contents'),
                            // 'class' => 'tab-general'
                            'children' => [
                                'toggle-type',
                                'toggle-position',
                                'toggle-button-icon',
                                'toggle-button-text-opened',
                                'toggle-button-text-closed',
                            ],
                        ],
                        'default' => 1,
                        'sanitize' => 'checkbox',
                    ],

                    [
                        'id' => 'toggle-position',
                        'title' => __('Toggle position', 'joli-table-of-contents'),
                        'type' => 'select',
                        'args' => [
                            'desc' => __('Position of the toggle button within the header.', 'joli-table-of-contents'),
                            'values' => [
                                'left' => __('Left', 'joli-table-of-contents'),
                                'right' => __('Right', 'joli-table-of-contents'),
                            ],
                        ],
                        'default' => 'right',
                    ],


                    [
                        'id' => 'toggle-type',
                        'title' => __('Toggle type', 'joli-table-of-contents'),
                        'type' => 'select',
                        'args' => [
                            'new' => true,
                            // 'class' => 'tab-general',
                            // 'pro' => true,
                            'desc' => __('Type of toggle to be displayed.', 'joli-table-of-contents'),
                            'values' => [ //value =>display
                                'icon' => __('Animated icon', 'joli-table-of-contents'),
                                'icon-std' => __('Icon', 'joli-table-of-contents'),
                                'text' => __('Text', 'joli-table-of-contents'),
                            ],
                            // 'behaviour' => [
                            //     'hide' => [
                            //         'toggle-button-text-closed',
                            //         'toggle-button-text-opened',
                            //         'toggle-button-icon-clo  sed',
                            //         'toggle-button-icon-opened',
                            //     ],
                            //     'icon' => [
                            //         'show' => [
                            //             'toggle-button-icon',
                            //         ],
                            //         'hide' => [
                            //             'toggle-button-text-closed',
                            //             'toggle-button-text-opened',
                            //             'toggle-button-icon-closed',
                            //             'toggle-button-icon-opened',
                            //         ],
                            //     ],
                            //     'icon-std' => [
                            //         'show' => [
                            //             'toggle-button-icon-closed',
                            //             'toggle-button-icon-opened',
                            //         ],
                            //         'hide' => [
                            //             'toggle-button-icon',
                            //             'toggle-button-text-closed',
                            //             'toggle-button-text-opened',
                            //         ],
                            //     ],
                            //     'text' => [
                            //         'show' => [
                            //             'toggle-button-text-closed',
                            //             'toggle-button-text-opened',
                            //         ],
                            //         'hide' => [
                            //             'toggle-button-icon',
                            //             'toggle-button-icon-closed',
                            //             'toggle-button-icon-opened',
                            //         ],
                            //     ],
                            // ],
                            'values_pro' => [],
                        ],
                        'default' => 'icon',
                    ],

                    [
                        'id' => 'toggle-button-icon',
                        'title' => __('Toggle button animated icon', 'joli-table-of-contents'),
                        'type' => 'radioicon',
                        'default' => 'toggle-1',
                        'args' => [
                            'new' => true,
                            // 'desc' => sprintf( '<span style="color:red;">%s</span>', __('Any changes in any styling below (title, headings, colors etc) will override theme defaults', 'joli_faq_seo') ),

                            'desc' => __('Animated icons are icons made from CSS.', 'joli-table-of-contents'),
                            'styles' => ':root{--jtoc-toggle-color: #333;}',
                            'values' => [
                                'toggle-1' => '<div class="wpj-jtoc --jtoc-toggle-1"><div class="wpj-jtoc--toggle"></div></div>',
                                'toggle-2' => '<div class="wpj-jtoc --jtoc-toggle-2"><div class="wpj-jtoc--toggle"></div></div>',
                                'toggle-3' => '<div class="wpj-jtoc --jtoc-toggle-3"><div class="wpj-jtoc--toggle"></div></div>',
                            ],
                            // 'values_pro' => [
                            //     'toggle-1',
                            //     'toggle-2',
                            //     // 'toggle-3',
                            // ],

                        ],
                    ],

                    [
                        'id' => 'toggle-button-text-closed',
                        'title' => __('Toggle button text (closed state)', 'joli-table-of-contents'),
                        'type' => 'text',
                        'args' => [
                            'new' => true,
                            'desc' => __('This setting only applies to the Toggle type "Text" setting.', 'joli-table-of-contents'),
                            'placeholder' => __('Table of contents', 'joli-table-of-contents'),
                        ],
                        'default' => __('show', 'joli-table-of-contents'),
                    ],

                    [
                        'id' => 'toggle-button-text-opened',
                        'title' => __('Toggle button text (opened state)', 'joli-table-of-contents'),
                        'type' => 'text',
                        'args' => [
                            'new' => true,
                            'desc' => __('This setting only applies to the Toggle type "Text" setting.', 'joli-table-of-contents'),
                            'placeholder' => __('Table of contents', 'joli-table-of-contents'),
                        ],
                        'default' => __('hide', 'joli-table-of-contents'),
                    ],
                    [
                        'id' => 'toggle-button-icon-closed',
                        'title' => __('Expand button icon (closed state)', 'joli-table-of-contents'),
                        'type' => 'radioicon',
                        'default' => 'gg-math-plus',
                        'args' => [
                            // 'desc' => sprintf( '<span style="color:red;">%s</span>', __('Any changes in any styling below (title, headings, colors etc) will override theme defaults', 'joli-table-of-contents') ),
                            'desc' => __('This setting only applies to the Toggle type "Icon" setting.', 'joli-table-of-contents'),
                            'values' => [
                                'gg-math-plus' => '<i class="gg-math-plus"></i>',
                                'gg-math-minus' => '<i class="gg-math-minus"></i>',
                                'gg-chevron-down' => '<i class="gg-chevron-down"></i>',
                                'gg-chevron-up' => '<i class="gg-chevron-up"></i>',
                                'gg-menu' => '<i class="gg-menu"></i>',
                                'gg-menu-left-alt' => '<i class="gg-menu-left-alt"></i>',
                                'gg-edit-highlight' => '<i class="gg-edit-highlight"></i>',
                                'gg-layout-grid-small' => '<i class="gg-layout-grid-small"></i>',
                                'gg-layout-list' => '<i class="gg-layout-list"></i>',
                                'gg-pentagon-down' => '<i class="gg-pentagon-down"></i>',
                                'gg-pentagon-up' => '<i class="gg-pentagon-up"></i>',
                                'gg-add-r' => '<i class="gg-add-r"></i>',
                                'gg-remove-r' => '<i class="gg-remove-r"></i>',
                                'gg-add' => '<i class="gg-add"></i>',
                                'gg-remove' => '<i class="gg-remove"></i>',
                                'gg-close' => '<i class="gg-close"></i>',
                                'gg-chevron-double-down' => '<i class="gg-chevron-double-down"></i>',
                                'gg-chevron-double-up' => '<i class="gg-chevron-double-up"></i>',
                                'gg-chevron-down-o' => '<i class="gg-chevron-down-o"></i>',
                                'gg-chevron-up-o' => '<i class="gg-chevron-up-o"></i>',
                            ],
                            'values_pro' => [
                                'gg-menu',
                                'gg-menu-left-alt',
                                'gg-edit-highlight',
                                'gg-layout-grid-small',
                                'gg-layout-list',
                                'gg-pentagon-down',
                                'gg-pentagon-up',
                                'gg-add-r',
                                'gg-remove-r',
                                'gg-add',
                                'gg-remove',
                                'gg-close',
                                'gg-chevron-double-down',
                                'gg-chevron-double-up',
                                'gg-chevron-down-o',
                                'gg-chevron-up-o',
                            ],
                        ],
                    ],
                    [
                        'id' => 'toggle-button-icon-opened',
                        'title' => __('Collapse button icon (opened state)', 'joli-table-of-contents'),
                        'type' => 'radioicon',
                        'default' => 'gg-math-minus',
                        'args' => [
                            // 'desc' => sprintf( '<span style="color:red;">%s</span>', __('Any changes in any styling below (title, headings, colors etc) will override theme defaults', 'joli-table-of-contents') ),
                            'desc' => __('This setting only applies to the Toggle type "Icon" setting.', 'joli-table-of-contents'),
                            'values' => [
                                'gg-math-plus' => '<i class="gg-math-plus"></i>',
                                'gg-math-minus' => '<i class="gg-math-minus"></i>',
                                'gg-chevron-down' => '<i class="gg-chevron-down"></i>',
                                'gg-chevron-up' => '<i class="gg-chevron-up"></i>',
                                'gg-menu' => '<i class="gg-menu"></i>',
                                'gg-menu-left-alt' => '<i class="gg-menu-left-alt"></i>',
                                'gg-edit-highlight' => '<i class="gg-edit-highlight"></i>',
                                'gg-layout-grid-small' => '<i class="gg-layout-grid-small"></i>',
                                'gg-layout-list' => '<i class="gg-layout-list"></i>',
                                'gg-pentagon-down' => '<i class="gg-pentagon-down"></i>',
                                'gg-pentagon-up' => '<i class="gg-pentagon-up"></i>',
                                'gg-add-r' => '<i class="gg-add-r"></i>',
                                'gg-remove-r' => '<i class="gg-remove-r"></i>',
                                'gg-add' => '<i class="gg-add"></i>',
                                'gg-remove' => '<i class="gg-remove"></i>',
                                'gg-close' => '<i class="gg-close"></i>',
                                'gg-chevron-double-down' => '<i class="gg-chevron-double-down"></i>',
                                'gg-chevron-double-up' => '<i class="gg-chevron-double-up"></i>',
                                'gg-chevron-down-o' => '<i class="gg-chevron-down-o"></i>',
                                'gg-chevron-up-o' => '<i class="gg-chevron-up-o"></i>',
                            ],
                            'values_pro' => [
                                'gg-menu',
                                'gg-menu-left-alt',
                                'gg-edit-highlight',
                                'gg-layout-grid-small',
                                'gg-layout-list',
                                'gg-pentagon-down',
                                'gg-pentagon-up',
                                'gg-add-r',
                                'gg-remove-r',
                                'gg-add',
                                'gg-remove',
                                'gg-close',
                                'gg-chevron-double-down',
                                'gg-chevron-double-up',
                                'gg-chevron-down-o',
                                'gg-chevron-up-o',
                            ],
                            'custom' => sprintf('<a href="%sadmin.php?page=joli_toc_user_guide#hooks">', get_admin_url()) . __('How to customize buttons with custom HTML ?', 'joli-table-of-contents') . '</a>',
                        ],
                    ],
                ],
            ],

            // Table of contents footer ----------
            // [
            //     'name' => 'table-of-contents-footer',
            //     'title' => __('Table of contents footer', 'joli-table-of-contents'),
            //     'fields' => [],
            // ],

            // Numeration ----------
            [
                'name' => 'numeration',
                'title' => __('Numeration', 'joli-table-of-contents'),
                'fields' => [
                    [
                        'id' => 'numeration-type',
                        'title' => __('Numeration type', 'joli-table-of-contents'),
                        'type' => 'select',
                        'args' => [
                            // 'class' => 'tab-general',
                            'desc' => __('Numeration will be displayed before the heading.', 'joli-table-of-contents'),
                            'values' => [ //value =>display
                                'none' => __('None', 'joli-table-of-contents'),
                                'numbers' => __('Numbers (1,2,3...)', 'joli-table-of-contents'),
                                'roman' => __('Roman numbers (I,V,X...)', 'joli-table-of-contents'),
                                'hexadecimal' => __('Hexadecimal', 'joli-table-of-contents'),
                                'binary' => __('Binary (1, 10, 11...)', 'joli-table-of-contents'),
                            ]
                        ],
                        'default' => 'numbers',
                    ],
                    [
                        'id' => 'numeration-display',
                        'title' => __('Numeration display', 'joli-table-of-contents'),
                        'type' => 'select',
                        'args' => [
                            'new' => true,
                            // 'class' => 'tab-general',
                            'desc' => __('Includes or not the parents numbers', 'joli-table-of-contents'),
                            'values' => [ //value =>display
                                'single' => __('Single number (current level only)', 'joli-table-of-contents'),
                                'full' => __('Full (include parent numbers, ex: 1.2.1)', 'joli-table-of-contents'),
                            ]
                        ],
                        'default' => 'full',
                    ],
                    [
                        'id' => 'numeration-separator',
                        'title' => __('Numeration separator', 'joli-table-of-contents'),
                        'type' => 'text',
                        'args' => [
                            'desc' => __('Character that will separate numbers. Ex: "." => "1.1.2"; "-" => "1-1-2"', 'joli-table-of-contents'),
                            'placeholder' => '.',
                        ],
                        'default' => '.',
                        'sanitize' => 'text',
                    ],
                    [
                        'id' => 'numeration-suffix',
                        'title' => __('Numeration suffix', 'joli-table-of-contents'),
                        'type' => 'text',
                        'args' => [
                            'desc' => __('Character that will be shown after the numbers. Ex: ")" => "1.1.2)"; "/" => "1.1.2/"', 'joli-table-of-contents'),
                            'placeholder' => '.',
                        ],
                        'default' => '.',
                        'sanitize' => 'text',
                    ],
                ],
            ],

            // Columns ----------
            [
                'name' => 'columns',
                'title' => __('Columns', 'joli-table-of-contents'),
                'fields' => [
                    [
                        'id' => 'columns-mode',
                        'title' => __('Activate multi-columns mode', 'joli-table-of-contents'),
                        'type' => 'switch',
                        'args' => [
                            'pro' => true,
                            'desc' => __('Enables multi-columns mode. Does not apply to floating widget.', 'joli-table-of-contents'),
                            // 'class' => 'tab-general'

                            'children' => [
                                'columns-min-headings',
                                'columns-breakpoint',
                            ],
                        ],
                        'default' => 0,
                        'sanitize' => 'checkbox',
                    ],

                    [
                        'id' => 'columns-min-headings',
                        'title' => __('Minimal headings count', 'joli-table-of-contents'),
                        'type' => 'text',
                        'args' => [
                            'pro' => true,
                            'desc' => __('Will not switch to multi-columns node until the minimum number of headings has been reached.', 'joli-table-of-contents'),
                        ],
                        'sanitize' => 'number',
                        'default' => 8,
                    ],

                    [
                        'id' => 'columns-breakpoint',
                        'title' => __('Responsive breakpoint', 'joli-table-of-contents'),
                        'type' => 'text',
                        'args' => [
                            'pro' => true,
                            'desc' => __('Breakpoint (in px) after which the multi-columns mode gets activated.', 'joli-table-of-contents') . ' ' . $vars['dontaddpx'],
                        ],
                        'sanitize' => 'number',
                        'default' => 768,
                    ],
                ],
            ],

            // SEO ----------
            [
                'name' => 'seo',
                'title' => __('SEO', 'joli-table-of-contents'),
                'fields' => [
                    [
                        'id' => 'seo-rel-nofollow',
                        'title' => __('Add rel="nofollow"', 'joli-table-of-contents'),
                        'type' => 'switch',
                        'args' => [
                            'new' => true,
                            'pro' => false,
                            'desc' => __('Add a rel="nofollow" attribute to the links.', 'joli-table-of-contents'),
                            'custom' => jtoc_tagify('p', __('To add more custom attributes, check this documentation: ', 'joli-table-of-contents') . jtoc_tagify(
                                'a',
                                __('joli_toc_item_link_attributes', 'joli-table-of-contents'),
                                [
                                    'href' => 'https://wpjoli.com/docs/joli-table-of-contents/developer-hooks/filters/joli_toc_item_link_attributes/',
                                    'target' => '_blank'
                                ]
                            ), ['class' => 'description']),

                        ],
                        'default' => 0,
                        'sanitize' => 'checkbox',
                    ],

                ],
            ],

            // Sticky Table of Contents ----------
            [
                'name' => 'sticky-table-of-contents',
                'title' => __('Sticky Table of Contents', 'joli-table-of-contents'),
                'desc' => jtoc_tagify('p', __('The Sticky Table of Contents mode only works in Desktop mode and when the actual table of contents is placed inside a ', 'joli-table-of-contents') . sprintf('<a href="%s">', admin_url('widgets.php')) . __('sidebar widget.', 'joli-table-of-contents') . '</a>'),
                'fields' => [

                    [
                        'id' => 'toc-is-sticky',
                        'title' => __('Activate Sticky TOC', 'joli-table-of-contents'),
                        'type' => 'switch',
                        'args' => [
                            // 'new' => true,
                            'pro' => true,
                            'desc' => __('Keeps the table of contents in a fixed position as the page is being scrolled. It is recommanded to have the table of contents as the last element of the sidebar.', 'joli-table-of-contents'),
                            // 'class' => 'tab-general'
                            // 'custom' => jtoc_tagify(
                            //     'p',
                            //     __('This option only works in Desktop mode and when the table of contents is placed inside a ', 'joli-table-of-contents') . sprintf('<a href="%s">', admin_url('widgets.php')) . __('sidebar widget.', 'joli-table-of-contents') . '</a>',
                            //     ['class' => ['description']]
                            // ),
                            'children' => [
                                'sticky-toc-offset-top'
                            ],
                        ],
                        'default' => 0,
                        'sanitize' => 'checkbox',
                    ],

                    [
                        'id' => 'sticky-toc-offset-top',
                        'type' => 'unitinput',
                        'title' => __('Sticky TOC offset top', 'joli-table-of-contents'),
                        'args' => [
                            // 'new' => true,
                            'pro' => true,
                            'placeholder' => '20',
                            'desc' => __('Adjust this setting to prevent the TOC from behing partially hidden if your theme has a fixed header for example', 'joli-table-of-contents'),
                            // 'classes' => 'joli-color-picker',//adds color picker
                            // 'custom' => jtoc_tagify('p', __('Set to "0" to prevent hierarchical view', 'joli-table-of-contents'), ['class' => 'description']),
                            'values' => [
                                'px' => 'px',
                            ],
                        ],
                        'default' => '0|px',
                        'sanitize' => 'unit',
                    ],
                ],
            ],
        ],
    ],
    // END GROUP: General ********************************************************


    // GROUP: HEADINGS ********************************************************
    [
        'group' => 'headings',
        'label' => __('Headings', 'joli-table-of-contents'),
        'sections' => [
            // Headings processing ----------
            [
            'name' => 'headings-processing',
                'title' => __('Headings processing', 'joli-table-of-contents'),
                'fields' => [
                    [
                        'id' => 'headings-depth',
                        'title' => __('Headings depth', 'joli-table-of-contents'),
                        'type' => 'checkboxes',
                        'args' => [
                            'desc' => __('Select one or more items to specify what type of headings to pick up on for the table of contents', 'joli-table-of-contents'),
                            'values' => [
                                'h2' => 'H2',
                                'h3' => 'H3',
                                'h4' => 'H4',
                                'h5' => 'H5',
                                'h6' => 'H6',
                            ],
                            'values_pro' => [],
                        ],
                        'default' => 'h2,h3,h4,h5,h6',
                        'sanitize' => 'checkboxes',
                    ],

                    [
                        'id' => 'skip-h-by-text',
                        'title' => __('Skip by text', 'joli-table-of-contents'),
                        'type' => 'textarea',
                        'args' => [
                            'placeholder' => "m*rch\nskip me",
                            'desc' => __('Headings to be excluded by custom text (one per line). Use * as wildcard to match any text. Ex: "m*rch" will exclude "march" and "merch"', 'joli-table-of-contents'),
                            // 'classes' => 'large-text',
                            // 'custom' => ,
                            'textarea-size' => 'small'
                        ],
                        'sanitize' => 'Textarea'
                    ],

                    [
                        'id' => 'skip-h-by-class',
                        'title' => __('Skip by class', 'joli-table-of-contents'),
                        'type' => 'text',
                        'args' => [
                            'placeholder' => 'my-class',
                            'desc' => __('Ignores headings with the specified css classes. For multiple classes, seperate by a blank space. ex: my-class1 my-class2', 'joli-table-of-contents'),
                        ],
                        'sanitize' => 'text'
                    ],

                    [
                        'id' => 'skip-h-by-ascending-class',
                        'title' => __('Skip by ascending class', 'joli-table-of-contents'),
                        'type' => 'text',
                        'args' => [
                            // 'new' => true,
                            'pro' => true,
                            'placeholder' => 'my-class',
                            'desc' => __('Ignores headings whose ancestor has the specified css class. For multiple classes, seperate by a blank space. ex: my-class1 my-class2', 'joli-table-of-contents'),
                        ],
                        'sanitize' => 'text'
                    ],
                ],
            ],

            // Headings hash ----------
            [
                'name' => 'headings-hash',
                'title' => __('Headings hash', 'joli-table-of-contents'),
                'fields' => [
                    [
                        'id' => 'hash-in-url',
                        'title' => __('Show hash in URL', 'joli-table-of-contents'),
                        'type' => 'switch',
                        'args' => [
                            'new' => true,
                            'desc' => __('Add the hash of the clicked heading to the current URL. Ex: https://mysite.com/my-article/#clicked-heading', 'joli-table-of-contents'),
                        ],
                        'default' => true,
                        'sanitize' => 'checkbox'
                    ],
                    
                    [
                        'id' => 'hash-format',
                        'title' => __('Hash format', 'joli-table-of-contents'),
                        'type' => 'select',
                        'args' => [
                            'values' => [
                                'latin' => __('Latin unaccented characters only (#my-heading)', 'joli-table-of-contents'),
                                'all' => __('Latin & non-latin characters (#我的头衔)', 'joli-table-of-contents'),
                                'all-translit' => __('Latin & non-latin transliterated characters (#История => #istoriya)', 'joli-table-of-contents'),
                                'counter' => __('Counter (#section_1, #section_2, etc)', 'joli-table-of-contents'),
                            ],
                            'desc' => __('Handling of the anchor IDs. Existing IDs will not be changed. If heading cannot be processed, counter will come as a fallback', 'joli-table-of-contents'),
                        ],
                        'default' => 'latin',
                    ],
                    [
                        'id' => 'hash-counter-prefix',
                        'title' => __('Counter prefix', 'joli-table-of-contents'),
                        'type' => 'text',
                        'args' => [
                            'desc' => __('This setting only applies to the Hash format "Counter" setting.', 'joli-table-of-contents'),
                            'placeholder' => 'section_',
                        ],
                        'default' => 'section_',
                        'sanitize' => 'text',
                    ],
                ],
            ],

            // Headings hash ----------
            [
                'name' => 'headings-dynamic-unfold',
                'title' => __('Headings dynamic unfold', 'joli-table-of-contents'),
                'fields' => [
                    [
                        'id' => 'activate-dynamic-unfold',
                        'title' => __('Activate dynamic unfold', 'joli-table-of-contents'),
                        'type' => 'switch',
                        'args' => [
                            // 'new' => true,
                            'pro' => true,
                            'desc' => __('Dynamic unfold mode keeps the table of contents headings folded to the first level, and unfolds dynamically the current active heading. This mode is mostly suitable for a fixed toc in a sidebar or for the slide-out table of contents widget.', 'joli-table-of-contents'),
                            'custom' => jtoc_tagify('p', __('It is recommanded to use this setting for a fixed sidebar table of contents, or if the slide-out table of contents is active.', 'joli-table-of-contents'), ['class' => 'description']),
                            // 'classes' => 'joli-color-picker',//adds color picker
                        ],
                        'default' => false,
                        'sanitize' => 'checkbox'
                    ],
                ],
            ],
        ],
    ],
    // END GROUP: HEADINGS ********************************************************

    // GROUP: AUTO-INSERT ********************************************************
    [
        'group' => 'auto-insert',
        'label' => __('Auto-insert', 'joli-table-of-contents'),
        // 'args' => [
        //     'post_type_settings' => false,
        // ],
        'sections' => [
            // Auto-insert behaviour ----------
            // [
            //     'name' => 'auto-insert',
            //     'title' => __('Auto-insert', 'joli-table-of-contents'),
            //     'fields' => [

            //         [
            //             'id' => 'activate-auto-insert',
            //             'title' => __('Activate auto-insert table of contents', 'joli-table-of-contents'),
            //             'type' => 'switch',
            //             'args' => [
            //                 'pro' => true,
            //                 'desc' => __('Activates the auto-insert mode', 'joli-table-of-contents'),
            //                 'children_sections' => [
            //                     'auto-insert-settings',
            //                     'post-inclusion',
            //                     'post-exclusion',
            //                 ],
            //                 // 'children' => [
            //                 //     'position-auto',
            //                 //     'auto-insert-post-types',
            //                 //     'inclusion-post-title',
            //                 //     'inclusion-post-id',
            //                 //     'exclusion-post-title',
            //                 //     'exclusion-post-id',
            //                 // ],
            //             ],
            //             'default' => 0,
            //             'sanitize' => 'checkbox',
            //         ],

            //     ],
            // ],
            // Auto-insert behaviour ----------
            [
                'name' => 'auto-insert-settings',
                'title' => __('Auto-insert settings', 'joli-table-of-contents'),
                'fields' => [
                    [
                        'id' => 'position-auto',
                        'title' => __('TOC Position', 'joli-table-of-contents'),
                        'type' => 'select',
                        'args' => [
                            'desc' => __('Auto insert TOC position. Where in the content the TOC should be automatically inserted', 'joli-table-of-contents'),
                            'values' => [ //value =>display
                                'before-content' => __('Before the content', 'joli-table-of-contents'),
                                'after-content' => __('After the content', 'joli-table-of-contents'),
                                'before-h1' => __('Before H1', 'joli-table-of-contents'),
                                'after-h1' => __('After H1', 'joli-table-of-contents'),
                                'before-h2-1' => __('Before first H2 tag', 'joli-table-of-contents'),
                                'after-p-1' => __('After first paragraph', 'joli-table-of-contents'),
                                'before-img-1' => __('Before first image', 'joli-table-of-contents'),
                                'after-img-1' => __('After first image', 'joli-table-of-contents'),
                            ],
                            'default' => 'before-content',
                        ],
                        'has_block_attr' => false,
                        // 'is_global' => true,
                    ],
                ],
            ],

            // Post selection ----------
            [
                'name' => 'post-inclusion',
                'title' => __('Auto-insert inclusion rules', 'joli-table-of-contents'),
                'desc' => jtoc_tagify('p', __('This section only applies to the global settings', 'joli-table-of-contents')),
                'fields' => [
                    [
                        'id' => 'auto-insert-post-types',
                        'title' => __('Post type', 'joli-table-of-contents'),
                        'type' => 'posttype',
                        'args' => [
                            'desc' => __('Auto insert TOC on specific post types', 'joli-table-of-contents'),
                            // 'placeholder' => 'Table of contents',
                            'is_global' => true,
                        ],
                        'default' => [],
                        'has_block_attr' => false,
                    ],

                    [
                        'id' => 'inclusion-post-title',
                        'title' => __('Post title', 'joli-table-of-contents'),
                        'type' => 'textarea',
                        'args' => [
                            // 'new' => true,
                            'pro' => true,
                            'placeholder' => "m*rch\nskip me",
                            'desc' => __('Includes posts that contains a specific sentence (one per line). Use * as wildcard to match any text. Ex: "m*rch" will exclude "march" and "merch"', 'joli-table-of-contents'),
                            // 'classes' => 'large-text',
                            // 'custom' => ,
                            'textarea-size' => 'small',
                            'is_global' => true,
                        ],
                        'has_block_attr' => false,
                        'sanitize' => 'Textarea',
                    ],

                    [
                        'id' => 'inclusion-post-id',
                        'title' => __('Post ID', 'joli-table-of-contents'),
                        'type' => 'text',
                        'args' => [
                            // 'new' => true,
                            'pro' => true,
                            'placeholder' => '123,234',
                            'desc' => __('Includes specific post by their ID. For multiple IDs, seperate by a coma. ex: 123,234,345', 'joli-table-of-contents'),
                            'is_global' => true,
                        ],
                        'has_block_attr' => false,
                        'sanitize' => 'text'
                    ],
                ],
            ],

            // Post exclusion ----------
            [
                'name' => 'post-exclusion',
                'title' => __('Auto-insert exclusion rules', 'joli-table-of-contents'),
                'desc' => jtoc_tagify('p', __('This section only applies to the global settings', 'joli-table-of-contents')),
                'fields' => [
                    [
                        'id' => 'exclusion-post-title',
                        'title' => __('Post title', 'joli-table-of-contents'),
                        'type' => 'textarea',
                        'args' => [
                            // 'new' => true,
                            'pro' => true,
                            'placeholder' => "m*rch\nskip me",
                            'desc' => __('Excludes posts that contains a specific sentence (one per line). Use * as wildcard to match any text. Ex: "m*rch" will exclude "march" and "merch"', 'joli-table-of-contents'),
                            // 'classes' => 'large-text',
                            // 'custom' => ,
                            'textarea-size' => 'small',
                            'is_global' => true,
                        ],
                        'has_block_attr' => false,
                        'sanitize' => 'Textarea'
                    ],

                    [
                        'id' => 'exclusion-post-id',
                        'title' => __('Post ID', 'joli-table-of-contents'),
                        'type' => 'text',
                        'args' => [
                            // 'new' => true,
                            'pro' => true,
                            'placeholder' => '123,234',
                            'desc' => __('Ignores specific post by their ID. For multiple IDs, seperate by a coma. ex: 123,234,345', 'joli-table-of-contents'),
                            'is_global' => true,
                        ],
                        'has_block_attr' => false,
                        'sanitize' => 'text'
                    ],
                ],
            ],
        ],
    ],
    // END GROUP: AUTO-INSERT ********************************************************

    // GROUP: WIDGET SUPPORT ********************************************************
    [
        'group' => 'widget-support',
        'label' => __('Widget support', 'joli-table-of-contents'),
        'args' => [
            'post_type_settings' => false,
        ],
        'sections' => [

            // Post selection ----------
            [
                'name' => 'widget-support-post-inclusion',
                'title' => __('Enable widget support', 'joli-table-of-contents'),
                'desc' => jtoc_tagify('p', __('You need to enable widget support if you plan to use the Table of contents inside a sidebar widget (Appearance > Widgets)', 'joli-table-of-contents')),
                'fields' => [
                    [
                        'id' => 'widget-support-post-types',
                        'title' => __('Post type', 'joli-table-of-contents'),
                        'type' => 'posttype',
                        'args' => [
                            'new' => true,
                            'desc' => __('Enables support for selected post type. Use this setting if you are using a Joli Table of contents block or shortcode inside a sidebar widget (Appearance > Widgets)', 'joli-table-of-contents'),
                            // 'placeholder' => 'Table of contents',
                            'is_global' => true,
                        ],
                        'has_block_attr' => false,
                    ],
                ],
            ],
        ],
    ],
    // END GROUP: WIDGET SUPPORT ********************************************************

    // GROUP: BULLET POINTS ********************************************************
    [
        'group' => 'bullet-points',
        'label' => __('Bullet points', 'joli-table-of-contents'),
        'sections' => [
            // Bullet points
            [
                'name' => 'bullet-points',
                'title' => __('Bullet points', 'joli-table-of-contents'),
                // 'desc' => JTOC()->render(['admin' => 'toc-view'], ['highlight' => 'main'], true),
                'fields' => [
                    [
                        'id' => 'activate-bullet-points',
                        'title' => __('Activate bullet points', 'joli-table-of-contents'),
                        'type' => 'switch',
                        'args' => [
                            // 'pro' => false,
                            'new' => true,
                            'desc' => __('Activates bullet points on the TOC', 'joli-table-of-contents'),
                            'children_sections' => [
                                'bullet-points-settings',
                                'bullet-points-settings-h2',
                                'bullet-points-settings-h3',
                                'bullet-points-settings-h4',
                                'bullet-points-settings-h5',
                                'bullet-points-settings-h6',
                            ],
                            // 'children' => [
                            //     'floating-compatibility-mode',
                            // ],
                        ],
                        'default' => 0,
                        'sanitize' => 'checkbox',
                    ],

                ],
            ],
            // Bullet points settings
            [
                'name' => 'bullet-points-settings',
                'title' => __('Bullet points settings', 'joli-table-of-contents'),
                // 'desc' => jtoc_tagify(
                //     'p',
                //     __('The color palette is a new experimental feature that aims to simplify color customization for themes. At the moment, it only works with the "Vertik" theme.', 'joli-table-of-contents'),
                //     ['class' => ['description', 'danger']]
                // ),
                'fields' => [
                    [
                        'id' => 'bullet-points-headings-depth',
                        'title' => __('Activate for', 'joli-table-of-contents'),
                        'type' => 'checkboxes',
                        'args' => [
                            'desc' => __('Select one or more items to activate the bullet points on specific heading depths', 'joli-table-of-contents'),
                            'values' => [
                                '2' => 'H2',
                                '3' => 'H3',
                                '4' => 'H4',
                                '5' => 'H5',
                                '6' => 'H6',
                            ],
                            'values_pro' => [],
                        ],
                        'default' => '2,3,4,5,6',
                        'sanitize' => 'checkboxes',
                    ],

                    [
                        'id' => 'bullet-points-type',
                        'title' => __('Bullet type', 'joli-table-of-contents'),
                        'type' => 'select',
                        'args' => [
                            // 'pro' => false,
                            // 'new' => true,
                            'desc' => __('Bullet points type for all headings.', 'joli-table-of-contents'),
                            'values' => [
                                'disc' => __('Disc', 'joli-table-of-contents'),
                                'square' => __('Square', 'joli-table-of-contents'),
                                'pill' => __('Pill', 'joli-table-of-contents'),
                            ],
                        ],
                        'default' => 'disc',
                    ],

                    [
                        'id' => 'bullet-points-color',
                        'title' => __('Bullet color', 'joli-table-of-contents'),
                        'type' => 'text',
                        'args' => [
                            // 'new' => true,
                            'placeholder' => '#ffffff',
                            'classes' => 'joli-color-picker', //adds color picker
                            'data' => [
                                'alpha-enabled' => 'true',
                                'alpha-color-type' => 'hex',
                            ],
                        ],
                        // 'default' => '#adadad',
                        'sanitize' => 'color'
                    ],

                    [
                        'id' => 'bullet-points-size',
                        'title' => __('Bullet size', 'joli-table-of-contents'),
                        'type' => 'select',
                        'args' => [
                            // 'pro' => false,
                            // 'new' => true,
                            'desc' => __('Size of the bullet points.', 'joli-table-of-contents'),
                            'values' => [
                                's' => __('Small', 'joli-table-of-contents'),
                                'm' => __('Medium', 'joli-table-of-contents'),
                                'l' => __('Large', 'joli-table-of-contents'),
                            ],
                        ],
                        'default' => 's',
                    ],
                ],
            ],
        ],
    ],

    // GROUP: STYLES ********************************************************
    [
        'group' => 'theme',
        'label' => __('Theme', 'joli-table-of-contents'),
        'sections' => [
            // Table of contents
            [
                'name' => 'base-theme',
                'title' => __('Base theme', 'joli-table-of-contents'),
                // 'desc' => JTOC()->render(['admin' => 'toc-view'], ['highlight' => 'main'], true),
                'fields' => [
                    [
                        'id' => 'theme',
                        'title' => __('Theme', 'joli-table-of-contents'),
                        'type' => 'select',
                        'args' => [
                            'desc' => sprintf('<span>%s</span>', __('The theme will define the look and feel of the table of contents. Override any theme preset in the STYLES tab. For advanced users, you can create your own theme.', 'joli-table-of-contents')),
                            'custom' => jtoc_tagify(
                                'p',
                                jtoc_tagify(
                                    'a',
                                    __('How to create your own theme ?', 'joli-table-of-contents'),
                                    [
                                        'href' => 'https://wpjoli.com/docs/joli-table-of-contents/customizing/create-my-own-theme/',
                                        'target' => '_blank'
                                    ]
                                )
                            ) . jtoc_tagify(
                                'p',
                                __('Go to the STYLES tab to add a border, round the corners, change the colors, or any other setting available !', 'joli-table-of-contents'),
                                ['class' => 'description']
                            ),
                            'values' => [
                                'none' => __('[no theme]', 'joli-table-of-contents'),
                                'basic-light' => __('Basic light', 'joli-table-of-contents'),
                                'basic-dark' => __('Basic dark', 'joli-table-of-contents'),
                                'original' => __('Original', 'joli-table-of-contents'),
                                'original-dark' => __('Original dark', 'joli-table-of-contents'),
                                'wikipedia' => __('Wikipedia', 'joli-table-of-contents'),
                                'metro' => __('Metro', 'joli-table-of-contents'),
                                'modern' => __('Modern', 'joli-table-of-contents'),
                                // 'dark' => __('Dark', 'joli-table-of-contents'),
                                // 'classic' => __('Classic', 'joli-table-of-contents'),
                                // 'classic-dark' => __('Classic dark', 'joli-table-of-contents'),
                                'smooth' => __('Smooth', 'joli-table-of-contents'),
                                'smooth-flat-gray' => __('Smooth flat gray', 'joli-table-of-contents'),
                                'silky-light' => __('Silky light', 'joli-table-of-contents'),
                                'clean-rounded' => __('Clean rounded', 'joli-table-of-contents'),
                                'vertik' => __('Vertik', 'joli-table-of-contents'),
                            ],
                            'values_pro' => [
                                'metro',
                                'modern',
                                'smooth',
                                'smooth-flat-gray',
                                'silky-light',
                                'clean-rounded',
                                'vertik',
                            ],
                            'values_custom' => 'jtoc_custom_themes', //JS var to pickup data from
                            'media' => [
                                // 'default' => 'default.png',
                                'basic-light' => 'themes/basic-light.png',
                                'basic-dark' => 'themes/basic-dark.png',
                                'original' => 'themes/original.png',
                                'original-dark' => 'themes/original-dark.png',
                                'metro' => 'themes/metro.png',
                                'modern' => 'themes/modern.png',
                                'wikipedia' => 'themes/wikipedia.png',
                                'smooth' => 'themes/smooth.png',
                                'smooth-flat-gray' => 'themes/smooth-flat-gray.png',
                                'silky-light' => 'themes/silky-light.png',
                                'clean-rounded' => 'themes/clean-rounded.png',
                                'vertik' => 'themes/vertik.png',
                            ],
                        ],
                        'default' => 'basic-light',
                    ],

                    [
                        'id' => 'preserve-theme-styles',
                        'title' => __('Preserve theme styles', 'joli-table-of-contents'),
                        'type' => 'switch',
                        'args' => [
                            'desc' => __("Check this option to disable all custom styles from the STYLES tab and preserve the selected theme's default styles", 'joli-table-of-contents'),
                            // 'classes' => 'joli-color-picker',//adds color picker
                        ],
                        'default' => false,
                        'sanitize' => 'checkbox'
                    ],
                ],
            ],
        ],
    ],

    // GROUP: STYLES ********************************************************
    [
        'group' => 'styles',
        'label' => __('Styles', 'joli-table-of-contents'),
        'sections' => [
            // Table of contents
            [
                'name' => 'table-of-contents-styles',
                'title' => __('Table of contents', 'joli-table-of-contents'),
                'desc' => JTOC()->render(['admin' => 'toc-view'], ['highlight' => 'main'], true),
                'fields' => [

                    [
                        'id' => 'toc-margin',
                        'title' => __('Margin', 'joli-table-of-contents'),
                        'type' => 'dimensions',
                        'args' => [
                            'desc' => __('Margin of the whole table of contents.', 'joli-table-of-contents'),
                            // 'placeholder' => '10',
                            'dimensions_type' => 'margin',
                            'sub_dimensions' => ['top', 'right', 'bottom', 'left'],
                            'units' => [
                                'px' => 'px',
                                'em' => 'em',
                                'rem' => 'rem',
                                'percent' => '%',
                            ],
                        ],
                        // 'default' => [],
                        'sanitize' => 'dimensions',
                        'sanitize_args' => [
                            'dimensions' => [
                                'top' => 'integer',
                                'right' => 'integer',
                                'bottom' => 'integer',
                                'left' => 'integer',
                            ],
                            'units' => [
                                'px' => 'px',
                                'em' => 'em',
                                'rem' => 'rem',
                                'percent' => '%',
                            ],
                        ],
                    ],

                    [
                        'id' => 'toc-padding',
                        'title' => __('Padding', 'joli-table-of-contents'),
                        'type' => 'dimensions',
                        'args' => [
                            'desc' => __('Padding of the whole table of contents.', 'joli-table-of-contents'),
                            // 'placeholder' => '10',
                            'dimensions_type' => 'padding',
                            'sub_dimensions' => ['top', 'right', 'bottom', 'left'],
                            'units' => [
                                'px' => 'px',
                                'em' => 'em',
                                'rem' => 'rem',
                                'percent' => '%',
                            ],
                        ],
                        // 'default' => [],
                        'sanitize' => 'dimensions',
                        'sanitize_args' => [
                            'dimensions' => [
                                'top' => 'integer',
                                'right' => 'integer',
                                'bottom' => 'integer',
                                'left' => 'integer',
                            ],
                            'units' => [
                                'px' => 'px',
                                'em' => 'em',
                                'rem' => 'rem',
                                'percent' => '%',
                            ],
                        ],
                    ],

                    [
                        'id' => 'toc-border-radius',
                        'title' => __('Border radius', 'joli-table-of-contents'),
                        'type' => 'dimensions',
                        'args' => [
                            'desc' => __('Border radius of the whole table of contents.', 'joli-table-of-contents'),
                            // 'placeholder' => '10',
                            'dimensions_type' => 'border',
                            'sub_dimensions' => ['top-left', 'top-right', 'bottom-right', 'bottom-left'],
                            'units' => [
                                'px' => 'px',
                                'em' => 'em',
                                'rem' => 'rem',
                                'percent' => '%',
                            ],
                        ],
                        // 'default' => [],
                        'sanitize' => 'dimensions',
                        'sanitize_args' => [
                            'dimensions' => [
                                'top-left' => 'integer',
                                'top-right' => 'integer',
                                'bottom-right' => 'integer',
                                'bottom-left' => 'integer',
                            ],
                            'units' => [
                                'px' => 'px',
                                'em' => 'em',
                                'rem' => 'rem',
                                'percent' => '%',
                            ],
                        ],
                    ],

                    [
                        'id' => 'toc-border',
                        'title' => __('Border', 'joli-table-of-contents'),
                        'type' => 'dimensions',
                        'args' => [
                            'desc' => __('Border of the whole table of contents.', 'joli-table-of-contents'),
                            // 'placeholder' => '10',
                            'dimensions_type' => 'border',
                            'sub_dimensions' => ['top', 'right', 'bottom', 'left'],
                            'units' => [
                                'px' => 'px',
                                'em' => 'em',
                                'rem' => 'rem',
                                'percent' => '%',
                            ],
                        ],
                        // 'default' => [],
                        'sanitize' => 'dimensions',
                        'sanitize_args' => [
                            'dimensions' => [
                                'top' => 'integer',
                                'right' => 'integer',
                                'bottom' => 'integer',
                                'left' => 'integer',
                            ],
                            'units' => [
                                'px' => 'px',
                                'em' => 'em',
                                'rem' => 'rem',
                                'percent' => '%',
                            ],
                        ],
                    ],

                    [
                        'id' => 'toc-border-color',
                        'title' => __('Border color', 'joli-table-of-contents'),
                        'type' => 'text',
                        'args' => [
                            'desc' => __('Border color of the whole table of contents.', 'joli-table-of-contents'),
                            // 'class' => 'tab-appearance',
                            'placeholder' => '#ffffff',
                            'classes' => 'joli-color-picker', //adds color picker
                            'data' => [
                                'alpha-enabled' => 'true',
                                'alpha-color-type' => 'hex',
                            ],
                        ],
                        // 'default' => '#ffffff',
                        'sanitize' => 'color'
                    ],

                    [
                        'id' => 'toc-background-color',
                        'title' => __('Background color', 'joli-table-of-contents'),
                        'type' => 'text',
                        'args' => [
                            // 'class' => 'tab-appearance',
                            'desc' => __('Background color of the whole table of contents.', 'joli-table-of-contents'),
                            'placeholder' => '#ffffff',
                            'classes' => 'joli-color-picker', //adds color picker
                            'data' => [
                                'alpha-enabled' => 'true',
                                'alpha-color-type' => 'hex',
                            ],
                        ],
                        // 'default' => '#ffffff',
                        'sanitize' => 'color'
                    ],

                    [
                        'id' => 'toc-shadow',
                        'title' => __('Shadow', 'joli-table-of-contents'),
                        'type' => 'switch',
                        'args' => [
                            'desc' => __('Displays a shadow around the Table of contents', 'joli-table-of-contents'),
                            // 'classes' => 'joli-color-picker',//adds color picker
                        ],
                        'default' => false,
                        'sanitize' => 'checkbox'
                    ],

                    [
                        'id' => 'toc-shadow-color',
                        'title' => __('Custom shadow color', 'joli-table-of-contents'),
                        'type' => 'text',
                        'args' => [
                            'placeholder' => '#c2c2c2',
                            'classes' => 'joli-color-picker', //adds color picker
                            'data' => [
                                'alpha-enabled' => 'true',
                                'alpha-color-type' => 'hex',
                            ],
                        ],
                        // 'default' => '#c2c2c2',
                        'sanitize' => 'Color'
                    ],
                ],
            ],

            // Table of contents header
            [
                'name' => 'table-of-contents-header-styles',
                'title' => __('Table of contents header', 'joli-table-of-contents'),
                'desc' => JTOC()->render(['admin' => 'toc-view'], ['highlight' => 'header'], true),
                'fields' => [
                    [
                        'id' => 'toc-header-height',
                        'title' => __('Height', 'joli-table-of-contents'),
                        'type' => 'unitinput',
                        'args' => [
                            'desc' => __('Specify a number for a fixed header height', 'joli-table-of-contents'),
                            'values' => [
                                'px' => 'px',
                                'em' => 'em',
                                'rem' => 'rem',
                            ],
                        ],
                        'sanitize' => 'unit',
                        // 'default' => '50|px',
                    ],

                    [
                        'id' => 'toc-header-margin',
                        'title' => __('Margin', 'joli-table-of-contents'),
                        'type' => 'dimensions',
                        'args' => [
                            'desc' => __('Margin of the header.', 'joli-table-of-contents'),
                            // 'placeholder' => '10',margin
                            'dimensions_type' => 'padding',
                            'sub_dimensions' => ['top', 'right', 'bottom', 'left'],
                            'units' => [
                                'px' => 'px',
                                'em' => 'em',
                                'rem' => 'rem',
                                'percent' => '%',
                            ],
                        ],
                        // 'default' => [],
                        'sanitize' => 'dimensions',
                        'sanitize_args' => [
                            'dimensions' => [
                                'top' => 'integer',
                                'right' => 'integer',
                                'bottom' => 'integer',
                                'left' => 'integer',
                            ],
                            'units' => [
                                'px' => 'px',
                                'em' => 'em',
                                'rem' => 'rem',
                                'percent' => '%',
                            ],
                        ],
                    ],
                    [
                        'id' => 'toc-header-padding',
                        'title' => __('Padding', 'joli-table-of-contents'),
                        'type' => 'dimensions',
                        'args' => [
                            'desc' => __('Padding of the header.', 'joli-table-of-contents'),
                            // 'placeholder' => '10',
                            'dimensions_type' => 'padding',
                            'sub_dimensions' => ['top', 'right', 'bottom', 'left'],
                            'units' => [
                                'px' => 'px',
                                'em' => 'em',
                                'rem' => 'rem',
                                'percent' => '%',
                            ],
                        ],
                        // 'default' => [],
                        'sanitize' => 'dimensions',
                        'sanitize_args' => [
                            'dimensions' => [
                                'top' => 'integer',
                                'right' => 'integer',
                                'bottom' => 'integer',
                                'left' => 'integer',
                            ],
                            'units' => [
                                'px' => 'px',
                                'em' => 'em',
                                'rem' => 'rem',
                                'percent' => '%',
                            ],
                        ],
                    ],

                    [
                        'id' => 'toc-header-background-color',
                        'title' => __('Background color', 'joli-table-of-contents'),
                        'type' => 'text',
                        'args' => [
                            'desc' => __('Background color of the header.', 'joli-table-of-contents'),
                            'placeholder' => '#ffffff',
                            'classes' => 'joli-color-picker', //adds color picker
                            'data' => [
                                'alpha-enabled' => 'true',
                                'alpha-color-type' => 'hex',
                            ],
                        ],
                        // 'default' => '#39383a',
                        'sanitize' => 'color'
                    ],
                ],
            ],

            // Table of contents title
            [
                'name' => 'table-of-contents-title-styles',
                'title' => __('Table of contents title', 'joli-table-of-contents'),
                'desc' => JTOC()->render(['admin' => 'toc-view'], ['highlight' => 'title'], true),
                'fields' => [
                    [
                        'id' => 'toc-title-color',
                        'title' => __('Title color', 'joli-table-of-contents'),
                        'type' => 'text',
                        'args' => [
                            'desc' => __('Color of the "Table of contents" title.', 'joli-table-of-contents'),
                            'placeholder' => '#ffffff',
                            'classes' => 'joli-color-picker', //adds color picker
                            'data' => [
                                'alpha-enabled' => 'true',
                                'alpha-color-type' => 'hex',
                            ],
                        ],
                        // 'default' => '#39383a',
                        'sanitize' => 'color'
                    ],

                    [
                        'id' => 'toc-title-font-size',
                        'title' => __('Font size', 'joli-table-of-contents'),
                        'type' => 'unitinput',
                        'args' => [
                            'placeholder' => '1.25',
                            'desc' => __('Font size of the "Table of contents" title.', 'joli-table-of-contents'),
                            'values' => [
                                'percent' => '%',
                                'em' => 'em',
                                'px' => 'px',
                                'rem' => 'rem',
                            ],
                        ],
                        'sanitize' => 'unit',
                        // 'default' => '50|px',
                    ],

                    [
                        'id' => 'toc-title-font-weight',
                        'title' => __('Font weight', 'joli-table-of-contents'),
                        'type' => 'select',
                        'args' => [
                            // 'class' => 'tab-general',
                            'desc' => __('Font weight of the "Table of contents" title.', 'joli-table-of-contents'),
                            'values' => $font_weight_list,
                        ],
                        'default' => 'none',
                    ],

                    [
                        'id' => 'toc-title-font-style',
                        'title' => __('Font style', 'joli-table-of-contents'),
                        'type' => 'select',
                        'args' => [
                            // 'class' => 'tab-general',
                            'new' => true,
                            'desc' => __('Font style of the "Table of contents" title.', 'joli-table-of-contents'),
                            'values' => $font_style_list,
                        ],
                        'default' => 'none',
                    ],
                ],
            ],

            // Toggle
            [
                'name' => 'toggle-styles',
                'title' => __('Toggle button', 'joli-table-of-contents'),
                'desc' => JTOC()->render(['admin' => 'toc-view'], ['highlight' => 'toggle'], true),
                'fields' => [
                    [
                        'id' => 'toc-toggle-color',
                        'title' => __('Color', 'joli-table-of-contents'),
                        'type' => 'text',
                        'args' => [
                            'desc' => __('Color of the Toggle button or the toggle button text.', 'joli-table-of-contents'),
                            'placeholder' => '#ffffff',
                            'classes' => 'joli-color-picker', //adds color picker
                            'data' => [
                                'alpha-enabled' => 'true',
                                'alpha-color-type' => 'hex',
                            ],
                        ],
                        // 'default' => '#39383a',
                        'sanitize' => 'color'
                    ],
                ],
            ],

            // Table of contents body
            [
                'name' => 'table-of-contents-body-styles',
                'title' => __('Table of contents body', 'joli-table-of-contents'),
                // 'desc' => jtoc_tagify('p', __('The Table of contents body contains all the headings', 'joli-table-of-contents')),
                'desc' => JTOC()->render(['admin' => 'toc-view'], ['highlight' => 'body'], true),
                'fields' => [

                    [
                        'id' => 'toc-body-margin',
                        'title' => __('Margin', 'joli-table-of-contents'),
                        'type' => 'dimensions',
                        'args' => [
                            'desc' => __('Margin of the table of contents body.', 'joli-table-of-contents'),
                            // 'placeholder' => '10',margin
                            'dimensions_type' => 'padding',
                            'sub_dimensions' => ['top', 'right', 'bottom', 'left'],
                            'units' => [
                                'px' => 'px',
                                'em' => 'em',
                                'rem' => 'rem',
                                'percent' => '%',
                            ],
                        ],
                        // 'default' => [],
                        'sanitize' => 'dimensions',
                        'sanitize_args' => [
                            'dimensions' => [
                                'top' => 'integer',
                                'right' => 'integer',
                                'bottom' => 'integer',
                                'left' => 'integer',
                            ],
                            'units' => [
                                'px' => 'px',
                                'em' => 'em',
                                'rem' => 'rem',
                                'percent' => '%',
                            ],
                        ],
                    ],
                    [
                        'id' => 'toc-body-padding',
                        'title' => __('Padding', 'joli-table-of-contents'),
                        'type' => 'dimensions',
                        'args' => [
                            'desc' => __('Padding of the table of contents body.', 'joli-table-of-contents'),
                            // 'placeholder' => '10',
                            'dimensions_type' => 'padding',
                            'sub_dimensions' => ['top', 'right', 'bottom', 'left'],
                            'units' => [
                                'px' => 'px',
                                'em' => 'em',
                                'rem' => 'rem',
                                'percent' => '%',
                            ],
                        ],
                        // 'default' => [],
                        'sanitize' => 'dimensions',
                        'sanitize_args' => [
                            'dimensions' => [
                                'top' => 'integer',
                                'right' => 'integer',
                                'bottom' => 'integer',
                                'left' => 'integer',
                            ],
                            'units' => [
                                'px' => 'px',
                                'em' => 'em',
                                'rem' => 'rem',
                                'percent' => '%',
                            ],
                        ],
                    ],

                    [
                        'id' => 'toc-body-background-color',
                        'title' => __('Background color', 'joli-table-of-contents'),
                        'type' => 'text',
                        'args' => [
                            // 'pro' => true,
                            'desc' => __('Background color of the table of contents body.', 'joli-table-of-contents'),
                            'placeholder' => '#ffffff',
                            'classes' => 'joli-color-picker', //adds color picker
                            'data' => [
                                'alpha-enabled' => 'true',
                                'alpha-color-type' => 'hex',
                            ],
                        ],
                        // 'default' => '#39383a',
                        'sanitize' => 'color'
                    ],
                ],
            ],

            // Headings group
            // [
            //     'name' => 'headings-group-styles',
            //     'title' => __('Headings group', 'joli-table-of-contents'),
            //     // 'desc' => JTOC()->render(['admin' => 'toc-view'], ['highlight' => 'body'], true),
            //     'fields' => [
            //         [
            //             'id' => 'headings-group-background-color',
            //             'title' => __('Background color', 'joli-table-of-contents'),
            //             'type' => 'text',
            //             'args' => [
            //                 'placeholder' => '#ffffff',
            //                 'classes' => 'joli-color-picker', //adds color picker
            //                 'data' => [
            //                     'alpha-enabled' => 'true',
            //                     'alpha-color-type' => 'hex',
            //                 ],
            //                 // 'desc' => __('Headings background color on mouse hover', 'joli-table-of-contents'),
            //             ],
            //             // 'default' => '#c9c9c9',
            //             'sanitize' => 'color'
            //         ],

            //     ],
            // ],

            // Headings
            [
                'name' => 'headings-styles',
                'title' => __('Headings', 'joli-table-of-contents'),
                // 'desc' => __('<p>Headings contain the whole row</p>', 'joli-table-of-contents'),
                'desc' => JTOC()->render(['admin' => 'toc-view'], ['highlight' => 'headings'], true),
                'fields' => [

                    [
                        'id' => 'headings-margin',
                        'title' => __('Margin', 'joli-table-of-contents'),
                        'type' => 'dimensions',
                        'args' => [
                            'desc' => __('Margin of each individual heading (full row).', 'joli-table-of-contents'),
                            // 'placeholder' => '10',margin
                            'dimensions_type' => 'padding',
                            'sub_dimensions' => ['top', 'right', 'bottom', 'left'],
                            'units' => [
                                'px' => 'px',
                                'em' => 'em',
                                'rem' => 'rem',
                                'percent' => '%',
                            ],
                        ],
                        // 'default' => [],
                        'sanitize' => 'dimensions',
                        'sanitize_args' => [
                            'dimensions' => [
                                'top' => 'integer',
                                'right' => 'integer',
                                'bottom' => 'integer',
                                'left' => 'integer',
                            ],
                            'units' => [
                                'px' => 'px',
                                'em' => 'em',
                                'rem' => 'rem',
                                'percent' => '%',
                            ],
                        ],
                    ],
                    [
                        'id' => 'headings-padding',
                        'title' => __('Padding', 'joli-table-of-contents'),
                        'type' => 'dimensions',
                        'args' => [
                            'desc' => __('Padding of each individual heading (full row). Adjust top/bottom padding to increase heading height.', 'joli-table-of-contents'),
                            // 'placeholder' => '10',
                            'dimensions_type' => 'padding',
                            'sub_dimensions' => ['top', 'right', 'bottom', 'left'],
                            'units' => [
                                'px' => 'px',
                                'em' => 'em',
                                'rem' => 'rem',
                                'percent' => '%',
                            ],
                        ],
                        // 'default' => [],
                        'sanitize' => 'dimensions',
                        'sanitize_args' => [
                            'dimensions' => [
                                'top' => 'integer',
                                'right' => 'integer',
                                'bottom' => 'integer',
                                'left' => 'integer',
                            ],
                            'units' => [
                                'px' => 'px',
                                'em' => 'em',
                                'rem' => 'rem',
                                'percent' => '%',
                            ],
                        ],
                    ],

                    [
                        'id' => 'headings-border-radius',
                        'title' => __('Border radius', 'joli-table-of-contents'),
                        'type' => 'dimensions',
                        'args' => [
                            'desc' => __('Border radius of each individual heading (full row).', 'joli-table-of-contents'),
                            // 'placeholder' => '10',
                            'dimensions_type' => 'border',
                            'sub_dimensions' => ['top-left', 'top-right', 'bottom-right', 'bottom-left'],
                            'units' => [
                                'px' => 'px',
                                'em' => 'em',
                                'rem' => 'rem',
                                'percent' => '%',
                            ],
                        ],
                        // 'default' => [],
                        'sanitize' => 'dimensions',
                        'sanitize_args' => [
                            'dimensions' => [
                                'top-left' => 'integer',
                                'top-right' => 'integer',
                                'bottom-right' => 'integer',
                                'bottom-left' => 'integer',
                            ],
                            'units' => [
                                'px' => 'px',
                                'em' => 'em',
                                'rem' => 'rem',
                                'percent' => '%',
                            ],
                        ],
                    ],
                    // [
                    //     'id' => 'headings-font-size',
                    //     'title' => __('Font size', 'joli-table-of-contents'),
                    //     'type' => 'unitinput',
                    //     'args' => [
                    //         'placeholder' => '1.25',
                    //         'desc' => __('Font size of each individual heading. "1" is the default size from your theme. "0.5" for 50% of the default size; "1.2" for 120% of the default size.', 'joli-table-of-contents') . ' ' . $vars['dontaddem'],
                    //         'values' => [
                    //             'em' => 'em',
                    //             'px' => 'px',
                    //             'rem' => 'rem',
                    //         ],
                    //     ],
                    //     'sanitize' => 'unit',
                    //     // 'default' => '50|px',
                    // ],

                    // [
                    //     'id' => 'headings-height',
                    //     'title' => __('Height', 'joli-table-of-contents'),
                    //     'type' => 'text',
                    //     'args' => [
                    //         'desc' => __('Determines the height of each individual heading', 'joli-table-of-contents') . ' ' . $vars['dontaddpx'] . __('Leave blank to automatically adjust to the font.', 'joli-table-of-contents'),
                    //         'placeholder' => '30',
                    //     ],
                    //     // 'default' => '#39383a',
                    //     'sanitize' => 'Number'
                    // ],
                    // [
                    //     'id' => 'headings-color',
                    //     'title' => __('Color', 'joli-table-of-contents'),
                    //     'type' => 'text',
                    //     'args' => [
                    //         'placeholder' => '#ffffff',
                    //         'classes' => 'joli-color-picker', //adds color picker
                    //         'data' => [
                    //             'alpha-enabled' => 'true',
                    //             'alpha-color-type' => 'hex',
                    //         ],
                    //     ],
                    //     // 'default' => '#39383a',
                    //     'sanitize' => 'color'
                    // ],
                    // [
                    //     'id' => 'headings-color-hover',
                    //     'title' => __('Color (hover)', 'joli-table-of-contents'),
                    //     'type' => 'text',
                    //     'args' => [
                    //         'placeholder' => '#ffffff',
                    //         'classes' => 'joli-color-picker', //adds color picker
                    //         'data' => [
                    //             'alpha-enabled' => 'true',
                    //             'alpha-color-type' => 'hex',
                    //         ],
                    //     ],
                    //     //// 'default' => '#ffffff',
                    //     'sanitize' => 'color'
                    // ],
                    // [
                    //     'id' => 'headings-color-active',
                    //     'title' => __('Color (active)', 'joli-table-of-contents'),
                    //     'type' => 'text',
                    //     'args' => [
                    //         'placeholder' => '#ffffff',
                    //         'classes' => 'joli-color-picker', //adds color picker
                    //         'data' => [
                    //             'alpha-enabled' => 'true',
                    //             'alpha-color-type' => 'hex',
                    //         ],
                    //     ],
                    //     // 'default' => '#ffffff',
                    //     'sanitize' => 'color'
                    // ],
                    [
                        'id' => 'headings-line-height',
                        'title' => __('Line height', 'joli-table-of-contents'),
                        'type' => 'unitinput',
                        'args' => [
                            'desc' => __('Adjust this setting to achieve a more compact look.', 'joli-table-of-contents'),
                            // 'class' => 'tab-appearance'
                            'values' => [
                                'px' => 'px',
                                'em' => 'em',
                                'rem' => 'rem',
                            ],
                        ],
                        'sanitize' => 'unit',
                    ],
                    [
                        'id' => 'headings-background-color',
                        'title' => __('Background color', 'joli-table-of-contents'),
                        'type' => 'text',
                        'args' => [
                            'desc' => __('Background color of each individual heading (full row).', 'joli-table-of-contents'),
                            'placeholder' => '#ffffff',
                            'classes' => 'joli-color-picker', //adds color picker
                            'data' => [
                                'alpha-enabled' => 'true',
                                'alpha-color-type' => 'hex',
                            ],
                            // 'desc' => __('Headings background color on mouse hover', 'joli-table-of-contents'),
                        ],
                        // 'default' => '#c9c9c9',
                        'sanitize' => 'color'
                    ],
                    [
                        'id' => 'headings-background-color-hover',
                        'title' => __('Background color (hover)', 'joli-table-of-contents'),
                        'type' => 'text',
                        'args' => [
                            'desc' => __('Background color (hover) of each individual heading (full row).', 'joli-table-of-contents'),
                            'placeholder' => '#ffffff',
                            'classes' => 'joli-color-picker', //adds color picker
                            'data' => [
                                'alpha-enabled' => 'true',
                                'alpha-color-type' => 'hex',
                            ],
                            // 'desc' => __('Headings background color on mouse hover', 'joli-table-of-contents'),
                        ],
                        // 'default' => '#c9c9c9',
                        'sanitize' => 'color'
                    ],
                    [
                        'id' => 'headings-background-color-active',
                        'title' => __('Background color (active)', 'joli-table-of-contents'),
                        'type' => 'text',
                        'args' => [
                            'desc' => __('Background color (active) of each individual heading (full row).', 'joli-table-of-contents'),
                            'placeholder' => '#ffffff',
                            'classes' => 'joli-color-picker', //adds color picker
                            'data' => [
                                'alpha-enabled' => 'true',
                                'alpha-color-type' => 'hex',
                            ],
                        ],
                        // 'default' => '#39383a',
                        'sanitize' => 'color'
                    ],
                ],
            ],

            // Headings
            [
                'name' => 'headings-link-styles',
                'title' => __('Headings text link', 'joli-table-of-contents'),
                'desc' => JTOC()->render(['admin' => 'toc-view'], ['highlight' => 'links'], true),
                'fields' => [

                    [
                        'id' => 'headings-link-margin',
                        'title' => __('Margin', 'joli-table-of-contents'),
                        'type' => 'dimensions',
                        'args' => [
                            'desc' => __('Margin of each individual heading text link.', 'joli-table-of-contents'),
                            // 'placeholder' => '10',margin
                            'dimensions_type' => 'padding',
                            'sub_dimensions' => ['top', 'right', 'bottom', 'left'],
                            'units' => [
                                'px' => 'px',
                                'em' => 'em',
                                'rem' => 'rem',
                                'percent' => '%',
                            ],
                        ],
                        // 'default' => [],
                        'sanitize' => 'dimensions',
                        'sanitize_args' => [
                            'dimensions' => [
                                'top' => 'integer',
                                'right' => 'integer',
                                'bottom' => 'integer',
                                'left' => 'integer',
                            ],
                            'units' => [
                                'px' => 'px',
                                'em' => 'em',
                                'rem' => 'rem',
                                'percent' => '%',
                            ],
                        ],
                    ],
                    [
                        'id' => 'headings-link-padding',
                        'title' => __('Padding', 'joli-table-of-contents'),
                        'type' => 'dimensions',
                        'args' => [
                            'desc' => __('Padding of each individual heading text link.', 'joli-table-of-contents'),
                            // 'placeholder' => '10',
                            'dimensions_type' => 'padding',
                            'sub_dimensions' => ['top', 'right', 'bottom', 'left'],
                            'units' => [
                                'px' => 'px',
                                'em' => 'em',
                                'rem' => 'rem',
                                'percent' => '%',
                            ],
                        ],
                        // 'default' => [],
                        'sanitize' => 'dimensions',
                        'sanitize_args' => [
                            'dimensions' => [
                                'top' => 'integer',
                                'right' => 'integer',
                                'bottom' => 'integer',
                                'left' => 'integer',
                            ],
                            'units' => [
                                'px' => 'px',
                                'em' => 'em',
                                'rem' => 'rem',
                                'percent' => '%',
                            ],
                        ],
                    ],

                    [
                        'id' => 'headings-link-font-size',
                        'title' => __('Font size', 'joli-table-of-contents'),
                        'type' => 'unitinput',
                        'args' => [
                            'placeholder' => '90',
                            'desc' => __('Font size of each individual heading text link.', 'joli-table-of-contents'),
                            'values' => [
                                'percent' => '%',
                                'em' => 'em',
                                'px' => 'px',
                                'rem' => 'rem',
                            ],
                        ],
                        'sanitize' => 'unit',
                        // 'default' => '50|px',
                    ],

                    [
                        'id' => 'headings-link-font-weight',
                        'title' => __('Font weight', 'joli-table-of-contents'),
                        'type' => 'select',
                        'args' => [
                            // 'class' => 'tab-general',
                            'values' => $font_weight_list,
                        ],
                        'default' => 'none',
                    ],
                    // [
                    //     'id' => 'headings-link-height',
                    //     'title' => __('Height', 'joli-table-of-contents'),
                    //     'type' => 'text',
                    //     'args' => [
                    //         'desc' => __('Determines the height of each individual heading', 'joli-table-of-contents') . ' ' . $vars['dontaddpx'] . __('Leave blank to automatically adjust to the font.', 'joli-table-of-contents'),
                    //         'placeholder' => '30',
                    //     ],
                    //     // 'default' => '#39383a',
                    //     'sanitize' => 'Number'
                    // ],
                    [
                        'id' => 'headings-link-color',
                        'title' => __('Color', 'joli-table-of-contents'),
                        'type' => 'text',
                        'args' => [
                            'placeholder' => '#ffffff',
                            'classes' => 'joli-color-picker', //adds color picker
                            'data' => [
                                'alpha-enabled' => 'true',
                                'alpha-color-type' => 'hex',
                            ],
                        ],
                        // 'default' => '#39383a',
                        'sanitize' => 'color'
                    ],
                    [
                        'id' => 'headings-link-color-hover',
                        'title' => __('Color (hover)', 'joli-table-of-contents'),
                        'type' => 'text',
                        'args' => [
                            'placeholder' => '#ffffff',
                            'classes' => 'joli-color-picker', //adds color picker
                            'data' => [
                                'alpha-enabled' => 'true',
                                'alpha-color-type' => 'hex',
                            ],
                        ],
                        //// 'default' => '#ffffff',
                        'sanitize' => 'color'
                    ],
                    [
                        'id' => 'headings-link-color-active',
                        'title' => __('Color (active)', 'joli-table-of-contents'),
                        'type' => 'text',
                        'args' => [
                            'placeholder' => '#ffffff',
                            'classes' => 'joli-color-picker', //adds color picker
                            'data' => [
                                'alpha-enabled' => 'true',
                                'alpha-color-type' => 'hex',
                            ],
                        ],
                        // 'default' => '#ffffff',
                        'sanitize' => 'color'
                    ],
                    [
                        'id' => 'headings-link-background-color',
                        'title' => __('Background color', 'joli-table-of-contents'),
                        'type' => 'text',
                        'args' => [
                            'placeholder' => '#ffffff',
                            'classes' => 'joli-color-picker', //adds color picker
                            'data' => [
                                'alpha-enabled' => 'true',
                                'alpha-color-type' => 'hex',
                            ],
                            // 'desc' => __('Headings background color on mouse hover', 'joli-table-of-contents'),
                        ],
                        // 'default' => '#c9c9c9',
                        'sanitize' => 'color'
                    ],
                    [
                        'id' => 'headings-link-background-color-hover',
                        'title' => __('Background color (hover)', 'joli-table-of-contents'),
                        'type' => 'text',
                        'args' => [
                            'placeholder' => '#ffffff',
                            'classes' => 'joli-color-picker', //adds color picker
                            'data' => [
                                'alpha-enabled' => 'true',
                                'alpha-color-type' => 'hex',
                            ],
                            // 'desc' => __('Headings background color on mouse hover', 'joli-table-of-contents'),
                        ],
                        // 'default' => '#c9c9c9',
                        'sanitize' => 'color'
                    ],
                    [
                        'id' => 'headings-link-background-color-active',
                        'title' => __('Background color (active)', 'joli-table-of-contents'),
                        'type' => 'text',
                        'args' => [
                            'placeholder' => '#ffffff',
                            'classes' => 'joli-color-picker', //adds color picker
                            'data' => [
                                'alpha-enabled' => 'true',
                                'alpha-color-type' => 'hex',
                            ],
                        ],
                        // 'default' => '#39383a',
                        'sanitize' => 'color'
                    ],
                ],
            ],

            // Numeration
            [
                'name' => 'numeration-styles',
                'title' => __('Numeration', 'joli-table-of-contents'),
                'fields' => [
                    [
                        'id' => 'numeration-color',
                        'title' => __('Numeration color', 'joli-table-of-contents'),
                        'type' => 'text',
                        'args' => [
                            'placeholder' => '#ffffff',
                            'classes' => 'joli-color-picker', //adds color picker
                            'data' => [
                                'alpha-enabled' => 'true',
                                'alpha-color-type' => 'hex',
                            ],
                        ],
                        // 'default' => '#adadad',
                        'sanitize' => 'color'
                    ],
                    [
                        'id' => 'numeration-color-hover',
                        'title' => __('Numeration color (hover)', 'joli-table-of-contents'),
                        'type' => 'text',
                        'args' => [
                            'placeholder' => '#ffffff',
                            'classes' => 'joli-color-picker', //adds color picker
                            'data' => [
                                'alpha-enabled' => 'true',
                                'alpha-color-type' => 'hex',
                            ],
                        ],
                        //// 'default' => '#ffffff',
                        'sanitize' => 'color'
                    ],
                    [
                        'id' => 'numeration-color-active',
                        'title' => __('Numeration color (active)', 'joli-table-of-contents'),
                        'type' => 'text',
                        'args' => [
                            'placeholder' => '#ffffff',
                            'classes' => 'joli-color-picker', //adds color picker
                            'data' => [
                                'alpha-enabled' => 'true',
                                'alpha-color-type' => 'hex',
                            ],
                        ],
                        //// 'default' => '#ffffff',
                        'sanitize' => 'color'
                    ],
                ],
            ],
            [
                'name' => 'columns-style',
                'title' => __('Columns', 'joli-table-of-contents'),
                // 'desc' => __('<p class="joli-section-desc">Set custom colors to overrides defaults</p>', 'joli-table-of-contents'),
                'fields' => [
                    [
                        'id' => 'columns-separator-style',
                        'title' => __('Separator style', 'joli-table-of-contents'),
                        'type' => 'select',
                        'args' => [
                            'pro' => true,
                            'desc' => __('Defines the separator style between columns', 'joli-table-of-contents'),
                            'values' => [
                                'solid' => __('Solid [Default]', 'joli-table-of-contents'),
                                'dashed' => __('Dashed', 'joli-table-of-contents'),
                                'dotted' => __('Dotted', 'joli-table-of-contents'),
                                'double' => __('Double', 'joli-table-of-contents'),
                                'ridge' => __('Ridge', 'joli-table-of-contents'),
                                'none' => __('None', 'joli-table-of-contents'),
                            ],
                        ],
                    ],

                    [

                        'id' => 'columns-separator-width',
                        'title' => __('Separator width', 'joli-table-of-contents'),
                        'type' => 'unitinput',
                        'args' => [
                            'pro' => true,
                            'placeholder' => '1',
                            'desc' => __('Width of the separator', 'joli-table-of-contents'),
                            'values' => [
                                'px' => 'px',
                            ],
                        ],
                        'default' => '1|px',
                        'sanitize' => 'unit',
                    ],
                    [
                        'id' => 'columns-separator-color',
                        'title' => __('Separator color', 'joli-table-of-contents'),
                        'type' => 'text',
                        'args' => [
                            'pro' => false,
                            'placeholder' => '#ffffff',
                            'classes' => 'joli-color-picker', //adds color picker
                            'data' => [
                                'alpha-enabled' => 'true',
                                'alpha-color-type' => 'hex',
                            ],
                        ],
                        // 'default' => '#39383a',
                        'sanitize' => 'Color'
                    ],
                ],
            ],
            // Columns
            // [
            //     'name' => 'columns-styles',
            //     'title' => __('Columns', 'joli-table-of-contents'),
            //     'fields' => [],
            // ],

            [
                'name' => 'custom-css',
                'title' => __('Custom CSS', 'joli-table-of-contents'),
                'fields' => [
                    [
                        'id' => 'css-code',
                        'title' => __('CSS code', 'joli-table-of-contents'),
                        'type' => 'textarea',
                        'args' => [
                            'placeholder' => '.wpj-jtoc--toc{ background: #ffffff; }',
                            'desc' => __('Write your own CSS to override settings or customize to your liking.', 'joli-table-of-contents'),
                            'classes' => 'large-text',
                            // 'custom' => sprintf('<a href="%sadmin.php?page=joli_toc_user_guide#custom-css">', get_admin_url()) . __('What can I customize ?', 'joli-table-of-contents') . '</a>',
                        ],
                        'sanitize' => 'Textarea',
                        'has_block_attr' => false,
                    ],
                ],
            ],
        ],
    ],
    // END GROUP: STYLES ********************************************************

    // GROUP: FLOATING TABLE OF CONTENTS ********************************************************
    [
        'group' => 'floating-table-of-contents',
        'label' => __('Floating table of contents', 'joli-table-of-contents'),
        'sections' => [
            // Floating table of contents settings ----------
            [
                'name' => 'floating-table-of-contents',
                'title' => __('Floating table of contents', 'joli-table-of-contents'),
                'fields' => [

                    [
                        'id' => 'activate-floating-table-of-contents',
                        'title' => __('Activate floating table of contents', 'joli-table-of-contents'),
                        'type' => 'switch',
                        'args' => [
                            'pro' => true,
                            'desc' => __('Activates the floating TOC widget', 'joli-table-of-contents'),
                            'children_sections' => [
                                'floating-widget-settings',
                                'floating-table-of-contents-settings',
                                'floating-table-of-contents-position',
                                'floating-widget-styles',
                            ],
                            'children' => [
                                'floating-compatibility-mode',
                            ],
                        ],
                        'default' => 0,
                        'sanitize' => 'checkbox',
                    ],
                    [
                        'id' => 'floating-compatibility-mode',
                        'title' => __('Compatibility mode', 'joli-table-of-contents'),
                        'type' => 'switch',
                        'args' => [
                            // 'new' => true,
                            'pro' => true,
                            'desc' => __('Use this mode if you plan to use the table of contents in a page builder such as Divi or if you notice any visual issue with the widget. Since some page builder nest content in several divs, this can in some cases prevent the floating widget from showing properly. When this setting is active, the widget will be moved up to the root div of the article if possible. If the floating TOC works normally, keep this setting off.', 'joli-table-of-contents'),
                        ],
                        'default' => 0,
                        'sanitize' => 'checkbox',
                    ],
                ],
            ],
            [
                'name' => 'floating-widget-settings',
                'title' => __('Floating widget settings', 'joli-table-of-contents'),
                'desc' => jtoc_tagify('p', __('The floating widget is showing only the active heading and remains on top of the content in a fixed position.', 'joli-table-of-contents')),
                'fields' => [
                    [
                        'id' => 'floating-widget-height',
                        'title' => __('Height', 'joli-table-of-contents'),
                        'type' => 'unitinput',
                        'args' => [
                            // 'new' => true,
                            'pro' => true,
                            'desc' => __('Floating widget height.', 'joli-table-of-contents'),
                            'values' => [
                                'px' => 'px',
                                'em' => 'em',
                                'rem' => 'rem',
                            ],
                        ],
                        'sanitize' => 'unit',
                        'default' => '32|px',
                    ],
                    [
                        'id' => 'floating-hide-numeration',
                        'title' => __('Hide numeration', 'joli-table-of-contents'),
                        'type' => 'switch',
                        'args' => [
                            // 'new' => true,
                            'pro' => true,
                            'desc' => __('Hides the numeration from the current heading in the floating widget', 'joli-table-of-contents'),
                        ],
                        'default' => 0,
                        'sanitize' => 'checkbox',
                    ],

                    [
                        'id' => 'floating-nav-buttons',
                        'title' => __('Navigation buttons', 'joli-table-of-contents'),
                        'type' => 'select',
                        'args' => [
                            // 'new' => true,
                            'pro' => true,
                            'desc' => __('Shows navigation buttons next to the active heading', 'joli-table-of-contents'),
                            'values' => [
                                'none' => __('None', 'joli-table-of-contents'),
                                'next' => __('Next', 'joli-table-of-contents'),
                                'prev_next' => __('Prev/Next', 'joli-table-of-contents'),
                            ],
                        ],
                        'default' => 'none',
                    ],

                    [
                        'id' => 'floating-nav-buttons-position',
                        'title' => __('Navigation buttons position', 'joli-table-of-contents'),
                        'type' => 'select',
                        'args' => [
                            // 'new' => true,
                            'pro' => true,
                            'desc' => __('Navigation buttons position relative to the active heading', 'joli-table-of-contents'),
                            'values' => [
                                'left' => __('Left', 'joli-table-of-contents'),
                                'right' => __('Right', 'joli-table-of-contents'),
                                'around' => __('Around', 'joli-table-of-contents'),
                            ],
                        ],
                        'default' => 'none',
                    ],

                    [
                        'id' => 'floating-nav-buttons-width',
                        'title' => __('Navigation buttons width', 'joli-table-of-contents'),
                        'type' => 'unitinput',
                        'args' => [
                            // 'new' => true,
                            'pro' => true,
                            // 'desc' => __('Offset on the X axis from the edge of the container.', 'joli-table-of-contents'),
                            'values' => [
                                'px' => 'px',
                                'em' => 'em',
                                'rem' => 'rem',
                            ],
                        ],
                        'sanitize' => 'unit',
                        'default' => '32|px',
                    ],
                ],
            ],
            [
                'name' => 'floating-table-of-contents-settings',
                'title' => __('Floating table of contents settings', 'joli-table-of-contents'),
                'desc' => jtoc_tagify('p', __('The floating table of contents is showing after the user hovered/clicked the floating widget. It will show the whole table of contents over the content.', 'joli-table-of-contents')),
                'fields' => [

                    [
                        'id' => 'floating-show-header',
                        'title' => __('Show header', 'joli-table-of-contents'),
                        'type' => 'switch',
                        'args' => [
                            'pro' => true,
                            'desc' => __('Shows the header and the TOC title from the floating TOC', 'joli-table-of-contents'),
                        ],
                        'default' => 0,
                        'sanitize' => 'checkbox',
                    ],

                    [
                        'id' => 'expands-on',
                        'title' => __('Expands on (when folded)', 'joli-table-of-contents'),
                        'type' => 'select',
                        'args' => [
                            'pro' => true,
                            'desc' => __('Event that will expand the Table of contents. (hover event does not apply to mobile)', 'joli-table-of-contents'),
                            'values' => [
                                'hover' => __('Hover (only for desktop)', 'joli-table-of-contents'),
                                'click' => __('Click', 'joli-table-of-contents'),
                            ],
                        ],
                        'default' => 'hover',
                    ],

                    [
                        'id' => 'collapses-on',
                        'title' => __('Collapses on (when unfolded)', 'joli-table-of-contents'),
                        'type' => 'select',
                        'args' => [
                            'pro' => true,
                            'desc' => __('Event that will collapse the Table of contents. (hover event does not apply to mobile)', 'joli-table-of-contents'),
                            'values' => [
                                'hover-off' => __('Leave hover (only for desktop)', 'joli-table-of-contents'),
                                'click-away' => __('Click away', 'joli-table-of-contents'),
                            ],
                        ],
                        'default' => 'hover-off',
                    ],

                    [
                        'id' => 'floating-position',
                        'title' => __('Floating position', 'joli-table-of-contents'),
                        'type' => 'select',
                        'args' => [
                            'pro' => true,
                            'desc' => __('Position of the fixed floating menu relative to the screen.', 'joli-table-of-contents'),
                            'values' => [
                                'top' => __('Top', 'joli-table-of-contents'),
                                'bottom' => __('Bottom', 'joli-table-of-contents'),
                            ],
                        ],
                        'default' => '10',
                    ],

                ],
            ],

            // Floating table of contents behaviour ----------
            [
                'name' => 'floating-table-of-contents-position',
                'title' => __('Floating table of contents position', 'joli-table-of-contents'),
                'fields' => [

                    [
                        'id' => 'floating-offset-y',
                        'title' => __('Floating vertical offset (in pixels)', 'joli-table-of-contents'),
                        'type' => 'unitinput',
                        'args' => [
                            'pro' => true,
                            'desc' => __('Offset on the Y axis from the edge of the viewport (from top or bottom depending on the Floating position).', 'joli-table-of-contents'),
                            'values' => [
                                'px' => 'px',
                                'em' => 'em',
                                'rem' => 'rem',
                            ],
                        ],
                        'sanitize' => 'unit',
                        'default' => '10|px',
                    ],

                    [
                        'id' => 'floating-offset-y-mobile',
                        'title' => __('Floating vertical offset for mobile (in pixels)', 'joli-table-of-contents'),
                        'type' => 'unitinput',
                        'args' => [
                            'pro' => true,
                            'desc' => __('If not set, the value will be the same as for Desktop', 'joli-table-of-contents'),
                            'values' => [
                                'px' => 'px',
                                'em' => 'em',
                                'rem' => 'rem',
                            ],
                        ],
                        'sanitize' => 'unit',
                        // 'default' => '10|px',
                    ],

                    [
                        'id' => 'floating-offset-x',
                        'title' => __('Floating horizontal offset (in pixels)', 'joli-table-of-contents'),
                        'type' => 'unitinput',
                        'args' => [
                            'pro' => true,
                            'desc' => __('Offset on the X axis from the edge of the container.', 'joli-table-of-contents'),
                            'values' => [
                                'px' => 'px',
                                'em' => 'em',
                                'rem' => 'rem',
                            ],
                        ],
                        'sanitize' => 'unit',
                        // 'default' => '0',
                    ],
                ],
            ],

            // Floating table of contents behaviour ----------
            [
                'name' => 'floating-widget-styles',
                'title' => __('Floating widget styles', 'joli-table-of-contents'),
                'fields' => [

                    [
                        'id' => 'floating-widget-background-color',
                        'title' => __('Background color', 'joli-table-of-contents'),
                        'type' => 'text',
                        'args' => [
                            'pro' => true,
                            'placeholder' => '#ffffff',
                            'classes' => 'joli-color-picker', //adds color picker
                            'data' => [
                                'alpha-enabled' => 'true',
                                'alpha-color-type' => 'hex',
                            ],
                        ],
                        // 'default' => '#39383a',
                        'sanitize' => 'color'
                    ],

                    [
                        'id' => 'floating-widget-current-heading-padding',
                        'title' => __('Current heading padding', 'joli-table-of-contents'),
                        'type' => 'dimensions',
                        'args' => [
                            'pro' => true,
                            // 'desc' => __('Leave blank for default.', 'joli-table-of-contents') . ' ' . $vars['dontaddpx'],
                            // 'placeholder' => '10',
                            'dimensions_type' => 'padding',
                            'sub_dimensions' => ['top', 'right', 'bottom', 'left'],
                            'units' => [
                                'px' => 'px',
                                'em' => 'em',
                                'rem' => 'rem',
                                'percent' => '%',
                            ],
                        ],
                        'default' => [
                            'dim' => [
                                'top' => '0',
                                'right' => '10',
                                'bottom' => '0',
                                'left' => '10',
                            ],
                            'unit' => 'px',
                        ],
                        'sanitize' => 'dimensions',
                        'sanitize_args' => [
                            'dimensions' => [
                                'top' => 'integer',
                                'right' => 'integer',
                                'bottom' => 'integer',
                                'left' => 'integer',
                            ],
                            'units' => [
                                'px' => 'px',
                                'em' => 'em',
                                'rem' => 'rem',
                                'percent' => '%',
                            ],
                        ],
                    ],

                    [
                        'id' => 'floating-widget-color',
                        'title' => __('Current heading color', 'joli-table-of-contents'),
                        'type' => 'text',
                        'args' => [
                            'pro' => true,
                            'placeholder' => '#ffffff',
                            'classes' => 'joli-color-picker', //adds color picker
                            'data' => [
                                'alpha-enabled' => 'true',
                                'alpha-color-type' => 'hex',
                            ],
                        ],
                        // 'default' => '#39383a',
                        'sanitize' => 'color'
                    ],

                    [
                        'id' => 'floating-widget-font-size',
                        'title' => __('Current heading font size', 'joli-table-of-contents'),
                        'type' => 'unitinput',
                        'args' => [
                            'pro' => true,
                            'placeholder' => '1.25',
                            // 'desc' => __('Font size of the "Table of contents" title.', 'joli-table-of-contents') . ' ' . $vars['dontaddem'],
                            'values' => [
                                'px' => 'px',
                                'em' => 'em',
                                'rem' => 'rem',
                                'percent' => '%',
                            ],
                        ],
                        'sanitize' => 'unit',
                        // 'default' => '50|px',
                    ],

                    [
                        'id' => 'floating-widget-font-weight',
                        'title' => __('Current heading font weight', 'joli-table-of-contents'),
                        'type' => 'select',
                        'args' => [
                            'pro' => true,
                            // 'class' => 'tab-general',
                            'values' => $font_weight_list,
                        ],
                        'default' => 'none',
                    ],
                    [
                        'id' => 'floating-widget-nav-color',
                        'title' => __('Navigation buttons color', 'joli-table-of-contents'),
                        'type' => 'text',
                        'args' => [
                            'pro' => true,
                            'placeholder' => '#ffffff',
                            'classes' => 'joli-color-picker', //adds color picker
                            'data' => [
                                'alpha-enabled' => 'true',
                                'alpha-color-type' => 'hex',
                            ],
                        ],
                        // 'default' => '#39383a',
                        'sanitize' => 'color'
                    ],
                    [
                        'id' => 'floating-toc-shadow',
                        'title' => __('Shadow', 'joli-table-of-contents'),
                        'type' => 'switch',
                        'args' => [
                            'pro' => true,
                            'desc' => __('Displays a shadow around the floating widget & table of contents', 'joli-table-of-contents'),
                            // 'classes' => 'joli-color-picker',//adds color picker
                        ],
                        'default' => true,
                        'sanitize' => 'checkbox'
                    ],
                    [
                        'id' => 'floating-toc-shadow-color',
                        'title' => __('Custom shadow color', 'joli-table-of-contents'),
                        'type' => 'text',
                        'args' => [
                            'pro' => true,
                            'placeholder' => '#c2c2c2',
                            'classes' => 'joli-color-picker', //adds color picker
                            'data' => [
                                'alpha-enabled' => 'true',
                                'alpha-color-type' => 'hex',
                            ],
                        ],
                        // 'default' => '#c2c2c2',
                        'sanitize' => 'color'
                    ],
                    [
                        'id' => 'floating-widget-border-radius',
                        'title' => __('Border radius', 'joli-table-of-contents'),
                        'type' => 'dimensions',
                        'args' => [
                            'pro' => true,
                            // 'desc' => __('Leave blank for default.', 'joli-table-of-contents') . ' ' . $vars['dontaddpx'],
                            // 'placeholder' => '10',
                            'dimensions_type' => 'border',
                            'sub_dimensions' => ['top-left', 'top-right', 'bottom-right', 'bottom-left'],
                            'units' => [
                                'px' => 'px',
                                'em' => 'em',
                                'rem' => 'rem',
                                'percent' => '%',
                            ],
                        ],
                        'default' => [
                            'dim' => [
                                'top-left' => '8',
                                'top-right' => '8',
                                'bottom-right' => '8',
                                'bottom-left' => '8',
                            ],
                            'unit' => 'px',
                        ],
                        'sanitize' => 'dimensions',
                        'sanitize_args' => [
                            'dimensions' => [
                                'top-left' => 'integer',
                                'top-right' => 'integer',
                                'bottom-right' => 'integer',
                                'bottom-left' => 'integer',
                            ],
                            'units' => [
                                'px' => 'px',
                                'em' => 'em',
                                'rem' => 'rem',
                                'percent' => '%',
                            ],
                        ],
                    ],
                ],
            ],

        ],
    ],
    // END GROUP: SLIDE-OUT TABLE OF CONTENTS ********************************************************

    // GROUP: SLIDE-OUT TABLE OF CONTENTS ********************************************************
    [
        'group' => 'slide-out-table-of-contents',
        'label' => __('Slide-out table of contents', 'joli-table-of-contents'),
        'sections' => [
            // SLIDE-out table of contents behaviour ----------
            [
                'name' => 'slide-out-table-of-contents',
                'title' => __('Slide-out table of contents', 'joli-table-of-contents'),
                'fields' => [

                    [
                        'id' => 'activate-slide-out-table-of-contents',
                        'title' => __('Activate slide-out table of contents', 'joli-table-of-contents'),
                        'type' => 'switch',
                        'args' => [
                            //'new' => true,
                            'pro' => true,
                            'desc' => __('Activates the slide-out table of contents', 'joli-table-of-contents'),
                            'children_sections' => [
                                'slide-out-table-of-contents-settings',
                                'slide-out-toggle-button',
                                'slide-out-table-of-contents-styles',
                                'slide-out-toggle-button-styles',
                            ],
                        ],
                        'default' => 0,
                        'sanitize' => 'checkbox',
                    ],
                ],
            ],
            // SLIDE-out table of contents behaviour ----------
            [
                'name' => 'slide-out-table-of-contents-settings',
                'title' => __('Slide-out table of contents settings', 'joli-table-of-contents'),
                'fields' => [

                    [
                        'id' => 'slide-out-display',
                        'title' => __('Display', 'joli-table-of-contents'),
                        'type' => 'select',
                        'args' => [
                            'pro' => true,
                            'new' => true,
                            'desc' => __('Display mode. Shows the slide out on either Desktop or Mobile or Both.', 'joli-table-of-contents'),
                            'values' => [
                                'all' => __('Desktop & mobile', 'joli-table-of-contents'),
                                'desktop' => __('Desktop only', 'joli-table-of-contents'),
                                'mobile' => __('Mobile only', 'joli-table-of-contents'),
                            ],
                        ],
                        'default' => 'all',
                    ],

                    [
                        'id' => 'slide-out-auto-close-desktop',
                        'title' => __('Auto-close', 'joli-table-of-contents'),
                        'type' => 'switch',
                        'args' => [
                            'pro' => true,
                            'new' => true,
                            'desc' => __('Automatically close the slide out widget upon clicking a heading', 'joli-table-of-contents'),
                        ],
                        'default' => 1,
                        'sanitize' => 'checkbox',
                    ],

                    [
                        'id' => 'slide-out-auto-close',
                        'title' => __('Auto-close (mobile)', 'joli-table-of-contents'),
                        'type' => 'switch',
                        'args' => [
                            'pro' => true,
                            'new' => true,
                            'desc' => __('Automatically close the slide out widget upon clicking a heading', 'joli-table-of-contents'),
                        ],
                        'default' => 1,
                        'sanitize' => 'checkbox',
                    ],

                    [
                        'id' => 'slide-out-close-click-away',
                        'title' => __('Close on click away', 'joli-table-of-contents'),
                        'type' => 'switch',
                        'args' => [
                            'pro' => true,
                            'new' => true,
                            'desc' => __('Automatically close the slide out widget upon clicking anywhere on the page but the slide-out', 'joli-table-of-contents'),
                        ],
                        'default' => 0,
                        'sanitize' => 'checkbox',
                    ],

                    [
                        'id' => 'slide-out-open-on-load',
                        'title' => __('Open on load', 'joli-table-of-contents'),
                        'type' => 'switch',
                        'args' => [
                            'pro' => true,
                            'desc' => __('Have the slide-out TOC opened upon page load', 'joli-table-of-contents'),
                        ],
                        'default' => 0,
                        'sanitize' => 'checkbox',
                    ],

                    [
                        'id' => 'slide-out-hide-header',
                        'title' => __('Hide header', 'joli-table-of-contents'),
                        'type' => 'switch',
                        'args' => [
                            'pro' => true,
                            'desc' => __('Hides the header and the TOC title from the Slide-out TOC', 'joli-table-of-contents'),
                        ],
                        'default' => 0,
                        'sanitize' => 'checkbox',
                    ],

                    [
                        'id' => 'slide-out-width',
                        'title' => __('Width', 'joli-table-of-contents'),
                        'type' => 'unitinput',
                        'args' => [
                            'pro' => true,
                            'desc' => __('Width of the slide-out table of contents.', 'joli-table-of-contents'),
                            // 'class' => 'tab-appearance'
                            'values' => [
                                'px' => 'px',
                                'em' => 'em',
                                'rem' => 'rem',
                                'percent' => '%',
                            ],
                        ],
                        'default' => '300|px',
                        'sanitize' => 'unit',
                    ],
                    [
                        'id' => 'slide-out-mode',
                        'title' => __('Mode', 'joli-table-of-contents'),
                        'type' => 'select',
                        'args' => [
                            'pro' => true,
                            'desc' => __('Push content will push the content from the <body> tag to the right or the left.', 'joli-table-of-contents'),
                            'values' => [
                                'push' => __('Push content', 'joli-table-of-contents'),
                                'over' => __('Over content', 'joli-table-of-contents'),
                            ],
                        ],
                        'default' => 'push',
                    ],

                    [
                        'id' => 'slide-out-position',
                        'title' => __('Position', 'joli-table-of-contents'),
                        'type' => 'select',
                        'args' => [
                            'pro' => true,
                            'desc' => __('Position of the slide-out widget relative to the viewport', 'joli-table-of-contents'),
                            'values' => [
                                'left' => __('Left', 'joli-table-of-contents'),
                                'right' => __('Right', 'joli-table-of-contents'),
                            ],
                        ],
                        'default' => 'left',
                    ],
                ],
            ],

            // SLIDE-out table of contents behaviour ----------
            [
                'name' => 'slide-out-toggle-button',
                'title' => __('Slide-out toggle button', 'joli-table-of-contents'),
                'fields' => [

                    [
                        'id' => 'slide-out-toggle-position',
                        'title' => __('Position', 'joli-table-of-contents'),
                        'type' => 'select',
                        'args' => [
                            'pro' => true,
                            'desc' => __('Position of the slide-out toggle button.', 'joli-table-of-contents'),
                            'values' => [
                                'top' => __('Top', 'joli-table-of-contents'),
                                'center' => __('Center', 'joli-table-of-contents'),
                                'bottom' => __('Bottom', 'joli-table-of-contents'),
                            ],
                        ],
                        'default' => 'bottom',
                    ],

                    [
                        'id' => 'slide-out-toggle-width',
                        'title' => __('Width', 'joli-table-of-contents'),
                        'type' => 'unitinput',
                        'args' => [
                            // 'new' => true,
                            'pro' => true,
                            'desc' => __('Width of the slide-out toggle button.', 'joli-table-of-contents'),
                            // 'class' => 'tab-appearance'
                            'values' => [
                                'px' => 'px',
                                'em' => 'em',
                                'rem' => 'rem',
                                'percent' => '%',
                            ],
                        ],
                        'default' => '40|px',
                        'sanitize' => 'unit',
                    ],

                    [
                        'id' => 'slide-out-toggle-offset-y',
                        'title' => __('Vertical offset', 'joli-table-of-contents'),
                        'type' => 'unitinput',
                        'args' => [
                            'pro' => true,
                            'placeholder' => '50',
                            'desc' => __('Offset from the top/bottom of the viewport.', 'joli-table-of-contents'),
                            // 'classes' => 'joli-color-picker',//adds color picker
                            'values' => [
                                'px' => 'px',
                                'em' => 'em',
                                'rem' => 'rem',
                            ],
                        ],
                        'default' => '50|px',
                        'sanitize' => 'unit',
                    ],
                    [
                        'id' => 'slide-out-toggle-button-icon',
                        'title' => __('Icon', 'joli-table-of-contents'),
                        'type' => 'radioicon',
                        'default' => 'gg-layout-list',
                        'args' => [
                            'pro' => true,
                            // 'desc' => sprintf( '<span style="color:red;">%s</span>', __('Any changes in any styling below (title, headings, colors etc) will override theme defaults', 'joli-table-of-contents') ),
                            'values' => [
                                'gg-layout-list' => '<i class="gg-layout-list"></i>',
                                'gg-layout-grid-small' => '<i class="gg-layout-grid-small"></i>',
                                // 'gg-math-minus' => '<i class="gg-math-minus"></i>',
                                // 'gg-chevron-down' => '<i class="gg-chevron-down"></i>',
                                // 'gg-chevron-up' => '<i class="gg-chevron-up"></i>',
                                'gg-menu' => '<i class="gg-menu"></i>',
                                'gg-menu-left-alt' => '<i class="gg-menu-left-alt"></i>',
                                'gg-edit-highlight' => '<i class="gg-edit-highlight"></i>',
                                'gg-math-plus' => '<i class="gg-math-plus"></i>',
                                // 'gg-pentagon-down' => '<i class="gg-pentagon-down"></i>',
                                // 'gg-pentagon-up' => '<i class="gg-pentagon-up"></i>',
                                // 'gg-add-r' => '<i class="gg-add-r"></i>',
                                // 'gg-remove-r' => '<i class="gg-remove-r"></i>',
                                // 'gg-add' => '<i class="gg-add"></i>',
                                // 'gg-remove' => '<i class="gg-remove"></i>',
                                // 'gg-close' => '<i class="gg-close"></i>',
                                // 'gg-chevron-double-down' => '<i class="gg-chevron-double-down"></i>',
                                // 'gg-chevron-double-up' => '<i class="gg-chevron-double-up"></i>',
                                // 'gg-chevron-down-o' => '<i class="gg-chevron-down-o"></i>',
                                // 'gg-chevron-up-o' => '<i class="gg-chevron-up-o"></i>',
                            ],
                            'custom' => jtoc_tagify(
                                'p',
                                __('Check <a href="https://wpjoli.com/docs/joli-table-of-contents/developer-hooks/filters/jtoc_slide_out_toggle_html-pro/" target="_blank">this documentation</a> to find out how to use custom HTML for the toggle button', 'joli-table-of-contents'),
                                ['class' => 'description']
                            ),
                        ],
                    ],
                ],
            ],
            // SLIDE-out table of contents styles ----------
            [
                'name' => 'slide-out-table-of-contents-styles',
                'title' => __('Slide-out table of contents styles', 'joli-table-of-contents'),
                'fields' => [

                    [
                        'id' => 'slide-out-padding',
                        'title' => __('Padding', 'joli-table-of-contents'),
                        'type' => 'dimensions',
                        'args' => [
                            'pro' => true,
                            'desc' => __('Padding of the slide-out area that contains the table of contents.', 'joli-table-of-contents'),
                            // 'placeholder' => '10',
                            'dimensions_type' => 'padding',
                            'sub_dimensions' => ['top', 'right', 'bottom', 'left'],
                            'units' => [
                                'px' => 'px',
                                'em' => 'em',
                                'rem' => 'rem',
                                'percent' => '%',
                            ],
                        ],
                        'default' => [
                            'dim' => [
                                'top' => '20',
                                'right' => '20',
                                'bottom' => '20',
                                'left' => '20',
                            ],
                            'unit' => 'px',
                        ],
                        'sanitize' => 'dimensions',
                        'sanitize_args' => [
                            'dimensions' => [
                                'top' => 'integer',
                                'right' => 'integer',
                                'bottom' => 'integer',
                                'left' => 'integer',
                            ],
                            'units' => [
                                'px' => 'px',
                                'em' => 'em',
                                'rem' => 'rem',
                                'percent' => '%',
                            ],
                        ],
                    ],

                    [
                        'id' => 'slide-out-background-color',
                        'title' => __('Background color', 'joli-table-of-contents'),
                        'type' => 'text',
                        'args' => [
                            'pro' => true,
                            'placeholder' => '#ffffff',
                            'classes' => 'joli-color-picker', //adds color picker
                            'data' => [
                                'alpha-enabled' => 'true',
                                'alpha-color-type' => 'hex',
                            ],
                        ],
                        // 'default' => '#39383a',
                        'sanitize' => 'color'
                    ],
                ],
            ],

            // SLIDE-out table of contents styles ----------
            [
                'name' => 'slide-out-toggle-button-styles',
                'title' => __('Slide-out toggle button styles', 'joli-table-of-contents'),
                'fields' => [

                    [
                        'id' => 'slide-out-toggle-color',
                        'title' => __('Toggle color', 'joli-table-of-contents'),
                        'type' => 'text',
                        'args' => [
                            'pro' => true,
                            'desc' => __('Default is set to the links color.', 'joli-table-of-contents'),
                            'placeholder' => '#ffffff',
                            'classes' => 'joli-color-picker', //adds color picker
                            'data' => [
                                'alpha-enabled' => 'true',
                                'alpha-color-type' => 'hex',
                            ],
                        ],
                        // 'default' => '#39383a',
                        'sanitize' => 'color'
                    ],

                    [
                        'id' => 'slide-out-toggle-background-color',
                        'title' => __('Toggle background color', 'joli-table-of-contents'),
                        'type' => 'text',
                        'args' => [
                            'pro' => true,
                            'desc' => __('Default is set to the table of contents background color.', 'joli-table-of-contents'),
                            'placeholder' => '#ffffff',
                            'classes' => 'joli-color-picker', //adds color picker
                            'data' => [
                                'alpha-enabled' => 'true',
                                'alpha-color-type' => 'hex',
                            ],
                        ],
                        // 'default' => '#39383a',
                        'sanitize' => 'color'
                    ],
                ],
            ],
        ],
    ],
    // END GROUP: SLIDE-OUT TABLE OF CONTENTS ********************************************************

    // GROUP: PROGRESS BAR ********************************************************
    [
        'group' => 'progress-bar',
        'label' => __('Progress bar', 'joli-table-of-contents'),
        'sections' => [
            // Progress bar behaviour ----------
            [
                'name' => 'progress-bar',
                'title' => __('Progress bar', 'joli-table-of-contents'),
                'desc' => jtoc_tagify('p', __('The progress bar is a full width thin line that shows the percentage of advancement through the current article', 'joli-table-of-contents')),
                'fields' => [

                    [
                        'id' => 'activate-progress-bar',
                        'title' => __('Activate progress bar', 'joli-table-of-contents'),
                        'type' => 'switch',
                        'args' => [
                            // 'new' => true,
                            'pro' => true,
                            'desc' => __('Shows a fixed progress bar that indicates the progression through the actual article contents', 'joli-table-of-contents'),
                            'children_sections' => [
                                'progress-bar-settings',
                                'progress-bar-styles',
                            ],
                        ],
                        'default' => 0,
                        'sanitize' => 'checkbox',
                    ],
                ],
            ],
            // Progress bar behaviour ----------
            [
                'name' => 'progress-bar-settings',
                'title' => __('Progress bar settings', 'joli-table-of-contents'),
                'fields' => [

                    [
                        'id' => 'progress-bar-position',
                        'title' => __('Position', 'joli-table-of-contents'),
                        'type' => 'select',
                        'args' => [
                            'pro' => true,
                            'desc' => __('Position of the progress bar', 'joli-table-of-contents'),
                            'values' => [
                                'top' => __('Top', 'joli-table-of-contents'),
                                'bottom' => __('Bottom', 'joli-table-of-contents'),
                            ],
                        ],
                        'default' => 'none',
                    ],

                    [
                        'id' => 'progress-bar-offset-y',
                        'title' => __('Vertical offset', 'joli-table-of-contents'),
                        'type' => 'unitinput',
                        'args' => [
                            'pro' => true,
                            'placeholder' => '20',
                            'desc' => __('Offset from the top/bottom of the viewport.', 'joli-table-of-contents'),
                            // 'classes' => 'joli-color-picker',//adds color picker
                            'values' => [
                                'px' => 'px',
                                'em' => 'em',
                                'rem' => 'rem',
                            ],
                        ],
                        'default' => '0|px',
                        'sanitize' => 'unit',
                    ],

                    [
                        'id' => 'progress-bar-offset-y-mobile',
                        'title' => __('Vertical offset (mobile)', 'joli-table-of-contents'),
                        'type' => 'unitinput',
                        'args' => [
                            'pro' => true,
                            'placeholder' => '20',
                            'desc' => __('Offset from the top/bottom of the viewport.', 'joli-table-of-contents'),
                            // 'classes' => 'joli-color-picker',//adds color picker
                            'values' => [
                                'px' => 'px',
                                'em' => 'em',
                                'rem' => 'rem',
                            ],
                        ],
                        'default' => '0|px',
                        'sanitize' => 'unit',
                    ],
                ],
            ],
            // Progress bar styles ----------
            [
                'name' => 'progress-bar-styles',
                'title' => __('Progress bar styles', 'joli-table-of-contents'),
                'fields' => [

                    [
                        'id' => 'progress-bar-thickness',
                        'title' => __('Thickness', 'joli-table-of-contents'),
                        'type' => 'unitinput',
                        'args' => [
                            'pro' => true,
                            'placeholder' => '3',
                            'desc' => __('Thickness of the progress bar viewport.', 'joli-table-of-contents'),
                            // 'classes' => 'joli-color-picker',//adds color picker
                            'values' => [
                                'px' => 'px',
                                'em' => 'em',
                                'rem' => 'rem',
                            ],
                        ],
                        'default' => '4|px',
                        'sanitize' => 'unit',
                    ],

                    [
                        'id' => 'progress-bar-color',
                        'title' => __('Progress bar color', 'joli-table-of-contents'),
                        'type' => 'text',
                        'args' => [
                            'pro' => true,
                            'placeholder' => '#ffffff',
                            'classes' => 'joli-color-picker', //adds color picker
                            'data' => [
                                'alpha-enabled' => 'true',
                                'alpha-color-type' => 'hex',
                            ],
                        ],
                        // 'default' => '#39383a',
                        'sanitize' => 'color'
                    ],

                    [
                        'id' => 'progress-bar-background-color',
                        'title' => __('Progress bar background color', 'joli-table-of-contents'),
                        'type' => 'text',
                        'args' => [
                            'pro' => true,
                            'placeholder' => '#ffffff',
                            'classes' => 'joli-color-picker', //adds color picker
                            'data' => [
                                'alpha-enabled' => 'true',
                                'alpha-color-type' => 'hex',
                            ],
                        ],
                        // 'default' => '#ffffff00',
                        'sanitize' => 'color'
                    ],
                ],
            ],
        ],
    ],
    // END GROUP: PROGRESS BAR ********************************************************
];
