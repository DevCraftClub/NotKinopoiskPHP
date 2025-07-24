<?php

namespace NotKinopoisk\Responses;

use NotKinopoisk\Enums\ProfessionKey;
use NotKinopoisk\Exception\KpValidationException;
use NotKinopoisk\Interfaces\ResponseInterface;
use NotKinopoisk\Models\Staff;

/**
 * Ответ API со списком сотрудников фильма
 *
 * Представляет ответ от Kinopoisk API, содержащий информацию о съемочной команде фильма.
 * Позволяет получать и фильтровать участников съемочного процесса по их профессиям.
 *
 * Основные возможности:
 * - Хранение списка сотрудников в неизменяемом виде
 * - Создание объекта из массива данных API с валидацией класса
 * - Фильтрация персонала по профессиональным ролям
 * - Преобразование в массив для сериализации
 *
 * @package NotKinopoisk\Responses
 * @api     /api/v1/staff
 * @since   1.0.0
 * @see     \NotKinopoisk\Interfaces\ResponseInterface
 * @see     \NotKinopoisk\Models\Staff
 *
 * @example
 * ```php
 * // Создание из данных API
 * $staffResponse = MovieStaffResponse::fromArray($apiData, Staff::class);
 *
 * // Получение различных групп персонала
 * $actors = $staffResponse->getActors();
 * $directors = $staffResponse->getDirectors();
 * $writers = $staffResponse->getWriters();
 *
 * // Преобразование в массив
 * $staffArray = $staffResponse->toArray();
 * ```
 */
class MovieStaffResponse implements ResponseInterface {

	/**
	 * Конструктор ответа со списком сотрудников
	 *
	 * Создает новый экземпляр ответа с массивом объектов сотрудников.
	 * Принимает готовый массив объектов Staff, полученный после валидации и преобразования.
	 *
	 * @param \NotKinopoisk\Models\Staff[] $staff Массив объектов сотрудников фильма
	 *
	 * @example
	 * ```php
	 * $staffObjects = [new Staff(...), new Staff(...)];
	 * $response = new MovieStaffResponse($staffObjects);
	 * ```
	 */
	public function __construct(public array $staff) {}

	/**
	 * {@inheritDoc}
	 *
	 * Создает экземпляр ответа из массива данных API с валидацией целевого класса.
	 * Выполняет проверку существования и совместимости указанного класса,
	 * затем преобразует каждый элемент массива staff в объект указанного класса.
	 *
	 * @param array  $data Массив данных от API, должен содержать ключ 'staff' с массивом данных сотрудников
	 * @param string $cls  Полное имя класса для преобразования элементов (обычно Staff::class)
	 *
	 * @return MovieStaffResponse Новый экземпляр с преобразованными данными о сотрудниках
	 *
	 * @throws \NotKinopoisk\Exception\KpValidationException Если указанный класс не существует
	 * @throws \NotKinopoisk\Exception\KpValidationException Если класс не имеет статического метода fromArray
	 * @throws \NotKinopoisk\Exception\KpValidationException Если метод fromArray не является статическим
	 *
	 * @example
	 * ```php
	 * $apiData = [
	 *     'staff' => [
	 *         ['nameRu' => 'Иван Иванов', 'professionKey' => 'ACTOR'],
	 *         ['nameRu' => 'Петр Петров', 'professionKey' => 'DIRECTOR']
	 *     ]
	 * ];
	 * $response = MovieStaffResponse::fromArray($apiData, Staff::class);
	 * ```
	 */
	public static function fromArray(array $data, string $cls): MovieStaffResponse {
		self::checkClass($cls);
		return new self(array_map(fn(array $itemData) => $cls::fromArray($itemData), $data['staff']?? []));
	}

	/**
	 * {@inheritDoc}
	 *
	 * Проверяет существование класса и наличие статического метода fromArray.
	 * Выполняет полную валидацию указанного класса для использования в преобразовании
	 * элементов ответа, включая проверку статичности метода через рефлексию.
	 *
	 * @param string $cls Полное имя класса для проверки
	 *
	 * @return void
	 *
	 * @throws \NotKinopoisk\Exception\KpValidationException Если указанный класс не существует
	 * @throws \NotKinopoisk\Exception\KpValidationException Если класс не имеет статического метода fromArray
	 * @throws \NotKinopoisk\Exception\KpValidationException Если метод fromArray не является статическим
	 * @throws \ReflectionException При ошибке создания объекта рефлексии
	 *
	 * @example
	 * ```php
	 * MovieStaffResponse::checkClass(Staff::class); // Успешная проверка
	 * MovieStaffResponse::checkClass('NonExistentClass'); // Исключение
	 * ```
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
	 * Преобразует объект ответа в массив данных
	 *
	 * Конвертирует каждый объект Staff в массив с помощью метода toArray(),
	 * создавая полное представление данных о сотрудниках в виде массива.
	 * Полезен для сериализации, кэширования или передачи данных.
	 *
	 * @return array Массив массивов, представляющих данные каждого сотрудника
	 *
	 * @example
	 * ```php
	 * $staffResponse = MovieStaffResponse::fromArray($apiData, Staff::class);
	 * $arrayData = $staffResponse->toArray();
	 * // [
	 * //     ['nameRu' => 'Иван Иванов', 'professionKey' => 'ACTOR', ...],
	 * //     ['nameRu' => 'Петр Петров', 'professionKey' => 'DIRECTOR', ...]
	 * // ]
	 * ```
	 */
	public function toArray(): array {
		return array_map(fn(Staff $staff) => $staff->toArray(), $this->staff);
	}

