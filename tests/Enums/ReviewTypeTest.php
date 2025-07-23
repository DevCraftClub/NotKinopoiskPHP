<?php

declare(strict_types=1);

namespace Tests\Enums;

use NotKinopoisk\Enums\ReviewType;
use PHPUnit\Framework\TestCase;

/**
 * Тесты для enum ReviewType
 * 
 * @package Tests\Enums
 * @author Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @since 1.0.0
 */
class ReviewTypeTest extends TestCase
{
    /**
     * Тест создания enum из строки
     */
    public function testFromString(): void
    {
        $this->assertEquals(ReviewType::POSITIVE, ReviewType::from('POSITIVE'));
        $this->assertEquals(ReviewType::NEGATIVE, ReviewType::from('NEGATIVE'));
        $this->assertEquals(ReviewType::NEUTRAL, ReviewType::from('NEUTRAL'));
    }

    /**
     * Тест получения значения enum
     */
    public function testGetValue(): void
    {
        $this->assertEquals('POSITIVE', ReviewType::POSITIVE->value);
        $this->assertEquals('NEGATIVE', ReviewType::NEGATIVE->value);
        $this->assertEquals('NEUTRAL', ReviewType::NEUTRAL->value);
    }

    /**
     * Тест метода isPositive()
     */
    public function testIsPositive(): void
    {
        $this->assertTrue(ReviewType::POSITIVE->isPositive());
        $this->assertFalse(ReviewType::NEGATIVE->isPositive());
        $this->assertFalse(ReviewType::NEUTRAL->isPositive());
    }

    /**
     * Тест метода isNegative()
     */
    public function testIsNegative(): void
    {
        $this->assertFalse(ReviewType::POSITIVE->isNegative());
        $this->assertTrue(ReviewType::NEGATIVE->isNegative());
        $this->assertFalse(ReviewType::NEUTRAL->isNegative());
    }

    /**
     * Тест метода getDisplayName()
     */
    public function testGetDisplayName(): void
    {
        $this->assertEquals('Положительная', ReviewType::POSITIVE->getDisplayName());
        $this->assertEquals('Отрицательная', ReviewType::NEGATIVE->getDisplayName());
        $this->assertEquals('Нейтральная', ReviewType::NEUTRAL->getDisplayName());
    }

    /**
     * Тест исключения при неверном значении
     */
    public function testInvalidValueThrowsException(): void
    {
        $this->expectException(\ValueError::class);
        ReviewType::from('INVALID_TYPE');
    }

    /**
     * Тест сравнения enum значений
     */
    public function testEnumComparison(): void
    {
        $positive1 = ReviewType::POSITIVE;
        $positive2 = ReviewType::POSITIVE;
        $negative = ReviewType::NEGATIVE;

        $this->assertTrue($positive1 === $positive2);
        $this->assertTrue($positive1 === ReviewType::POSITIVE);
        $this->assertTrue($negative === ReviewType::NEGATIVE);
        $this->assertTrue($positive1 !== $negative);
    }

    /**
     * Тест всех доступных значений enum
     */
    public function testAllEnumValues(): void
    {
        $expectedValues = [
            'POSITIVE',
            'NEGATIVE',
            'NEUTRAL'
        ];

        $actualValues = array_map(fn($case) => $case->value, ReviewType::cases());
        $this->assertEquals($expectedValues, $actualValues);
    }
} 