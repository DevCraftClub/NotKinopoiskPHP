<?php

declare(strict_types=1);

namespace NotKinopoisk\Enums;

/**
 * Enum профессий персоны в фильме
 *
 * Представляет различные профессии, которые может иметь персона
 * в фильме согласно Kinopoisk API.
 *
 * @package NotKinopoisk\Enums
 * @since   1.0.0
 *
 * @author  Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @see     \NotKinopoisk\Models\PersonFilm
 * @api     /api/v1/persons/{id}
 * @link    https://kinopoiskapiunofficial.tech/documentation/api/#/persons/get_api_v1_persons__id_
 *
 * @example
 * ```php
 * // Проверка профессии
 * if ($professionKey === ProfessionKey::ACTOR) {
 *     echo "Актерская роль";
 * }
 *
 * // Получение отображаемого названия
 * echo ProfessionKey::DIRECTOR->getDisplayName(); // "Режиссер"
 * ```
 */
enum ProfessionKey: string {

	case WRITER            = 'WRITER';
	case OPERATOR          = 'OPERATOR';
	case EDITOR            = 'EDITOR';
	case COMPOSER          = 'COMPOSER';
	case PRODUCER_USSR     = 'PRODUCER_USSR';
	case HIMSELF           = 'HIMSELF';
	case HERSELF           = 'HERSELF';
	case HRONO_TITR_MALE   = 'HRONO_TITR_MALE';
	case HRONO_TITR_FEMALE = 'HRONO_TITR_FEMALE';
	case TRANSLATOR        = 'TRANSLATOR';
	case DIRECTOR          = 'DIRECTOR';
	case DESIGN            = 'DESIGN';
	case PRODUCER          = 'PRODUCER';
	case ACTOR             = 'ACTOR';
	case VOICE_DIRECTOR    = 'VOICE_DIRECTOR';
	case UNKNOWN           = 'UNKNOWN';

	/**
	 * Проверяет, является ли профессия актерской
	 *
	 * @return bool true если актер, false в противном случае
	 *
	 * @example
	 * ```php
	 * if (ProfessionKey::ACTOR->isActor()) {
	 *     echo "Актерская роль";
	 * }
	 * ```
	 */
	public function isActor(): bool {
		return $this === self::ACTOR;
	}

	/**
	 * Проверяет, является ли профессия режиссерской
	 *
	 * @return bool true если режиссер, false в противном случае
	 *
	 * @example
	 * ```php
	 * if (ProfessionKey::DIRECTOR->isDirector()) {
	 *     echo "Режиссерская работа";
	 * }
	 * ```
	 */
	public function isDirector(): bool {
		return $this === self::DIRECTOR;
	}

	/**
	 * Проверяет, является ли профессия сценарной
	 *
	 * @return bool true если сценарист, false в противном случае
	 *
	 * @example
	 * ```php
	 * if (ProfessionKey::WRITER->isWriter()) {
	 *     echo "Сценарная работа";
	 * }
	 * ```
	 */
	public function isWriter(): bool {
		return $this === self::WRITER;
	}

	/**
	 * Проверяет, является ли профессия продюсерской
	 *
	 * @return bool true если продюсер, false в противном случае
	 *
	 * @example
	 * ```php
	 * if (ProfessionKey::PRODUCER->isProducer()) {
	 *     echo "Продюсерская работа";
	 * }
	 * ```
	 */
	public function isProducer(): bool {
		return in_array($this, [self::PRODUCER, self::PRODUCER_USSR]);
	}

	/**
	 * Получает краткое название профессии
	 *
	 * Возвращает краткое название без дополнительных пояснений.
	 *
	 * @return string Краткое название профессии
	 *
	 * @example
	 * ```php
	 * echo ProfessionKey::HRONO_TITR_MALE->getShortName(); // "За кадром"
	 * echo ProfessionKey::HIMSELF->getShortName(); // "В роли самого себя"
	 * ```
	 */
	public function getShortName(): string {
		return match ($this) {
			self::HRONO_TITR_MALE,
			self::HRONO_TITR_FEMALE => 'За кадром',
			self::HIMSELF,
			self::HERSELF           => 'В роли самого себя',
			default                 => $this->getDisplayName(),
		};
	}

