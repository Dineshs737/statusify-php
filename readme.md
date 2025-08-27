# uthayakumar-dinesh/statusify

[![Packagist Version](https://img.shields.io/packagist/v/uthayakumar-dinesh/statusify)](https://packagist.org/packages/uthayakumar-dinesh/statusify)
[![PHP Version Require](https://img.shields.io/packagist/php-v/uthayakumar-dinesh/statusify)](https://packagist.org/packages/uthayakumar-dinesh/statusify)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![Total Downloads](https://img.shields.io/packagist/dt/uthayakumar-dinesh/statusify)](https://packagist.org/packages/uthayakumar-dinesh/statusify)

A lightweight PHP library that provides HTTP status codes as class constants with utility methods for reverse lookup operations. Perfect for building consistent and type-safe web applications and APIs.

## Features

✨ **HTTP Status Code Constants**: All common HTTP status codes available as class constants
🔍 **Reverse Lookup**: Static method to get status name from numeric code
🎯 **Zero Dependencies**: Lightweight package with no external dependencies
🚀 **PSR-4 Autoloading**: Follows PHP standards for easy integration
📝 **IDE Friendly**: Full IntelliSense support with class constants
⚡ **PHP 7.4+** Compatible with modern PHP versions

## Installation

Install via Composer:

```bash
composer require uthayakumar-dinesh/statusify
```

## Usage

### Basic Usage

```php
<?php

use UthayakumarDinesh\Statusify\Statusify;

// Using class constants
echo Statusify::OK; // 200
echo Statusify::NOT_FOUND; // 404
echo Statusify::INTERNAL_SERVER_ERROR; // 500

// Reverse lookup - get status name from code
echo Statusify::getStatusName(200); // "OK"
echo Statusify::getStatusName(404); // "NOT_FOUND"
echo Statusify::getStatusName(500); // "INTERNAL_SERVER_ERROR"
echo Statusify::getStatusName(999); // "UNKNOWN_STATUS"
```

### Laravel Example

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use UthayakumarDinesh\Statusify\Statusify;

class UserController extends Controller
{
    public function show($id): JsonResponse
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'error' => 'User not found',
                'status_code' => Statusify::NOT_FOUND,
                'status_name' => Statusify::getStatusName(Statusify::NOT_FOUND)
            ], Statusify::NOT_FOUND);
        }

        return response()->json($user, Statusify::OK);
    }

    public function store(Request $request): JsonResponse
    {
        $user = User::create($request->validated());

        return response()->json([
            'data' => $user,
            'status_code' => Statusify::CREATED,
            'status_name' => Statusify::getStatusName(Statusify::CREATED)
        ], Statusify::CREATED);
    }
}
```

### Symfony Example

```php
<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use UthayakumarDinesh\Statusify\Statusify;

class ApiController
{
    #[Route('/api/users/{id}', methods: ['GET'])]
    public function getUser(int $id): JsonResponse
    {
        $user = $this->userRepository->find($id);

        if (!$user) {
            return new JsonResponse([
                'error' => 'User not found',
                'status_code' => Statusify::NOT_FOUND,
                'status_name' => Statusify::getStatusName(Statusify::NOT_FOUND)
            ], Statusify::NOT_FOUND);
        }

        return new JsonResponse($user, Statusify::OK);
    }
}
```

### API Response Helper Class

```php
<?php

namespace App\Helpers;

use UthayakumarDinesh\Statusify\Statusify;

class ApiResponse
{
    public static function success($data = null, int $statusCode = null): array
    {
        $statusCode = $statusCode ?? Statusify::OK;

        return [
            'success' => true,
            'status_code' => $statusCode,
            'status_name' => Statusify::getStatusName($statusCode),
            'data' => $data
        ];
    }

    public static function error(string $message, int $statusCode = null): array
    {
        $statusCode = $statusCode ?? Statusify::INTERNAL_SERVER_ERROR;

        return [
            'success' => false,
            'status_code' => $statusCode,
            'status_name' => Statusify::getStatusName($statusCode),
            'error' => $message
        ];
    }
}

// Usage
return ApiResponse::success($users, Statusify::OK);
return ApiResponse::error('Validation failed', Statusify::BAD_REQUEST);
```

### Custom Exception Handler

```php
<?php

namespace App\Exceptions;

use UthayakumarDinesh\Statusify\Statusify;
use Exception;

class ApiException extends Exception
{
    private int $statusCode;

    public function __construct(string $message, int $statusCode = null)
    {
        $this->statusCode = $statusCode ?? Statusify::INTERNAL_SERVER_ERROR;
        parent::__construct($message);
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getStatusName(): string
    {
        return Statusify::getStatusName($this->statusCode);
    }

    public function toArray(): array
    {
        return [
            'error' => $this->getMessage(),
            'status_code' => $this->getStatusCode(),
            'status_name' => $this->getStatusName()
        ];
    }
}

// Usage
throw new ApiException('User not found', Statusify::NOT_FOUND);
throw new ApiException('Invalid request data', Statusify::BAD_REQUEST);
```

## API Reference

### Class Constants

The `Statusify` class provides HTTP status codes as public constants:

```php
class Statusify
{
    // 1xx Informational
    public const CONTINUE = 100;
    public const SWITCHING_PROTOCOLS = 101;

