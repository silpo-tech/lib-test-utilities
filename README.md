# Library Test Utilities

[![CI](https://github.com/silpo-tech/lib-test-utilities/workflows/CI/badge.svg)](https://github.com/silpo-tech/lib-test-utilities/actions)
[![codecov](https://codecov.io/gh/silpo-tech/lib-test-utilities/branch/master/graph/badge.svg)](https://codecov.io/gh/silpo-tech/lib-test-utilities)
[![Latest Stable Version](https://poser.pugx.org/silpo-tech/lib-test-utilities/v/stable)](https://packagist.org/packages/silpo-tech/lib-test-utilities)
[![License](https://poser.pugx.org/silpo-tech/lib-test-utilities/license)](https://packagist.org/packages/silpo-tech/lib-test-utilities)

## About

Library Test Utilities contains additional test utilities for PHP projects.

## Installation

Require package and its dependencies with composer:

```bash
composer require silpo-tech/lib-test-utilities --dev
```

## Usage

### Comparator

```php
protected function setUp(): void
{
    Factory::getInstance()->register(
        new IgnoreDynamicFieldsComparator(classes: [Recurring::class], properties: [
            'id',
            'createdAt',
            'updatedAt',
        ]),
    );
}

protected function tearDown(): void
{
    parent::tearDown();
    Factory::getInstance()->reset();
}
```

## Development

### Tests

To run the test suite, you need to install the dependencies:

```bash
composer install
```

Run test suite:

```bash
composer test:run
```

Run test suite with coverage (requires pcov extension):

```bash
composer test:coverage
```

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.