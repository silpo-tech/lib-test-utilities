<?php

namespace App\Tests\JsonSchema\Traits;

use SilpoTech\Lib\TestUtilities\JsonSchema\Traits\CollectionSchema;
use PHPUnit\Framework\TestCase;

class CollectionSchemaTest extends TestCase
{
    public function testGetCollectionJsonSchema(): void
    {
        $mock = new class {
            use CollectionSchema;

            public static function getCollectionItemJsonSchema(): array
            {
                return [
                    'type' => 'object',
                    'properties' => [
                        'id' => [
                            'type' => 'integer',
                        ],
                        'name' => [
                            'type' => 'string',
                        ],
                    ],
                    'required' => ['id', 'name'],
                ];
            }
        };

        $expected = [
            'type' => 'object',
            'properties' => [
                'items' => [
                    'type' => 'array',
                    'items' => [
                        'type' => 'object',
                        'properties' => [
                            'id' => ['type' => 'integer'],
                            'name' => ['type' => 'string'],
                        ],
                        'required' => ['id', 'name'],
                    ],
                ],
                'total' => [
                    'type' => 'integer',
                ],
            ],
            'required' => ['items', 'total'],
            'additionalProperties' => false,
        ];

        self::assertEquals($expected, $mock::getCollectionJsonSchema());
    }

    public function testGetPaginatedCollectionJsonSchema(): void
    {
        $mock = new class {
            use CollectionSchema;

            public static function getCollectionItemJsonSchema(): array
            {
                return [
                    'type' => 'object',
                    'properties' => [
                        'id' => [
                            'type' => 'integer',
                        ],
                        'name' => [
                            'type' => 'string',
                        ],
                    ],
                    'required' => ['id', 'name'],
                ];
            }
        };

        $expected = [
            'type' => 'object',
            'properties' => [
                'items' => [
                    'type' => 'array',
                    'items' => [
                        'type' => 'object',
                        'properties' => [
                            'id' => ['type' => 'integer'],
                            'name' => ['type' => 'string'],
                        ],
                        'required' => ['id', 'name'],
                    ],
                ],
                'total' => [
                    'type' => 'integer',
                ],
                'limit' => [
                    'type' => 'integer',
                ],
                'offset' => [
                    'type' => 'integer',
                ],
            ],
            'required' => ['items', 'total', 'limit', 'offset'],
            'additionalProperties' => false,
        ];

        self::assertEquals($expected, $mock::getPaginatedCollectionJsonSchema());
    }
}
