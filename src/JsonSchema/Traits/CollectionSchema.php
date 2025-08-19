<?php

declare(strict_types=1);

namespace SilpoTech\Lib\TestUtilities\JsonSchema\Traits;

trait CollectionSchema
{
    public static function getCollectionJsonSchema(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'items' => [
                    'type' => 'array',
                    'items' => self::getCollectionItemJsonSchema(),
                ],
                'total' => [
                    'type' => 'integer',
                ],
            ],
            'required' => [
                'items',
                'total',
            ],
            'additionalProperties' => false,
        ];
    }

    public static function getPaginatedCollectionJsonSchema(): array
    {
        return array_merge_recursive(self::getCollectionJsonSchema(), [
            'properties' => [
                'limit' => [
                    'type' => 'integer',
                ],
                'offset' => [
                    'type' => 'integer',
                ],
            ],
            'required' => [
                'limit',
                'offset',
            ],
        ]);
    }

    abstract public static function getCollectionItemJsonSchema(): array;
}