	/**
	 * Получает список актеров из съемочной команды
	 *
	 * Фильтрует массив сотрудников, возвращая только тех, кто является актерами.
	 * Использует метод professionKey->isActor() для определения актеров.
	 *
	 * @return \NotKinopoisk\Models\Staff[] Массив объектов актеров
	 *
	 * @example
	 * ```php
	 * $actors = $staffResponse->getActors();
	 * foreach ($actors as $actor) {
	 *     echo "Актер: " . $actor->nameRu . "\n";
	 * }
	 * ```
	 */
	public function getActors(): array {
		return array_filter($this->staff, static fn(Staff $staff) => $staff->professionKey->isActor());
	}

	/**
	 * Получает список сценаристов из съемочной команды
	 *
	 * Фильтрует массив сотрудников, возвращая только тех, кто является сценаристами.
	 * Использует метод professionKey->isWriter() для определения сценаристов.
	 *
	 * @return \NotKinopoisk\Models\Staff[] Массив объектов сценаристов
	 *
	 * @example
	 * ```php
	 * $writers = $staffResponse->getWriters();
	 * foreach ($writers as $writer) {
	 *     echo "Сценарист: " . $writer->nameRu . "\n";
	 * }
	 * ```
	 */
	public function getWriters(): array {
        return array_filter($this->staff, static fn(Staff $staff) => $staff->professionKey->isWriter());
    }

	/**
	 * Получает список режиссеров из съемочной команды
	 *
	 * Фильтрует массив сотрудников, возвращая только тех, кто является режиссерами.
	 * Использует метод professionKey->isDirector() для определения режиссеров.
	 *
	 * @return \NotKinopoisk\Models\Staff[] Массив объектов режиссеров
	 *
	 * @example
	 * ```php
	 * $directors = $staffResponse->getDirectors();
	 * foreach ($directors as $director) {
	 *     echo "Режиссер: " . $director->nameRu . "\n";
	 * }
	 * ```
	 */
	public function getDirectors(): array {
        return array_filter($this->staff, static fn(Staff $staff) => $staff->professionKey->isDirector());
    }

	/**
	 * Получает список продюсеров из съемочной команды
	 *
	 * Фильтрует массив сотрудников, возвращая только тех, кто является продюсерами.
	 * Использует метод professionKey->isProducer() для определения продюсеров.
	 *
	 * @return \NotKinopoisk\Models\Staff[] Массив объектов продюсеров
	 *
	 * @example
	 * ```php
	 * $producers = $staffResponse->getProducers();
	 * foreach ($producers as $producer) {
	 *     echo "Продюсер: " . $producer->nameRu . "\n";
	 * }
	 * ```
	 */
	public function getProducers(): array {
		return array_filter($this->staff, static fn(Staff $staff) => $staff->professionKey->isProducer());
	}

	/**
	 * Получает список композиторов из съемочной команды
	 *
	 * Фильтрует массив сотрудников, возвращая только тех, кто является композиторами.
	 * Использует метод professionKey->isComposer() для определения композиторов.
	 *
	 * @return \NotKinopoisk\Models\Staff[] Массив объектов композиторов
	 *
	 * @example
	 * ```php
	 * $composers = $staffResponse->getCompositors();
	 * foreach ($composers as $composer) {
	 *     echo "Композитор: " . $composer->nameRu . "\n";
	 * }
	 * ```
	 */
	public function getCompositors(): array {
		return array_filter($this->staff, static fn(Staff $staff) => $staff->professionKey->isComposer());
	}

	/**
	 * Получает список монтажеров из съемочной команды
	 *
	 * Фильтрует массив сотрудников, возвращая только тех, кто является монтажерами.
	 * Использует метод professionKey->isEditor() для определения монтажеров.
	 *
	 * @return \NotKinopoisk\Models\Staff[] Массив объектов монтажеров
	 *
	 * @example
	 * ```php
	 * $editors = $staffResponse->getEditors();
	 * foreach ($editors as $editor) {
	 *     echo "Монтажер: " . $editor->nameRu . "\n";
	 * }
	 * ```
	 */
	public function getEditors(): array {
		return array_filter($this->staff, static fn(Staff $staff) => $staff->professionKey->isEditor());
	}

	/**
	 * Получает список художников из съемочной команды
	 *
	 * Фильтрует массив сотрудников, возвращая только тех, кто является художниками.
	 * Использует метод professionKey->isDesigner() для определения художников.
	 *
	 * @return \NotKinopoisk\Models\Staff[] Массив объектов художников
	 *
	 * @example
	 * ```php
	 * $designers = $staffResponse->getDesigners();
	 * foreach ($designers as $designer) {
	 *     echo "Художник: " . $designer->nameRu . "\n";
	 * }
	 * ```
	 */
	public function getDesigners(): array {
		return array_filter($this->staff, static fn(Staff $staff) => $staff->professionKey->isDesigner());
	}

}