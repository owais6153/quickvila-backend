<?php

return [
    // Type of permission using in whole project
    'permissions' => [
        'admin' => [ //Admin Permission
            'Basic Permissions' => [
                'Admin Area' => 'admin-area',
            ],
            'Product Permissions' => [
                'View Products' => 'view-product',
                'Create Product' => 'create-product',
                'Edit Product ' => 'edit-product',
                'Delete Product' => 'delete-product',
            ],
            'Product Category Permissions' => [
                'View Product Categories' => 'view-product-category',
                'Create Product Category' => 'create-product-category',
                'Edit Product Category ' => 'edit-product-category',
                'Delete Product Category' => 'delete-product-category',
            ],
            'Store Permissions' => [
                'Store Settings' => 'setting-store',
                'View Stores' => 'view-store',
                'Create Store' => 'create-store',
                'Edit Store ' => 'edit-store',
                'Delete Store' => 'delete-store',
            ],
            'Store Category Permissions' => [
                'View Store Categories' => 'view-store-category',
                'Create Store Category' => 'create-store-category',
                'Edit Store Category ' => 'edit-store-category',
                'Delete Store Category' => 'delete-store-category',
            ],
            'Testimonial Permissions' => [
                'View Testimonials' => 'view-testimonial',
                'Create Testimonial' => 'create-testimonial',
                'Edit Testimonial ' => 'edit-testimonial',
                'Delete Testimonial' => 'delete-testimonial',
            ],
            'Video Permissions' => [
                'View Videos' => 'view-video',
                'Create Video' => 'create-video',
                'Edit Video ' => 'edit-video',
                'Delete Video' => 'delete-video',
            ],
            'Setting Permissions' => [
                'View Settings' => 'view-setting',
                'Save Settings' => 'edit-setting',
            ],
            'Order Permissions' => [
                'All Orders' => 'all-orders',
                'View Order' => 'view-order',
            ],
            'User Permissions' => [
                'All Users' => 'all-users',
                'View User' => 'view-user',
            ],
        ],
        'manager' => [ //Manager Permission
            'Basic Permissions' => [
                'Admin Area' => 'admin-area',
            ],
            'Product Permissions' => [
                'View Products' => 'view-product',
                'Create Product' => 'create-product',
                'Edit Product ' => 'edit-product',
                'Delete Product' => 'delete-product',
            ],
            'Product Category Permissions' => [
                'View Product Categories' => 'view-product-category',
                'Create Product Category' => 'create-product-category',
                'Edit Product Category ' => 'edit-product-category',
                'Delete Product Category' => 'delete-product-category',
            ],
            'Store Permissions' => [
                'Store Settings' => 'setting-store',
                'View Stores' => 'view-store',
                'Create Store' => 'create-store',
                'Edit Store ' => 'edit-store',
                'Delete Store' => 'delete-store',
            ],
            'Store Category Permissions' => [
                'View Store Categories' => 'view-store-category',
                'Create Store Category' => 'create-store-category',
                'Edit Store Category ' => 'edit-store-category',
                'Delete Store Category' => 'delete-store-category',
            ],
        ]
    ],
    'setting' => [ //Default Settingss
        'general' => [
            'default_verification_method' => 'email',
        ],
        'hidden' => [
            'currency' => 'USD',
            'currency_symbol' => '$',
        ],
        'store' => [
            'tax' => '20',
            'default_price' => '0',
            'default_price_condition' => '0',
            'default_radius' => '10',
        ],
        'email' => [
            'should_send' => true,
            'host' => env('MAIL_HOST'),
            'port' => env('MAIL_PORT'),
            'username' => env('MAIL_USERNAME'),
            'password' => env('MAIL_PASSWORD'),
            'encryption' => env('MAIL_ENCRYPTION'),
        ],
        'sms' => [
            'should_send' => true,
            'sid' => env('TWILIO_SID'),
            'token' => env('TWILIO_TOKEN'),
            'number' => env('TWILIO_NUMBER'),
            'messaging_service' => env('TWILIO_MESSAGING_SERVICE_ID'),
        ],

    ]
];
