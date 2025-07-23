<?php

declare(strict_types=1);

namespace Tests\Enums;

use NotKinopoisk\Enums\FactType;
use PHPUnit\Framework\TestCase;

/**
 * Тесты для enum FactType
 * 
 * @package Tests\Enums
 * @author Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @since 1.0.0
 */
class FactTypeTest extends TestCase
{
    /**
     * Тест создания enum из строки
     */
    public function testFromString(): void
    {
        $this->assertEquals(FactType::FACT, FactType::from('FACT'));
        $this->assertEquals(FactType::BLOOPER, FactType::from('BLOOPER'));
    }

    /**
     * Тест получения значения enum
     */
    public function testGetValue(): void
    {
        $this->assertEquals('FACT', FactType::FACT->value);
        $this->assertEquals('BLOOPER', FactType::BLOOPER->value);
    }

    /**
     * Тест метода isFact()
     */
    public function testIsFact(): void
    {
        $this->assertTrue(FactType::FACT->isFact());
        $this->assertFalse(FactType::BLOOPER->isFact());
    }

    /**
     * Тест метода isBlooper()
     */
    public function testIsBlooper(): void
    {
        $this->assertFalse(FactType::FACT->isBlooper());
        $this->assertTrue(FactType::BLOOPER->isBlooper());
    }

    /**
     * Тест метода getDisplayName()
     */
    public function testGetDisplayName(): void
    {
        $this->assertEquals('Интересный факт', FactType::FACT->getDisplayName());
        $this->assertEquals('Ошибка в фильме', FactType::BLOOPER->getDisplayName());
    }

    /**
     * Тест исключения при неверном значении
     */
    public function testInvalidValueThrowsException(): void
    {
        $this->expectException(\ValueError::class);
        FactType::from('INVALID_TYPE');
    }

    /**
     * Тест сравнения enum значений
     */
    public function testEnumComparison(): void
    {
        $fact1 = FactType::FACT;
        $fact2 = FactType::FACT;
        $blooper = FactType::BLOOPER;

        $this->assertTrue($fact1 === $fact2);
        $this->assertTrue($fact1 === FactType::FACT);
        $this->assertTrue($blooper === FactType::BLOOPER);
        $this->assertTrue($fact1 !== $blooper);
    }

    /**
     * Тест всех доступных значений enum
     */
    public function testAllEnumValues(): void
    {
        $expectedValues = [
            'FACT',
            'BLOOPER'
        ];

        $actualValues = array_map(fn($case) => $case->value, FactType::cases());
        $this->assertEquals($expectedValues, $actualValues);
    }
} 