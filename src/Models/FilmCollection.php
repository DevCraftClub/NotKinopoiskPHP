<?php

declare(strict_types=1);

namespace NotKinopoisk\Models;

/**
 * Модель коллекции фильмов из Kinopoisk API
 *
 * Представляет коллекцию фильмов, полученную в результате поиска или
 * запроса списков (популярные, топ-250 и т.д.). Содержит массив фильмов
 * и метаданные о пагинации.
 *
 * Основные возможности:
 * - Хранение массива фильмов в неизменяемом виде
 * - Информация о пагинации (общее количество, количество страниц)
 * - Удобные методы для работы с коллекцией
 *
 * @package NotKinopoisk\Models
 * @since   1.0.0
 *
 * @author  Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @see     \NotKinopoisk\Models\Film
 * @see     \NotKinopoisk\Services\FilmService
 *
 * @example
 * ```php
 * // Создание из данных API
 * $collection = FilmCollection::fromArray($apiData);
 *
 * // Работа с коллекцией
 * echo "Найдено фильмов: {$collection->getCount()}\n";
 * echo "Всего страниц: {$collection->totalPages}\n";
 *
 * foreach ($collection->items as $film) {
 *     echo "- {$film->getDisplayName()}\n";
 * }
 * ```
 */
class FilmCollection {

	/**
	 * Конструктор коллекции фильмов
	 *
	 * Создает новую коллекцию фильмов с указанными данными.
	 * Все свойства являются readonly для обеспечения неизменяемости объекта.
	 *
	 * @param   \NotKinopoisk\Models\FilmCollectionItem[]  $items       Массив объектов Film в коллекции
	 * @param   int                                        $total       Общее количество фильмов (всего в базе данных)
	 * @param   int                                        $totalPages  Общее количество страниц для пагинации
	 *
	 * @example
	 * ```php
	 * $collection = new FilmCollection(
	 *     items: [$film1, $film2, $film3],
	 *     total: 150,
	 *     totalPages: 5
	 * );
	 * ```
	 */
	public function __construct(
		public readonly array $items,
		public readonly int   $total,
		public readonly int   $totalPages,
	) {}

	/**
	 * Создает экземпляр коллекции фильмов из массива данных API
	 *
	 * Статический метод для удобного создания объекта FilmCollection из данных,
	 * полученных от Kinopoisk API. Автоматически обрабатывает различные форматы
	 * ответов API и создает объекты Film для каждого элемента.
	 *
	 * @param   array  $data  Массив данных коллекции от API
	 *
	 * @return self Новый экземпляр коллекции фильмов
	 *
	 * @throws \InvalidArgumentException Если данные имеют неверный формат
	 *
	 * @example
	 * ```php
	 * $apiData = [
	 *     'items' => [
	 *         ['kinopoiskId' => 301, 'nameRu' => 'Матрица', ...],
	 *         ['kinopoiskId' => 302, 'nameRu' => 'Матрица: Перезагрузка', ...]
	 *     ],
	 *     'total' => 150,
	 *     'totalPages' => 5
	 * ];
	 *
	 * $collection = FilmCollection::fromArray($apiData);
	 * ```
	 */
	public static function fromArray(array $data): self {

		return new self(
			items     : array_map(fn ($filmData) => FilmCollectionItem::fromArray($filmData), $data['items']),
			total     : $data['total'] ?? $data['searchFilmsCountResult'] ?? 0,
			totalPages: $data['totalPages'] ?? $data['pagesCount'] ?? 1,
		);
	}

	/**
	 * Получает количество фильмов в текущей коллекции
	 *
	 * Возвращает количество фильмов на текущей странице/в текущем результате.
	 * Для получения общего количества фильмов используйте свойство $total.
	 *
	 * @return int Количество фильмов в коллекции
	 *
	 * @example
	 * ```php
	 * $collection = FilmCollection::fromArray($apiData);
	 * echo "На этой странице: {$collection->getCount()} фильмов\n";
	 * echo "Всего найдено: {$collection->total} фильмов\n";
	 * ```
	 */
	public function getCount(): int {
		return count($this->items);
	}

	/**
	 * Проверяет, пуста ли коллекция
	 *
	 * Возвращает true, если в коллекции нет фильмов, и false в противном случае.
	 *
	 * @return bool true если коллекция пуста, false если содержит фильмы
	 *
	 * @example
	 * ```php
	 * if ($collection->isEmpty()) {
	 *     echo "Фильмы не найдены\n";
	 * } else {
	 *     echo "Найдено фильмов: {$collection->getCount()}\n";
	 * }
	 * ```
	 */
	public function isEmpty(): bool {
		return empty($this->items);
	}

	/**
	 * Получает первый фильм из коллекции
	 *
	 * Возвращает первый элемент массива фильмов или null, если коллекция пуста.
	 *
	 * @return \NotKinopoisk\Models\FilmCollectionItem|null Первый фильм или null
	 *
	 * @example
	 * ```php
	 * $firstFilm = $collection->getFirst();
	 * if ($firstFilm !== null) {
	 *     echo "Первый фильм: {$firstFilm->getDisplayName()}\n";
	 * }
	 * ```
	 */
	public function getFirst(): ?FilmCollectionItem {
		return $this->items[0] ?? NULL;
	}

	/**
	 * Получает последний фильм из коллекции
	 *
	 * Возвращает последний элемент массива фильмов или null, если коллекция пуста.
	 *
	 * @return \NotKinopoisk\Models\FilmCollectionItem|null Последний фильм или null
	 *
	 * @example
	 * ```php
	 * $lastFilm = $collection->getLast();
	 * if ($lastFilm !== null) {
	 *     echo "Последний фильм: {$lastFilm->getDisplayName()}\n";
	 * }
	 * ```
	 */
	public function getLast(): ?FilmCollectionItem {
		return $this->items[count($this->items) - 1] ?? NULL;
	}

}