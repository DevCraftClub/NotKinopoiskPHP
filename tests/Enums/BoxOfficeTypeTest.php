<?php

declare(strict_types=1);

namespace Tests\Enums;

use NotKinopoisk\Enums\BoxOfficeType;
use PHPUnit\Framework\TestCase;

/**
 * Тесты для enum BoxOfficeType
 * 
 * @package Tests\Enums
 * @author Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @since 1.0.0
 */
class BoxOfficeTypeTest extends TestCase
{
    /**
     * Тест создания enum из строки
     */
    public function testFromString(): void
    {
        $this->assertEquals(BoxOfficeType::BUDGET, BoxOfficeType::from('BUDGET'));
        $this->assertEquals(BoxOfficeType::RUS, BoxOfficeType::from('RUS'));
        $this->assertEquals(BoxOfficeType::USA, BoxOfficeType::from('USA'));
        $this->assertEquals(BoxOfficeType::WORLD, BoxOfficeType::from('WORLD'));
        $this->assertEquals(BoxOfficeType::MARKETING, BoxOfficeType::from('MARKETING'));
    }

    /**
     * Тест получения значения enum
     */
    public function testGetValue(): void
    {
        $this->assertEquals('BUDGET', BoxOfficeType::BUDGET->value);
        $this->assertEquals('RUS', BoxOfficeType::RUS->value);
        $this->assertEquals('USA', BoxOfficeType::USA->value);
        $this->assertEquals('WORLD', BoxOfficeType::WORLD->value);
        $this->assertEquals('MARKETING', BoxOfficeType::MARKETING->value);
    }

    /**
     * Тест метода isBudget()
     */
    public function testIsBudget(): void
    {
        $this->assertTrue(BoxOfficeType::BUDGET->isBudget());
        $this->assertFalse(BoxOfficeType::RUS->isBudget());
        $this->assertFalse(BoxOfficeType::USA->isBudget());
        $this->assertFalse(BoxOfficeType::WORLD->isBudget());
        $this->assertFalse(BoxOfficeType::MARKETING->isBudget());
    }

    /**
     * Тест метода isRevenue()
     */
    public function testIsRevenue(): void
    {
        $this->assertFalse(BoxOfficeType::BUDGET->isRevenue());
        $this->assertTrue(BoxOfficeType::RUS->isRevenue());
        $this->assertTrue(BoxOfficeType::USA->isRevenue());
        $this->assertTrue(BoxOfficeType::WORLD->isRevenue());
        $this->assertFalse(BoxOfficeType::MARKETING->isRevenue());
    }

    /**
     * Тест метода getDisplayName()
     */
    public function testGetDisplayName(): void
    {
        $this->assertEquals('Бюджет', BoxOfficeType::BUDGET->getDisplayName());
        $this->assertEquals('Сборы в России', BoxOfficeType::RUS->getDisplayName());
        $this->assertEquals('Сборы в США', BoxOfficeType::USA->getDisplayName());
        $this->assertEquals('Мировые сборы', BoxOfficeType::WORLD->getDisplayName());
        $this->assertEquals('Средства спущенные на маркетинг', BoxOfficeType::MARKETING->getDisplayName());
    }

    /**
     * Тест исключения при неверном значении
     */
    public function testInvalidValueThrowsException(): void
    {
        $this->expectException(\ValueError::class);
        BoxOfficeType::from('INVALID_TYPE');
    }

    /**
     * Тест сравнения enum значений
     */
    public function testEnumComparison(): void
    {
        $budget1 = BoxOfficeType::BUDGET;
        $budget2 = BoxOfficeType::BUDGET;
        $rus = BoxOfficeType::RUS;

        $this->assertTrue($budget1 === $budget2);
        $this->assertTrue($budget1 === BoxOfficeType::BUDGET);
        $this->assertTrue($rus === BoxOfficeType::RUS);
        $this->assertTrue($budget1 !== $rus);
    }

    /**
     * Тест всех доступных значений enum
     */
    public function testAllEnumValues(): void
    {
        $expectedValues = [
            'BUDGET',
            'RUS',
            'USA',
            'WORLD',
            'MARKETING'
        ];

        $actualValues = array_map(fn($case) => $case->value, BoxOfficeType::cases());
        $this->assertEquals($expectedValues, $actualValues);
    }
} 