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
            'Attribute Permissions' => [
                'View Attributes' => 'view-attribute',
                'Create Attribute' => 'create-attribute',
                'Edit Attribute ' => 'edit-attribute',
                'Delete Attribute' => 'delete-attribute',
            ],
            'Store Banners Permissions' => [
                'View Store Banners' => 'view-store-banner',
                'Create Store Banner' => 'create-store-banner',
                'Edit Store Banner ' => 'edit-store-banner',
                'Delete Store Banner' => 'delete-store-banner',
            ],
            'Setting Permissions' => [
                'View Settings' => 'view-setting',
                'Save Settings' => 'edit-setting',
            ],
            'Order Permissions' => [
                'View Order' => 'view-order',
            ],
            'User Permissions' => [
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
            'Attribute Permissions' => [
                'View Attributes' => 'view-attribute',
                'Create Attribute' => 'create-attribute',
                'Edit Attribute ' => 'edit-attribute',
                'Delete Attribute' => 'delete-attribute',
            ],
        ]
    ],
    'setting' => [ //Default Settingss
        'hidden' => [
            'currency' => 'USD',
            'currency_symbol' => '$',
        ],
        'general' => [
            'default_verification_method' => 'email',
        ],
        'tax' => [
            'platform_fees' => '10',
            'tax' => '5',
        ],
        'store' => [
            'default_price' => '10',
            'default_price_condition' => 'percentage',
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
        'payment' => [
            'paypal_mode' => env('PAYPAL_MODE'),
            'paypal_account' => env('PAYPAL_SANDBOX_ACCOUNT'),
            'paypal_secret_key' => env('PAYPAL_SANDBOX_API_SECRET'),
            'paypal_client_id' => env('PAYPAL_SANDBOX_CLIENT_ID'),
            'paypal_currency' => env('PAYPAL_CURRENCY'),
            'paypal_api_credentials' => env('PAYPAL_SANDBOX_API_CERTIFICATE'),
        ],
    ]
];
