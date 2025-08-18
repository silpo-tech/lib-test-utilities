# Library Test Utilities #

## About ##

Library Test Utilities contains additional test utilities

## Installation ##

Require package and its dependencies with composer:

```bash
$ composer require silpo-tech/lib-test-utilities --dev
```

Usage for Comparator

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

### Tests
To run the test suite, you need to install the dependencies:

```bash
composer install
```
Run test suite:

```bash
composer test:run
```
Run test suite with coverage (requires pcov extension)
```bash
composer test:coverage
```