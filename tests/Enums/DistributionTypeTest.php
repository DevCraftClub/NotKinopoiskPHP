<?php

declare(strict_types=1);

namespace Tests\Enums;

use NotKinopoisk\Enums\DistributionType;
use PHPUnit\Framework\TestCase;

/**
 * Тесты для enum DistributionType
 * 
 * @package Tests\Enums
 * @author Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @since 1.0.0
 */
class DistributionTypeTest extends TestCase
{
    /**
     * Тест создания enum из строки
     */
    public function testFromString(): void
    {
        $this->assertEquals(DistributionType::CINEMA, DistributionType::from('CINEMA'));
        $this->assertEquals(DistributionType::DVD, DistributionType::from('DVD'));
        $this->assertEquals(DistributionType::BLURAY, DistributionType::from('BLURAY'));
        $this->assertEquals(DistributionType::DIGITAL, DistributionType::from('DIGITAL'));
        $this->assertEquals(DistributionType::TV, DistributionType::from('TV'));
        $this->assertEquals(DistributionType::STREAMING, DistributionType::from('STREAMING'));
    }

    /**
     * Тест получения значения enum
     */
    public function testGetValue(): void
    {
        $this->assertEquals('CINEMA', DistributionType::CINEMA->value);
        $this->assertEquals('DVD', DistributionType::DVD->value);
        $this->assertEquals('BLURAY', DistributionType::BLURAY->value);
        $this->assertEquals('DIGITAL', DistributionType::DIGITAL->value);
        $this->assertEquals('TV', DistributionType::TV->value);
        $this->assertEquals('STREAMING', DistributionType::STREAMING->value);
    }

    /**
     * Тест метода isCinema()
     */
    public function testIsCinema(): void
    {
        $this->assertTrue(DistributionType::CINEMA->isCinema());
        $this->assertFalse(DistributionType::DVD->isCinema());
        $this->assertFalse(DistributionType::BLURAY->isCinema());
        $this->assertFalse(DistributionType::DIGITAL->isCinema());
        $this->assertFalse(DistributionType::TV->isCinema());
        $this->assertFalse(DistributionType::STREAMING->isCinema());
    }

    /**
     * Тест метода isHomeVideo()
     */
    public function testIsHomeVideo(): void
    {
        $this->assertFalse(DistributionType::CINEMA->isHomeVideo());
        $this->assertTrue(DistributionType::DVD->isHomeVideo());
        $this->assertTrue(DistributionType::BLURAY->isHomeVideo());
        $this->assertFalse(DistributionType::DIGITAL->isHomeVideo());
        $this->assertFalse(DistributionType::TV->isHomeVideo());
        $this->assertFalse(DistributionType::STREAMING->isHomeVideo());
    }

    /**
     * Тест метода isDigital()
     */
    public function testIsDigital(): void
    {
        $this->assertFalse(DistributionType::CINEMA->isDigital());
        $this->assertFalse(DistributionType::DVD->isDigital());
        $this->assertFalse(DistributionType::BLURAY->isDigital());
        $this->assertTrue(DistributionType::DIGITAL->isDigital());
        $this->assertFalse(DistributionType::TV->isDigital());
        $this->assertTrue(DistributionType::STREAMING->isDigital());
    }

    /**
     * Тест метода getDisplayName()
     */
    public function testGetDisplayName(): void
    {
        $this->assertEquals('Кинотеатральный прокат', DistributionType::CINEMA->getDisplayName());
        $this->assertEquals('DVD прокат', DistributionType::DVD->getDisplayName());
        $this->assertEquals('Blu-ray прокат', DistributionType::BLURAY->getDisplayName());
        $this->assertEquals('Цифровой прокат', DistributionType::DIGITAL->getDisplayName());
        $this->assertEquals('Телевизионный прокат', DistributionType::TV->getDisplayName());
        $this->assertEquals('Стриминговый прокат', DistributionType::STREAMING->getDisplayName());
    }

    /**
     * Тест исключения при неверном значении
     */
    public function testInvalidValueThrowsException(): void
    {
        $this->expectException(\ValueError::class);
        DistributionType::from('INVALID_TYPE');
    }

    /**
     * Тест сравнения enum значений
     */
    public function testEnumComparison(): void
    {
        $cinema1 = DistributionType::CINEMA;
        $cinema2 = DistributionType::CINEMA;
        $dvd = DistributionType::DVD;

        $this->assertTrue($cinema1 === $cinema2);
        $this->assertTrue($cinema1 === DistributionType::CINEMA);
        $this->assertTrue($dvd === DistributionType::DVD);
        $this->assertTrue($cinema1 !== $dvd);
    }

    /**
     * Тест всех доступных значений enum
     */
    public function testAllEnumValues(): void
    {
        $expectedValues = [
            'ALL',
            'CINEMA',
            'DVD',
            'BLURAY',
            'DIGITAL',
            'TV',
            'STREAMING',
            'COUNTRY_SPECIFIC',
            'PREMIERE',
            'WORLD_PREMIER'
        ];

        $actualValues = array_map(fn($case) => $case->value, DistributionType::cases());
        $this->assertEquals($expectedValues, $actualValues);
    }
} 