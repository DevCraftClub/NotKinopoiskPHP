<?php

namespace NotKinopoisk\Responses;

use NotKinopoisk\Exception\KpValidationException;

/**
 * Пагинированный ответ API с поддержкой навигации по страницам
 *
 * Расширяет DefaultResponse, добавляя функциональность пагинации
 * с безопасной навигацией по страницам и валидацией границ.
 *
 * Основные возможности:
 * - Безопасная навигация по страницам с валидацией границ
 * - Promoted constructor properties для краткости кода
 * - Typed properties для строгой типизации
 * - Улучшенная обработка ошибок
 *
 * @package NotKinopoisk\Responses
 * @since   1.0.0
 *
 * @author  Maxim Harder <dev@devcraft.club>
 * @version 2.0.0
 * @see     \NotKinopoisk\Responses\DefaultResponse
 * @see     \NotKinopoisk\Exception\KpValidationException
 *
 * @example
 * ```php
 * $response = PaginatedResponse::fromArray($apiData, Film::class);
 *
 * // Навигация по страницам
 * if ($response->hasNextPage()) {
 *     $nextPage = $response->getNextPage();
 * }
 *
 * // Получение информации о пагинации
 * echo "Страница {$response->currentPage} из {$response->totalPages}";
 * ```
 */
class PaginatedResponse extends DefaultResponse {

	/**
	 * Конструктор пагинированного ответа
	 *
	 * Создает новый экземпляр с поддержкой пагинации.
	 * Использует promoted constructor properties для краткости.
	 *
	 * @param   int    $total        Общее количество элементов
	 * @param   array  $items        Массив элементов текущей страницы
	 * @param   int    $currentPage  Номер текущей страницы (начиная с 1)
	 * @param   int    $totalPages   Общее количество страниц
	 *
	 * @throws \NotKinopoisk\Exception\KpValidationException При некорректных значениях пагинации
	 *
	 * @example
	 * ```php
	 * $response = new PaginatedResponse(
	 *     total: 1000,
	 *     items: $filmsArray,
	 *     currentPage: 5,
	 *     totalPages: 50
	 * );
	 * ```
	 */

	public function __construct(int $total, array $items, public int $currentPage, public int $totalPages) {
		$this->validatePaginationParameters($currentPage, $totalPages);
		parent::__construct($total, $items);
	}

	/**
	 * Валидирует параметры пагинации
	 *
	 * @param   int  $currentPage  Номер текущей страницы
	 * @param   int  $totalPages   Общее количество страниц
	 *
	 * @throws KpValidationException При неверных параметрах пагинации
	 */
	protected function validatePaginationParameters(int $currentPage, int $totalPages): void {
		if ($currentPage < 1) {
			throw new KpValidationException(
				message: "Номер текущей страницы должен быть больше 0, получен: {$currentPage}",
			);
		}

		if ($totalPages < 0) {
			throw new KpValidationException(
				message: "Общее количество страниц должно быть неотрицательным, получено: {$totalPages}",
			);
		}

		if ($totalPages > 0 && $currentPage > $totalPages) {
			throw new KpValidationException(
				message: "Номер текущей страницы ({$currentPage}) не может превышать общее количество страниц ({$totalPages})",
			);
		}
	}

	/**
	 * Создает экземпляр из массива данных API
	 *
	 * Фабричный метод с улучшенной валидацией и обработкой ошибок.
	 * Автоматически преобразует элементы в указанный тип класса.
	 *
	 * @param   array   $data  Данные API с обязательными ключами
	 * @param   string  $cls   Класс для преобразования элементов
	 *
	 * @return static Новый экземпляр с преобразованными данными
	 *
	 * @throws KpValidationException При ошибках валидации или преобразования
	 *
	 * @example
	 * ```php
	 * $apiData = [
	 *     'total' => 1000,
	 *     'total_pages' => 50,
	 *     'current_page' => 5,
	 *     'items' => [...]
	 * ];
	 *
	 * $response = PaginatedResponse::fromArray($apiData, Film::class);
	 * ```
	 */
	public static function fromArray(array $data, string $cls): static {
		static::validateApiData($data);
		parent::checkClass($cls);

		try {
			$items = array_map(
				callback: static fn (array $itemData): object => $cls::fromArray($itemData),
				array   : $data['items'] ?? [],
			);

			return new static(
				total      : (int) $data['total'],
				items      : $items,
				currentPage: (int) ($data['current_page'] ?? $data['page'] ?? 1),
				totalPages : (int) ($data['totalPages'] ?? 1), // Fallback to 1 if not provided
			);
		} catch (\TypeError|\ValueError $e) {
			throw new KpValidationException(
				message : "Ошибка типизации при создании пагинированного ответа: {$e->getMessage()}",
				previous: $e,
			);
		} catch (\Exception $e) {
			throw new KpValidationException(
				message : "Ошибка при создании экземпляра пагинированного ответа: {$e->getMessage()}",
				code    : $e->getCode(),
				previous: $e,
			);
		}
	}

