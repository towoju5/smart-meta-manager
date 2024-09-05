<?php

/**
 * Configuration file for Smart Meta Manager package
 *
 * This file contains the configuration settings for the Smart Meta Manager package.
 * It defines the models that can have meta data associated with them and specifies
 * the authentication guard to be used.
 */

return [
    /**
     * Meta Data Models
     *
     * This array defines the models that can have meta data associated with them.
     * Each key-value pair represents a model name and its corresponding class.
     *
     * @var array
     */
    'meta_data_models' => [
        'user' => App\Modules\user\Models\User::class,
        'product' => App\Models\Product::class,
        // Add more models as needed
    ],

    /**
     * Authentication Guard
     *
     * Specifies the authentication guard to be used for this package.
     *
     * @var string
     */
    'auth_guard' => 'api',
];
