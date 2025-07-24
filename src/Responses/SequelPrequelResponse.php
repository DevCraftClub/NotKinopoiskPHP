<?php

declare(strict_types=1);

namespace NotKinopoisk\Responses;

use NotKinopoisk\Enums\RelationType;
use NotKinopoisk\Exception\KpValidationException;
use NotKinopoisk\Models\RelatedFilm;

/**
 * Ответ API для сиквелов и приквелов фильмов
 *
 * Предоставляет специализированные методы для работы с связанными фильмами
 * по типу отношения. Поддерживает сиквелы, приквелы, римейки и похожие фильмы.
 * Наследует функциональность SimpleResponse для базовой работы с коллекциями.
 *
 * Основные возможности:
 * - Фильтрация фильмов по типу отношения (сиквелы, приквелы, римейки, похожие)
 * - Объединение и сортировка связанных фильмов
 * - Статистика по типам отношений
 * - Группировка фильмов по типам связей
 * - Проверка наличия фильмов определенного типа
 *
 * @package NotKinopoisk\Responses
 * @since   1.0.0
 * @author  Maxim Harder <dev@devcraft.club>
 * @version 2.0.0
 */
final class SequelPrequelResponse extends SimpleResponse {

	/**
	 * Получает объединённый и отсортированный список приквелов и сиквелов
	 *
	 * Объединяет фильмы-приквелы и сиквелы в один массив и сортирует
	 * их по ключам для обеспечения консистентного порядка.
	 * Метод полезен для получения хронологически упорядоченного списка
	 * связанных фильмов франшизы.
	 *
	 * @return array<int, RelatedFilm> Отсортированный массив приквелов и сиквелов
	 *
	 * @throws KpValidationException При ошибках во время обработки данных
	 *
	 * @example
	 * ```php
	 * $combined = $response->getPrequelsAndSequels();
	 * foreach ($combined as $film) {
	 *     echo "{$film->getDisplayName()} - {$film->relationType->getDescription()}\n";
	 * }
	 * ```
	 */
	public function getPrequelsAndSequels(): array {
		try {
			$combined = array_merge(
				$this->getSequels(),
				$this->getPrequels(),
			);

			if (empty($combined)) {
				return [];
			}

			// ksort модифицирует массив in-place и возвращает bool
			if (!ksort($combined)) {
				throw new KpValidationException(
					message: 'Ошибка при сортировке объединённого списка фильмов',
				);
			}

			return $combined;
		} catch (KpValidationException $e) {
			// Повторно выбрасываем наши собственные исключения
			throw $e;
		} catch (\Error|\Exception $e) {
			throw new KpValidationException(
				message : "Неожиданная ошибка при объединении приквелов и сиквелов: {$e->getMessage()}",
				previous: $e,
			);
		}
	}

	/**
	 * Получает все фильмы-сиквелы
	 *
	 * Фильтрует коллекцию и возвращает только фильмы, которые являются
	 * продолжениями основного фильма (сиквелы).
	 *
	 * @return array<int, RelatedFilm> Массив фильмов-сиквелов
	 *
	 * @throws KpValidationException При некорректной структуре данных
	 *
	 * @example
	 * ```php
	 * $sequels = $response->getSequels();
	 * echo "Найдено сиквелов: " . count($sequels) . "\n";
	 * foreach ($sequels as $sequel) {
	 *     echo "- {$sequel->getDisplayName()}\n";
	 * }
	 * ```
	 */
	public function getSequels(): array {
		try {
			return array_filter(
				array   : $this->items,
				callback: static fn (RelatedFilm $item): bool => $item->relationType === RelationType::SEQUEL,
			);
		} catch (\TypeError $e) {
			throw new KpValidationException(
				message : 'Ошибка при фильтрации фильмов-сиквелов: некорректная структура данных',
				previous: $e,
			);
		}
	}

	/**
	 * Получает все фильмы-приквелы
	 *
	 * Фильтрует коллекцию и возвращает только фильмы, которые являются
	 * предысториями основного фильма (приквелы).
	 *
	 * @return array<int, RelatedFilm> Массив фильмов-приквелов
	 *
	 * @throws KpValidationException При некорректной структуре данных
	 *
	 * @example
	 * ```php
	 * $prequels = $response->getPrequels();
	 * echo "Найдено приквелов: " . count($prequels) . "\n";
	 * foreach ($prequels as $prequel) {
	 *     echo "- {$prequel->getDisplayName()}\n";
	 * }
	 * ```
	 */
	public function getPrequels(): array {
		try {
			return array_filter(
				array   : $this->items,
				callback: static fn (RelatedFilm $item): bool => $item->relationType === RelationType::PREQUEL,
			);
		} catch (\TypeError $e) {
			throw new KpValidationException(
				message : 'Ошибка при фильтрации фильмов-приквелов: некорректная структура данных',
				previous: $e,
			);
		}
	}