	/**
	 * Валидирует данные API для создания пагинированного ответа
	 *
	 * Проверяет наличие обязательных полей в данных API.
	 * Выбрасывает исключение, если данные не соответствуют ожидаемому формату.
	 *
	 * @param   array  $data  Данные от API
	 *
	 * @throws \NotKinopoisk\Exception\KpValidationException Если данные невалидны
	 */
	protected static function validateApiData(array $data): void {
		$requiredKeys = ['total', 'items'];
		$missingKeys  = array_diff($requiredKeys, array_keys($data));

		if (!empty($missingKeys)) {
			throw new KpValidationException(
				message: 'Отсутствуют обязательные ключи в данных API: ' . implode(', ', $missingKeys),
			);
		}
	}

	/**
	 * Получает номер следующей страницы
	 *
	 * @return int Номер следующей страницы
	 *
	 * @throws \OutOfBoundsException Если следующая страница недоступна
	 */
	public function getNextPage(): int {
		if (!$this->hasNextPage()) {
			throw new \OutOfBoundsException(
				message: "Нет следующей страницы. Текущая страница {$this->currentPage} из {$this->totalPages}",
			);
		}

		return $this->currentPage + 1;
	}

	/**
	 * Проверяет наличие следующей страницы
	 *
	 * @return bool Доступна ли следующая страница
	 */
	public function hasNextPage(): bool {
		return $this->currentPage < $this->totalPages;
	}

	/**
	 * Получает номер предыдущей страницы
	 *
	 * @return int Номер предыдущей страницы
	 *
	 * @throws \OutOfBoundsException Если предыдущая страница недоступна
	 */
	public function getPreviousPage(): int {
		if (!$this->hasPreviousPage()) {
			throw new \OutOfBoundsException(
				message: "Нет предыдущей страницы. Текущая страница {$this->currentPage}",
			);
		}

		return $this->currentPage - 1;
	}

	/**
	 * Проверяет наличие предыдущей страницы
	 *
	 * @return bool Доступна ли предыдущая страница
	 */
	public function hasPreviousPage(): bool {
		return $this->currentPage > 1;
	}

	/**
	 * Получает номер первой страницы
	 *
	 * @return int Всегда возвращает 1
	 */
	public function getFirstPage(): int {
		return 1;
	}

	/**
	 * Получает номер последней страницы
	 *
	 * @return int Номер последней страницы
	 */
	public function getLastPage(): int {
		return $this->totalPages;
	}

	/**
	 * Проверяет, является ли текущая страница первой
	 *
	 * @return bool Является ли первой страницей
	 */
	public function isFirstPage(): bool {
		return $this->currentPage === 1;
	}

	/**
	 * Проверяет, является ли текущая страница последней
	 *
	 * @return bool Является ли последней страницей
	 */
	public function isLastPage(): bool {
		return $this->currentPage === $this->totalPages;
	}

	/**
	 * Получает информацию о пагинации в виде массива
	 *
	 * @return array{current: int, total: int, hasNext: bool, hasPrevious: bool}
	 */
	public function getPaginationInfo(): array {
		return [
			'current'     => $this->currentPage,
			'total'       => $this->totalPages,
			'hasNext'     => $this->hasNextPage(),
			'hasPrevious' => $this->hasPreviousPage(),
		];
	}

	/**
	 * Преобразует объект пагинированного ответа в массив
	 *
	 * Создает массив со всеми данными о пагинации и элементами результата.
	 * Каждый элемент в массиве items также преобразуется в массив с помощью
	 * вызова его метода toArray().
	 *
	 * @return array Массив с данными пагинации, содержащий:
	 *               - total: общее количество элементов
	 *               - items: массив элементов, преобразованных в массивы
	 *               - current_page: текущая страница
	 *               - total_pages: общее количество страниц
	 *
	 * @example
	 * ```php
	 * $paginatedResponse = new PaginatedResponse(
	 *     items: [$film1, $film2],
	 *     total: 150,
	 *     currentPage: 1,
	 *     totalPages: 15
	 * );
	 *
	 * $array = $paginatedResponse->toArray();
	 * // Результат:
	 * // [
	 * //     'total' => 150,
	 * //     'items' => [
	 * //         ['id' => 1, 'name' => 'Фильм 1', ...],
	 * //         ['id' => 2, 'name' => 'Фильм 2', ...]
	 * //     ],
	 * //     'current_page' => 1,
	 * //     'total_pages' => 15
	 * // ]
	 * ```
	 */
	public function toArray(): array {
		return [
			'total'        => $this->total,
			'items'        => array_map(
				callback: static fn ($item): array => $item->toArray(),
				array   : $this->items,
			),
			'current_page' => $this->currentPage,
			'total_pages'  => $this->totalPages,
		];
	}

}