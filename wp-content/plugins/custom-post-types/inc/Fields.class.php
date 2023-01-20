<?php

namespace CPT;

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

class Fields
{
    public $available_fields;
    public $available_fields_options = [];

    public function __construct()
    {
        $this->set_available_fields();
        $this->set_available_fields_options();
        $this->add_repeater_field_type_options();

        return $this;
    }

    public function get_new_field_form()
    {
        return [
            [ //label
                'key' => 'label',
                'label' => __('Label', 'custom-post-types'),
                'info' => false,
                'required' => true,
                'type' => 'text',
                'extra' => [],
                'wrap' => [
                    'width' => '40',
                    'class' => '',
                    'id' => '',
                    'layout' => ''
                ]
            ],
            [ //key
                'key' => 'key',
                'label' => __('Key', 'custom-post-types'),
                'info' => false,
                'required' => true,
                'type' => 'text',
                'extra' => [],
                'wrap' => [
                    'width' => '40',
                    'class' => '',
                    'id' => '',
                    'layout' => ''
                ]
            ],
            [ //required
                'key' => 'required',
                'label' => __('Required', 'custom-post-types'),
                'info' => false,
                'required' => false,
                'type' => 'select',
                'extra' => [
                    'placeholder' => __('NO', 'custom-post-types') . ' - ' . __('Default', 'custom-post-types'),
                    'multiple' => false,
                    'options' => [
                        'true' => __('YES', 'custom-post-types'),
                        'false' => __('NO', 'custom-post-types') . ' - ' . __('Default', 'custom-post-types'),
                    ]
                ],
                'wrap' => [
                    'width' => '20',
                    'class' => '',
                    'id' => '',
                    'layout' => ''
                ]
            ],
            [ //type
                'key' => 'type',
                'label' => __('Type', 'custom-post-types'),
                'info' => false,
                'required' => true,
                'type' => 'select',
                'extra' => [
                    'multiple' => false,
                    'options' => [] /*cpt_get_available_fields_options()*/,
                ],
                'wrap' => [
                    'width' => '40',
                    'class' => 'field-type',
                    'id' => '',
                    'layout' => ''
                ]
            ],
            [ //info
                'key' => 'info',
                'label' => __('Info', 'custom-post-types'),
                'info' => false,
                'required' => false,
                'type' => 'text',
                'extra' => [],
                'wrap' => [
                    'width' => '60',
                    'class' => '',
                    'id' => '',
                    'layout' => ''
                ]
            ],
            [ //wrap_width
                'key' => 'wrap_width',
                'label' => __('Container width', 'custom-post-types'),
                'info' => false,
                'required' => false,
                'type' => 'number',
                'extra' => [],
                'wrap' => [
                    'width' => '25',
                    'class' => '',
                    'id' => '',
                    'layout' => ''
                ],
                'parent' => ''
            ],
            [ //wrap_layout
                'key' => 'wrap_layout',
                'label' => __('Container layout', 'custom-post-types'),
                'info' => false,
                'required' => false,
                'type' => 'select',
                'extra' => [
                    'placeholder' => __('VERTICAL', 'custom-post-types') . ' - ' . __('Default', 'custom-post-types'),
                    'multiple' => false,
                    'options' => [
                        'vertical' => __('VERTICAL', 'custom-post-types') . ' - ' . __('Default', 'custom-post-types'),
                        'horizontal' => __('HORIZONTAL', 'custom-post-types'),
                    ]
                ],
                'wrap' => [
                    'width' => '25',
                    'class' => '',
                    'id' => '',
                    'layout' => ''
                ]
            ],
            [ //wrap_class
                'key' => 'wrap_class',
                'label' => __('Container class', 'custom-post-types'),
                'info' => false,
                'required' => false,
                'type' => 'text',
                'extra' => [],
                'wrap' => [
                    'width' => '25',
                    'class' => '',
                    'id' => '',
                    'layout' => ''
                ]
            ],
            [ //wrap_id
                'key' => 'wrap_id',
                'label' => __('Container id', 'custom-post-types'),
                'info' => false,
                'required' => false,
                'type' => 'text',
                'extra' => [],
                'wrap' => [
                    'width' => '25',
                    'class' => '',
                    'id' => '',
                    'layout' => ''
                ]
            ],
            [ //extra-fields
                'key' => 'extra',
                'label' => '',
                'info' => false,
                'required' => false,
                'type' => 'extra',
                'extra' => [],
                'wrap' => [
                    'width' => '',
                    'class' => 'extra-fields',
                    'id' => '',
                    'layout' => ''
                ]
            ],
        ];
    }