	/**
	 * Проверяет наличие фильмов указанного типа
	 *
	 * Быстрая проверка существования фильмов определенного типа отношения
	 * без необходимости получения полного списка.
	 *
	 * @param   RelationType  $type  Тип отношения для проверки
	 *
	 * @return bool True если фильмы присутствуют, false в противном случае
	 *
	 * @example
	 * ```php
	 * if ($response->hasFilmsOfType(RelationType::SEQUEL)) {
	 *     echo "У фильма есть сиквелы\n";
	 * }
	 *
	 * if ($response->hasFilmsOfType(RelationType::PREQUEL)) {
	 *     echo "У фильма есть приквелы\n";
	 * }
	 * ```
	 */
	public function hasFilmsOfType(RelationType $type): bool {
		return !empty($this->getByRelationType($type));
	}

	/**
	 * Получает все фильмы указанного типа отношения
	 *
	 * Централизованный метод для получения фильмов по типу отношения.
	 * Использует современную PHP 8.3 реализацию с match-выражением
	 * для эффективной фильтрации по типу отношения.
	 *
	 * @param   RelationType  $type  Тип отношения между фильмами
	 *
	 * @return array<int, RelatedFilm> Массив фильмов указанного типа
	 *
	 * @throws KpValidationException При некорректных параметрах или данных
	 *
	 * @example
	 * ```php
	 * // Получение фильмов по типу
	 * $sequels = $response->getByRelationType(RelationType::SEQUEL);
	 * $prequels = $response->getByRelationType(RelationType::PREQUEL);
	 * $remakes = $response->getByRelationType(RelationType::REMAKE);
	 * $similar = $response->getByRelationType(RelationType::SIMILAR);
	 * ```
	 */
	public function getByRelationType(RelationType $type): array {
		try {
			return match ($type) {
				RelationType::SEQUEL  => $this->getSequels(),
				RelationType::PREQUEL => $this->getPrequels(),
				RelationType::REMAKE  => $this->getRemakes(),
				RelationType::SIMILAR => $this->getSimilar(),
				RelationType::UNKNOWN => array_filter($this->items,
					static fn (RelatedFilm $item): bool => $item->relationType === RelationType::UNKNOWN),
				default               => throw new KpValidationException(
					message: "Неподдерживаемый тип отношения: {$type->value}",
				)
			};
		} catch (KpValidationException $e) {
			throw $e;
		} catch (\Error|\Exception $e) {
			throw new KpValidationException(
				message : "Ошибка при фильтрации по типу отношения: {$e->getMessage()}",
				previous: $e,
			);
		}
	}

	/**
	 * Получает все римейки
	 *
	 * Фильтрует коллекцию и возвращает только фильмы, которые являются
	 * новыми версиями основного фильма (римейки).
	 *
	 * @return array<int, RelatedFilm> Массив римейков
	 *
	 * @throws KpValidationException При некорректной структуре данных
	 *
	 * @example
	 * ```php
	 * $remakes = $response->getRemakes();
	 * echo "Найдено римейков: " . count($remakes) . "\n";
	 * foreach ($remakes as $remake) {
	 *     echo "- {$remake->getDisplayName()}\n";
	 * }
	 * ```
	 */
	public function getRemakes(): array {
		try {
			return array_filter(
				array   : $this->items,
				callback: static fn (RelatedFilm $item): bool => $item->relationType === RelationType::REMAKE,
			);
		} catch (\TypeError $e) {
			throw new KpValidationException(
				message : 'Ошибка при фильтрации римейков: некорректная структура данных',
				previous: $e,
			);
		}
	}

	/**
	 * Получает все похожие фильмы
	 *
	 * Фильтрует коллекцию и возвращает только фильмы, которые похожи
	 * по жанру, стилю или содержанию на основной фильм.
	 *
	 * @return array<int, RelatedFilm> Массив похожих фильмов
	 *
	 * @throws KpValidationException При некорректной структуре данных
	 *
	 * @example
	 * ```php
	 * $similar = $response->getSimilar();
	 * echo "Найдено похожих фильмов: " . count($similar) . "\n";
	 * foreach ($similar as $film) {
	 *     echo "- {$film->getDisplayName()}\n";
	 * }
	 * ```
	 */
	public function getSimilar(): array {
		try {
			return array_filter(
				array   : $this->items,
				callback: static fn (RelatedFilm $item): bool => $item->relationType === RelationType::SIMILAR,
			);
		} catch (\TypeError $e) {
			throw new KpValidationException(
				message : 'Ошибка при фильтрации похожих фильмов: некорректная структура данных',
				previous: $e,
			);
		}
	}

	/**
	 * Получает статистику по всем типам отношений
	 *
	 * Возвращает ассоциативный массив с количеством фильмов
	 * для каждого типа отношения. Полезно для анализа структуры
	 * связанных фильмов.
	 *
	 * @return array<string, int> Статистика по типам отношений
	 *
	 * @example
	 * ```php
	 * $stats = $response->getStatistics();
	 * echo "Общая статистика:\n";
	 * echo "- Сиквелов: {$stats['sequels']}\n";
	 * echo "- Приквелов: {$stats['prequels']}\n";
	 * echo "- Римейков: {$stats['remakes']}\n";
	 * echo "- Похожих: {$stats['similar']}\n";
	 * echo "- Всего: {$stats['total']}\n";
	 * ```
	 */
	public function getStatistics(): array {
		return [
			'sequels'  => $this->countByType(RelationType::SEQUEL),
			'prequels' => $this->countByType(RelationType::PREQUEL),
			'remakes'  => $this->countByType(RelationType::REMAKE),
			'similar'  => $this->countByType(RelationType::SIMILAR),
			'unknown'  => $this->countByType(RelationType::UNKNOWN),
			'total'    => count($this->items),
		];
	}

