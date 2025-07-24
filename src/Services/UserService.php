<?php

declare(strict_types=1);

namespace NotKinopoisk\Services;

use NotKinopoisk\Enums\ApiVersion;
use NotKinopoisk\Models\ApiKeyInfo;
use NotKinopoisk\Models\UserVote;
use NotKinopoisk\Responses\DefaultResponse;
use NotKinopoisk\Responses\PaginatedResponse;

/**
 * Сервис для работы с пользовательскими данными в Kinopoisk API
 *
 * Предоставляет методы для получения информации о пользователе и его API ключе,
 * включая статистику использования и лимиты запросов.
 *
 * Основные возможности:
 * - Получение информации об API ключе
 * - Просмотр статистики использования
 * - Проверка лимитов и квот
 * - Получение оценок пользователя
 *
 * @package NotKinopoisk\Services
 * @since   1.0.0
 *
 * @author  Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @see     \NotKinopoisk\Services\AbstractService
 * @see     \NotKinopoisk\Models\ApiKeyInfo
 *
 * @example
 * ```php
 * $client = new Client('api-key');
 * $userService = $client->users;
 *
 * // Получение информации об API ключе
 * $keyInfo = $userService->getApiKeyInfo();
 * echo "Тип аккаунта: {$keyInfo->accountType}";
 * echo "Использовано запросов: {$keyInfo->getTotalQuotaUsed()}";
 * ```
 */
class UserService extends AbstractService {

	/**
	 * Получает информацию об API ключе
	 *
	 * Извлекает детальную информацию об API ключе:
	 * тип аккаунта, статистика использования, лимиты запросов.
	 *
	 * @param   string  $apiKey  API ключ для проверки
	 * @api /api/v1/api_keys/{apiKey}
	 *
	 * @return \NotKinopoisk\Models\ApiKeyInfo Информация об API ключе
	 *
	 * @throws \NotKinopoisk\Exception\InvalidApiKeyException Если API ключ неверный
	 * @throws \NotKinopoisk\Exception\ApiException При других ошибках API
	 *
	 * @example
	 * ```php
	 * $keyInfo = $userService->getApiKeyInfo('your-api-key');
	 *
	 * echo "Тип аккаунта: {$keyInfo->accountType}\n";
	 * echo "Использовано запросов: {$keyInfo->getTotalQuotaUsed()}\n";
	 * echo "Лимит запросов: {$keyInfo->getTotalQuotaValue()}\n";
	 * echo "Осталось запросов: {$keyInfo->getRemainingQuota()}\n";
	 * ```
	 */
	public function getApiKeyInfo(string $apiKey): ApiKeyInfo {
		$data = $this->get($this->buildUri("api_keys/{$apiKey}"));

		return ApiKeyInfo::fromArray($data);
	}

	/**
	 * Получает оценки пользователя
	 *
	 * Извлекает список оценок, которые пользователь поставил фильмам.
	 * Возвращает пагинированный список с информацией о фильмах и оценках.
	 *
	 * @param   int  $userId  ID пользователя
	 * @param   int  $page    Номер страницы для пагинации
	 *
	 * @return \NotKinopoisk\Responses\PaginatedResponse Пагинированный список оценок пользователя
	 *
	 * @throws \NotKinopoisk\Exception\ApiException При ошибках API
	 * @throws \NotKinopoisk\Exception\ResourceNotFoundException Если пользователь не найден
	 *
	 * @example
	 * ```php
	 * $votes = $userService->getVotes(12345, 1);
	 * echo "Всего оценок: {$votes->total}\n";
	 * 
	 * foreach ($votes->items as $vote) {
	 *     echo "Фильм: {$vote->filmName}, Оценка: {$vote->rating}\n";
	 * }
	 * ```
	 */
	public function getVotes(int $userId, int $page = 1): PaginatedResponse {
		$data = $this->get($this->buildUri("kp_users/{$userId}/votes"), ['page' => $page]);
		
		$response = PaginatedResponse::fromArray($data, UserVote::class);
		$response->currentPage = $page;
		
		return $response;
	}

}