<?php

namespace NotKinopoisk\Responses;

use NotKinopoisk\Exception\KpValidationException;
use NotKinopoisk\Interfaces\ResponseInterface;

/**
 * Базовый класс для ответов Kinopoisk API
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
 * @see     \NotKinopoisk\Interfaces\ResponseInterface
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
class DefaultResponse implements ResponseInterface {

	/**
	 * Конструктор ответа
	 *
	 * Создает новый экземпляр ответа с указанным общим количеством элементов
	 * и массивом данных. Используется в дочерних классах для инициализации
	 * базовых свойств ответа.
	 *
	 * @param   int    $total  Общее количество элементов в коллекции (включая не загруженные)
	 * @param   array  $items  Массив элементов данных в текущем ответе
	 *
	 * @throws \NotKinopoisk\Exception\KpValidationException Если данные невалидны
	 * @example
	 *     ```php
	 *     // В дочернем классе
	 *     public function __construct(int $total, array $films) {
	 *     parent::__construct($total, $films);
	 *     }
	 *     ```
	 */
	public function __construct(
		public int   $total,
		public array $items,
	) {
		$this->validateTotalCount($total);
		$this->validateItems($items);
	}

	/**
	 * Валидирует общее количество элементов
	 *
	 * @param   int  $total  Общее количество элементов
	 *
	 * @throws KpValidationException Если значение невалидно
	 */
	private function validateTotalCount(int $total): void {
		if ($total < 0) {
			throw new KpValidationException('Общее количество элементов не может быть отрицательным');
		}
	}

	/**
	 * Валидирует массив элементов
	 *
	 * @param   array  $items  Массив элементов
	 *
	 * @throws KpValidationException Если массив невалиден
	 */
	private function validateItems(array $items): void {
		if ($this->total > 0 && empty($items)) {
			// Это нормальная ситуация - может быть пагинация
			return;
		}

		if ($this->total === 0 && !empty($items)) {
			throw new KpValidationException('Несоответствие: total=0, но items содержит элементы');
		}
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
	 * @throws KpValidationException Если указанный класс не существует
	 * @throws KpValidationException Если класс не имеет метода fromArray
	 * @throws KpValidationException Если данные имеют неверный формат
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
	public static function fromArray(array $data, string $cls): static {
		self::validateApiData($data);
		self::checkClass($cls);

		$items = match (empty($data['items'])) {
			TRUE  => [],
			FALSE => array_map(
				static fn (array $itemData): object => $cls::fromArray($itemData),
				$data['items'],
			),
		};

		return new static(
			total: $data['total'],
			items: $items,
		);
	}

	/**
	 * Валидирует данные API перед обработкой
	 *
	 * @param   array  $data  Данные для валидации
	 *
	 * @throws KpValidationException Если данные имеют неверный формат
	 */
	private static function validateApiData(array $data): void {
		if (!array_key_exists('total', $data)) {
			throw new KpValidationException('Отсутствует обязательное поле "total" в данных API');
		}

		if (!array_key_exists('items', $data)) {
			throw new KpValidationException('Отсутствует обязательное поле "items" в данных API');
		}

		if (!is_int($data['total']) || $data['total'] < 0) {
			throw new KpValidationException('Поле "total" должно быть неотрицательным целым числом');
		}

		if (!is_array($data['items'])) {
			throw new KpValidationException('Поле "items" должно быть массивом');
		}
	}

	/**
	 * Валидирует целевой класс для преобразования элементов
	 *
	 * @param   string  $cls  Полное имя класса
	 *
	 * @throws KpValidationException Если класс невалиден
	 */
	public static function checkClass(string $cls): void {
		if (!class_exists($cls)) {
			throw new KpValidationException("Указанный класс не существует: {$cls}");
		}

		if (!method_exists($cls, 'fromArray')) {
			throw new KpValidationException("Класс {$cls} не имеет статического метода fromArray");
		}

		$reflection = new \ReflectionMethod($cls, 'fromArray');
		if (!$reflection->isStatic()) {
			throw new KpValidationException("Метод fromArray в классе {$cls} должен быть статическим");
		}
	}

	/**
	 * Возвращает первый элемент или null, если элементов нет
	 *
	 * @return object|null Первый элемент или null
	 */
	public function first(): ?object {
		return $this->items[0] ?? NULL;
	}

	/**
	 * Возвращает последний элемент или null, если элементов нет
	 *
	 * @return object|null Последний элемент или null
	 */
	public function last(): ?object {
		return $this->isEmpty() ? NULL : $this->items[array_key_last($this->items)];
	}

	/**
	 * Проверяет, пуст ли ответ
	 *
	 * @return bool true, если нет элементов
	 */
	public function isEmpty(): bool {
		return empty($this->items);
	}

	/**
	 * Преобразует ответ в массив
	 *
	 * @return array{total: int, items: array}
	 */
	public function toArray(): array {
		return [
			'total' => $this->total,
			'items' => $this->items
		];
	}

	/**
	 * Возвращает количество элементов в текущем ответе
	 *
	 * @return int Количество загруженных элементов
	 */
	public function getCount(): int {
		return count($this->items);
	}

}