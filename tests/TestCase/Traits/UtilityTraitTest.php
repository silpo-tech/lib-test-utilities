<?php

declare(strict_types=1);

namespace App\Tests\TestCase\Traits;

use FT\Lib\TestUtilities\TestCase\Traits\UtilityTrait;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Closure;

class UtilityTraitTest extends TestCase
{
    private object $mock;

    protected function setUp(): void
    {
        $this->mock = new class {
            use UtilityTrait;

            public function testResolveValues(array ...$params): array
            {
                return self::resolveValues(...$params);
            }

            public function testResolveValue($value)
            {
                return self::resolveValue($value);
            }

            public function testPrepareArrayWithoutKeys(array $array, array $skipKeys = []): array
            {
                return self::prepareArrayWithoutKeys($array, $skipKeys);
            }

            public function testPrepareArrayWithHashedKeys(array $array): array
            {
                return self::prepareArrayWithHashedKeys($array);
            }
        };
    }

    #[DataProvider('resolveValuesDataProvider')]
    public function testResolveValues(array $input, array $expected): void
    {
        self::assertEquals($expected, $this->mock->testResolveValues(...$input));
    }

    public static function resolveValuesDataProvider(): \Generator
    {
        yield [
            [[1, 2, 3], [4, Closure::fromCallable(fn() => 5)]],
            [[1, 2, 3], [4, 5]],
        ];

        yield [
            [[10, 20], [30, Closure::fromCallable(fn() => 40)]],
            [[10, 20], [30, 40]],
        ];
    }

    #[DataProvider('resolveValueDataProvider')]
    public function testResolveValue($input, $expected): void
    {
        self::assertEquals($expected, $this->mock->testResolveValue($input));
    }

    public static function resolveValueDataProvider(): \Generator
    {
        yield [
            [1, Closure::fromCallable(fn() => 2), [3, Closure::fromCallable(fn() => [4, 5])]],
            [1, 2, [3, [4, 5]]],
        ];

        yield [
            [10, Closure::fromCallable(fn() => 20), [30, Closure::fromCallable(fn() => [40, 50])]],
            [10, 20, [30, [40, 50]]],
        ];
    }

    #[DataProvider('prepareArrayWithoutKeysDataProvider')]
    public function testPrepareArrayWithoutKeys(array $array, array $skipKeys, array $expected): void
    {
        self::assertEquals($expected, $this->mock->testPrepareArrayWithoutKeys($array, $skipKeys));
    }

    public static function prepareArrayWithoutKeysDataProvider(): \Generator
    {
        yield [
            [
                'key1' => 'value1',
                'key2' => 'value2',
                'key3' => [
                    'subkey1' => 'subvalue1',
                    'subkey2' => 'subvalue2',
                ],
            ],
            ['key2', 'subkey2'],
            [
                'key1' => 'value1',
                'key3' => [
                    'subkey1' => 'subvalue1',
                ],
            ],
        ];

        yield [
            [
                'key1' => 'value1',
                'key2' => 'value2',
                'key3' => [
                    'subkey1' => 'subvalue1',
                    'subkey2' => 'subvalue2',
                ],
            ],
            ['key1'],
            [
                'key2' => 'value2',
                'key3' => [
                    'subkey1' => 'subvalue1',
                    'subkey2' => 'subvalue2',
                ],
            ],
        ];
    }

    #[DataProvider('prepareArrayWithHashedKeysDataProvider')]
    public function testPrepareArrayWithHashedKeys(array $array, array $expected): void
    {
        $result = $this->mock->testPrepareArrayWithHashedKeys($array);

        self::assertEquals($expected, $result);
        self::assertEquals(array_keys($expected), array_keys($result)); // Ensure keys are hashed properly.
    }

    public static function prepareArrayWithHashedKeysDataProvider(): \Generator
    {
        yield [
            [
                'key1' => 'value1',
                'key2' => [
                    'subkey1' => 'subvalue1',
                    'subkey2' => 'subvalue2',
                ],
                'key3' => 'value3',
            ],
            [
                'key1' => 'value1',
                'key2' => [
                    'subkey1' => 'subvalue1',
                    'subkey2' => 'subvalue2',
                ],
                'key3' => 'value3',
            ]
        ];

        yield [
            [
               [ 'key1' => 'valueA'],
            ],
            [
                // md5 hash
                 'cf9f9f3cb245f9418f0aba4567e935be'  => [
                     'key1' => 'valueA'
                 ],
            ]
        ];

        yield [
            [
                [ 1 => 'valueA'],
            ],
            [
                // md5 hash
                '15b1c67009f3294d79013e7cad409c68'  => [
                    'valueA' => 'valueA'
                ],
            ]
        ];
    }

    public function testCopyInstanceOnBefore(): void
    {
        $reflection = new \ReflectionClass($this->mock);
        $method = $reflection->getMethod('copyInstanceOnBefore');
        $method->invoke($this->mock);

        self::assertSame($this->mock, $reflection->getStaticPropertyValue('_this'));
    }
}