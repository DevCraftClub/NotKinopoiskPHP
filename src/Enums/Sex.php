<?php

declare(strict_types=1);

namespace NotKinopoisk\Enums;

/**
 * Пол (Sex) из Kinopoisk API
 *
 * @package NotKinopoisk\Enums
 * @author Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @since 1.0.0
 */
enum Sex: string
{
    case MALE = 'MALE';
    case FEMALE = 'FEMALE';
    case UNKNOWN = 'UNKNOWN';

    public function getDisplayName(): string
    {
        return match($this) {
            self::MALE => 'Мужской',
            self::FEMALE => 'Женский',
            self::UNKNOWN => 'Неизвестно',
        };
    }
} 