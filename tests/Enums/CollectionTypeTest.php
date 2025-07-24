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
        $this->assertEquals(CollectionType::TOP_250_TV_SHOWS, CollectionType::from('TOP_250_TV_SHOWS'));
        $this->assertEquals(CollectionType::VAMPIRE_THEME, CollectionType::from('VAMPIRE_THEME'));
        $this->assertEquals(CollectionType::COMICS_THEME, CollectionType::from('COMICS_THEME'));
        $this->assertEquals(CollectionType::CLOSES_RELEASES, CollectionType::from('CLOSES_RELEASES'));
        $this->assertEquals(CollectionType::FAMILY, CollectionType::from('FAMILY'));
        $this->assertEquals(CollectionType::OSKAR_WINNERS_2021, CollectionType::from('OSKAR_WINNERS_2021'));
        $this->assertEquals(CollectionType::LOVE_THEME, CollectionType::from('LOVE_THEME'));
        $this->assertEquals(CollectionType::ZOMBIE_THEME, CollectionType::from('ZOMBIE_THEME'));
        $this->assertEquals(CollectionType::CATASTROPHE_THEME, CollectionType::from('CATASTROPHE_THEME'));
        $this->assertEquals(CollectionType::KIDS_ANIMATION_THEME, CollectionType::from('KIDS_ANIMATION_THEME'));
        $this->assertEquals(CollectionType::POPULAR_SERIES, CollectionType::from('POPULAR_SERIES'));
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
        $this->assertEquals('TOP_250_TV_SHOWS', CollectionType::TOP_250_TV_SHOWS->value);
        $this->assertEquals('VAMPIRE_THEME', CollectionType::VAMPIRE_THEME->value);
        $this->assertEquals('COMICS_THEME', CollectionType::COMICS_THEME->value);
        $this->assertEquals('CLOSES_RELEASES', CollectionType::CLOSES_RELEASES->value);
        $this->assertEquals('FAMILY', CollectionType::FAMILY->value);
        $this->assertEquals('OSKAR_WINNERS_2021', CollectionType::OSKAR_WINNERS_2021->value);
        $this->assertEquals('LOVE_THEME', CollectionType::LOVE_THEME->value);
        $this->assertEquals('ZOMBIE_THEME', CollectionType::ZOMBIE_THEME->value);
        $this->assertEquals('CATASTROPHE_THEME', CollectionType::CATASTROPHE_THEME->value);
        $this->assertEquals('KIDS_ANIMATION_THEME', CollectionType::KIDS_ANIMATION_THEME->value);
        $this->assertEquals('POPULAR_SERIES', CollectionType::POPULAR_SERIES->value);
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
        $this->assertEquals('Топ-250 сериалов', CollectionType::TOP_250_TV_SHOWS->getDisplayName());
        $this->assertEquals('Вампиры', CollectionType::VAMPIRE_THEME->getDisplayName());
        $this->assertEquals('Комиксы', CollectionType::COMICS_THEME->getDisplayName());
        $this->assertEquals('Скоро выходящие', CollectionType::CLOSES_RELEASES->getDisplayName());
        $this->assertEquals('Семейные фильмы', CollectionType::FAMILY->getDisplayName());
        $this->assertEquals('Победители Оскара 2021', CollectionType::OSKAR_WINNERS_2021->getDisplayName());
        $this->assertEquals('Любовь', CollectionType::LOVE_THEME->getDisplayName());
        $this->assertEquals('Зомби', CollectionType::ZOMBIE_THEME->getDisplayName());
        $this->assertEquals('Катастрофы', CollectionType::CATASTROPHE_THEME->getDisplayName());
        $this->assertEquals('Детская анимация', CollectionType::KIDS_ANIMATION_THEME->getDisplayName());
        $this->assertEquals('Популярные сериалы', CollectionType::POPULAR_SERIES->getDisplayName());
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
            'TOP_250_TV_SHOWS',
            'VAMPIRE_THEME',
            'COMICS_THEME',
            'CLOSES_RELEASES',
            'FAMILY',
            'OSKAR_WINNERS_2021',
            'LOVE_THEME',
            'ZOMBIE_THEME',
            'CATASTROPHE_THEME',
            'KIDS_ANIMATION_THEME',
            'POPULAR_SERIES'
        ];

        $actualValues = array_map(fn($case) => $case->value, CollectionType::cases());
        $this->assertEquals($expectedValues, $actualValues);
    }

    /**
     * Тест тематических коллекций
     */
    public function testThematicCollections(): void
    {
        $thematicCollections = [
            CollectionType::VAMPIRE_THEME,
            CollectionType::COMICS_THEME,
            CollectionType::LOVE_THEME,
            CollectionType::ZOMBIE_THEME,
            CollectionType::CATASTROPHE_THEME,
            CollectionType::KIDS_ANIMATION_THEME
        ];

        foreach ($thematicCollections as $collection) {
            $this->assertStringContainsString('THEME', $collection->value);
            $this->assertNotEmpty($collection->getDisplayName());
        }
    }

    /**
     * Тест топ-коллекций
     */
    public function testTopCollections(): void
    {
        $topCollections = [
            CollectionType::TOP_POPULAR_ALL,
            CollectionType::TOP_POPULAR_MOVIES,
            CollectionType::TOP_POPULAR_SERIES,
            CollectionType::TOP_250_MOVIES,
            CollectionType::TOP_250_TV_SHOWS
        ];

        foreach ($topCollections as $collection) {
            $this->assertStringContainsString('TOP', $collection->value);
            $this->assertNotEmpty($collection->getDisplayName());
        }
    }
} 