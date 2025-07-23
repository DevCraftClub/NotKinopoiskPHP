<?php

declare(strict_types=1);

namespace NotKinopoisk\Enums;

/**
 * Типы связи между фильмами
 *
 * Определяет различные типы связей между фильмами в API Кинопоиска.
 *
 * @package NotKinopoisk\Enums
 * @author  Maxim Harder
 * @version 1.0.0
 * @since   1.0.0
 */
enum RelationType: string
{
    /**
     * Похожий фильм
     *
     * Фильм, который похож по жанру, стилю или содержанию
     */
    case SIMILAR = 'SIMILAR';

    /**
     * Сиквел
     *
     * Продолжение фильма, действие которого происходит после событий оригинала
     */
    case SEQUEL = 'SEQUEL';

    /**
     * Приквел
     *
     * Фильм, действие которого происходит до событий оригинала
     */
    case PREQUEL = 'PREQUEL';

    /**
     * Ремейк
     *
     * Новая версия существующего фильма
     */
    case REMAKE = 'REMAKE';

    /**
     * Неизвестный тип связи
     *
     * Используется для случаев, когда тип связи не определен
     */
    case UNKNOWN = 'UNKNOWN';

    /**
     * Получает описание типа связи
     *
     * @return string Описание типа связи на русском языке
     *
     * @example
     * ```php
     * $description = RelationType::SIMILAR->getDescription();
     * echo $description; // "Похожий фильм"
     * ```
     */
    public function getDescription(): string
    {
        return match ($this) {
            self::SIMILAR => 'Похожий фильм',
            self::SEQUEL => 'Сиквел',
            self::PREQUEL => 'Приквел',
            self::REMAKE => 'Ремейк',
            self::UNKNOWN => 'Неизвестный тип связи',
        };
    }

    /**
     * Проверяет, является ли тип связи известным
     *
     * @return bool true, если тип связи известен
     *
     * @example
     * ```php
     * if (RelationType::SIMILAR->isKnown()) {
     *     echo "Это известный тип связи";
     * }
     * ```
     */
    public function isKnown(): bool
    {
        return $this !== self::UNKNOWN;
    }
} 