	/**
	 * Возвращает количество фильмов указанного типа
	 *
	 * Быстрый подсчет фильмов определенного типа без получения
	 * полного списка объектов.
	 *
	 * @param   RelationType  $type  Тип отношения для подсчёта
	 *
	 * @return int Количество фильмов указанного типа
	 *
	 * @example
	 * ```php
	 * $sequelCount = $response->countByType(RelationType::SEQUEL);
	 * echo "Количество сиквелов: $sequelCount\n";
	 *
	 * $prequelCount = $response->countByType(RelationType::PREQUEL);
	 * echo "Количество приквелов: $prequelCount\n";
	 * ```
	 */
	public function countByType(RelationType $type): int {
		return count($this->getByRelationType($type));
	}

	/**
	 * Проверяет, есть ли связанные фильмы
	 *
	 * Быстрая проверка наличия любых связанных фильмов в коллекции.
	 *
	 * @return bool True если есть хотя бы один связанный фильм
	 *
	 * @example
	 * ```php
	 * if ($response->hasRelatedFilms()) {
	 *     echo "У фильма есть связанные фильмы\n";
	 * } else {
	 *     echo "У фильма нет связанных фильмов\n";
	 * }
	 * ```
	 */
	public function hasRelatedFilms(): bool {
		return !empty($this->items);
	}

	/**
	 * Получает все уникальные типы отношений в текущем наборе данных
	 *
	 * Возвращает массив уникальных типов отношений, которые присутствуют
	 * в текущей коллекции связанных фильмов.
	 *
	 * @return array<RelationType> Массив уникальных типов отношений
	 *
	 * @example
	 * ```php
	 * $types = $response->getAvailableRelationTypes();
	 * echo "Доступные типы отношений:\n";
	 * foreach ($types as $type) {
	 *     echo "- {$type->getDescription()}\n";
	 * }
	 * ```
	 */
	public function getAvailableRelationTypes(): array {
		$types = [];
		foreach ($this->items as $item) {
			if ($item instanceof RelatedFilm) {
				$types[$item->relationType->value] = $item->relationType;
			}
		}

		return array_values($types);
	}

	/**
	 * Группирует связанные фильмы по типу отношения
	 *
	 * Выполняет группировку элементов коллекции по типам связей с основным фильмом.
	 * Создает структурированный массив, где ключами являются строковые значения
	 * типов связей, а значениями - массивы соответствующих фильмов.
	 *
	 * Алгоритм работы:
	 * 1. Инициализирует пустой массив групп
	 * 2. Перебирает все элементы коллекции $this->items
	 * 3. Для каждого элемента типа RelatedFilm извлекает строковое значение типа связи
	 * 4. Создает новую группу, если она ещё не существует
	 * 5. Добавляет фильм в соответствующую группу
	 *
	 * @see \NotKinopoisk\Models\RelatedFilm Модель связанного фильма
	 * @see \NotKinopoisk\Enums\RelationType Перечисление типов связей
	 *
	 * @return array<string, array<RelatedFilm>> Ассоциативный массив, где ключи - строковые значения типов связей (SIMILAR, SEQUEL, PREQUEL, REMAKE,
	 *                       UNKNOWN), а значения - массивы объектов RelatedFilm соответствующего типа
	 *
	 * @example
	 * ```php
	 * // Группировка связанных фильмов
	 * $groups = $response->groupByRelationType();
	 *
	 * // Обработка сгруппированных данных
	 * foreach ($groups as $relationType => $films) {
	 *     echo "Тип связи: {$relationType}\n";
	 *     echo "Количество фильмов: " . count($films) . "\n";
	 *
	 *     foreach ($films as $film) {
	 *         echo "  - {$film->getDisplayName()}\n";
	 *     }
	 *     echo "\n";
	 * }
	 *
	 * // Получение конкретного типа связи
	 * $sequels = $groups['SEQUEL'] ?? [];
	 * $similars = $groups['SIMILAR'] ?? [];
	 *
	 * // Проверка наличия определённого типа
	 * if (isset($groups['PREQUEL'])) {
	 *     echo "Найдены приквелы: " . count($groups['PREQUEL']) . " шт.\n";
	 * }
	 * ```
	 */
	public function groupByRelationType(): array {
		$groups = [];
		foreach ($this->items as $item) {
			if ($item instanceof RelatedFilm) {
				$typeKey = $item->relationType->value;
				if (!isset($groups[$typeKey])) {
					$groups[$typeKey] = [];
				}
				$groups[$typeKey][] = $item;
			}
		}

		return $groups;
	}

}