	/**
	 * Получает отображаемое название профессии на русском языке
	 *
	 * Возвращает человекочитаемое название профессии для отображения пользователю.
	 *
	 * @return string Отображаемое название профессии
	 *
	 * @example
	 * ```php
	 * echo ProfessionKey::ACTOR->getDisplayName(); // "Актер"
	 * echo ProfessionKey::DIRECTOR->getDisplayName(); // "Режиссер"
	 * ```
	 */
	public function getDisplayName(): string {
		return match ($this) {
			self::ACTOR             => 'Актер',
			self::DIRECTOR          => 'Режиссер',
			self::WRITER            => 'Сценарист',
			self::PRODUCER          => 'Продюсер',
			self::PRODUCER_USSR     => 'Продюсер',
			self::COMPOSER          => 'Композитор',
			self::OPERATOR          => 'Оператор',
			self::EDITOR            => 'Монтажер',
			self::DESIGN            => 'Художник',
			self::TRANSLATOR        => 'Переводчик',
			self::VOICE_DIRECTOR    => 'Режиссер дубляжа',
			self::HIMSELF           => 'В роли самого себя',
			self::HERSELF           => 'В роли самой себя',
			self::HRONO_TITR_MALE   => 'За кадром (мужской голос)',
			self::HRONO_TITR_FEMALE => 'За кадром (женский голос)',
			self::UNKNOWN           => 'Неизвестно',
		};
	}

	/**
	 * Получает категорию профессии
	 *
	 * Возвращает категорию, к которой относится профессия.
	 *
	 * @return string Категория профессии
	 *
	 * @example
	 * ```php
	 * echo ProfessionKey::ACTOR->getCategory(); // "Творческая"
	 * echo ProfessionKey::PRODUCER->getCategory(); // "Управленческая"
	 * ```
	 */
	public function getCategory(): string {
		if ($this->isCreative()) {
			return 'Творческая';
		}
		if ($this->isManagement()) {
			return 'Управленческая';
		}
		if ($this->isTechnical()) {
			return 'Техническая';
		}
		if ($this->isSpecial()) {
			return 'Специальная';
		}

		return 'Другая';
	}

	/**
	 * Проверяет, является ли профессия творческой
	 *
	 * К творческим профессиям относятся актер, режиссер, сценарист, композитор.
	 *
	 * @return bool true если творческая профессия, false в противном случае
	 *
	 * @example
	 * ```php
	 * if (ProfessionKey::ACTOR->isCreative()) {
	 *     echo "Творческая профессия";
	 * }
	 * ```
	 */
	public function isCreative(): bool {
		return in_array($this, [
			self::ACTOR,
			self::DIRECTOR,
			self::WRITER,
			self::COMPOSER,
		]);
	}

	/**
	 * Проверяет, является ли профессия управленческой
	 *
	 * К управленческим профессиям относятся продюсеры.
	 *
	 * @return bool true если управленческая профессия, false в противном случае
	 *
	 * @example
	 * ```php
	 * if (ProfessionKey::PRODUCER->isManagement()) {
	 *     echo "Управленческая профессия";
	 * }
	 * ```
	 */
	public function isManagement(): bool {
		return in_array($this, [self::PRODUCER, self::PRODUCER_USSR]);
	}

	/**
	 * Проверяет, является ли профессия технической
	 *
	 * К техническим профессиям относятся оператор, монтажер, художник.
	 *
	 * @return bool true если техническая профессия, false в противном случае
	 *
	 * @example
	 * ```php
	 * if (ProfessionKey::OPERATOR->isTechnical()) {
	 *     echo "Техническая профессия";
	 * }
	 * ```
	 */
	public function isTechnical(): bool {
		return in_array($this, [
			self::OPERATOR,
			self::EDITOR,
			self::DESIGN,
			self::VOICE_DIRECTOR,
		]);
	}

	/**
	 * Проверяет, является ли профессия специальной
	 *
	 * К специальным профессиям относятся роли самого себя, за кадром, переводчик.
	 *
	 * @return bool true если специальная профессия, false в противном случае
	 *
	 * @example
	 * ```php
	 * if (ProfessionKey::HIMSELF->isSpecial()) {
	 *     echo "Специальная роль";
	 * }
	 * ```
	 */
	public function isSpecial(): bool {
		return in_array($this, [
			self::HIMSELF,
			self::HERSELF,
			self::HRONO_TITR_MALE,
			self::HRONO_TITR_FEMALE,
			self::TRANSLATOR,
			self::UNKNOWN,
		]);
	}

}