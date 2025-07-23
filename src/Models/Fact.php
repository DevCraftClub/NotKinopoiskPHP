<?php

declare(strict_types=1);

namespace NotKinopoisk\Models;

/**
 * Модель факта или ошибки в фильме из Kinopoisk API
 * 
 * Представляет интересные факты, ошибки (блуперы) и другие
 * занимательные детали, связанные с фильмом или сериалом.
 * 
 * Основные возможности:
 * - Хранение информации о фактах в неизменяемом виде
 * - Создание объекта из массива данных API
 * - Определение типа факта (ошибка или интересный факт)
 * - Проверка на спойлеры
 * 
 * @package NotKinopoisk\Models
 * @author Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @since 1.0.0
 * 
 * @see \NotKinopoisk\Services\FilmService
 * 
 * @example
 * ```php
 * // Создание из данных API
 * $fact = Fact::fromArray($apiData);
 * 
 * // Работа с фактом
 * if ($fact->isBlooper()) {
 *     echo "Ошибка в фильме: {$fact->text}\n";
 * } elseif ($fact->isFact()) {
 *     echo "Интересный факт: {$fact->text}\n";
 * }
 * 
 * if ($fact->spoiler) {
 *     echo "⚠️ Внимание: спойлер!\n";
 * }
 * ```
 */
class Fact
{
    /**
     * Конструктор модели факта
     * 
     * Создает новый экземпляр факта со всеми необходимыми данными.
     * Все свойства являются readonly для обеспечения неизменяемости объекта.
     * 
     * @param string $text Текст факта или описания ошибки
     * @param string $type Тип факта (FACT, BLOOPER и т.д.)
     * @param bool $spoiler Флаг, указывающий на наличие спойлера
     * 
     * @example
     * ```php
     * $fact = new Fact(
     *     text: 'В сцене драки видно, что актер использует дублера',
     *     type: 'BLOOPER',
     *     spoiler: false
     * );
     * ```
     */
    public function __construct(
        public readonly string $text,
        public readonly string $type,
        public readonly bool $spoiler
    ) {
    }

    /**
     * Создает экземпляр факта из массива данных API
     * 
     * Статический метод для удобного создания объекта Fact из данных,
     * полученных от Kinopoisk API.
     * 
     * @param array $data Массив данных факта от API
     * 
     * @return self Новый экземпляр факта
     * 
     * @throws \InvalidArgumentException Если данные имеют неверный формат
     * 
     * @example
     * ```php
     * $apiData = [
     *     'text' => 'Интересный факт о съемках...',
     *     'type' => 'FACT',
     *     'spoiler' => false
     * ];
     * 
     * $fact = Fact::fromArray($apiData);
     * ```
     */
    public static function fromArray(array $data): self
    {
        return new self(
            text: $data['text'],
            type: $data['type'],
            spoiler: $data['spoiler']
        );
    }

    /**
     * Проверяет, является ли факт ошибкой в фильме
     * 
     * Определяет, относится ли факт к категории ошибок (блуперов),
     * которые были допущены во время съемок фильма.
     * 
     * @return bool true если это ошибка в фильме, false в противном случае
     * 
     * @example
     * ```php
     * if ($fact->isBlooper()) {
     *     echo "Найдена ошибка: {$fact->text}";
     * }
     * ```
     */
    public function isBlooper(): bool
    {
        return $this->type === 'BLOOPER';
    }

    /**
     * Проверяет, является ли факт интересным фактом
     * 
     * Определяет, относится ли факт к категории интересных фактов
     * о фильме, съемках или актерах.
     * 
     * @return bool true если это интересный факт, false в противном случае
     * 
     * @example
     * ```php
     * if ($fact->isFact()) {
     *     echo "Интересно: {$fact->text}";
     * }
     * ```
     */
    public function isFact(): bool
    {
        return $this->type === 'FACT';
    }
} 