    // 2xx Success
    public const OK = 200;
    public const CREATED = 201;
    // ... and more
}
```

### Static Methods

#### `getStatusName(int $code): string`

Returns the constant name for a given HTTP status code.

**Parameters:**

- `$code` (int): The numeric HTTP status code

**Returns:**

- `string`: The constant name corresponding to the status code, or `"UNKNOWN_STATUS"` if not found

**Example:**

```php
Statusify::getStatusName(200); // Returns: "OK"
Statusify::getStatusName(404); // Returns: "NOT_FOUND"
Statusify::getStatusName(500); // Returns: "INTERNAL_SERVER_ERROR"
Statusify::getStatusName(999); // Returns: "UNKNOWN_STATUS"
```

## Available HTTP Status Codes

### 1xx Informational

| Code | Constant              | Description         |
| ---- | --------------------- | ------------------- |
| 100  | `CONTINUE`            | Continue            |
| 101  | `SWITCHING_PROTOCOLS` | Switching Protocols |

### 2xx Success

| Code | Constant     | Description |
| ---- | ------------ | ----------- |
| 200  | `OK`         | OK          |
| 201  | `CREATED`    | Created     |
| 202  | `ACCEPTED`   | Accepted    |
| 204  | `NO_CONTENT` | No Content  |

### 3xx Redirection

| Code | Constant            | Description       |
| ---- | ------------------- | ----------------- |
| 301  | `MOVED_PERMANENTLY` | Moved Permanently |
| 302  | `FOUND`             | Found             |
| 304  | `NOT_MODIFIED`      | Not Modified      |

### 4xx Client Errors

| Code | Constant             | Description        |
| ---- | -------------------- | ------------------ |
| 400  | `BAD_REQUEST`        | Bad Request        |
| 401  | `UNAUTHORIZED`       | Unauthorized       |
| 403  | `FORBIDDEN`          | Forbidden          |
| 404  | `NOT_FOUND`          | Not Found          |
| 405  | `METHOD_NOT_ALLOWED` | Method Not Allowed |

### 5xx Server Errors

| Code | Constant                | Description           |
| ---- | ----------------------- | --------------------- |
| 500  | `INTERNAL_SERVER_ERROR` | Internal Server Error |
| 501  | `NOT_IMPLEMENTED`       | Not Implemented       |
| 502  | `BAD_GATEWAY`           | Bad Gateway           |
| 503  | `SERVICE_UNAVAILABLE`   | Service Unavailable   |

## Requirements

- PHP 7.4 or higher
- Composer for installation

## Framework Compatibility

This package works seamlessly with:

- ✅ **Laravel** (5.5+)
- ✅ **Symfony** (4.0+)
- ✅ **CodeIgniter** (4.0+)
- ✅ **CakePHP** (4.0+)
- ✅ **Slim Framework** (4.0+)
- ✅ **Laminas** (formerly Zend)
- ✅ **Pure PHP** projects

## Testing

```bash
# Run tests
composer test

# Run tests with coverage
composer test-coverage

# Run PHP CS Fixer
composer cs-fix

# Run PHPStan
composer phpstan
```

## Contributing

We welcome contributions! Please follow these steps:

1. **Fork the repository**

   ```bash
   git clone https://github.com/uthayakumar-dinesh/statusify-php.git
   ```

2. **Install dependencies**

   ```bash
   cd statusify-php
   composer install
   ```

3. **Create a feature branch**

   ```bash
   git checkout -b feature/your-feature-name
   ```

4. **Make your changes**
   - Follow PSR-12 coding standards
   - Add tests for any new functionality
   - Ensure all tests pass: `composer test`

5. **Commit your changes**

   ```bash
   git commit -m "feat: add your feature description"
   ```

6. **Push to your fork and create a Pull Request**

### Development Setup

```bash
# Clone the repository
git clone https://github.com/uthayakumar-dinesh/statusify-php.git
cd statusify-php

# Install dependencies
composer install

# Run tests
composer test

# Check code style
composer cs-check

# Fix code style
composer cs-fix

# Run static analysis
composer phpstan
```

### Code Standards

- Follow [PSR-12](https://www.php-fig.org/psr/psr-12/) coding standards
- Use meaningful variable and method names
- Add PHPDoc blocks for all public methods
- Write tests for new functionality
- Maintain backward compatibility

## Issues and Support

- 🐛 **Bug Reports**: [GitHub Issues](https://github.com/uthayakumar-dinesh/statusify-php/issues)
- 💡 **Feature Requests**: [GitHub Issues](https://github.com/uthayakumar-dinesh/statusify-php/issues)
- 📖 **Documentation**: [GitHub Repository](https://github.com/uthayakumar-dinesh/statusify-php)

## Changelog

Please see [CHANGELOG.md](CHANGELOG.md) for more information on what has changed recently.

## Security

If you discover any security-related issues, please email security@example.com instead of using the issue tracker.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Repository

- **GitHub**: [https://github.com/uthayakumar-dinesh/statusify-php](git@github.com:Dineshs737/statusify-php.git)
- **Packagist**: [uthayakumar-dinesh/statusify](https://packagist.org/packages/uthayakumar-dinesh/statusify)

---

Made with ❤️ by [Uthayakumar Dinesh](https://github.com/Dineshs737)
