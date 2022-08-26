<?php

return [
    // Type of permission using in whole project
    'permissions' => [
        'Basic Permissions' => [
            'Admin Area' => 'admin-area',
        ],
        'Product Permissions' =>[
            'View Products' => 'view-product',
            'Create Product' => 'create-product',
            'Edit Product ' => 'edit-product',
            'Delete Product' => 'delete-product',
        ],
        'Product Category Permissions' =>[
            'View Product Categories' => 'view-category',
            'Create Product Category' => 'create-category',
            'Edit Product Category ' => 'edit-category',
            'Delete Product Category' => 'delete-category',
        ],
        'Store Permissions' =>[
            'View Stores' => 'view-store',
            'Create Store' => 'create-store',
            'Edit Store ' => 'edit-store',
            'Delete Store' => 'delete-store',
        ],
        'Store Category Permissions' =>[
            'View Store Categories' => 'view-store-category',
            'Create Store Category' => 'create-store-category',
            'Edit Store Category ' => 'edit-store-category',
            'Delete Store Category' => 'delete-store-category',
        ],
        'Setting Permissions' =>[
            'View Settings' => 'view-setting',
            'Save Settings' => 'edit-setting',
        ],
    ],
    'settings' => [
        'APP_NAME' => env('APP_NAME', 'Trikaro'),
        'APP_LOGO' => '',
    ]
];
