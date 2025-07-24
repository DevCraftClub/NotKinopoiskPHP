<?php

declare(strict_types=1);

namespace Tests\Enums;

use NotKinopoisk\Enums\Month;
use PHPUnit\Framework\TestCase;

/**
 * Тесты для enum Month
 * 
 * @package Tests\Enums
 * @author Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @since 1.0.0
 */
class MonthTest extends TestCase
{
    /**
     * Тест всех значений enum
     */
    public function testAllValues(): void
    {
        $this->assertEquals('JANUARY', Month::JANUARY->value);
        $this->assertEquals('FEBRUARY', Month::FEBRUARY->value);
        $this->assertEquals('MARCH', Month::MARCH->value);
        $this->assertEquals('APRIL', Month::APRIL->value);
        $this->assertEquals('MAY', Month::MAY->value);
        $this->assertEquals('JUNE', Month::JUNE->value);
        $this->assertEquals('JULY', Month::JULY->value);
        $this->assertEquals('AUGUST', Month::AUGUST->value);
        $this->assertEquals('SEPTEMBER', Month::SEPTEMBER->value);
        $this->assertEquals('OCTOBER', Month::OCTOBER->value);
        $this->assertEquals('NOVEMBER', Month::NOVEMBER->value);
        $this->assertEquals('DECEMBER', Month::DECEMBER->value);
    }

    /**
     * Тест метода getDisplayName()
     */
    public function testGetDisplayName(): void
    {
        $this->assertEquals('Январь', Month::JANUARY->getDisplayName());
        $this->assertEquals('Февраль', Month::FEBRUARY->getDisplayName());
        $this->assertEquals('Март', Month::MARCH->getDisplayName());
        $this->assertEquals('Апрель', Month::APRIL->getDisplayName());
        $this->assertEquals('Май', Month::MAY->getDisplayName());
        $this->assertEquals('Июнь', Month::JUNE->getDisplayName());
        $this->assertEquals('Июль', Month::JULY->getDisplayName());
        $this->assertEquals('Август', Month::AUGUST->getDisplayName());
        $this->assertEquals('Сентябрь', Month::SEPTEMBER->getDisplayName());
        $this->assertEquals('Октябрь', Month::OCTOBER->getDisplayName());
        $this->assertEquals('Ноябрь', Month::NOVEMBER->getDisplayName());
        $this->assertEquals('Декабрь', Month::DECEMBER->getDisplayName());
    }

    /**
     * Тест метода getShortName()
     */
    public function testGetShortName(): void
    {
        $this->assertEquals('Янв', Month::JANUARY->getShortName());
        $this->assertEquals('Фев', Month::FEBRUARY->getShortName());
        $this->assertEquals('Мар', Month::MARCH->getShortName());
        $this->assertEquals('Апр', Month::APRIL->getShortName());
        $this->assertEquals('Май', Month::MAY->getShortName());
        $this->assertEquals('Июн', Month::JUNE->getShortName());
        $this->assertEquals('Июл', Month::JULY->getShortName());
        $this->assertEquals('Авг', Month::AUGUST->getShortName());
        $this->assertEquals('Сен', Month::SEPTEMBER->getShortName());
        $this->assertEquals('Окт', Month::OCTOBER->getShortName());
        $this->assertEquals('Ноя', Month::NOVEMBER->getShortName());
        $this->assertEquals('Дек', Month::DECEMBER->getShortName());
    }

    /**
     * Тест метода getNumber()
     */
    public function testGetNumber(): void
    {
        $this->assertEquals(1, Month::JANUARY->getNumber());
        $this->assertEquals(2, Month::FEBRUARY->getNumber());
        $this->assertEquals(3, Month::MARCH->getNumber());
        $this->assertEquals(4, Month::APRIL->getNumber());
        $this->assertEquals(5, Month::MAY->getNumber());
        $this->assertEquals(6, Month::JUNE->getNumber());
        $this->assertEquals(7, Month::JULY->getNumber());
        $this->assertEquals(8, Month::AUGUST->getNumber());
        $this->assertEquals(9, Month::SEPTEMBER->getNumber());
        $this->assertEquals(10, Month::OCTOBER->getNumber());
        $this->assertEquals(11, Month::NOVEMBER->getNumber());
        $this->assertEquals(12, Month::DECEMBER->getNumber());
    }

