# Nellalink SmartMetaManager

Nellalink SmartMetaManager is a powerful Laravel package designed to simplify the management of metadata for your models. It provides a flexible and efficient way to associate additional information with your model instances, allowing for dynamic and extensible data storage without altering your database schema.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/towoju5/smart-meta-manager.svg?style=flat-square)](https://packagist.org/packages/towoju5/smart-meta-manager)
[![Total Downloads](https://img.shields.io/packagist/dt/towoju5/smart-meta-manager.svg?style=flat-square)](https://packagist.org/packages/towoju5/smart-meta-manager)
![GitHub Actions](https://github.com/towoju5/smart-meta-manager/actions/workflows/main.yml/badge.svg)

## Table of Contents

- [Installation](#installation)
- [Configuration](#configuration)

- [Usage](#usage)
    - [Model Setup](#model-setup)
    - [API Endpoints](#api-endpoints)
    - [Authentication](#authentication)
- [MetaDataTrait](#metadatatrait)

- [Nellalink SmartMetaManager Controller](#SmartMetaManager-controller)
- [API Usage Examples](#api-usage-examples)
- [Error Handling](#error-handling)
- [Best Practices](#best-practices)
- [Performance Considerations](#performance-considerations)
- [Security](#security)
- [Extending Nellalink SmartMetaManager](#extending-SmartMetaManager)
- [Troubleshooting](#troubleshooting)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)

## Installation

You can install the package via composer:

```
composer require towoju5/smart-meta-manager
```

After installation, publish the configuration file:

```
    php artisan vendor:publish --tag=smart-meta-manager-config
```

## Configuration

The configuration file `config/meta_models.php` allows you to specify which models can have associated metadata and set the authentication guard:

```
    return [
    'meta_data_models' => [
        'user' => App\Models\User::class,
        'product' => App\Models\Product::class,
        // Add more models as needed
    ],
    'auth_guard' => 'api',
    ];
```

## Usage

### Model Setup

To enable metadata functionality for a model, use the MetaDataTrait:

```
use Towoju5\Nellalink SmartMetaManager\Trait\MetaDataTrait;

class User extends Model
{
  use MetaDataTrait;
  // ... other model code
}
```

### API Endpoints

Nellalink SmartMetaManager provides the following API endpoints:

- GET `/api/meta/{model}` - Retrieve all meta data for a model
- POST `/api/meta/{model}` - Add new meta data
- GET `/api/meta/{model}/search` - Search meta data
- GET `/api/meta/user/all` - Retrieve all user meta data
- GET `/api/meta/{model}/{key}` - Retrieve specific meta data
- PUT `/api/meta/{model}/{key}` - Update specific meta data
- DELETE `/api/meta/{model}/{key}` - Delete specific meta data
- POST `/api/meta/{model}/check` - Check if a key-value pair exists

### Authentication

All API endpoints require authentication using the guard specified in the config file. Ensure you're authenticated before making requests.

## MetaDataTrait

The MetaDataTrait provides methods for interacting with metadata at the model level. Available methods include:

- `metaData()`
- `setMeta($key, $value, $userId)`
- `getMeta($key, $userId, $default = null)`
- `deleteMeta($key, $userId)`
- `getAllMetaForUser($userId)`
- `searchMeta($userId, $search)`
- `hasMetaKeyValue($key, $value, $userId)`

## Nellalink SmartMetaManager Controller

The Nellalink SmartMetaManager controller handles API requests for metadata operations. Controller methods include:

- `getModelMeta(Request $request, $model)`
- `getAllUserMeta(Request $request)`
- `searchMeta(Request $request, $model)`
- `setMeta(Request $request, $model)`
- `getMeta(Request $request, $model, $key)`
- `updateMeta(Request $request, $model, $key)`
- `deleteMeta(Request $request, $model, $key)`
- `checkMetaKeyValue(Request $request, $model)`

## API Usage Examples

For detailed API usage examples, including requests and responses for various operations, please refer to the full documentation.

## Error Handling

Nellalink SmartMetaManager uses consistent error responses for all API endpoints. Common error scenarios include invalid model names, non-existent keys, validation errors, and authentication failures.

## Best Practices

- Use consistent and descriptive key names for your metadata
- Implement caching for frequently accessed metadata
- Use batch operations for setting or updating multiple meta values
- Regularly clean up orphaned or outdated metadata

## Performance Considerations

- Ensure proper indexing on the metadata table
- Use eager loading to avoid N+1 query problems
- Implement chunking for operations on large datasets
- Consider using Laravel's queue system for time-consuming metadata operations

## Security

- Implement proper authorization checks
- Validate and sanitize input data
- Avoid storing sensitive information as metadata
- Implement rate limiting on API endpoints

## Extending Nellalink SmartMetaManager

You can extend the functionality of Nellalink SmartMetaManager by adding custom scopes, utilizing Laravel's model events, implementing custom validation rules, and creating custom middleware.

## Troubleshooting

For common issues and their solutions, please refer to the Troubleshooting section in the full documentation.

## Contributing

Contributions to Nellalink SmartMetaManager are welcome! Please follow the steps outlined in the Contributing section of the full documentation.

## Credits

- [EMMANUEL TOWOJU](https://github.com/towoju5)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
