<?php

namespace NotKinopoisk\Responses;

use NotKinopoisk\Exception\KpValidationException;

/**
 * Ответ на поиск фильмов по ключевому слову
 *
 * Представляет результат поиска фильмов в Kinopoisk API по заданному ключевому слову.
 * Наследует функциональность пагинации от PaginatedResponse и добавляет специфичные
 * для поиска поля: ключевое слово, количество страниц и общее количество найденных фильмов.
 *
 * Основные возможности:
 * - Хранение результатов поиска с пагинацией
 * - Валидация входных параметров поиска
 * - Создание объекта из данных API
 * - Проверка логической согласованности данных
 *
 * @package NotKinopoisk\Responses
 * @since   1.0.0
 * @see     \NotKinopoisk\Responses\PaginatedResponse
 * @see     \NotKinopoisk\Services\FilmService::searchByKeyword()
 *
 * @example
 * ```php
 * // Создание ответа поиска
 * $response = new KeywordSearchResponse(
 *     keyword: 'матрица',
 *     pagesCount: 3,
 *     searchFilmsCountResult: 150,
 *     films: $filmsArray
 * );
 *
 * echo "По запросу '{$response->keyword}' найдено {$response->searchFilmsCountResult} фильмов";
 * echo "Страниц результатов: {$response->pagesCount}";
 * ```
 */
class KeywordSearchResponse extends PaginatedResponse {

	/**
	 * Конструктор ответа поиска по ключевому слову
	 *
	 * Создает новый экземпляр ответа поиска с валидацией входных параметров.
	 * Инициализирует родительский класс PaginatedResponse с фиксированной
	 * текущей страницей равной 1, так как поиск всегда возвращает первую страницу.
	 *
	 * @param   string  $keyword                 Ключевое слово для поиска (не может быть пустым)
	 * @param   int     $pagesCount              Общее количество страниц результатов (неотрицательное)
	 * @param   int     $searchFilmsCountResult  Общее количество найденных фильмов (неотрицательное)
	 * @param   array   $films                   Массив найденных фильмов (по умолчанию пустой)
	 *
	 * @throws \NotKinopoisk\Exception\KpValidationException При некорректных параметрах поиска
	 *
	 * @example
	 * ```php
	 * $response = new KeywordSearchResponse(
	 *     keyword: 'драма',
	 *     pagesCount: 5,
	 *     searchFilmsCountResult: 250,
	 *     films: $searchResults
	 * );
	 * ```
	 */
	public function __construct(public string $keyword, public int $pagesCount, public int $searchFilmsCountResult, public array $films = []) {
		$this->validateSearchParameters();

		parent::__construct(
			total      : $this->searchFilmsCountResult,
			items      : $this->films,
			currentPage: 1,
			totalPages : $this->pagesCount,
		);
	}

	/**
	 * Валидация параметров поиска
	 *
	 * Выполняет комплексную проверку корректности параметров поиска:
	 * - Проверяет, что ключевое слово не является пустой строкой
	 * - Валидирует неотрицательность количества страниц
	 * - Валидирует неотрицательность количества найденных фильмов
	 * - Проверяет логическую согласованность данных (если найдены фильмы, должны быть страницы)
	 *
	 * @throws \NotKinopoisk\Exception\KpValidationException При обнаружении некорректных данных
	 * @internal Метод предназначен для внутреннего использования
	 *
	 */
	private function validateSearchParameters(): void {
		if (empty(trim($this->keyword))) {
			throw new KpValidationException('Ключевое слово для поиска не может быть пустым');
		}

		if ($this->pagesCount < 0) {
			throw new KpValidationException(
				"Количество страниц должно быть неотрицательным, получено: {$this->pagesCount}",
			);
		}

		if ($this->searchFilmsCountResult < 0) {
			throw new KpValidationException(
				"Количество найденных фильмов должно быть неотрицательным, получено: {$this->searchFilmsCountResult}",
			);
		}

		// Проверка логической согласованности данных
		if ($this->searchFilmsCountResult > 0 && $this->pagesCount === 0) {
			throw new KpValidationException(
				'Некорректные данные: найдены фильмы, но количество страниц равно нулю',
			);
		}
	}

	/**
	 * Создает экземпляр ответа поиска из массива данных API
	 *
	 * Статический фабричный метод для создания объекта KeywordSearchResponse
	 * из данных, полученных от Kinopoisk API. Выполняет маппинг данных фильмов
	 * через переданный класс и обрабатывает ошибки типизации.
	 *
	 * @param   array   $data  Массив данных от API, содержащий результаты поиска
	 * @param   string  $cls   Имя класса для создания объектов фильмов (обычно Film::class)
	 *
	 * @return static Новый экземпляр ответа поиска
	 *
	 * @throws \NotKinopoisk\Exception\KpValidationException При ошибках типизации или некорректных данных
	 *
	 * @example
	 * ```php
	 * $apiResponse = [
	 *     'keyword' => 'комедия',
	 *     'pagesCount' => 10,
	 *     'searchFilmsCountResult' => 500,
	 *     'films' => [
	 *         ['kinopoiskId' => 123, 'nameRu' => 'Комедия 1'],
	 *         ['kinopoiskId' => 456, 'nameRu' => 'Комедия 2']
	 *     ]
	 * ];
	 *
	 * $response = KeywordSearchResponse::fromArray($apiResponse, Film::class);
	 * ```
	 */
	public static function fromArray(array $data, string $cls): self {
		parent::checkClass($cls);

		try {
			return new self(
				keyword               : $data['keyword'],
				pagesCount            : $data['pagesCount'],
				searchFilmsCountResult: $data['searchFilmsCountResult'],
				films                 : array_map(
					callback: static fn (array $itemData): object => $cls::fromArray($itemData),
					array   : $data['films'] ?? [],
				),
			);
		} catch (\TypeError|\ValueError $exception) {
			throw new KpValidationException(
				message : "Ошибка типизации при создании ответа поиска: {$exception->getMessage()}",
				previous: $exception,
			);
		}
	}

	/**
	 * Преобразует ответ поиска в массив
	 *
	 * Конвертирует все свойства объекта в ассоциативный массив,
	 * включая наследованные поля от PaginatedResponse.
	 * Преобразует объекты фильмов в массивы через их метод toArray().
	 *
	 * @return array Ассоциативный массив с данными ответа поиска
	 *
	 * @example
	 * ```php
	 * $responseArray = $searchResponse->toArray();
	 *
	 * // Результат:
	 * // [
	 * //     'keyword' => 'драма',
	 * //     'pagesCount' => 5,
	 * //     'searchFilmsCountResult' => 250,
	 * //     'films' => [...], // массив фильмов
	 * //     'total' => 250,
	 * //     'currentPage' => 1,
	 * //     'totalPages' => 5
	 * // ]
	 * ```
	 */
	public function toArray(): array {
		return [
			'keyword'                => $this->keyword,
			'pagesCount'             => $this->pagesCount,
			'searchFilmsCountResult' => $this->searchFilmsCountResult,
			'films'                  => array_map(fn (object $film) => $film->toArray(), $this->films),
			'total'                  => $this->total,
			'currentPage'            => $this->currentPage,
			'totalPages'             => $this->totalPages,
		];
	}

}