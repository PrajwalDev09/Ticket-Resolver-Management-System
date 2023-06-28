<?php

defined( 'BASEPATH' ) OR exit( 'No direct script access allowed' );

$config = [
    // @version 1.8
    'custom_page' => [
        [
            'field' => 'name',
            'label' => 'lang:name',
            'rules' => 'required|max_length[30]'
        ],
        [
            'field' => 'content',
            'label' => 'lang:content',
            'rules' => 'required'
        ],
        [
            'field' => 'meta_description',
            'label' => 'lang:meta_description',
            'rules' => 'max_length[255]'
        ],
        [
            'field' => 'meta_keywords',
            'label' => 'lang:meta_keywords',
            'rules' => 'max_length[255]'
        ]
    ],
    'support_note' => [
        [
            'field' => 'note',
            'label' => 'lang:message',
            'rules' => 'required|max_length[500]'
        ]
    ],
    
    // @version 1.5
    'custom_field' => [
        [
            'field' => 'name',
            'label' => 'lang:name',
            'rules' => 'required|max_length[90]'
        ],
        [
            'field' => 'options',
            'label' => 'lang:options',
            'rules' => 'max_length[1500]'
        ],
        [
            'field' => 'guide_text',
            'label' => 'lang:guide_text',
            'rules' => 'max_length[255]'
        ]
    ],
    
    // @version 1.4
    'create_chat' => [
        [
            'field' => 'subject',
            'label' => 'lang:subject',
            'rules' => 'required|max_length[90]'
        ],
        [
            'field' => 'department',
            'label' => 'lang:department',
            'rules' => 'required'
        ],
        [
            'field' => 'message',
            'label' => 'lang:message',
            'rules' => 'required'
        ]
    ],
    'create_ticket_admin_unregistered_users' => [
        [
            'field' => 'subject',
            'label' => 'lang:subject',
            'rules' => 'required|max_length[90]'
        ],
        [
            'field' => 'priority',
            'label' => 'lang:priority',
            'rules' => 'required'
        ],
        [
            'field' => 'department',
            'label' => 'lang:department',
            'rules' => 'required'
        ],
        [
            'field' => 'email_address',
            'label' => 'lang:email_address',
            'rules' => 'valid_email|required|max_length[255]'
        ],
        [
            'field' => 'message',
            'label' => 'lang:message',
            'rules' => 'required'
        ]
    ],
    
    // @version 1.1
    'update_ticket_reply' => [
        [
            'field' => 'message',
            'label' => 'lang:message',
            'rules' => 'required'
        ]
    ],
    'create_ticket_admin' => [
        [
            'field' => 'subject',
            'label' => 'lang:subject',
            'rules' => 'required|max_length[90]'
        ],
        [
            'field' => 'priority',
            'label' => 'lang:priority',
            'rules' => 'required'
        ],
        [
            'field' => 'department',
            'label' => 'lang:department',
            'rules' => 'required'
        ],
        [
            'field' => 'customer',
            'label' => 'lang:customer',
            'rules' => 'required'
        ],
        [
            'field' => 'message',
            'label' => 'lang:message',
            'rules' => 'required'
        ]
    ],
    
    'send_email_user' => [
        [
            'field' => 'subject',
            'label' => 'lang:subject',
            'rules' => 'required|max_length[255]'
        ],
        [
            'field' => 'message',
            'label' => 'lang:message',
            'rules' => 'required'
        ]
    ],
    'just_email_address' => [
        [
            'field' => 'email_address',
            'label' => 'lang:email_address',
            'rules' => 'required|valid_email'
        ]
    ],
    'login' => [
        [
            'field' => 'username',
            'label' => 'lang:username_email_address',
            'rules' => 'trim|required'
        ],
        [
            'field' => 'password',
            'label' => 'lang:password',
            'rules' => 'required'
        ]
    ],
    'register' => [
        [
            'field' => 'first_name',
            'label' => 'lang:first_name',
            'rules' => 'required|max_length[25]'
        ],
        [
            'field' => 'last_name',
            'label' => 'lang:last_name',
            'rules' => 'required|max_length[25]'
        ],
        [
            'field' => 'email_address',
            'label' => 'lang:email_address',
            'rules' => 'required|valid_email|max_length[255]'
        ],
        [
            'field' => 'password',
            'label' => 'lang:password',
            'rules' => 'required'
        ],
        [
            'field' => 'retype_password',
            'label' => 'lang:retype_password',
            'rules' => 'required|matches[password]'
        ],
        [
            'field' => 'terms',
            'label' => 'lang:agree_terms_just',
            'rules' => 'required'
        ]
    ],
    'new_user' => [
        [
            'field' => 'first_name',
            'label' => 'lang:first_name',
            'rules' => 'required|max_length[25]'
        ],
        [
            'field' => 'last_name',
            'label' => 'lang:last_name',
            'rules' => 'required|max_length[25]'
        ],
        [
            'field' => 'email_address',
            'label' => 'lang:email_address',
            'rules' => 'required|valid_email|max_length[255]'
        ],
        [
            'field' => 'password',
            'label' => 'lang:password',
            'rules' => 'required'
        ],
        [
            'field' => 'retype_password',
            'label' => 'lang:retype_password',
            'rules' => 'required|matches[password]'
        ],
        [
            'field' => 'role',
            'label' => 'lang:role',
            'rules' => 'required'
        ]
    ],
    'profile_settings' => [
        [
            'field' => 'first_name',
            'label' => 'lang:first_name',
            'rules' => 'required|max_length[25]'
        ],
        [
            'field' => 'last_name',
            'label' => 'lang:last_name',
            'rules' => 'required|max_length[25]'
        ],
        [
            'field' => 'email_address',
            'label' => 'lang:email_address',
            'rules' => 'required|valid_email|max_length[255]'
        ],
        [
            'field' => 'username',
            'label' => 'lang:username',
            'rules' => 'required|alpha_dash|min_length[5]|max_length[50]'
        ]
    ],
    'change_password' => [
        [
            'field' => 'password',
            'label' => 'lang:password',
            'rules' => 'required'
        ],
        [
            'field' => 'retype_password',
            'label' => 'lang:retype_password',
            'rules' => 'required|matches[password]'
        ]
    ],
    'change_password_whole' => [
        [
            'field' => 'current_password',
            'label' => 'lang:current_password',
            'rules' => 'required'
        ],
        [
            'field' => 'password',
            'label' => 'lang:password',
            'rules' => 'required'
        ],
        [
            'field' => 'retype_password',
            'label' => 'lang:retype_password',
            'rules' => 'required|matches[password]'
        ]
    ],
    
    // @version 1.6
    'create_ticket_guest' => [
        [
            'field' => 'subject',
            'label' => 'lang:subject',
            'rules' => 'required|max_length[90]'
        ],
        [
            'field' => 'priority',
            'label' => 'lang:priority',
            'rules' => 'required'
        ],
        [
            'field' => 'department',
            'label' => 'lang:department',
            'rules' => 'required'
        ],
        [
            'field' => 'message',
            'label' => 'lang:message',
            'rules' => 'required|min_length[15]'
        ],
        [
            'field' => 'email_address',
            'label' => 'lang:email_address',
            'rules' => 'required|valid_email'
        ],
        [
            'field' => 'retype_email_address',
            'label' => 'lang:retype_email_address',
            'rules' => 'required|valid_email|matches[email_address]'
        ]
    ],
    
    'create_ticket' => [
        [
            'field' => 'subject',
            'label' => 'lang:subject',
            'rules' => 'required|max_length[90]'
        ],
        [
            'field' => 'priority',
            'label' => 'lang:priority',
            'rules' => 'required'
        ],
        [
            'field' => 'department',
            'label' => 'lang:department',
            'rules' => 'required'
        ],
        [
            'field' => 'message',
            'label' => 'lang:message',
            'rules' => 'required|min_length[15]'
        ]
    ],
    'canned_reply' => [
        [
            'field' => 'subject',
            'label' => 'lang:subject',
            'rules' => 'required|max_length[60]'
        ],
        [
            'field' => 'message',
            'label' => 'lang:message',
            'rules' => 'required'
        ]
    ],
    'faq' => [
        [
            'field' => 'question',
            'label' => 'lang:question',
            'rules' => 'required|max_length[90]'
        ],
        [
            'field' => 'answer',
            'label' => 'lang:answer',
            'rules' => 'required'
        ],
        [
            'field' => 'category',
            'label' => 'lang:category',
            'rules' => 'required'
        ]
    ],
    'faqs_category' => [
        [
            'field' => 'category',
            'label' => 'lang:category',
            'rules' => 'required|max_length[50]'
        ]
    ],
    'articles_category' => [
        [
            'field' => 'category',
            'label' => 'lang:category',
            'rules' => 'required|max_length[50]'
        ],
        [
            'field' => 'slug',
            'label' => 'lang:slug',
            'rules' => 'max_length[50]'
        ],
        [
            'field' => 'meta_description',
            'label' => 'lang:meta_description',
            'rules' => 'max_length[255]'
        ],
        [
            'field' => 'meta_keywords',
            'label' => 'lang:meta_keywords',
            'rules' => 'max_length[255]'
        ]
    ],
    'article' => [
        [
            'field' => 'title',
            'label' => 'lang:title',
            'rules' => 'required|max_length[255]'
        ],
        [
            'field' => 'slug',
            'label' => 'lang:slug',
            'rules' => 'max_length[255]'
        ],
        [
            'field' => 'content',
            'label' => 'lang:content',
            'rules' => 'required'
        ],
        [
            'field' => 'meta_description',
            'label' => 'lang:meta_description',
            'rules' => 'max_length[255]'
        ],
        [
            'field' => 'meta_keywords',
            'label' => 'lang:meta_keywords',
            'rules' => 'max_length[255]'
        ],
        [
            'field' => 'category',
            'label' => 'lang:category',
            'rules' => 'required'
        ]
    ],
    'tickets_department' => [
        [
            'field' => 'department',
            'label' => 'lang:department',
            'rules' => 'required|max_length[255]'
        ]
    ],
    'announcement' => [
        [
            'field' => 'subject',
            'label' => 'lang:subject',
            'rules' => 'required|max_length[90]'
        ],
        [
            'field' => 'announcement',
            'label' => 'lang:announcement',
            'rules' => 'required'
        ]
    ],
    'email_template' => [
        [
            'field' => 'title',
            'label' => 'lang:title',
            'rules' => 'required|max_length[90]'
        ],
        [
            'field' => 'subject',
            'label' => 'lang:subject',
            'rules' => 'required|max_length[90]'
        ],
        [
            'field' => 'hook',
            'label' => 'lang:hook',
            'rules' => 'required|max_length[50]'
        ],
        [
            'field' => 'template',
            'label' => 'lang:template',
            'rules' => 'required'
        ]
    ],
    'add_reply' => [
        [
            'field' => 'reply',
            'label' => 'lang:your_reply',
            'rules' => 'required'
        ]
    ],
    'user_invite' => [
        [
            'field' => 'email_address',
            'label' => 'lang:email_address',
            'rules' => 'required|valid_email'
        ],
        [
            'field' => 'expires_in',
            'label' => 'lang:expires_in_hrs',
            'rules' => 'required|is_natural'
        ]
    ],
    'page' => [
        [
            'field' => 'content',
            'label' => 'lang:content',
            'rules' => 'required'
        ],
        [
            'field' => 'meta_description',
            'label' => 'lang:meta_description',
            'rules' => 'max_length[255]'
        ],
        [
            'field' => 'meta_keywords',
            'label' => 'lang:meta_keywords',
            'rules' => 'max_length[255]'
        ]
    ],
    'settings_general' => [
        [
            'field' => 'site_name',
            'label' => 'lang:site_name',
            'rules' => 'required'
        ],
        [
            'field' => 'site_tagline',
            'label' => 'lang:site_tagline',
            'rules' => 'required'
        ]
    ],
    'settings_role_permission' => [
        [
            'field' => 'name',
            'label' => 'lang:name',
            'rules' => 'required|max_length[50]'
        ]
    ],
    'settings_email_smtp' => [
        [
            'field' => 'e_sender',
            'label' => 'lang:from_address',
            'rules' => 'required|valid_email'
        ],
        [
            'field' => 'e_sender_name',
            'label' => 'lang:from_name',
            'rules' => 'required'
        ],
        [
            'field' => 'e_host',
            'label' => 'lang:host',
            'rules' => 'required'
        ],
        [
            'field' => 'e_username',
            'label' => 'lang:username',
            'rules' => 'required'
        ],
        [
            'field' => 'e_password',
            'label' => 'lang:password',
            'rules' => 'required'
        ],
        [
            'field' => 'e_port',
            'label' => 'lang:port',
            'rules' => 'required|is_natural'
        ]
    ],
    'settings_email_mail' => [
        [
            'field' => 'e_sender',
            'label' => 'lang:from_address',
            'rules' => 'required|valid_email'
        ],
        [
            'field' => 'e_sender_name',
            'label' => 'lang:from_name',
            'rules' => 'required'
        ]
    ]
];
