<?php

declare(strict_types=1);

namespace Tests\Enums;

use NotKinopoisk\Enums\CollectionType;
use PHPUnit\Framework\TestCase;

/**
 * Тесты для enum CollectionType
 * 
 * @package Tests\Enums
 * @author Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @since 1.0.0
 */
class CollectionTypeTest extends TestCase
{
    /**
     * Тест создания enum из строки
     */
    public function testFromString(): void
    {
        $this->assertEquals(CollectionType::TOP_POPULAR_ALL, CollectionType::from('TOP_POPULAR_ALL'));
        $this->assertEquals(CollectionType::TOP_POPULAR_MOVIES, CollectionType::from('TOP_POPULAR_MOVIES'));
        $this->assertEquals(CollectionType::TOP_POPULAR_SERIES, CollectionType::from('TOP_POPULAR_SERIES'));
        $this->assertEquals(CollectionType::TOP_250_MOVIES, CollectionType::from('TOP_250_MOVIES'));
        $this->assertEquals(CollectionType::TOP_250_SERIES, CollectionType::from('TOP_250_SERIES'));
    }

    /**
     * Тест получения значения enum
     */
    public function testGetValue(): void
    {
        $this->assertEquals('TOP_POPULAR_ALL', CollectionType::TOP_POPULAR_ALL->value);
        $this->assertEquals('TOP_POPULAR_MOVIES', CollectionType::TOP_POPULAR_MOVIES->value);
        $this->assertEquals('TOP_POPULAR_SERIES', CollectionType::TOP_POPULAR_SERIES->value);
        $this->assertEquals('TOP_250_MOVIES', CollectionType::TOP_250_MOVIES->value);
        $this->assertEquals('TOP_250_SERIES', CollectionType::TOP_250_SERIES->value);
    }

    /**
     * Тест метода getDisplayName()
     */
    public function testGetDisplayName(): void
    {
        $this->assertEquals('Топ популярных фильмов и сериалов', CollectionType::TOP_POPULAR_ALL->getDisplayName());
        $this->assertEquals('Топ популярных фильмов', CollectionType::TOP_POPULAR_MOVIES->getDisplayName());
        $this->assertEquals('Топ популярных сериалов', CollectionType::TOP_POPULAR_SERIES->getDisplayName());
        $this->assertEquals('Топ-250 фильмов', CollectionType::TOP_250_MOVIES->getDisplayName());
        $this->assertEquals('Топ-250 сериалов', CollectionType::TOP_250_SERIES->getDisplayName());
    }

    /**
     * Тест исключения при неверном значении
     */
    public function testInvalidValueThrowsException(): void
    {
        $this->expectException(\ValueError::class);
        CollectionType::from('INVALID_TYPE');
    }

    /**
     * Тест сравнения enum значений
     */
    public function testEnumComparison(): void
    {
        $popular1 = CollectionType::TOP_POPULAR_ALL;
        $popular2 = CollectionType::TOP_POPULAR_ALL;
        $top250 = CollectionType::TOP_250_MOVIES;

        $this->assertTrue($popular1 === $popular2);
        $this->assertTrue($popular1 === CollectionType::TOP_POPULAR_ALL);
        $this->assertTrue($top250 === CollectionType::TOP_250_MOVIES);
        $this->assertTrue($popular1 !== $top250);
    }

    /**
     * Тест всех доступных значений enum
     */
    public function testAllEnumValues(): void
    {
        $expectedValues = [
            'TOP_POPULAR_ALL',
            'TOP_POPULAR_MOVIES',
            'TOP_POPULAR_SERIES',
            'TOP_250_MOVIES',
            'TOP_250_SERIES'
        ];

        $actualValues = array_map(fn($case) => $case->value, CollectionType::cases());
        $this->assertEquals($expectedValues, $actualValues);
    }
} 