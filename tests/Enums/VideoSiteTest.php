<?php

declare(strict_types=1);

namespace Tests\Enums;

use NotKinopoisk\Enums\VideoSite;
use PHPUnit\Framework\TestCase;

class VideoSiteTest extends TestCase
{
    public function testFromString(): void
    {
        $this->assertEquals(VideoSite::YOUTUBE, VideoSite::from('YOUTUBE'));
        $this->assertEquals(VideoSite::KINOPOISK_WIDGET, VideoSite::from('KINOPOISK_WIDGET'));
        $this->assertEquals(VideoSite::YANDEX_DISK, VideoSite::from('YANDEX_DISK'));
        $this->assertEquals(VideoSite::UNKNOWN, VideoSite::from('UNKNOWN'));
    }

    public function testGetDisplayName(): void
    {
        $this->assertEquals('YouTube', VideoSite::YOUTUBE->getDisplayName());
        $this->assertEquals('Кинопоиск-виджет', VideoSite::KINOPOISK_WIDGET->getDisplayName());
        $this->assertEquals('Яндекс.Диск', VideoSite::YANDEX_DISK->getDisplayName());
        $this->assertEquals('Неизвестно', VideoSite::UNKNOWN->getDisplayName());
    }

    public function testInvalidValueThrowsException(): void
    {
        $this->expectException(\ValueError::class);
        VideoSite::from('INVALID');
    }

    public function testAllEnumValues(): void
    {
        $cases = VideoSite::cases();
        $this->assertCount(4, $cases);
    }
} 