    private function set_available_fields()
    {
        if (!empty($available_fields)) return;
        $available_fields = [
            'text' => [
                'label' => __('Text', 'custom-post-types'),
                'fields' => [
                    [ //placeholder
                        'key' => 'placeholder',
                        'label' => __('Placeholder', 'custom-post-types'),
                        'info' => false,
                        'required' => false,
                        'type' => 'text',
                        'extra' => [],
                        'wrap' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                            'layout' => ''
                        ]
                    ],
                ]
            ],
            'email' => [
                'label' => __('Email', 'custom-post-types'),
                'fields' => [
                    [ //placeholder
                        'key' => 'placeholder',
                        'label' => __('Placeholder', 'custom-post-types'),
                        'info' => false,
                        'required' => false,
                        'type' => 'text',
                        'extra' => [],
                        'wrap' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                            'layout' => ''
                        ]
                    ],
                ]
            ],
            'tel' => [
                'label' => __('Tel', 'custom-post-types'),
                'fields' => [
                    [ //placeholder
                        'key' => 'placeholder',
                        'label' => __('Placeholder', 'custom-post-types'),
                        'info' => false,
                        'required' => false,
                        'type' => 'text',
                        'extra' => [],
                        'wrap' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                            'layout' => ''
                        ]
                    ],
                ]
            ],
            'number' => [
                'label' => __('Number', 'custom-post-types'),
                'fields' => [
                    [ //placeholder
                        'key' => 'placeholder',
                        'label' => __('Placeholder', 'custom-post-types'),
                        'info' => false,
                        'required' => false,
                        'type' => 'text',
                        'extra' => [],
                        'wrap' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                            'layout' => ''
                        ]
                    ],
                ]
            ],
            'textarea' => [
                'label' => __('Textarea', 'custom-post-types'),
                'fields' => [
                    [ //placeholder
                        'key' => 'placeholder',
                        'label' => __('Placeholder', 'custom-post-types'),
                        'info' => false,
                        'required' => false,
                        'type' => 'text',
                        'extra' => [],
                        'wrap' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                            'layout' => ''
                        ]
                    ]
                ]
            ],
            'tinymce' => [
                'label' => __('WYSIWYG editor', 'custom-post-types'),
                'fields' => [
                    [ //multiple
                        'key' => 'autoresize',
                        'label' => __('Auto-resize', 'custom-post-types'),
                        'info' => false,
                        'required' => false,
                        'type' => 'select',
                        'extra' => [
                            'placeholder' => __('NO', 'custom-post-types') . ' - ' . __('Default', 'custom-post-types'),
                            'multiple' => false,
                            'options' => [
                                'true' => __('YES', 'custom-post-types'),
                                'false' => __('NO', 'custom-post-types') . ' - ' . __('Default', 'custom-post-types'),
                            ]
                        ],
                        'wrap' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                            'layout' => ''
                        ]
                    ],
                ]
            ],
            'select' => [
                'label' => __('Dropdown', 'custom-post-types'),
                'fields' => [
                    [ //multiple
                        'key' => 'multiple',
                        'label' => __('Multiple', 'custom-post-types'),
                        'info' => false,
                        'required' => false,
                        'type' => 'select',
                        'extra' => [
                            'placeholder' => __('NO', 'custom-post-types') . ' - ' . __('Default', 'custom-post-types'),
                            'multiple' => false,
                            'options' => [
                                'true' => __('YES', 'custom-post-types'),
                                'false' => __('NO', 'custom-post-types') . ' - ' . __('Default', 'custom-post-types'),
                            ]
                        ],
                        'wrap' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                            'layout' => ''
                        ]
                    ],
                    [ //options
                        'key' => 'options',
                        'label' => __('Options', 'custom-post-types'),
                        'info' => __('One per row (value|label).', 'custom-post-types'),
                        'required' => true,
                        'type' => 'textarea',
                        'extra' => [],
                        'wrap' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                            'layout' => ''
                        ]
                    ],
                ]
            ],
            'checkbox' => [
                'label' => __('Checkbox', 'custom-post-types'),
                'fields' => [
                    [ //options
                        'key' => 'options',
                        'label' => __('Options', 'custom-post-types'),
                        'info' => __('One per row (value|label).', 'custom-post-types'),
                        'required' => true,
                        'type' => 'textarea',
                        'extra' => [],
                        'wrap' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                            'layout' => ''
                        ]
                    ],
                ]
            ],
            'radio' => [
                'label' => __('Radio', 'custom-post-types'),
                'fields' => [
                    [ //options
                        'key' => 'options',
                        'label' => __('Options', 'custom-post-types'),
                        'info' => __('One per row (value|label).', 'custom-post-types'),
                        'required' => true,
                        'type' => 'textarea',
                        'extra' => [],
                        'wrap' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                            'layout' => ''
                        ]
                    ],
                ]
            ],
            'file' => [
                'label' => __('File upload', 'custom-post-types'),
                'fields' => [
                    [ //types
                        'key' => 'types',
                        'label' => __('Type', 'custom-post-types'),
                        'info' => false,
                        'required' => false,
                        'type' => 'select',
                        'extra' => [
                            'placeholder' => __('Image', 'custom-post-types') . ' - ' . __('Default', 'custom-post-types'),
                            'multiple' => true,
                            'options' => [
                                'image' => __('Image (all extensions)', 'custom-post-types') . ' - ' . __('Default', 'custom-post-types'),
                                'audio' => __('Audio (all extensions)', 'custom-post-types'),
                                'video' => __('Video (all extensions)', 'custom-post-types'),
                                'application/pdf' => __('.pdf', 'custom-post-types'),
                                'application/zip' => __('.zip', 'custom-post-types'),
                                'text/plain' => __('.txt', 'custom-post-types'),
                                'application/msword' => __('.doc', 'custom-post-types'),
                            ]
                        ],
                        'wrap' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                            'layout' => ''
                        ]
                    ],
                ]
            ],
            'color' => [
                'label' => __('Color picker', 'custom-post-types'),
                'fields' => []
            ],
            'date' => [
                'label' => __('Date picker', 'custom-post-types'),
                'fields' => [
                    [ //min
                        'key' => 'min',
                        'label' => __('Minimum limit to selectable date', 'custom-post-types'),
                        'info' => false,
                        'required' => false,
                        'type' => 'date',
                        'extra' => [],
                        'wrap' => [
                            'width' => '50',
                            'class' => '',
                            'id' => '',
                            'layout' => ''
                        ]
                    ],
                    [ //max
                        'key' => 'max',
                        'label' => __('Maximum limit to selectable date', 'custom-post-types'),
                        'info' => false,
                        'required' => false,
                        'type' => 'date',
                        'extra' => [],
                        'wrap' => [
                            'width' => '50',
                            'class' => '',
                            'id' => '',
                            'layout' => ''
                        ]
                    ],
                ]
            ],
            'time' => [
                'label' => __('Time picker', 'custom-post-types'),
                'fields' => [
                    [ //min
                        'key' => 'min',
                        'label' => __('Minimum limit to selectable time', 'custom-post-types'),
                        'info' => false,
                        'required' => false,
                        'type' => 'time',
                        'extra' => [],
                        'wrap' => [
                            'width' => '50',
                            'class' => '',
                            'id' => '',
                            'layout' => ''
                        ]
                    ],
                    [ //max
                        'key' => 'max',
                        'label' => __('Maximum limit to selectable time', 'custom-post-types'),
                        'info' => false,
                        'required' => false,
                        'type' => 'time',
                        'extra' => [],
                        'wrap' => [
                            'width' => '50',
                            'class' => '',
                            'id' => '',
                            'layout' => ''
                        ]
                    ],
                ]
            ],
            'repeater' => [
                'label' => __('Repeater', 'custom-post-types'),
                'fields' => [
                    [ //fields
                        'key' => 'fields',
                        'label' => __('Minimum limit to selectable date', 'custom-post-types'),
                        'info' => false,
                        'required' => false,
                        'type' => 'repeater',
                        'extra' => [
                            'fields' => $this->get_new_field_form()
                        ],
                        'wrap' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                            'layout' => ''
                        ]
                    ],
                ]
            ],
            'post_rel' => [
                'label' => __('Post relationship', 'custom-post-types'),
                'fields' => [
                    [ //multiple
                        'key' => 'multiple',
                        'label' => __('Multiple', 'custom-post-types'),
                        'info' => false,
                        'required' => false,
                        'type' => 'select',
                        'extra' => [
                            'placeholder' => __('NO', 'custom-post-types') . ' - ' . __('Default', 'custom-post-types'),
                            'multiple' => false,
                            'options' => [
                                'true' => __('YES', 'custom-post-types'),
                                'false' => __('NO', 'custom-post-types') . ' - ' . __('Default', 'custom-post-types'),
                            ]
                        ],
                        'wrap' => [
                            'width' => '20',
                            'class' => '',
                            'id' => '',
                            'layout' => ''
                        ]
                    ],
                    [ //post_type
                        'key' => 'post_type',
                        'label' => __('Post type', 'custom-post-types'),
                        'info' => false,
                        'required' => false,
                        'type' => 'select',
                        'extra' => [
                            'placeholder' => __('Posts', 'custom-post-types') . ' - ' . __('Default', 'custom-post-types'),
                            'multiple' => false,
                            'options' => $this->get_post_types_array(),
                        ],
                        'wrap' => [
                            'width' => '80',
                            'class' => '',
                            'id' => '',
                            'layout' => ''
                        ]
                    ],
                ]
            ],
            'tax_rel' => [
                'label' => __('Taxonomy relationship', 'custom-post-types'),
                'fields' => [
                    [ //multiple
                        'key' => 'multiple',
                        'label' => __('Multiple', 'custom-post-types'),
                        'info' => false,
                        'required' => false,
                        'type' => 'select',
                        'extra' => [
                            'placeholder' => __('NO', 'custom-post-types') . ' - ' . __('Default', 'custom-post-types'),
                            'multiple' => false,
                            'options' => [
                                'true' => __('YES', 'custom-post-types'),
                                'false' => __('NO', 'custom-post-types') . ' - ' . __('Default', 'custom-post-types'),
                            ]
                        ],
                        'wrap' => [
                            'width' => '20',
                            'class' => '',
                            'id' => '',
                            'layout' => ''
                        ]
                    ],
                    [ //taxonomy
                        'key' => 'taxonomy',
                        'label' => __('Taxonomy', 'custom-post-types'),
                        'info' => false,
                        'required' => false,
                        'type' => 'select',
                        'extra' => [
                            'placeholder' => __('Category', 'custom-post-types') . ' - ' . __('Default', 'custom-post-types'),
                            'multiple' => false,
                            'options' => $this->get_taxonomies_array(),
                        ],
                        'wrap' => [
                            'width' => '80',
                            'class' => '',
                            'id' => '',
                            'layout' => ''
                        ]
                    ],
                ]
            ],
        ];
        $this->available_fields = apply_filters("cpt_available_fields", $available_fields);
        return $this;
    }

    public function set_available_fields_options()
    {
        $fields = $this->available_fields;
        foreach ($fields as $type => $settings) {
            if (isset($this->available_fields_options[$type])) break;
            $this->available_fields_options[$type] = $settings['label'];
        }
        return $this;
    }

    private function add_repeater_field_type_options()
    {
        $i = 0;
        $this->available_fields['repeater']['fields'][$i]['extra']['fields'][3]['extra']['options'] = $this->available_fields_options;
        $i++;
        return $this;
    }

    private function get_post_types_array()
    {
        $output = [
            'page' => get_post_type_object('page')->labels->singular_name,
            'post' => get_post_type_object('post')->labels->singular_name,
        ];
        $query = [
            'public'   => true,
            '_builtin' => false
        ];
        $post_types = get_post_types($query, 'objects');
        foreach ($post_types as $post_type) {
            $output[$post_type->name] = $post_type->labels->singular_name;
        }
        return $output;
    }

    private function get_taxonomies_array()
    {
        $output = [
            'category' => get_taxonomy('category')->labels->singular_name,
            'post_tag' => get_taxonomy('post_tag')->labels->singular_name,
        ];
        $query = [
            'public'   => true,
            '_builtin' => false
        ];
        $taxonomies = get_taxonomies($query, 'objects');
        foreach ($taxonomies as $taxonomy) {
            $output[$taxonomy->name] = $taxonomy->labels->singular_name;
        }
        return $output;
    }
}
