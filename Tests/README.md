# FastPix SDK Test Suite

This directory contains the comprehensive test suite for the FastPix PHP SDK.

## Test Structure

```
Tests/
├── Unit/                    # Unit tests for individual components
│   ├── SDKTest.php         # Tests for main SDK class
│   ├── SDKConfigurationTest.php
│   ├── Models/
│   │   └── Components/     # Tests for model components
│   ├── Utils/              # Tests for utility classes
│   └── Hooks/              # Tests for hook system
├── Integration/            # Integration tests
│   ├── InputVideoTest.php  # Tests for video input operations
│   └── ManageVideosTest.php
├── Models/
│   ├── Components/         # Tests for component models
│   ├── Operations/         # Tests for operation models
│   └── Errors/             # Tests for error models
└── TestRunner.php          # Test runner utility
```

## Running Tests

### Using PHPUnit (Recommended)

```bash
# Run all tests
./vendor/bin/phpunit

# Run specific test suite
./vendor/bin/phpunit Tests/Unit
./vendor/bin/phpunit Tests/Integration

# Run with coverage
./vendor/bin/phpunit --coverage-html coverage/

# Run specific test file
./vendor/bin/phpunit Tests/Unit/SDKTest.php
```

### Using Composer Scripts

```bash
# Run tests (defined in composer.json)
composer test

# Run static analysis
composer stan
```

## Test Categories

### Unit Tests
- **SDK Tests**: Test the main SDK class and builder
- **Configuration Tests**: Test SDK configuration and settings
- **Model Tests**: Test individual model classes and their properties
- **Utility Tests**: Test utility classes and helper functions
- **Hook Tests**: Test the hook system and event handling

### Integration Tests
- **Service Tests**: Test complete service operations
- **API Integration**: Test actual API calls (with mock credentials)
- **End-to-End Tests**: Test complete workflows

### Model Tests
- **Component Tests**: Test data model components
- **Operation Tests**: Test request/response models
- **Error Tests**: Test error handling and exceptions

## Test Coverage

The test suite aims for comprehensive coverage of:

- ✅ SDK initialization and configuration
- ✅ All service classes and methods
- ✅ Model classes and properties
- ✅ Error handling and exceptions
- ✅ Hook system functionality
- ✅ Utility classes and helpers
- ✅ Retry configuration and logic

## Writing New Tests

### Unit Test Example

```php
<?php

declare(strict_types=1);

namespace FastPix\Sdk\Tests\Unit;

use FastPix\Sdk\YourClass;
use PHPUnit\Framework\TestCase;

class YourClassTest extends TestCase
{
    public function testYourMethod(): void
    {
        $instance = new YourClass();
        $result = $instance->yourMethod();
        
        $this->assertNotNull($result);
    }
}
```

### Integration Test Example

```php
<?php

declare(strict_types=1);

namespace FastPix\Sdk\Tests\Integration;

use FastPix\Sdk\SDK;
use FastPix\Sdk\Models\Components\Security;
use PHPUnit\Framework\TestCase;

class YourServiceTest extends TestCase
{
    private SDK $sdk;

    protected function setUp(): void
    {
        $this->sdk = SDK::builder()
            ->setSecurity(new Security(username: 'test', password: 'test'))
            ->build();
    }

    public function testYourOperation(): void
    {
        // Test your operation here
        $this->assertTrue(true);
    }
}
```

## Best Practices

1. **Test Naming**: Use descriptive test method names that explain what is being tested
2. **Arrange-Act-Assert**: Structure tests with clear setup, execution, and verification
3. **Mock External Dependencies**: Use mocks for HTTP clients and external services
4. **Test Edge Cases**: Include tests for error conditions and edge cases
5. **Keep Tests Independent**: Each test should be able to run independently
6. **Use Data Providers**: For testing multiple scenarios with the same logic

## Continuous Integration

The test suite is designed to run in CI/CD environments:

- All tests should pass without external dependencies
- Integration tests use mock credentials
- Tests are fast and reliable
- Coverage reports are generated automatically

## Troubleshooting

### Common Issues

1. **Autoloader Issues**: Ensure `composer install` has been run
2. **Missing Dependencies**: Check that all required packages are installed
3. **PHP Version**: Ensure you're using PHP 8.2 or higher
4. **Memory Issues**: Increase memory limit if needed: `php -d memory_limit=2G`

### Debug Mode

Run tests with verbose output:

```bash
./vendor/bin/phpunit --verbose
```

### Test Specific Issues

If a specific test is failing:

```bash
# Run with debug output
./vendor/bin/phpunit --debug Tests/Unit/SDKTest.php

# Run with stop on failure
./vendor/bin/phpunit --stop-on-failure Tests/Unit/SDKTest.php
```
