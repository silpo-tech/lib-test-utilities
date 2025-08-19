<?php

declare(strict_types=1);

namespace SilpoTech\Lib\TestUtilities\TestCase\Traits;

use PHPUnit\Framework\Attributes\Before;

trait UtilityTrait
{
    private static ?self $_this = null;

    #[Before]
    public function copyInstanceOnBefore(): void
    {
        self::$_this = $this;
    }

    private static function resolveValues(array ...$params): array
    {
        foreach ($params as &$value) {
            $value = self::resolveValue($value);
        }

        return $params;
    }

    private static function resolveValue($value)
    {
        if (is_array($value)) {
            foreach ($value as &$subValue) {
                $subValue = self::resolveValue($subValue);
            }
        }

        if ($value instanceof \Closure) {
            $value = $value();
        }

        return $value;
    }

    private static function prepareArrayWithoutKeys(array $array, array $skipKeys = []): array
    {
        $result = [];

        foreach ($array as $key => $value) {
            if (!in_array($key, $skipKeys)) {
                $result[$key] = is_array($value) ? self::prepareArrayWithoutKeys($value, $skipKeys) : $value;
            }
        }

        return $result;
    }

    private static function prepareArrayWithHashedKeys(array $array): array
    {
        $hashedKeysArray = [];

        foreach ($array as $key => $value) {
            if (is_string($key)) {
                if (!is_array($value) || empty($value)) {
                    $hashedKeysArray[$key] = $value;

                    continue;
                }

                self::prepareArrayWithHashedKeys($value);
            }

            if (!is_array($value)) {
                $hashedKeysArray[(string) $value] = $value;

                continue;
            }

            $hashedValue = self::prepareArrayWithHashedKeys($value);
            ksort($hashedValue);

            $newKey = is_string($key) ? $key : md5(json_encode($hashedValue));

            $hashedKeysArray[$newKey] = $hashedValue;
        }

        return $hashedKeysArray;
    }
}
