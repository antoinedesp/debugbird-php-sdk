# DebugBird PHP SDK

DebugBird is a lightweight PHP SDK for capturing logs and crashes, sending them to your DebugBird dashboard.

## Installation

You can install the package via Composer:

```sh
composer require debugbird/php-sdk
```

## Usage

### Initialize the Logger

Before logging, initialize DebugBird with your project credentials:

```php
require 'vendor/autoload.php';

use DebugBird\DebugBird;

DebugBird::init([
    'project_id' => 'your_project_id',
    'api_key' => 'your_api_key'
]);
```

### Logging Messages

Log messages with different types:

```php
DebugBird::log('info', 'This is an informational message');
DebugBird::log('warning', 'This is a warning message');
DebugBird::log('error', 'This is an error message');
```

### Capturing Exceptions & Errors

Exceptions and errors are automatically captured once DebugBird is initialized:

```php
throw new Exception('Something went wrong!');
```

### Disabling Specific Logging

You can disable log or crash collection while initializing:

```php
DebugBird::init([
    'project_id' => 'your_project_id',
    'api_key' => 'your_api_key',
    'disable_logs' => true, // Disables log collection
    'disable_errors' => true // Disables error/crash collection
]);
```

## Contributing

Feel free to submit issues and pull requests to improve this SDK.

## License

This package is proprietary. Contact [contact@debugbird.com](mailto:contact@debugbird.com) for licensing inquiries.
