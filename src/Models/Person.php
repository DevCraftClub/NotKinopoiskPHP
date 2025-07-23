<?php

declare(strict_types=1);

namespace NotKinopoisk\Models;

/**
 * Модель персоны из Kinopoisk API
 * 
 * Представляет информацию о персоне (актер, режиссер, сценарист и т.д.),
 * полученную из Kinopoisk API. Содержит биографические данные, фильмографию
 * и другую информацию о персоне.
 * 
 * Основные возможности:
 * - Хранение полной информации о персоне в неизменяемом виде
 * - Создание объекта из массива данных API
 * - Удобные методы для получения отображаемого имени
 * 
 * @package NotKinopoisk\Models
 * @author Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @since 1.0.0
 * 
 * @see \NotKinopoisk\Services\PersonService
 * @see \NotKinopoisk\Models\Staff
 * 
 * @example
 * ```php
 * // Создание из данных API
 * $person = Person::fromArray($apiData);
 * 
 * // Получение отображаемого имени
 * echo $person->getDisplayName();
 * 
 * // Работа с биографией
 * if ($person->biography) {
 *     echo "Биография: " . substr($person->biography, 0, 100) . "...";
 * }
 * ```
 */
class Person
{
    /**
     * Конструктор модели персоны
     * 
     * Создает новый экземпляр персоны со всеми необходимыми данными.
     * Все свойства являются readonly для обеспечения неизменяемости объекта.
     * 
     * @param int|null $personId Уникальный идентификатор персоны в Кинопоиске
     * @param string|null $nameRu Имя персоны на русском языке
     * @param string|null $nameEn Имя персоны на английском языке
     * @param string|null $sex Пол персоны
     * @param string|null $posterUrl URL постера/фотографии персоны
     * @param string|null $growth Рост персоны
     * @param string|null $birthday Дата рождения
     * @param string|null $death Дата смерти (если применимо)
     * @param int|null $age Возраст персоны
     * @param string|null $birthplace Место рождения
     * @param string|null $deathplace Место смерти
     * @param string|null $spouses Информация о супругах
     * @param int|null $hasAwards Наличие наград
     * @param string|null $profession Профессия персоны
     * @param string|null $facts Интересные факты
     * @param string|null $films Информация о фильмах
     * @param string|null $biography Биография персоны
     * @param string|null $births Информация о рождении
     * @param string|null $deaths Информация о смерти
     * @param string|null $total Информация о количестве работ
     * 
     * @example
     * ```php
     * $person = new Person(
     *     personId: 12345,
     *     nameRu: 'Том Круз',
     *     nameEn: 'Tom Cruise',
     *     posterUrl: 'https://...',
     *     profession: 'Актер',
     *     biography: 'Биография...'
     * );
     * ```
     */
    public function __construct(
        public readonly ?int $personId,
        public readonly ?string $nameRu,
        public readonly ?string $nameEn,
        public readonly ?string $sex,
        public readonly ?string $posterUrl,
        public readonly ?string $growth,
        public readonly ?string $birthday,
        public readonly ?string $death,
        public readonly ?int $age,
        public readonly ?string $birthplace,
        public readonly ?string $deathplace,
        public readonly ?string $spouses,
        public readonly ?int $hasAwards,
        public readonly ?string $profession,
        public readonly ?string $facts,
        public readonly ?string $films,
        public readonly ?string $biography,
        public readonly ?string $births,
        public readonly ?string $deaths,
        public readonly ?string $total
    ) {
    }

    /**
     * Создает экземпляр персоны из массива данных API
     * 
     * Статический метод для удобного создания объекта Person из данных,
     * полученных от Kinopoisk API. Автоматически обрабатывает nullable поля
     * и устанавливает значения по умолчанию.
     * 
     * @param array $data Массив данных персоны от API
     * 
     * @return self Новый экземпляр персоны
     * 
     * @throws \InvalidArgumentException Если данные имеют неверный формат
     * 
     * @example
     * ```php
     * $apiData = [
     *     'personId' => 12345,
     *     'nameRu' => 'Том Круз',
     *     'nameEn' => 'Tom Cruise',
     *     'profession' => 'Актер',
     *     'biography' => 'Биография...'
     * ];
     * 
     * $person = Person::fromArray($apiData);
     * ```
     */
    public static function fromArray(array $data): self
    {
        return new self(
            personId: $data['personId'] ?? null,
            nameRu: $data['nameRu'] ?? null,
            nameEn: $data['nameEn'] ?? null,
            sex: $data['sex'] ?? null,
            posterUrl: $data['posterUrl'] ?? null,
            growth: $data['growth'] ?? null,
            birthday: $data['birthday'] ?? null,
            death: $data['death'] ?? null,
            age: $data['age'] ?? null,
            birthplace: $data['birthplace'] ?? null,
            deathplace: $data['deathplace'] ?? null,
            spouses: $data['spouses'] ?? null,
            hasAwards: $data['hasAwards'] ?? null,
            profession: $data['profession'] ?? null,
            facts: $data['facts'] ?? null,
            films: $data['films'] ?? null,
            biography: $data['biography'] ?? null,
            births: $data['births'] ?? null,
            deaths: $data['deaths'] ?? null,
            total: $data['total'] ?? null
        );
    }

    /**
     * Получает отображаемое имя персоны
     * 
     * Возвращает наиболее подходящее имя для отображения пользователю.
     * Приоритет: русское имя → английское имя → "Неизвестно"
     * 
     * @return string Отображаемое имя персоны
     * 
     * @example
     * ```php
     * echo $person->getDisplayName(); // "Том Круз" или "Tom Cruise" или "Неизвестно"
     * ```
     */
    public function getDisplayName(): string
    {
        return $this->nameRu ?? $this->nameEn ?? 'Неизвестно';
    }
} 