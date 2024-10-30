<?php

$vars = [
    'dontaddpx' => '<span style="color:orange;">' . __('Do not add "px".', 'joli-table-of-contents') . '</span>',
    'dontaddem' => '<span style="color:orange;">' . __('Do not add "em".', 'joli-table-of-contents') . '</span>',
];

return [
    [
        'group' => 'general',
        'label' => __('General', 'joli-table-of-contents'),
        'sections' => [
            //General TAB
            [
                'name' => 'general',
                'title' => __('General', 'joli-table-of-contents'),
                // 'desc' => 'Section description',
                'fields' => [
                    [
                        'id' => 'toc-title',
                        'title' => __('TOC title', 'joli-table-of-contents'),
                        'type' => 'text',
                        'args' => [
                            'desc' => __('Shows a title on top of the Table of contents. Will not show if empty', 'joli-table-of-contents'),
                            'placeholder' => __('Table of contents', 'joli-table-of-contents'),
                        ],
                        'default' => __('Table of contents', 'joli-table-of-contents'),
                    ],
                    [
                        'id' => 'title-depth',
                        'title' => __('Title depth', 'joli-table-of-contents'),
                        'type' => 'select',
                        'args' => [
                            // 'class' => 'tab-general',
                            // 'pro' => true,
                            'desc' => __('Maximum depth of title tags to be displayed.', 'joli-table-of-contents'),
                            'values' => [ //value =>display
                                '2' => 'H2',
                                '3' => 'H3',
                                '4' => 'H4',
                                '5' => 'H5',
                                '6' => 'H6',
                            ],
                            'values_pro' => [
                                '5',
                                '6',
                            ],
                        ],
                        'default' => '4',
                    ],
                    [
                        'id' => 'min-headings',
                        'title' => __('Minimum headings count', 'joli-table-of-contents'),
                        'type' => 'text',
                        'args' => [
                            'desc' => __('Table of contents will not be displayed if the number of headings of the current post is below this number', 'joli-table-of-contents'),
                            'placeholder' => '3',
                        ],
                        'default' => 3,
                        'sanitize' => 'Number',
                    ],
                    [
                        'id' => 'hierarchy-offset',
                        'title' => __('Hierarchy offset (in pixels per level)', 'joli-table-of-contents'),
                        'type' => 'text',
                        'args' => [
                            'placeholder' => '20',
                            'desc' => __('Empty space per level of title depth. Set to "0" to have all the titles vertically inline. Value is in pixels.', 'joli-table-of-contents') . ' ' . $vars['dontaddpx'],
                            // 'classes' => 'joli-color-picker',//adds color picker
                        ],
                        'default' => '20',
                        'sanitize' => 'Number'
                    ],
                    [
                        'id' => 'prefix',
                        'title' => __('Prefix', 'joli-table-of-contents'),
                        'type' => 'select',
                        'args' => [
                            // 'class' => 'tab-general',
                            'desc' => __('Prefix type that should be displayed before a title.', 'joli-table-of-contents'),
                            'values' => [ //value =>display
                                'none' => __('None', 'joli-table-of-contents'),
                                'numbers' => __('Numbers (1,2,3...)', 'joli-table-of-contents'),
                                'roman' => __('Roman numbers (I,V,X...)', 'joli-table-of-contents'),
                            ]
                        ],
                        'default' => 'none',
                    ],
                    [
                        'id' => 'prefix-separator',
                        'title' => __('Prefix numbers separator', 'joli-table-of-contents'),
                        'type' => 'text',
                        'args' => [
                            'pro' => true,
                            'desc' => __('Character that will separate numbers. Ex: "." => "1.1.2"; "-" => "1-1-2"', 'joli-table-of-contents'),
                            'placeholder' => '.',
                        ],
                        'default' => '.',
                        'sanitize' => 'text',
                    ],
                    [
                        'id' => 'prefix-suffix',
                        'title' => __('Prefix numbers suffix', 'joli-table-of-contents'),
                        'type' => 'text',
                        'args' => [
                            'pro' => true,
                            'desc' => __('Character that will be shown after the numbers. Ex: ")" => "1.1.2)"; "/" => "1.1.2/"', 'joli-table-of-contents'),
                            'placeholder' => '.',
                        ],
                        'default' => '.',
                        'sanitize' => 'text',
                    ],
                ],
            ],
            //HEadings processin TAB

            [
                'name' => 'headings-processing',
                'title' => __('Headings Processing', 'joli-table-of-contents'),
                // 'desc' => 'Section description',
                'fields' => [
                    [
                        'id' => 'skip-h-by-text',
                        'title' => __('Skip headings by text', 'joli-table-of-contents'),
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
                        'title' => __('Skip headings by class', 'joli-table-of-contents'),
                        'type' => 'text',
                        'args' => [
                            'placeholder' => 'my-class',
                            'desc' => __('Will ignore headings with the specified css classes. For multiple classes, seperate by a blank space. ex: my-class1 my-class2', 'joli-table-of-contents'),
                        ],
                        'sanitize' => 'text'
                    ],
                ],
            ],
            //HEadings processin TAB
            [
                'name' => 'headings-hash',
                'title' => __('Headings Hash', 'joli-table-of-contents'),
                // 'desc' => 'Section description',
                'fields' => [
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
                            'placeholder' => 'section_',
                        ],
                        'default' => 'section_',
                        'sanitize' => 'text',
                    ],
                ],
            ],
            [
                'name' => 'support-us',
                'title' => __('Support us', 'joli-table-of-contents'),
                'fields' => [
                    [
                        'id' => 'show-credits',
                        'title' => __('Show WPJoli credits', 'joli-table-of-contents'),
                        'type' => 'switch',
                        'args' => [
                            'desc' => __('This option will show your gratitude for our plugin by displaying a discreet "Powered by wpjoli" at the end of the Table of contents', 'joli-table-of-contents'),
                            'img' => 'powered-by-wpjoli.png',
                        ],
                        'default' => false,
                        'sanitize' => 'checkbox',
                    ],
                ],
            ],
        ],
    ],
    [
        'group' => 'auto-insert',
        'label' => __('Auto-insert', 'joli-table-of-contents'),
        'sections' => [
            [
                'name' => 'auto-insert',
                'title' => __('Auto-insert table of contents', 'joli-table-of-contents'),
                // 'desc' => 'Section description',
                'fields' => [
                    [
                        'id' => 'position-auto',
                        'title' => __('TOC Position', 'joli-table-of-contents'),
                        'type' => 'select',
                        'args' => [
                            'desc' => __('Where in the content the TOC should be automatically display', 'joli-table-of-contents'),
                            'values' => [ //value =>display
                                'before-content' => __('Before the content', 'joli-table-of-contents'),
                                'after-content' => __('After the content', 'joli-table-of-contents'),
                                'before-h1' => __('Before H1', 'joli-table-of-contents'),
                                'after-h1' => __('After H1', 'joli-table-of-contents'),
                                'before-h2-1' => __('Before first H2 tag', 'joli-table-of-contents'),
                                'after-p-1' => __('After first paragraph', 'joli-table-of-contents'),
                            ],
                            'default' => 'before-content',
                        ],
                    ],
                    [
                        'id' => 'post-types',
                        'title' => __('Post type', 'joli-table-of-contents'),
                        'type' => 'posttype',
                        'args' => [
                            'desc' => __('Auto insert TOC on specific post types', 'joli-table-of-contents'),
                            // 'placeholder' => 'Table of contents',
                        ],
                    ],
                ],
            ],
        ],
    ],
    [
        'group' => 'behaviour',
        'label' => __('Behaviour', 'joli-table-of-contents'),
        'sections' => [
            [
                'name' => 'behaviour',
                'title' => __('Behaviour', 'joli-table-of-contents'),
                'fields' => [
                    [
                        'id' => 'visibility',
                        'title' => __('Visibility', 'joli-table-of-contents'),
                        'type' => 'select',
                        'args' => [
                            'values' => [
                                'invisible' => __('Invisible, floating on scroll', 'joli-table-of-contents'),
                                'unfolded-incontent' => __('Unfolded, in-content', 'joli-table-of-contents'),
                                'unfolded-floating' => __('Unfolded, folded & floating on scroll', 'joli-table-of-contents'),
                                // 'unfolded-ufloating' => __('Unfolded, unfolded & floating on scroll', 'joli-table-of-contents'),
                                'folded-incontent' => __('Folded, in-content', 'joli-table-of-contents'),
                                'folded-floating' => __('Folded, folded & floating on scroll', 'joli-table-of-contents'),
                                'responsive-incontent' => __('Responsive*, in-content', 'joli-table-of-contents'),
                                'responsive-floating' => __('Responsive*, floating on scroll', 'joli-table-of-contents'),
                            ],
                            'values_pro' => [
                                'invisible',
                                'unfolded-floating',
                                'folded-floating',
                                'responsive-floating',
                            ],
                            // 'media' => [
                            //     'invisible' => 'invisible-floating.gif',
                            //     'unfolded-incontent' => 'unfolded-incontent.gif',
                            //     'unfolded-floating' => 'unfolded-floating.gif',
                            //     // 'unfolded-ufloating' => 'unfolded-ufloating.gif',
                            //     'folded-incontent' => 'folded-incontent.gif',
                            //     'folded-floating' => 'folded-floating.gif',
                            //     'responsive-incontent' => 'folded-incontent.gif',
                            //     'responsive-floating' => 'folded-floating.gif',
                            // ],
                        ],
                        'default' => 'unfolded-incontent',
                    ],
                    [
                        'id' => 'smooth-scrolling',
                        'title' => __('Smooth scrolling', 'joli-table-of-contents'),
                        'type' => 'switch',
                        'args' => [
                            'desc' => __('Enables smooth scrolling when clicking an element of the table of contents', 'joli-table-of-contents')
                            . '<br>' . sprintf('<span style="color:red;">%s</span>', __('Some themes have built-in smooth scrolling for links. This may interfere with Joli TOC\'s smooth scrolling if both are activated.', 'joli-table-of-contents')),
                            // 'class' => 'tab-general'
                        ],
                        'default' => 1,
                        'sanitize' => 'checkbox',
                    ],
                    [
                        'id' => 'jump-to-offset',
                        'title' => __('Jump-to offset (in pixels)', 'joli-table-of-contents'),
                        'type' => 'text',
                        'args' => [
                            'desc' => __('Offset between the top of the viewport and the clicked heading.', 'joli-table-of-contents')  . ' ' . $vars['dontaddpx'],
                        ],
                        'sanitize' => 'number',
                        'default' => 50,
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
                ],
            ],
            [
                'name' => 'incontent-behaviour',
                'title' => __('In-content behaviour', 'joli-table-of-contents'),
                'fields' => [
                    [
                        'id' => 'toggle-position',
                        'title' => __('Toggle position', 'joli-table-of-contents'),
                        'type' => 'select',
                        'args' => [
                            'desc' => __('Position of the expand/collapse toggle on the folded state. This option does not apply to the "unfolded" visibility.', 'joli-table-of-contents'),
                            'values' => [
                                'left' => __('Left', 'joli-table-of-contents'),
                                'right' => __('Right', 'joli-table-of-contents'),
                            ],
                        ],
                        'default' => 'left',
                    ],
                ],
            ],
            [
                'name' => 'floating-behaviour',
                'title' => __('Floating behaviour', 'joli-table-of-contents'),
                'fields' => [
                    [
                        'id' => 'floating-position',
                        'title' => __('Floating position', 'joli-table-of-contents'),
                        'type' => 'select',
                        'args' => [
                            'pro' => true,
                            'desc' => __('Position of the fixed floating menu relative to the screen. This option does not apply to the "in-content" visibility.', 'joli-table-of-contents'),
                            'values' => [
                                'top' => __('Top', 'joli-table-of-contents'),
                                'bottom' => __('Bottom', 'joli-table-of-contents'),
                            ],
                        ],
                        'default' => '10',
                    ],
                    [
                        'id' => 'floating-offset-y',
                        'title' => __('Floating vertical offset (in pixels)', 'joli-table-of-contents'),
                        'type' => 'text',
                        'args' => [
                            'pro' => true,
                            'desc' => __('Offset on the Y axis from the edge of the viewport (from top or bottom depending on the Floating position).', 'joli-table-of-contents')  . ' ' . $vars['dontaddpx'],
                        ],
                        'sanitize' => 'number',
                        'default' => 10,
                    ],
                    [
                        'id' => 'floating-offset-y-mobile',
                        'title' => __('Floating vertical offset for mobile (in pixels)', 'joli-table-of-contents'),
                        'type' => 'text',
                        'args' => [
                            'pro' => true,
                            'desc' => __('If not set, the value will be the same as for Desktop', 'joli-table-of-contents')  . ' ' . $vars['dontaddpx'],
                        ],
                        'sanitize' => 'number',
                        // 'default' => 10,
                    ],
                    [
                        'id' => 'floating-offset-x',
                        'title' => __('Floating horizontal offset (in pixels)', 'joli-table-of-contents'),
                        'type' => 'text',
                        'args' => [
                            'pro' => true,
                            'desc' => __('Offset on the X axis from the edge of the container.', 'joli-table-of-contents') . ' ' . $vars['dontaddpx'],
                        ],
                        'sanitize' => 'number',
                        'default' => 0,
                    ],
                    [
                        'id' => 'expands-on',
                        'title' => __('Expands on (when folded)', 'joli-table-of-contents'),
                        'type' => 'select',
                        'args' => [
                            'pro' => true,
                            'desc' => __('Event that will unfold the Table of content. (hover event does not apply to mobile)', 'joli-table-of-contents'),
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
                            'desc' => __('Event that will unfold the Table of content. (hover event does not apply to mobile)', 'joli-table-of-contents'),
                            'values' => [
                                'hover-off' => __('Leave hover (only for desktop)', 'joli-table-of-contents'),
                                'click-away' => __('Click away', 'joli-table-of-contents'),
                            ],
                        ],
                        'default' => 'hover-off',
                    ],
                    [
                        'id' => 'expanding-animation',
                        'title' => __('Enable expanding animation', 'joli-table-of-contents'),
                        'type' => 'switch',
                        'args' => [
                            'pro' => true,
                            'desc' => __('Enables a small transition animation when going from collapsed to expanded', 'joli-table-of-contents'),
                            // 'class' => 'tab-general'
                        ],
                        'default' => 0,
                        'sanitize' => 'checkbox',
                    ],
                ],
            ],
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
                        ],
                        'default' => 0,
                        'sanitize' => 'checkbox',
                    ],
                    [
                        'id' => 'columns-min-headings',
                        'title' => __('Minimal number of headings required', 'joli-table-of-contents'),
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
        ],
    ],
    // [
    //     'group' => 'headings',
    //     'label' => __('Headings', 'joli-table-of-contents'),
    //     'sections' => [
    //         //Appearance TAB
    //         [
    //             'name' => 'headings-options',
    //             'title' => __('Headings options', 'joli-table-of-contents'),
    //             'fields' => [
    //                 [
    //                     'id' => 'force-id-gen',
    //                     'title' => __('Force id generation for heading tags', 'joli-table-of-contents'),
    //                     'type' => 'checkbox',
    //                     'args' => [
    //                         'desc' => __('<span style="color:red;">Check this option if you intend to use the shortcode inside a sidebar widget</span>', 'joli-table-of-contents'),
    //                         // 'classes' => 'joli-color-picker',//adds color picker
    //                     ],
    //                     'sanitize' => 'checkbox'
    //                 ],
    //             ],
    //         ],
    //     ],
    // ],
    [
        // 'group' => Application::SLUG . '_settings',
        'group' => 'appearance',
        'label' => __('Appearance', 'joli-table-of-contents'),
        'sections' => [
            //Appearance TAB
            [
                'name' => 'themes',
                'title' => __('Themes', 'joli-table-of-contents'),
                'fields' => [
                    [
                        'id' => 'theme',
                        'title' => __('Theme', 'joli-table-of-contents'),
                        'type' => 'select',
                        'args' => [
                            'desc' => sprintf('<span style="color:red;">%s</span>', __('Any changes in any styling below (title, headings, colors etc) will override theme defaults', 'joli-table-of-contents')),
                            'values' => [
                                'default' => __('[Default]', 'joli-table-of-contents'),
                                'dark' => __('Dark', 'joli-table-of-contents'),
                                'classic' => __('Classic', 'joli-table-of-contents'),
                                'classic-dark' => __('Classic dark', 'joli-table-of-contents'),
                                'wikipedia' => __('Wikipedia', 'joli-table-of-contents'),
                            ],
                            'values_pro' => [
                                'dark',
                                'classic-dark',
                            ],
                        ],
                    ],
                ],
            ],
            //Buttons TAB
            [
                'name' => 'buttons',
                'title' => __('Buttons', 'joli-table-of-contents'),
                'fields' => [
                    [
                        'id' => 'expand-button-icon',
                        'title' => __('Expand button icon', 'joli-table-of-contents'),
                        'type' => 'radioicon',
                        'default' => 'gg-math-plus',
                        'args' => [
                            // 'desc' => sprintf( '<span style="color:red;">%s</span>', __('Any changes in any styling below (title, headings, colors etc) will override theme defaults', 'joli-table-of-contents') ),
                            'values' => [
                                'gg-math-plus' => '<i class="gg-math-plus"></i>',
                                'gg-math-minus' => '<i class="gg-math-minus"></i>',
                                'gg-chevron-down' => '<i class="gg-chevron-down"></i>',
                                'gg-chevron-up' => '<i class="gg-chevron-up"></i>',
                            ],
                        ],
                    ],
                    [
                        'id' => 'collapse-button-icon',
                        'title' => __('Collapse button icon', 'joli-table-of-contents'),
                        'type' => 'radioicon',
                        'default' => 'gg-math-minus',
                        'args' => [
                            // 'desc' => sprintf( '<span style="color:red;">%s</span>', __('Any changes in any styling below (title, headings, colors etc) will override theme defaults', 'joli-table-of-contents') ),
                            'values' => [
                                'gg-math-plus' => '<i class="gg-math-plus"></i>',
                                'gg-math-minus' => '<i class="gg-math-minus"></i>',
                                'gg-chevron-down' => '<i class="gg-chevron-down"></i>',
                                'gg-chevron-up' => '<i class="gg-chevron-up"></i>',
                            ],
                            'custom' => sprintf('<a href="%sadmin.php?page=joli_toc_user_guide#hooks">', get_admin_url()) . __('How to customize buttons with custom HTML ?', 'joli-table-of-contents') . '</a>',
                        ],
                    ],
                ],
            ],
            [
                'name' => 'column-style',
                'title' => __('Column style', 'joli-table-of-contents'),
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
                        'type' => 'text',
                        'args' => [
                            'pro' => true,
                            'desc' => __('Width of the separator (in pixels)', 'joli-table-of-contents') . ' ' . $vars['dontaddpx'],
                            'placeholder' => '1',
                        ],
                        'default' => 1,
                        'sanitize' => 'Number'
                    ],
                    [
                        'id' => 'columns-separator-color',
                        'title' => __('Separator color', 'joli-table-of-contents'),
                        'type' => 'text',
                        'args' => [
                            'pro' => true,
                            'placeholder' => '#ffffff',
                            'classes' => 'joli-color-picker', //adds color picker
                        ],
                        // 'default' => '#39383a',
                        'sanitize' => 'Color'
                    ],
                ],
            ],
            [
                'name' => 'table-of-contents',
                'title' => __('Table of contents', 'joli-table-of-contents'),
                'fields' => [
                    [
                        'id' => 'toc-background-color',
                        'title' => __('Table of contents background color', 'joli-table-of-contents'),
                        'type' => 'text',
                        'args' => [
                            // 'class' => 'tab-appearance',
                            'placeholder' => '#ffffff',
                            'classes' => 'joli-color-picker', //adds color picker
                        ],
                        // 'default' => '#ffffff',
                        'sanitize' => 'Color'
                    ],
                    [
                        'id' => 'toc-padding',
                        'title' => __('Table of contents padding', 'joli-table-of-contents'),
                        'type' => 'text',
                        'args' => [
                            'desc' => __('Value in pixels.', 'joli-table-of-contents') . ' ' . $vars['dontaddpx'],
                            'placeholder' => '10',
                        ],
                        'default' => 10,
                        'sanitize' => 'Number'
                    ],
                    [
                        'id' => 'min-width',
                        'title' => __('Minimum width', 'joli-table-of-contents'),
                        'type' => 'text',
                        'args' => [
                            'desc' => __('Value in pixels.', 'joli-table-of-contents') . ' ' . $vars['dontaddpx'],
                            // 'class' => 'tab-appearance'
                        ],
                        'sanitize' => 'number',
                    ],
                    [
                        'id' => 'max-width',
                        'title' => __('Maximum width', 'joli-table-of-contents'),
                        'type' => 'text',
                        'args' => [
                            'desc' => __('Value in pixels.', 'joli-table-of-contents') . ' ' . $vars['dontaddpx'],
                            // 'class' => 'tab-appearance'
                        ],
                        'sanitize' => 'number',
                    ],
                    [
                        'id' => 'width-incontent',
                        'title' => __('Width (in-content)', 'joli-table-of-contents'),
                        'type' => 'select',
                        'args' => [
                            'desc' => __('Make sure Maximum width is not set when using "100%" value', 'joli-table-of-contents'),
                            'values' => [
                                'width-auto' => __('Auto', 'joli-table-of-contents'),
                                'width-100' => '100%',
                            ],
                        ],
                        'default' => 'width-auto',
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
                        ],
                        // 'default' => '#c2c2c2',
                        'sanitize' => 'Color'
                    ],
                ],
            ],
            [
                'name' => 'title',
                'title' => __('Title', 'joli-table-of-contents'),
                // 'desc' => __('<p class="joli-section-desc">Set custom colors to overrides defaults</p>', 'joli-table-of-contents'),
                'fields' => [
                    [
                        'id' => 'title-alignment',
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
                        // 'default' => '#39383a',
                        'sanitize' => 'Text'
                    ],
                    [
                        'id' => 'title-color',
                        'title' => __('Title color', 'joli-table-of-contents'),
                        'type' => 'text',
                        'args' => [
                            'desc' => __('Color of the "Table of contents" title.', 'joli-table-of-contents'),
                            'placeholder' => '#ffffff',
                            'classes' => 'joli-color-picker', //adds color picker
                        ],
                        // 'default' => '#39383a',
                        'sanitize' => 'Color'
                    ],
                    [
                        'id' => 'title-font-size',
                        'title' => __('Title font size (in em)', 'joli-table-of-contents'),
                        'type' => 'text',
                        'args' => [
                            'desc' => __('Font size of the "Table of contents" title.', 'joli-table-of-contents') . ' ' . $vars['dontaddem'],
                            'placeholder' => '1.4',
                        ],
                        // 'default' => '#39383a',
                        'sanitize' => 'Float'
                    ],
                    [
                        'id' => 'title-font-weight',
                        'title' => __('Title font weight', 'joli-table-of-contents'),
                        'type' => 'select',
                        'args' => [
                            // 'class' => 'tab-general',
                            'values' => [ //value =>display
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
                            ]
                        ],
                        'default' => 'none',
                    ],
                ],
            ],
            [
                'name' => 'prefix',
                'title' => __('Prefix', 'joli-table-of-contents'),
                // 'desc' => __('<p class="joli-section-desc">Set custom colors to overrides defaults</p>', 'joli-table-of-contents'),
                'fields' => [
                    [
                        'id' => 'prefix-color',
                        'title' => __('Prefix color', 'joli-table-of-contents'),
                        'type' => 'text',
                        'args' => [
                            'placeholder' => '#ffffff',
                            'classes' => 'joli-color-picker', //adds color picker
                        ],
                        // 'default' => '#39383a',
                        'sanitize' => 'Color'
                    ],
                    [
                        'id' => 'prefix-hover-color',
                        'title' => __('Prefix color (hover)', 'joli-table-of-contents'),
                        'type' => 'text',
                        'args' => [
                            'placeholder' => '#ffffff',
                            'classes' => 'joli-color-picker', //adds color picker
                        ],
                        //// 'default' => '#ffffff',
                        'sanitize' => 'Color'
                    ],
                    [
                        'id' => 'prefix-active-color',
                        'title' => __('Prefix color (active)', 'joli-table-of-contents'),
                        'type' => 'text',
                        'args' => [
                            'placeholder' => '#ffffff',
                            'classes' => 'joli-color-picker', //adds color picker
                        ],
                        //// 'default' => '#ffffff',
                        'sanitize' => 'Color'
                    ],
                ],
            ],
            [
                'name' => 'headings',
                'title' => __('Headings', 'joli-table-of-contents'),
                // 'desc' => __('<p class="joli-section-desc">Set custom colors to overrides defaults</p>', 'joli-table-of-contents'),
                'fields' => [
                    [
                        'id' => 'headings-font-size',
                        'title' => __('Headings font size (in em)', 'joli-table-of-contents'),
                        'type' => 'text',
                        'args' => [
                            'desc' => __('Font size of each individual heading. "1" is the default size from your theme. "0.5" for 50% of the default size; "1.2" for 120% of the default size.', 'joli-table-of-contents') . ' ' . $vars['dontaddem'],
                            'placeholder' => '1.2',
                        ],
                        // 'default' => '#39383a',
                        'sanitize' => 'Float'
                    ],
                    [
                        'id' => 'headings-height',
                        'title' => __('Headings height', 'joli-table-of-contents'),
                        'type' => 'text',
                        'args' => [
                            'desc' => __('Determines the height of each individual heading', 'joli-table-of-contents') . ' ' . $vars['dontaddpx'] . __('Leave blank to automatically adjust to the font.', 'joli-table-of-contents'),
                            'placeholder' => '30',
                        ],
                        // 'default' => '#39383a',
                        'sanitize' => 'Number'
                    ],
                    [
                        'id' => 'headings-color',
                        'title' => __('Headings color', 'joli-table-of-contents'),
                        'type' => 'text',
                        'args' => [
                            'placeholder' => '#ffffff',
                            'classes' => 'joli-color-picker', //adds color picker
                        ],
                        // 'default' => '#39383a',
                        'sanitize' => 'Color'
                    ],
                    [
                        'id' => 'headings-hover-color',
                        'title' => __('Headings color (hover)', 'joli-table-of-contents'),
                        'type' => 'text',
                        'args' => [
                            'placeholder' => '#ffffff',
                            'classes' => 'joli-color-picker', //adds color picker
                        ],
                        //// 'default' => '#ffffff',
                        'sanitize' => 'Color'
                    ],
                    [
                        'id' => 'headings-active-color',
                        'title' => __('Headings color (active)', 'joli-table-of-contents'),
                        'type' => 'text',
                        'args' => [
                            'placeholder' => '#ffffff',
                            'classes' => 'joli-color-picker', //adds color picker
                        ],
                        // 'default' => '#ffffff',
                        'sanitize' => 'Color'
                    ],
                    [
                        'id' => 'headings-hover-background-color',
                        'title' => __('Headings background color (hover)', 'joli-table-of-contents'),
                        'type' => 'text',
                        'args' => [
                            'placeholder' => '#ffffff',
                            'classes' => 'joli-color-picker', //adds color picker
                            // 'desc' => __('Headings background color on mouse hover', 'joli-table-of-contents'),
                        ],
                        // 'default' => '#c9c9c9',
                        'sanitize' => 'Color'
                    ],
                    [
                        'id' => 'headings-active-background-color',
                        'title' => __('Headings background color (active)', 'joli-table-of-contents'),
                        'type' => 'text',
                        'args' => [
                            'placeholder' => '#ffffff',
                            'classes' => 'joli-color-picker', //adds color picker
                        ],
                        // 'default' => '#39383a',
                        'sanitize' => 'Color'
                    ],
                ],
            ],
            [
                'name' => 'custom-css',
                'title' => __('Custom CSS', 'joli-table-of-contents'),
                'fields' => [
                    [
                        'id' => 'css-code',
                        'title' => __('CSS code', 'joli-table-of-contents'),
                        'type' => 'textarea',
                        'args' => [
                            'placeholder' => '#joli-toc-wrapper{ background: #ffffff; }',
                            'desc' => __('Write your own CSS to override settings or customize to your liking.', 'joli-table-of-contents'),
                            'classes' => 'large-text',
                            'custom' => sprintf('<a href="%sadmin.php?page=joli_toc_user_guide#custom-css">', get_admin_url()) . __('What can I customize ?', 'joli-table-of-contents') . '</a>',
                        ],
                        'sanitize' => 'Textarea'
                    ],
                ],
            ],
        ],
    ],
];
