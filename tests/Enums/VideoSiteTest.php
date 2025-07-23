<?php

declare(strict_types=1);

namespace Tests\Enums;

use NotKinopoisk\Enums\VideoSite;
use PHPUnit\Framework\TestCase;

/**
 * Тесты для enum VideoSite
 * 
 * @package Tests\Enums
 * @author Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @since 1.0.0
 */
class VideoSiteTest extends TestCase
{
    /**
     * Тест создания enum из строки
     */
    public function testFromString(): void
    {
        $this->assertEquals(VideoSite::YOUTUBE, VideoSite::from('YOUTUBE'));
        $this->assertEquals(VideoSite::VIMEO, VideoSite::from('VIMEO'));
        $this->assertEquals(VideoSite::KINOPOISK, VideoSite::from('KINOPOISK'));
        $this->assertEquals(VideoSite::KINOPOISK_WIDGET, VideoSite::from('KINOPOISK_WIDGET'));
        $this->assertEquals(VideoSite::UNKNOWN, VideoSite::from('UNKNOWN'));
    }

    /**
     * Тест получения значения enum
     */
    public function testGetValue(): void
    {
        $this->assertEquals('YOUTUBE', VideoSite::YOUTUBE->value);
        $this->assertEquals('VIMEO', VideoSite::VIMEO->value);
        $this->assertEquals('KINOPOISK', VideoSite::KINOPOISK->value);
        $this->assertEquals('KINOPOISK_WIDGET', VideoSite::KINOPOISK_WIDGET->value);
        $this->assertEquals('UNKNOWN', VideoSite::UNKNOWN->value);
    }

    /**
     * Тест метода getDisplayName()
     */
    public function testGetDisplayName(): void
    {
        $this->assertEquals('YouTube', VideoSite::YOUTUBE->getDisplayName());
        $this->assertEquals('Vimeo', VideoSite::VIMEO->getDisplayName());
        $this->assertEquals('Кинопоиск', VideoSite::KINOPOISK->getDisplayName());
        $this->assertEquals('Кинопоиск-виджет', VideoSite::KINOPOISK_WIDGET->getDisplayName());
        $this->assertEquals('Неизвестно', VideoSite::UNKNOWN->getDisplayName());
    }

    /**
     * Тест метода isExternal()
     */
    public function testIsExternal(): void
    {
        $this->assertTrue(VideoSite::YOUTUBE->isExternal());
        $this->assertTrue(VideoSite::VIMEO->isExternal());
        $this->assertFalse(VideoSite::KINOPOISK->isExternal());
        $this->assertFalse(VideoSite::KINOPOISK_WIDGET->isExternal());
        $this->assertTrue(VideoSite::UNKNOWN->isExternal());
    }

    /**
     * Тест исключения при неверном значении
     */
    public function testInvalidValueThrowsException(): void
    {
        $this->expectException(\ValueError::class);
        VideoSite::from('INVALID_SITE');
    }

    /**
     * Тест сравнения enum значений
     */
    public function testEnumComparison(): void
    {
        $youtube1 = VideoSite::YOUTUBE;
        $youtube2 = VideoSite::YOUTUBE;
        $vimeo = VideoSite::VIMEO;

        $this->assertTrue($youtube1 === $youtube2);
        $this->assertTrue($youtube1 === VideoSite::YOUTUBE);
        $this->assertTrue($vimeo === VideoSite::VIMEO);
        $this->assertTrue($youtube1 !== $vimeo);
    }

    /**
     * Тест всех доступных значений enum
     */
    public function testAllEnumValues(): void
    {
        $expectedValues = [
            'YOUTUBE',
            'VIMEO',
            'KINOPOISK',
            'KINOPOISK_WIDGET',
            'UNKNOWN'
        ];

        $actualValues = array_map(fn($case) => $case->value, VideoSite::cases());
        $this->assertEquals($expectedValues, $actualValues);
    }

    /**
     * Тест внешних сайтов
     */
    public function testExternalSites(): void
    {
        $externalSites = [
            VideoSite::YOUTUBE,
            VideoSite::VIMEO,
            VideoSite::UNKNOWN
        ];

        foreach ($externalSites as $site) {
            $this->assertTrue($site->isExternal());
        }
    }

    /**
     * Тест внутренних сайтов
     */
    public function testInternalSites(): void
    {
        $internalSites = [
            VideoSite::KINOPOISK,
            VideoSite::KINOPOISK_WIDGET
        ];

        foreach ($internalSites as $site) {
            $this->assertFalse($site->isExternal());
        }
    }
} 