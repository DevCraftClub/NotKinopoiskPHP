<?php

declare(strict_types=1);

namespace NotKinopoisk\Models;

use NotKinopoisk\Enums\VideoSite;

/**
 * Модель видео из Kinopoisk API
 *
 * Представляет информацию о видео материале, связанном с фильмом:
 * трейлеры, клипы, закулисные материалы и другие видео.
 *
 * Основные возможности:
 * - Хранение информации о видео в неизменяемом виде
 * - Создание объекта из массива данных API
 * - Доступ к URL, названию и платформе видео
 *
 * @package NotKinopoisk\Models
 * @since   1.0.0
 *
 * @author  Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @see     \NotKinopoisk\Services\MediaService
 *
 * @example
 * ```php
 * // Создание из данных API
 * $video = Video::fromArray($apiData);
 *
 * // Использование
 * echo "Название: {$video->name}\n";
 * echo "URL: {$video->url}\n";
 * echo "Платформа: {$video->site}";
 * ```
 */
class Video {

	/**
	 * Конструктор модели видео
	 *
	 * Создает новый экземпляр видео со всеми необходимыми данными.
	 * Все свойства являются readonly для обеспечения неизменяемости объекта.
	 *
	 * @param   string                         $url   URL видео
	 * @param   string                         $name  Название видео
	 * @param   \NotKinopoisk\Enums\VideoSite  $site  Платформа или сайт, где размещено видео
	 *
	 * @example
	 * ```php
	 * $video = new Video(
	 *     url: 'https://youtube.com/watch?v=...',
	 *     name: 'Трейлер фильма',
	 *     site: 'YOUTUBE'
	 * );
	 * ```
	 */
	public function __construct(
		public readonly string    $url,
		public readonly string    $name,
		public readonly VideoSite $site,
	) {}

	/**
	 * Создает экземпляр видео из массива данных API
	 *
	 * Статический метод для удобного создания объекта Video из данных,
	 * полученных от Kinopoisk API.
	 *
	 * @param   array  $data  Массив данных видео от API
	 *
	 * @return self Новый экземпляр видео
	 *
	 * @throws \InvalidArgumentException Если данные имеют неверный формат
	 *
	 * @example
	 * ```php
	 * $apiData = [
	 *     'url' => 'https://youtube.com/watch?v=...',
	 *     'name' => 'Трейлер фильма',
	 *     'site' => 'YOUTUBE'
	 * ];
	 *
	 * $video = Video::fromArray($apiData);
	 * ```
	 */
	public static function fromArray(array $data): self {
		return new self(
			url : $data['url'],
			name: $data['name'],
			site: VideoSite::from($data['site']),
		);
	}

}