<?php

declare(strict_types=1);

namespace NotKinopoisk\Models;

use NotKinopoisk\Interfaces\ModelInterface;

/**
 * Модель внешнего источника из Kinopoisk API
 *
 * Представляет информацию о рецензии или отзыве с внешней платформы,
 * включая данные о платформе, рейтинге и содержании.
 *
 * Основные возможности:
 * - Хранение информации о внешнем источнике в неизменяемом виде
 * - Создание объекта из массива данных API
 * - Доступ к данным платформы и рейтингу
 *
 * @package NotKinopoisk\Models
 * @api     /api/v2.2/films/{id}/external_sources
 * @since   1.0.0
 *
 * @author  Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @see     \NotKinopoisk\Services\FilmService
 * @link    https://kinopoiskapiunofficial.tech/documentation/api/#/films/get_api_v2_2_films__id__distributions
 *
 * @example
 * ```php
 * // Создание из данных API
 * $externalSource = ExternalSource::fromArray($apiData);
 *
 * // Использование
 * echo "Платформа: {$externalSource->platform}\n";
 * echo "Автор: {$externalSource->author}\n";
 * echo "Рейтинг: {$externalSource->positiveRating}/{$externalSource->negativeRating}";
 * ```
 */
class ExternalSource implements ModelInterface {

	/**
	 * Конструктор модели внешнего источника
	 *
	 * Создает новый экземпляр внешнего источника со всеми необходимыми данными.
	 * Все свойства являются readonly для обеспечения неизменяемости объекта.
	 *
	 * @param   string       $url             URL источника
	 * @param   string       $platform        Название платформы
	 * @param   string       $logoUrl         URL логотипа платформы
	 * @param   int|null     $positiveRating  Количество положительных оценок
	 * @param   int|null     $negativeRating  Количество отрицательных оценок
	 * @param   string|null  $author          Автор отзыва
	 * @param   string|null  $title           Заголовок отзыва
	 * @param   string|null  $description     Содержание отзыва
	 *
	 * @example
	 * ```php
	 * $externalSource = new ExternalSource(
	 *     url: 'https://example.com/review',
	 *     platform: 'IMDb',
	 *     logoUrl: 'https://...',
	 *     positiveRating: 90,
	 *     negativeRating: 10,
	 *     author: 'Пользователь',
	 *     title: 'Отличный фильм',
	 *     description: 'Подробный отзыв...'
	 * );
	 * ```
	 */
	public function __construct(
		public readonly string  $url,
		public readonly string  $platform,
		public readonly string  $logoUrl,
		public readonly ?int    $positiveRating,
		public readonly ?int    $negativeRating,
		public readonly ?string $author,
		public readonly ?string $title,
		public readonly ?string $description,
	) {}

	/**
	 * Создает экземпляр внешнего источника из массива данных API
	 *
	 * Статический метод для удобного создания объекта ExternalSource из данных,
	 * полученных от Kinopoisk API. Автоматически обрабатывает nullable поля.
	 *
	 * @param   array  $data  Массив данных внешнего источника от API
	 *
	 * @return self Новый экземпляр внешнего источника
	 *
	 * @throws \InvalidArgumentException Если данные имеют неверный формат
	 *
	 * @example
	 * ```php
	 * $apiData = [
	 *     'url' => 'https://example.com/review',
	 *     'platform' => 'IMDb',
	 *     'logoUrl' => 'https://...',
	 *     'positiveRating' => 90,
	 *     'negativeRating' => 10,
	 *     'author' => 'Пользователь',
	 *     'title' => 'Отличный фильм',
	 *     'description' => 'Подробный отзыв...'
	 * ];
	 *
	 * $externalSource = ExternalSource::fromArray($apiData);
	 * ```
	 */
	public static function fromArray(array $data): static {
		return new self(
			url           : $data['url'],
			platform      : $data['platform'],
			logoUrl       : $data['logoUrl'],
			positiveRating: $data['positiveRating'] ?? NULL,
			negativeRating: $data['negativeRating'] ?? NULL,
			author        : $data['author'] ?? NULL,
			title         : $data['title'] ?? NULL,
			description   : $data['description'] ?? NULL,
		);
	}

	/**
	 * Преобразует объект внешнего источника в массив
	 *
	 * Возвращает все свойства объекта в виде ассоциативного массива.
	 * Полезно для сериализации, логирования или передачи данных.
	 *
	 * @return array Массив с данными внешнего источника
	 *
	 * @example
	 * ```php
	 * $externalSourceArray = $externalSource->toArray();
	 * echo json_encode($externalSourceArray); // JSON представление внешнего источника
	 * ```
	 */
	public function toArray(): array {
		return [
			'url'            => $this->url,
			'platform'       => $this->platform,
			'logoUrl'        => $this->logoUrl,
			'positiveRating' => $this->positiveRating,
			'negativeRating' => $this->negativeRating,
			'author'         => $this->author,
			'title'          => $this->title,
			'description'    => $this->description,
		];
	}

}