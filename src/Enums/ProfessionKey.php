<?php

declare(strict_types=1);

namespace NotKinopoisk\Enums;

/**
 * Ключи профессий (ProfessionKey) из Kinopoisk API
 *
 * @package NotKinopoisk\Enums
 * @author Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @since 1.0.0
 */
enum ProfessionKey: string
{
    case WRITER = 'WRITER';
    case OPERATOR = 'OPERATOR';
    case EDITOR = 'EDITOR';
    case COMPOSER = 'COMPOSER';
    case PRODUCER_USSR = 'PRODUCER_USSR';
    case TRANSLATOR = 'TRANSLATOR';
    case DIRECTOR = 'DIRECTOR';
    case DESIGN = 'DESIGN';
    case PRODUCER = 'PRODUCER';
    case ACTOR = 'ACTOR';
    case VOICE_DIRECTOR = 'VOICE_DIRECTOR';
    case UNKNOWN = 'UNKNOWN';
    case HIMSELF = 'HIMSELF';
    case HERSELF = 'HERSELF';
    case HRONO_TITR_MALE = 'HRONO_TITR_MALE';
    case HRONO_TITR_FEMALE = 'HRONO_TITR_FEMALE';

    public function getDisplayName(): string
    {
        return match($this) {
            self::WRITER => 'Сценарист',
            self::OPERATOR => 'Оператор',
            self::EDITOR => 'Монтажер',
            self::COMPOSER => 'Композитор',
            self::PRODUCER_USSR => 'Продюсер (СССР)',
            self::TRANSLATOR => 'Переводчик',
            self::DIRECTOR => 'Режиссер',
            self::DESIGN => 'Дизайнер',
            self::PRODUCER => 'Продюсер',
            self::ACTOR => 'Актер',
            self::VOICE_DIRECTOR => 'Режиссер дубляжа',
            self::UNKNOWN => 'Неизвестно',
            self::HIMSELF => 'Сам',
            self::HERSELF => 'Сама',
            self::HRONO_TITR_MALE => 'Хроно титр (муж.)',
            self::HRONO_TITR_FEMALE => 'Хроно титр (жен.)',
        };
    }
} 