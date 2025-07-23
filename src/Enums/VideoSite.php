<?php

declare(strict_types=1);

namespace NotKinopoisk\Enums;

/**
 * Платформы видео (VideoSite) из Kinopoisk API
 *
 * @package NotKinopoisk\Enums
 * @author Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @since 1.0.0
 */
enum VideoSite: string
{
    case YOUTUBE = 'YOUTUBE';
    case KINOPOISK_WIDGET = 'KINOPOISK_WIDGET';
    case YANDEX_DISK = 'YANDEX_DISK';
    case UNKNOWN = 'UNKNOWN';

    public function getDisplayName(): string
    {
        return match($this) {
            self::YOUTUBE => 'YouTube',
            self::KINOPOISK_WIDGET => 'Кинопоиск-виджет',
            self::YANDEX_DISK => 'Яндекс.Диск',
            self::UNKNOWN => 'Неизвестно',
        };
    }
} 