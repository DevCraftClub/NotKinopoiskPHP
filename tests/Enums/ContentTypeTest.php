<?php

declare(strict_types=1);

namespace Tests\Enums;

use NotKinopoisk\Enums\ContentType;
use PHPUnit\Framework\TestCase;

/**
 * Тесты для enum ContentType
 * 
 * @package Tests\Enums
 * @author Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @since 1.0.0
 */
class ContentTypeTest extends TestCase
{
    /**
     * Тест создания enum из строки
     */
    public function testFromString(): void
    {
        $this->assertEquals(ContentType::FILM, ContentType::from('FILM'));
        $this->assertEquals(ContentType::SERIES, ContentType::from('SERIES'));
        $this->assertEquals(ContentType::MINI_SERIES, ContentType::from('MINI_SERIES'));
        $this->assertEquals(ContentType::TV_SHOW, ContentType::from('TV_SHOW'));
        $this->assertEquals(ContentType::TV_MOVIE, ContentType::from('TV_MOVIE'));
        $this->assertEquals(ContentType::VIDEO, ContentType::from('VIDEO'));
        $this->assertEquals(ContentType::SHORT, ContentType::from('SHORT'));
        $this->assertEquals(ContentType::DOCUMENTARY, ContentType::from('DOCUMENTARY'));
        $this->assertEquals(ContentType::TV_SERIES, ContentType::from('TV_SERIES'));
        $this->assertEquals(ContentType::UNKNOWN, ContentType::from('UNKNOWN'));
        $this->assertEquals(ContentType::ALL, ContentType::from('ALL'));
    }

    /**
     * Тест получения значения enum
     */
    public function testGetValue(): void
    {
        $this->assertEquals('FILM', ContentType::FILM->value);
        $this->assertEquals('SERIES', ContentType::SERIES->value);
        $this->assertEquals('MINI_SERIES', ContentType::MINI_SERIES->value);
        $this->assertEquals('TV_SHOW', ContentType::TV_SHOW->value);
        $this->assertEquals('TV_MOVIE', ContentType::TV_MOVIE->value);
        $this->assertEquals('VIDEO', ContentType::VIDEO->value);
        $this->assertEquals('SHORT', ContentType::SHORT->value);
        $this->assertEquals('DOCUMENTARY', ContentType::DOCUMENTARY->value);
        $this->assertEquals('TV_SERIES', ContentType::TV_SERIES->value);
        $this->assertEquals('UNKNOWN', ContentType::UNKNOWN->value);
        $this->assertEquals('ALL', ContentType::ALL->value);
    }

    /**
     * Тест метода isFilm()
     */
    public function testIsFilm(): void
    {
        $this->assertTrue(ContentType::FILM->isFilm());
        $this->assertTrue(ContentType::TV_MOVIE->isFilm());
        $this->assertTrue(ContentType::VIDEO->isFilm());
        $this->assertTrue(ContentType::SHORT->isFilm());
        $this->assertTrue(ContentType::DOCUMENTARY->isFilm());
        $this->assertFalse(ContentType::SERIES->isFilm());
        $this->assertFalse(ContentType::MINI_SERIES->isFilm());
        $this->assertFalse(ContentType::TV_SHOW->isFilm());
    }

    /**
     * Тест метода isSeries()
     */
    public function testIsSeries(): void
    {
        $this->assertFalse(ContentType::FILM->isSeries());
        $this->assertTrue(ContentType::SERIES->isSeries());
        $this->assertTrue(ContentType::MINI_SERIES->isSeries());
        $this->assertTrue(ContentType::TV_SHOW->isSeries());
        $this->assertTrue(ContentType::TV_SERIES->isSeries());
        $this->assertFalse(ContentType::TV_MOVIE->isSeries());
        $this->assertFalse(ContentType::VIDEO->isSeries());
        $this->assertFalse(ContentType::SHORT->isSeries());
        $this->assertFalse(ContentType::DOCUMENTARY->isSeries());
    }

    /**
     * Тест метода getDisplayName()
     */
    public function testGetDisplayName(): void
    {
        $this->assertEquals('Фильм', ContentType::FILM->getDisplayName());
        $this->assertEquals('Сериал', ContentType::SERIES->getDisplayName());
        $this->assertEquals('Мини-сериал', ContentType::MINI_SERIES->getDisplayName());
        $this->assertEquals('Телешоу', ContentType::TV_SHOW->getDisplayName());
        $this->assertEquals('ТВ-фильм', ContentType::TV_MOVIE->getDisplayName());
        $this->assertEquals('Видео', ContentType::VIDEO->getDisplayName());
        $this->assertEquals('Короткометражка', ContentType::SHORT->getDisplayName());
        $this->assertEquals('Документальный фильм', ContentType::DOCUMENTARY->getDisplayName());
        $this->assertEquals('ТВ-сериал', ContentType::TV_SERIES->getDisplayName());
        $this->assertEquals('Неизвестно', ContentType::UNKNOWN->getDisplayName());
        $this->assertEquals('Все типы', ContentType::ALL->getDisplayName());
    }

    /**
     * Тест исключения при неверном значении
     */
    public function testInvalidValueThrowsException(): void
    {
        $this->expectException(\ValueError::class);
        ContentType::from('INVALID_TYPE');
    }

    /**
     * Тест сравнения enum значений
     */
    public function testEnumComparison(): void
    {
        $film1 = ContentType::FILM;
        $film2 = ContentType::FILM;
        $series = ContentType::SERIES;

        $this->assertTrue($film1 === $film2);
        $this->assertTrue($film1 === ContentType::FILM);
        $this->assertTrue($series === ContentType::SERIES);
        $this->assertTrue($film1 !== $series);
    }

    /**
     * Тест всех доступных значений enum
     */
    public function testAllEnumValues(): void
    {
        $cases = ContentType::cases();
        $this->assertCount(11, $cases);
    }
} 