<?php

declare(strict_types=1);

namespace NotKinopoisk\Services;

use NotKinopoisk\Models\UserVote;
use NotKinopoisk\Models\ApiKeyInfo;

/**
 * Сервис для работы с пользователями Кинопоиска
 * Реализует CRUD операции: Read (получение)
 */
class UserService extends AbstractService
{
    /**
     * Получает оценки пользователя
     * READ операция
     *
     * @param int $userId ID пользователя на Кинопоиске
     * @param int $page Номер страницы
     * @return UserVote[]
     */
    public function getVotes(int $userId, int $page = 1): array
    {
        $data = $this->get($this->buildV1Uri("kp_users/{$userId}/votes"), [
            'page' => $page
        ]);
        return array_map(fn($voteData) => UserVote::fromArray($voteData), $data['items']);
    }

    /**
     * Получает информацию об API ключе
     * READ операция
     *
     * @param string $apiKey API ключ
     * @return ApiKeyInfo
     */
    public function getApiKeyInfo(string $apiKey): ApiKeyInfo
    {
        $data = $this->get($this->buildV1Uri("api_keys/{$apiKey}"));
        return ApiKeyInfo::fromArray($data);
    }
} 