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
class MovieStaffResponse extends SimpleResponse {

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
		return array_filter($this->items, static fn(Staff $staff) => $staff->professionKey->isActor());
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
        return array_filter($this->items, static fn(Staff $staff) => $staff->professionKey->isWriter());
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
        return array_filter($this->items, static fn(Staff $staff) => $staff->professionKey->isDirector());
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
		return array_filter($this->items, static fn(Staff $staff) => $staff->professionKey->isProducer());
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
		return array_filter($this->items, static fn(Staff $staff) => $staff->professionKey->isComposer());
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
		return array_filter($this->items, static fn(Staff $staff) => $staff->professionKey->isEditor());
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
		return array_filter($this->items, static fn(Staff $staff) => $staff->professionKey->isDesigner());
	}

}