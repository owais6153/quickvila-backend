<?php

return [
    // Type of permission using in whole project
    'permissions' => [
        'all' => [ //Admin Permission
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
            'Setting Permissions' => [
                'View Settings' => 'view-setting',
                'Save Settings' => 'edit-setting',
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
            ]
        ]
    ],
    'settings' => [
        'APP_NAME' => env('APP_NAME', 'Trikaro'),
        'APP_LOGO' => '',
    ]
];
