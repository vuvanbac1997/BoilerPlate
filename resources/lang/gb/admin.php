<?php

return [
    'menu'       => [
        'dashboard'                => 'Dashboard',
        'admin_users'              => 'Admin Users',
        'users'                    => 'Users',
        'admin_user_notifications' => 'AdminUserNotifications',
        'user_notifications'       => 'User Notifications',
        'site_configuration'       => 'Site Configuration',
        'log_system'               => 'Logs System',
        'images'                   => 'Images',
        'articles'                 => 'Articles',
    ],
    'breadcrumb' => [
        'dashboard'                => 'Dashboard',
        'admin_users'              => 'Admin Users',
        'users'                    => 'Users',
        'admin_user_notifications' => 'AdminUserNotifications',
        'user_notifications'       => 'UserNotifications',
        'site_configuration'       => 'Site Configuration',
        'log_system'               => 'Logs',
        'images'                   => 'Images',
        'articles'                 => 'Articles',
        'create_new'               => 'Create New',
    ],
    'messages'   => [
        'general' => [
            'update_success' => 'Successfully updated.',
            'create_success' => 'Successfully created.',
            'delete_success' => 'Successfully deleted.',
        ],
    ],
    'errors'     => [
        'general'  => [
            'save_failed' => 'Failed To Save. Please contact with developers',
        ],
        'requests' => [
            'me' => [
                'email'    => [
                    'required' => 'Email Required',
                    'email'    => 'Email is not valid',
                ],
                'password' => [
                    'min' => 'Password need at least 6 letters',
                ],
            ],
        ],
    ],
    'pages'      => [
        'common'                   => [
            'label'   => [
                'page'             => 'Page',
                'actions'          => 'Actions',
                'is_enabled'       => 'Status',
                'is_enabled_true'  => 'Enabled',
                'is_enabled_false' => 'Disabled',
                'search_results'   => 'Total: :count results',
                'select_province'  => 'Select a Province',
                'select_district'  => 'Select a District',
                'select_locale'    => 'Select a Locale',
                'select_category'  => 'Select a Category',
            ],
            'buttons' => [
                'create'          => 'Create New',
                'edit'            => 'Edit',
                'save'            => 'Save',
                'delete'          => 'Delete',
                'cancel'          => 'Cancel',
                'back'            => 'Back',
                'add'             => 'Add',
                'preview'         => 'Preview',
                'forgot_password' => 'Send Me Email!',
                'reset_password'  => 'Reset Password',
            ],
        ],
        'auth'                     => [
            'buttons'  => [
                'sign_in'         => 'Sign In',
                'forgot_password' => 'Send Me Email!',
                'reset_password'  => 'Reset Password',
            ],
            'messages' => [
                'remember_me'     => 'Remember Me',
                'please_sign_in'  => 'Sign in to start your session',
                'forgot_password' => 'I forgot my password',
                'reset_password'  => 'Please enter your e-mail address and new password',
            ],
        ],
        'articles'                 => [
            'columns' => [
                'slug'               => 'Slug',
                'title'              => 'Title',
                'keywords'           => 'Keywords',
                'description'        => 'Description',
                'content'            => 'Content',
                'cover_image_id'     => 'Cover Image',
                'locale'             => 'Locale',
                'is_enabled'         => 'Active',
                'publish_started_at' => 'Publish Started At',
                'publish_ended_at'   => 'Publish Ended At',
                'is_enabled_true'    => 'Enabled',
                'is_enabled_false'   => 'Disabled',

            ],
        ],
        'user-notifications'       => [
            'columns' => [
                'user_id'       => 'User',
                'category_type' => 'Category',
                'type'          => 'Type',
                'data'          => 'Data',
                'locale'        => 'Locale',
                'content'       => 'Content',
                'read'          => 'Read',
                'read_true'     => 'Read',
                'read_false'    => 'Unread',
                'sent_at'       => 'Sent At',
            ],
        ],
        'admin-user-notifications' => [
            'columns' => [
                'user_id'       => 'Receiver',
                'category_type' => 'Category',
                'type'          => 'Type',
                'data'          => 'Data',
                'locale'        => 'Locale',
                'content'       => 'Content',
                'read'          => 'Read',
                'read_true'     => 'Read',
                'read_false'    => 'Unread',
                'sent_at'       => 'Sent At',
            ],
        ],
        'images'                   => [
            'columns' => [
                'url'                    => 'URL',
                'title'                  => 'Title',
                'is_local'               => 'is Local',
                'entity_type'            => 'EntityType',
                'entity_id'              => 'ID',
                'file_category_type'     => 'Category',
                's3_key'                 => 'S3 Key',
                's3_bucket'              => 'S3 Bucket',
                's3_region'              => 'S3 Region',
                's3_extension'           => 'S3 Extension',
                'media_type'             => 'Media Type',
                'format'                 => 'Format',
                'file_size'              => 'File Size',
                'width'                  => 'Width',
                'height'                 => 'Height',
                'has_alternative'        => 'has Alternative',
                'alternative_media_type' => 'Alternative Media Type',
                'alternative_format'     => 'Alternative Format',
                'alternative_extension'  => 'Alternative Extension',
                'is_enabled'             => 'Status',
                'is_enabled_true'        => 'Enabled',
                'is_enabled_false'       => 'Disabled',
            ],
        ],
        'admin-users'              => [
            'columns' => [
                'name'             => 'Name',
                'email'            => 'Email',
                'password'         => 'Password',
                're_password'      => 'Confirm Password',
                'role'             => 'Role',
                'locale'           => 'Locale',
                'profile_image_id' => 'Avatar',
                'permissions'      => 'Permissions',
            ],
        ],
        'users'                    => [
            'columns' => [
                'name'                 => 'Name',
                'email'                => 'Email',
                'password'             => 'Password',
                're_password'          => 'Confirm Password',
                'gender'               => 'Gender',
                'gender_male'          => 'Male',
                'gender_female'        => 'Female',
                'telephone'            => 'Telephone',
                'birthday'             => 'Birthday',
                'locale'               => 'Locale',
                'address'              => 'Address',
                'remember_token'       => 'Remember Token',
                'api_access_token'     => 'Api Access Token',
                'profile_image_id'     => 'Profile Image',
                'last_notification_id' => 'Last Notification Id',
                'is_activated'         => 'is Activated',
            ],
        ],
        'logs'                     => [
            'columns' => [
                'user_name' => 'User Name',
                'email'     => 'Email',
                'action'    => 'Action',
                'table'     => 'Table',
                'record_id' => 'Record ID',
                'query'     => 'Query',
            ],
        ],
        'oauth-clients'            => [
            'columns' => [
                'user_id'                => 'User ID',
                'name'                   => 'Name',
                'secret'                 => 'Secret',
                'redirect'               => 'Redirect',
                'personal_access_client' => 'Personal Access Client',
                'password_client'        => 'Password Client',
                'revoked'                => 'Revoked',
            ],
        ],
        'tests'   => [
            'columns'  => [
            ],
        ],
        'tests'   => [
            'columns'  => [
                'name' => 'Name',
            ],
        ],
        'tests'   => [
            'columns'  => [
                'title' => 'Title',
                'content' => 'Content',
            ],
        ],
        'tests'   => [
            'columns'  => [
                'title' => 'Title',
                'content' => 'Content',
            ],
        ],
        'tests'   => [
            'columns'  => [
                'title' => 'Title',
                'content' => 'Content',
            ],
        ],
        'tests'   => [
            'columns'  => [
                'title' => 'Title',
                'content' => 'Content',
            ],
        ],
        'tests'   => [
            'columns'  => [
                'title' => 'Title',
                'content' => 'Content',
            ],
        ],
        /* NEW PAGE STRINGS */
    ],
    'roles'      => [
        'super_user' => 'Super User',
        'admin'      => 'Administrator',
    ],
];
