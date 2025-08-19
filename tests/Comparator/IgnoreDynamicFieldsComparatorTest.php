<?php

declare(strict_types=1);

namespace App\Tests\Comparator;

use SilpoTech\Lib\TestUtilities\Comparator\IgnoreDynamicFieldsComparator;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\Comparator\ComparisonFailure;
use SebastianBergmann\Comparator\Factory;
use stdClass;

class IgnoreDynamicFieldsComparatorTest extends TestCase
{
    public function testAcceptsReturnsTrueForSpecifiedClasses(): void
    {
        $comparator = new IgnoreDynamicFieldsComparator([stdClass::class]);

        $object1 = new stdClass();
        $object2 = new stdClass();

        self::assertTrue($comparator->accepts($object1, $object2));
    }

    public function testAcceptsReturnsFalseForDifferentClasses(): void
    {
        $comparator = new IgnoreDynamicFieldsComparator([stdClass::class]);

        $object1 = new stdClass();
        $object2 = new class {};

        self::assertFalse($comparator->accepts($object1, $object2));
    }

    public function testToArrayRemovesSpecifiedProperties(): void
    {
        $comparator = new IgnoreDynamicFieldsComparator([]);

        $object = new class {
            public $id = 1;
            public $name = 'Test';
            public $createdAt = '2025-01-22';
            public $updatedAt = '2025-01-23';
        };

        $expectedArray = [
            'id' => 1,
            'name' => 'Test',
        ];

        $reflection = new \ReflectionClass($comparator);
        $method = $reflection->getMethod('toArray');
        $result = $method->invoke($comparator, $object);

        self::assertEquals($expectedArray, $result);
    }

    public function testCompareIgnoresSpecifiedProperties(): void
    {
        $comparator = new IgnoreDynamicFieldsComparator([], ['createdAt', 'updatedAt']);
        $comparator->setFactory(new Factory());

        $object1 = new class {
            public $id = 1;
            public $name = 'Test';
            public $createdAt = '2025-01-01';
            public $updatedAt = '2025-01-02';
        };

        $object2 = clone $object1;
        $object2->createdAt = '2024-01-01';
        $object2->updatedAt = '2024-01-02';

        // This should not throw an exception since createdAt and updatedAt are ignored
        $comparator->assertEquals($object1, $object2);
        
        // If we reach this point, the comparison succeeded as expected
        $this->addToAssertionCount(1);
    }

    public function testCompareThrowsExceptionForDifferentValues(): void
    {
        $comparator = new IgnoreDynamicFieldsComparator([]);
        $comparator->setFactory(new Factory());
        $object1 = new class {
            public $id = 1;
            public $name = 'Test1';
            public $createdAt = '2025-01-01';
            public $updatedAt = '2025-01-02';
        };

        $object2 = clone $object1;
        $object2->name = 'Test2';

        self::expectException(ComparisonFailure::class);
        self::expectExceptionMessage('Failed asserting that two objects are equal');

        $comparator->assertEquals($object1, $object2);
    }
}