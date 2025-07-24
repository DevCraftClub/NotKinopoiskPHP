<?php

declare(strict_types=1);

namespace NotKinopoisk\Models;

use NotKinopoisk\Interfaces\ModelInterface;

/**
 * Модель медиа-поста
 *
 * Представляет медиа-пост в Kinopoisk API.
 * Содержит информацию о изображении, заголовке, описании и ссылке.
 *
 * @package NotKinopoisk\Models
 * @api     /api/v1/media_posts
 * @since   1.0.0
 * @author  Maxim Harder
 * @version 1.0.0
 * @link    https://kinopoiskapiunofficial.tech/documentation/api/#/media_posts/get_api_v1_media_posts
 */
class MediaPost implements ModelInterface {

	/**
	 * Конструктор модели медиа-поста
	 *
	 * @param   int     $kinopoiskId  Уникальный идентификатор в Кинопоиске
	 * @param   string  $imageUrl     URL изображения
	 * @param   string  $title        Заголовок поста
	 * @param   string  $description  Описание поста
	 * @param   string  $url          URL поста
	 * @param   string  $publishedAt  Дата публикации
	 */
	public function __construct(
		public readonly int    $kinopoiskId,
		public readonly string $imageUrl,
		public readonly string $title,
		public readonly string $description,
		public readonly string $url,
		public readonly string $publishedAt,
	) {}

	/**
	 * Создает экземпляр модели из массива данных API
	 *
	 * @param   array  $data  Массив данных от API
	 *
	 * @return self Экземпляр модели
	 *
	 * @throws \InvalidArgumentException При некорректных данных
	 *
	 * @example
	 * ```php
	 * $postData = [
	 *     'kinopoiskId' => 301,
	 *     'imageUrl' => 'https://example.com/image.jpg',
	 *     'title' => 'Новости о фильме',
	 *     'description' => 'Описание новости',
	 *     'url' => 'https://example.com/post',
	 *     'publishedAt' => '2024-01-01T12:00:00'
	 * ];
	 * $post = MediaPost::fromArray($postData);
	 * ```
	 */
	public static function fromArray(array $data): self {
		return new self(
			kinopoiskId: $data['kinopoiskId'],
			imageUrl   : $data['imageUrl'],
			title      : $data['title'],
			description: $data['description'],
			url        : $data['url'],
			publishedAt: $data['publishedAt'],
		);
	}

	/**
	 * Преобразует объект медиа-поста в массив
	 *
	 * Возвращает все свойства объекта в виде ассоциативного массива.
	 * Полезно для сериализации, логирования или передачи данных.
	 *
	 * @return array Массив с данными медиа-поста
	 *
	 * @example
	 * ```php
	 * $postArray = $post->toArray();
	 * echo json_encode($postArray); // JSON представление медиа-поста
	 * ```
	 */
	public function toArray(): array {
		return [
			'kinopoiskId' => $this->kinopoiskId,
			'imageUrl'    => $this->imageUrl,
			'title'       => $this->title,
			'description' => $this->description,
			'url'         => $this->url,
			'publishedAt' => $this->publishedAt,
		];
	}

}