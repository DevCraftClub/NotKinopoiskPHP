<?php

namespace NotKinopoisk\Responses;

use NotKinopoisk\Exception\KpValidationException;

/**
 * Абстрактный базовый класс для ответов Kinopoisk API
 *
 * Предоставляет базовую функциональность для всех типов ответов API,
 * включая создание из массива данных и валидацию целевого класса.
 * Содержит общие свойства: общее количество элементов и массив элементов.
 *
 * Основные возможности:
 * - Хранение общего количества элементов и массива данных
 * - Создание экземпляра из массива данных API с валидацией
 * - Автоматическое преобразование элементов в указанный класс
 * - Проверка существования и совместимости целевого класса
 *
 * @package NotKinopoisk\Responses
 * @since   1.0.0
 *
 * @author  Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @see     \NotKinopoisk\Responses\ResponseInterface
 * @see     \NotKinopoisk\Exception\KpValidationException
 *
 * @example
 * ```php
 * // Создание конкретного класса-наследника
 * class FilmResponse extends AbstractResponse {
 *     // Реализация конкретного типа ответа
 * }
 *
 * // Использование
 * $response = FilmResponse::fromArray($apiData, Film::class);
 * echo "Всего элементов: {$response->total}\n";
 * echo "Загружено: " . count($response->items);
 * ```
 */
class PaginatedResponse extends DefaultResponse {
	public int $currentPage;
	public int $totalPages;


	/**
	 * Конструктор абстрактного ответа
	 *
	 * Создает новый экземпляр ответа с указанным общим количеством элементов
	 * и массивом данных. Используется в дочерних классах для инициализации
	 * базовых свойств ответа.
	 *
	 * @param   int    $total  Общее количество элементов в коллекции (включая не загруженные)
	 * @param   array  $items  Массив элементов данных в текущем ответе
	 *
	 * @example
	 * ```php
	 * // В дочернем классе
	 * public function __construct(int $total, array $films) {
	 *     parent::__construct($total, $films);
	 * }
	 * ```
	 */
	public function __construct(int $total, int $currentPage, int $totalPages, array $items) {
		parent::__construct($total, $items);
		$this->totalPages = $totalPages;
		$this->currentPage = $currentPage;
	}

	/**
	 * Создает экземпляр ответа из массива данных API
	 *
	 * Статический метод-фабрика для создания объекта ответа из данных,
	 * полученных от Kinopoisk API. Выполняет валидацию целевого класса
	 * и автоматически преобразует каждый элемент массива items в указанный тип.
	 *
	 * @param   array   $data  Массив данных от API, содержащий 'total' и 'items'
	 * @param   string  $cls   Полное имя класса для преобразования элементов
	 *
	 * @return static Новый экземпляр текущего класса с преобразованными данными
	 *
	 * @throws \NotKinopoisk\Exception\KpValidationException Если указанный класс не существует
	 * @throws \NotKinopoisk\Exception\KpValidationException Если класс не имеет метода fromArray
	 *
	 * @example
	 * ```php
	 * $apiData = [
	 *     'total' => 1000,
	 *     'items' => [
	 *         ['id' => 1, 'name' => 'Film 1'],
	 *         ['id' => 2, 'name' => 'Film 2']
	 *     ]
	 * ];
	 *
	 * $response = FilmResponse::fromArray($apiData, Film::class);
	 * // $response->items будет содержать массив объектов Film
	 * ```
	 */
	public static function fromArray(array $data, string $cls): self {
		DefaultResponse::checkClass($cls);

		try {
			return new static(
				total: $data['total'],
				totalPages: $data['total_pages'],
				items: array_map(fn ($itemData) => $cls::fromArray($itemData), $data['items']),
			);
		} catch (\Exception $e) {
			throw new KpValidationException("Ошибка при создании экземпляра ответа: {$e->getMessage()}", $e->getCode(), $e);
		}
	}

	public function hasNextPage(int $currentPage): bool {
		return $currentPage < $this->totalPages;
	}

	public function getNextPage(): int {
        return $this->currentPage + 1;
    }

}