    /**
     * Тест методов сезонов
     */
    public function testSeasons(): void
    {
        // Зимние месяцы
        $this->assertTrue(Month::DECEMBER->isWinter());
        $this->assertTrue(Month::JANUARY->isWinter());
        $this->assertTrue(Month::FEBRUARY->isWinter());
        $this->assertFalse(Month::MARCH->isWinter());

        // Весенние месяцы
        $this->assertTrue(Month::MARCH->isSpring());
        $this->assertTrue(Month::APRIL->isSpring());
        $this->assertTrue(Month::MAY->isSpring());
        $this->assertFalse(Month::JUNE->isSpring());

        // Летние месяцы
        $this->assertTrue(Month::JUNE->isSummer());
        $this->assertTrue(Month::JULY->isSummer());
        $this->assertTrue(Month::AUGUST->isSummer());
        $this->assertFalse(Month::SEPTEMBER->isSummer());

        // Осенние месяцы
        $this->assertTrue(Month::SEPTEMBER->isAutumn());
        $this->assertTrue(Month::OCTOBER->isAutumn());
        $this->assertTrue(Month::NOVEMBER->isAutumn());
        $this->assertFalse(Month::DECEMBER->isAutumn());
    }

    /**
     * Тест метода getSeason()
     */
    public function testGetSeason(): void
    {
        $this->assertEquals('Зима', Month::JANUARY->getSeason());
        $this->assertEquals('Зима', Month::FEBRUARY->getSeason());
        $this->assertEquals('Весна', Month::MARCH->getSeason());
        $this->assertEquals('Весна', Month::APRIL->getSeason());
        $this->assertEquals('Весна', Month::MAY->getSeason());
        $this->assertEquals('Лето', Month::JUNE->getSeason());
        $this->assertEquals('Лето', Month::JULY->getSeason());
        $this->assertEquals('Лето', Month::AUGUST->getSeason());
        $this->assertEquals('Осень', Month::SEPTEMBER->getSeason());
        $this->assertEquals('Осень', Month::OCTOBER->getSeason());
        $this->assertEquals('Осень', Month::NOVEMBER->getSeason());
        $this->assertEquals('Зима', Month::DECEMBER->getSeason());
    }

    /**
     * Тест методов кварталов
     */
    public function testQuarters(): void
    {
        // Первые месяцы кварталов
        $this->assertTrue(Month::JANUARY->isQuarterStart());
        $this->assertTrue(Month::APRIL->isQuarterStart());
        $this->assertTrue(Month::JULY->isQuarterStart());
        $this->assertTrue(Month::OCTOBER->isQuarterStart());
        $this->assertFalse(Month::FEBRUARY->isQuarterStart());

        // Номера кварталов
        $this->assertEquals(1, Month::JANUARY->getQuarter());
        $this->assertEquals(1, Month::FEBRUARY->getQuarter());
        $this->assertEquals(1, Month::MARCH->getQuarter());
        $this->assertEquals(2, Month::APRIL->getQuarter());
        $this->assertEquals(2, Month::MAY->getQuarter());
        $this->assertEquals(2, Month::JUNE->getQuarter());
        $this->assertEquals(3, Month::JULY->getQuarter());
        $this->assertEquals(3, Month::AUGUST->getQuarter());
        $this->assertEquals(3, Month::SEPTEMBER->getQuarter());
        $this->assertEquals(4, Month::OCTOBER->getQuarter());
        $this->assertEquals(4, Month::NOVEMBER->getQuarter());
        $this->assertEquals(4, Month::DECEMBER->getQuarter());
    }

    /**
     * Тест методов начала и конца года
     */
    public function testYearStartEnd(): void
    {
        $this->assertTrue(Month::JANUARY->isYearStart());
        $this->assertFalse(Month::FEBRUARY->isYearStart());
        $this->assertFalse(Month::DECEMBER->isYearStart());

        $this->assertTrue(Month::DECEMBER->isYearEnd());
        $this->assertFalse(Month::JANUARY->isYearEnd());
        $this->assertFalse(Month::JUNE->isYearEnd());
    }

    /**
     * Тест создания enum из строки
     */
    public function testFromString(): void
    {
        $this->assertEquals(Month::JANUARY, Month::from('JANUARY'));
        $this->assertEquals(Month::FEBRUARY, Month::from('FEBRUARY'));
        $this->assertEquals(Month::MARCH, Month::from('MARCH'));
        $this->assertEquals(Month::APRIL, Month::from('APRIL'));
        $this->assertEquals(Month::MAY, Month::from('MAY'));
        $this->assertEquals(Month::JUNE, Month::from('JUNE'));
        $this->assertEquals(Month::JULY, Month::from('JULY'));
        $this->assertEquals(Month::AUGUST, Month::from('AUGUST'));
        $this->assertEquals(Month::SEPTEMBER, Month::from('SEPTEMBER'));
        $this->assertEquals(Month::OCTOBER, Month::from('OCTOBER'));
        $this->assertEquals(Month::NOVEMBER, Month::from('NOVEMBER'));
        $this->assertEquals(Month::DECEMBER, Month::from('DECEMBER'));
    }

    /**
     * Тест исключения при неверном значении
     */
    public function testInvalidValue(): void
    {
        $this->expectException(\ValueError::class);
        Month::from('INVALID_MONTH');
    }
} 