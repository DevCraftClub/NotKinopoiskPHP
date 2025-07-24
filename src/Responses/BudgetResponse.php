<?php

namespace NotKinopoisk\Responses;

use NotKinopoisk\Enums\BoxOfficeType;
use NotKinopoisk\Exception\KpValidationException;

/**
 * Ответ бюджета от Kinopoisk API
 *
 * Предоставляет функциональность для работы с данными о бюджете фильма,
 * включая расчет общего дохода от различных источников поступлений.
 *
 * @package NotKinopoisk\Responses
 * @since   1.0.0
 * @author  Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 */
class BudgetResponse extends DefaultResponse {

	/**
	 * Вычисляет общий доход от всех источников поступлений
	 *
	 * Суммирует суммы всех элементов бюджета, которые являются источниками
	 * дохода (доходные статьи). Использует функциональный подход с array_reduce
	 * для более эффективного и читаемого кода.
	 *
	 * @return int Общая сумма дохода в указанной валюте
	 *
	 * @throws \NotKinopoisk\Exception\KpValidationException Если элементы не содержат корректных данных
	 *
	 * @example
	 * ```php
	 * $budgetResponse = BudgetResponse::fromArray($apiData, BoxOffice::class);
	 * $totalRevenue = $budgetResponse->getTotalRevenue();
	 * echo __('Общий доход: :amount', ['amount' => $totalRevenue]);
	 * ```
	 */
	public function getTotalRevenue(): int {
		try {
			return array_reduce(
				array: $this->items,
				callback: static fn(int $total, object $box): int =>
				$box->type->isRevenue() ? $total + $box->amount : $total,
				initial: 0
			);
		} catch (\TypeError $e) {
			throw new KpValidationException(
				'Ошибка при вычислении общего дохода: некорректная структура данных',
				previous: $e
			);
		} catch (\Error $e) {
			throw new KpValidationException(
				"Критическая ошибка при обработке элементов бюджета: {$e->getMessage()}",
				previous: $e
			);
		}

	}
	/**
	 * Получает детализированную информацию о доходах по типам
	 *
	 * Возвращает массив с разбивкой доходов по типам источников
	 * для более детального анализа финансовых показателей.
	 *
	 * @return array<string, int> Ассоциативный массив типов доходов и их сумм
	 *
	 * @example
	 * ```php
	 * $revenueBreakdown = $budgetResponse->getRevenueBreakdown();
	 * foreach ($revenueBreakdown as $type => $amount) {
	 *     echo __('Доход от :type: :amount', ['type' => $type, 'amount' => $amount]);
	 * }
	 * ```
	 */
	public function getRevenueBreakdown(): array {
		$breakdown = [];

		foreach ($this->items as $box) {
			if ($box->type->isRevenue()) {
				$typeName = match($box->type) {
					BoxOfficeType::RUS => 'Россия',
					BoxOfficeType::USA => 'США',
					BoxOfficeType::WORLD => 'Мировые сборы',
					default => 'Неизвестный тип'
				};

				$breakdown[$typeName] = ($breakdown[$typeName] ?? 0) + $box->amount;
			}
		}

		return $breakdown;
	}

	/**
	 * Получает количество доходных статей
	 *
	 * @return int Количество элементов с доходными статьями
	 */
	public function getRevenueItemsCount(): int {
		return count(array_filter(
			$this->items,
			static fn(object $box): bool => $box->type->isRevenue()
		));
	}

}