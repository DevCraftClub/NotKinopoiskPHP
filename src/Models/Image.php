<?php

declare(strict_types=1);

namespace NotKinopoisk\Models;

/**
 * Модель изображения из Kinopoisk API
 *
 * Представляет информацию об изображении, связанном с фильмом,
 * включая URL полного изображения и превью.
 *
 * Основные возможности:
 * - Хранение информации об изображении в неизменяемом виде
 * - Создание объекта из массива данных API
 * - Доступ к полному и превью изображениям
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
 * $image = Image::fromArray($apiData);
 *
 * // Использование
 * echo "Полное изображение: {$image->imageUrl}\n";
 * echo "Превью: {$image->previewUrl}";
 * ```
 */
class Image {

	/**
	 * Конструктор модели изображения
	 *
	 * Создает новый экземпляр изображения со всеми необходимыми данными.
	 * Все свойства являются readonly для обеспечения неизменяемости объекта.
	 *
	 * @param   string  $imageUrl    URL полного изображения
	 * @param   string  $previewUrl  URL превью изображения
	 *
	 * @example
	 * ```php
	 * $image = new Image(
	 *     imageUrl: 'https://kinopoisk.ru/images/full.jpg',
	 *     previewUrl: 'https://kinopoisk.ru/images/preview.jpg'
	 * );
	 * ```
	 */
	public function __construct(
		public readonly string $imageUrl,
		public readonly string $previewUrl,
	) {}

	/**
	 * Создает экземпляр изображения из массива данных API
	 *
	 * Статический метод для удобного создания объекта Image из данных,
	 * полученных от Kinopoisk API.
	 *
	 * @param   array  $data  Массив данных изображения от API
	 *
	 * @return self Новый экземпляр изображения
	 *
	 * @throws \InvalidArgumentException Если данные имеют неверный формат
	 *
	 * @example
	 * ```php
	 * $apiData = [
	 *     'imageUrl' => 'https://kinopoisk.ru/images/full.jpg',
	 *     'previewUrl' => 'https://kinopoisk.ru/images/preview.jpg'
	 * ];
	 *
	 * $image = Image::fromArray($apiData);
	 * ```
	 */
	public static function fromArray(array $data): self {
		return new self(
			imageUrl  : $data['imageUrl'],
			previewUrl: $data['previewUrl'],
		);
	}

}