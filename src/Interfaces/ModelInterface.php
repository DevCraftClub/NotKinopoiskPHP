<?php

namespace NotKinopoisk\Interfaces;

/**
 * Интерфейс для моделей с возможностью преобразования в/из массива
 *
 * Определяет базовый контракт для всех моделей данных в системе.
 * Обеспечивает единообразную работу с преобразованием объектов в массивы
 * и создания объектов из массивов данных API.
 *
 * Интерфейс используется для:
 * - Унификации процесса создания моделей из данных API
 * - Стандартизации сериализации объектов в массивы
 * - Обеспечения совместимости с системами кэширования
 * - Упрощения работы с JSON API responses
 *
 * @package NotKinopoisk\Models
 * @since   1.0.0
 *
 * @see     \NotKinopoisk\Models\Film
 * @see     \NotKinopoisk\Models\Person
 * @see     \NotKinopoisk\Models\Review
 *
 * @example
 * ```php
 * // Реализация в модели
 * class ExampleModel implements ModelInterface {
 *     public static function fromArray(array $data): static {
 *         return new static($data['field1'], $data['field2']);
 *     }
 *
 *     public function toArray(): array {
 *         return ['field1' => $this->field1, 'field2' => $this->field2];
 *     }
 * }
 *
 * // Использование
 * $model = ExampleModel::fromArray($apiData);
 * $serialized = $model->toArray();
 * ```
 */
interface ModelInterface {
	/**
	 * Создает экземпляр модели из массива данных API
	 *
	 * Статический метод-фабрика для создания объекта модели из данных,
	 * полученных от Kinopoisk API. Должен выполнять базовую валидацию
	 * входных данных и корректно инициализировать все свойства объекта.
	 *
	 * @param   array  $data  Массив данных от API, содержащий все необходимые поля для модели
	 *
	 * @return self Новый экземпляр класса-реализации с данными из массива
	 *
	 * @throws \InvalidArgumentException Если обязательные поля отсутствуют в массиве данных
	 * @throws \InvalidArgumentException Если данные имеют неверный тип или формат
	 *
	 * @example
	 * ```php
	 * $apiData = [
	 *     'id' => 123,
	 *     'name' => 'Название',
	 *     'description' => 'Описание'
	 * ];
	 * $model = ConcreteModel::fromArray($apiData);
	 * ```
	 */
	public static function fromArray(array $data): object;
	/**
	 * Преобразует объект модели в массив
	 *
	 * Конвертирует все свойства объекта в ассоциативный массив
	 * для дальнейшей сериализации, кэширования или передачи в API.
	 * Должен включать все значимые данные объекта.
	 *
	 * @return array Ассоциативный массив со всеми данными объекта
	 *
	 * @example
	 * ```php
	 * $model = new ConcreteModel('value1', 'value2');
	 * $array = $model->toArray();
	 * // Результат: ['field1' => 'value1', 'field2' => 'value2']
	 * ```
	 */
	public function toArray(): array;
}