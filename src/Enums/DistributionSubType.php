<?php

declare(strict_types=1);

namespace NotKinopoisk\Enums;

/**
 * Подтипы проката (DistributionSubType) из Kinopoisk API
 *
 * @package NotKinopoisk\Enums
 * @author Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @since 1.0.0
 */
enum DistributionSubType: string
{
    /** Кинотеатры */
    case CINEMA = 'CINEMA';
    /** DVD */
    case DVD = 'DVD';
    /** Цифровой релиз */
    case DIGITAL = 'DIGITAL';
    /** Blu-ray */
    case BLURAY = 'BLURAY';

    /** Получить человекочитаемое название */
    public function getDisplayName(): string
    {
        return match($this) {
            self::CINEMA => 'Кинотеатры',
            self::DVD => 'DVD',
            self::DIGITAL => 'Цифровой релиз',
            self::BLURAY => 'Blu-ray',
        };
    }
} 