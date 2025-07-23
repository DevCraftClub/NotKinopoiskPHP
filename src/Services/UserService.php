<?php

declare(strict_types=1);

namespace NotKinopoisk\Services;

use NotKinopoisk\Models\ApiKeyInfo;

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
 * 
 * @package NotKinopoisk\Services
 * @author Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @since 1.0.0
 * 
 * @see \NotKinopoisk\Services\AbstractService
 * @see \NotKinopoisk\Models\ApiKeyInfo
 * 
 * @example
 * ```php
 * $client = new Client('api-key');
 * $userService = $client->users;
 * 
 * // Получение информации об API ключе
 * $keyInfo = $userService->getApiKeyInfo('your-api-key');
 * echo "Тип аккаунта: {$keyInfo->accountType}";
 * echo "Использовано запросов: {$keyInfo->getTotalQuotaUsed()}";
 * ```
 */
class UserService extends AbstractService
{
    /**
     * Получает информацию об API ключе
     * 
     * READ операция - извлекает детальную информацию об API ключе:
     * тип аккаунта, статистика использования, лимиты запросов.
     * 
     * @param string $apiKey API ключ для проверки
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
    public function getApiKeyInfo(string $apiKey): ApiKeyInfo
    {
        $data = $this->get($this->buildV1Uri("api_key_info"), [
            'key' => $apiKey
        ]);
        return ApiKeyInfo::fromArray($data);
    }
} 