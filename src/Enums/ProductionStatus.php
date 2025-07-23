<?php

namespace NotKinopoisk\Enums;

/**
 * Статус производства контента в Kinopoisk API
 *
 * Перечисление определяет различные этапы производственного процесса фильмов и сериалов.
 * Предоставляет удобные методы для определения состояния производства и получения
 * человекочитаемых названий статусов.
 *
 * @package NotKinopoisk\Enums
 * @since   1.0.0
 *
 * @author  Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @see     \NotKinopoisk\Models\Film Модель фильма, использующая данное перечисление
 *
 * @example
 * ```php
 * // Проверка статуса производства
 * if ($film->productionStatus === ProductionStatus::COMPLETED) {
 *     echo "Фильм завершен";
 * }
 *
 * // Проверка, находится ли фильм в производстве
 * if ($film->productionStatus->inProduction()) {
 *     echo "Фильм находится в процессе производства";
 * }
 * ```
 */
enum ProductionStatus: string {

	/** Статус съемки фильма - активная фаза производства */
	case FILMING         = 'FILMING';
	/** Статус пре-производства - подготовительная фаза */
	case PRE_PRODUCTION  = 'PRE_PRODUCTION';
	/** Статус завершенного производства */
	case COMPLETED       = 'COMPLETED';
	/** Статус объявленного проекта - только анонс */
	case ANNOUNCED       = 'ANNOUNCED';
	/** Неизвестный статус производства */
	case UNKNOWN         = 'UNKNOWN';
	/** Статус пост-производства - монтаж и обработка */
	case POST_PRODUCTION = 'POST_PRODUCTION';

	/**
	 * Проверяет, завершено ли производство контента
	 *
	 * Определяет, находится ли производство в завершенной стадии или далее.
	 * Использует строковое сравнение значений enum для определения порядка.
	 *
	 * @return bool true если производство завершено, false в противном случае
	 *
	 * @example
	 * ```php
	 * if ($productionStatus->isFinished()) {
	 *     echo "Производство завершено, можно смотреть";
	 * }
	 * ```
	 */
	public function isFinished(): bool {
		return $this->value >= self::COMPLETED->value;
	}

	/**
	 * Проверяет, находится ли контент в активной фазе производства
	 *
	 * Определяет, находится ли проект в одной из активных стадий производства:
	 * съемки, пре-производство или пост-производство.
	 *
	 * @return bool true если контент находится в производстве, false в противном случае
	 *
	 * @example
	 * ```php
	 * if ($productionStatus->inProduction()) {
	 *     echo "Проект активно разрабатывается";
	 * } else {
	 *     echo "Проект либо завершен, либо только анонсирован";
	 * }
	 * ```
	 */
	public function inProduction(): bool {
		return in_array($this, [self::FILMING, self::PRE_PRODUCTION, self::POST_PRODUCTION]);
	}

	/**
	 * Получает человекочитаемое название статуса производства
	 *
	 * Возвращает локализованное название статуса на русском языке,
	 * подходящее для отображения в пользовательском интерфейсе.
	 *
	 * @return string Название статуса производства на русском языке
	 *
	 * @example
	 * ```php
	 * echo ProductionStatus::FILMING->getDisplayName(); // "В производстве"
	 * echo ProductionStatus::COMPLETED->getDisplayName(); // "Завершено"
	 * ```
	 */
	public function getDisplayName(): string {
		return match ($this) {
			self::FILMING         => 'В производстве',
			self::PRE_PRODUCTION  => 'Пре-производство',
            self::COMPLETED       => 'Завершено',
            self::ANNOUNCED       => 'Объявлено',
            self::UNKNOWN         => 'Неизвестно',
            self::POST_PRODUCTION => 'Пост-производство',
		};
	}

}
