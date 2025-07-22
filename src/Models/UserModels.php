<?php

declare(strict_types=1);

namespace NotKinopoisk\Models;

/**
 * Модель оценки пользователя
 */
class UserVote
{
    public function __construct(
        public readonly int $kinopoiskId,
        public readonly ?string $nameRu,
        public readonly ?string $nameEn,
        public readonly ?string $nameOriginal,
        public readonly array $countries,
        public readonly array $genres,
        public readonly ?float $ratingKinopoisk,
        public readonly ?float $ratingImbd,
        public readonly ?string $year,
        public readonly string $type,
        public readonly string $posterUrl,
        public readonly string $posterUrlPreview,
        public readonly int $userRating
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            kinopoiskId: $data['kinopoiskId'],
            nameRu: $data['nameRu'] ?? null,
            nameEn: $data['nameEn'] ?? null,
            nameOriginal: $data['nameOriginal'] ?? null,
            countries: array_map(fn($country) => Country::fromArray($country), $data['countries']),
            genres: array_map(fn($genre) => Genre::fromArray($genre), $data['genres']),
            ratingKinopoisk: $data['ratingKinopoisk'] ?? null,
            ratingImbd: $data['ratingImbd'] ?? null,
            year: $data['year'] ?? null,
            type: $data['type'],
            posterUrl: $data['posterUrl'],
            posterUrlPreview: $data['posterUrlPreview'],
            userRating: $data['userRating']
        );
    }

    public function getDisplayName(): string
    {
        return $this->nameRu ?? $this->nameEn ?? $this->nameOriginal ?? 'Без названия';
    }
}

/**
 * Модель информации об API ключе
 */
class ApiKeyInfo
{
    public function __construct(
        public readonly array $totalQuota,
        public readonly array $dailyQuota,
        public readonly string $accountType
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            totalQuota: $data['totalQuota'],
            dailyQuota: $data['dailyQuota'],
            accountType: $data['accountType']
        );
    }

    public function getTotalQuotaUsed(): int
    {
        return $this->totalQuota['used'] ?? 0;
    }

    public function getTotalQuotaValue(): int
    {
        return $this->totalQuota['value'] ?? 0;
    }

    public function getDailyQuotaUsed(): int
    {
        return $this->dailyQuota['used'] ?? 0;
    }

    public function getDailyQuotaValue(): int
    {
        return $this->dailyQuota['value'] ?? 0;
    }

    public function isUnlimited(): bool
    {
        return $this->accountType === 'UNLIMITED';
    }
}

/**
 * Модель результата поиска персон
 */
class PersonSearchResult
{
    public function __construct(
        public readonly array $items,
        public readonly int $total
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            items: array_map(fn($personData) => Person::fromArray($personData), $data['items']),
            total: $data['total']
        );
    }

    public function getCount(): int
    {
        return count($this->items);
    }

    public function isEmpty(): bool
    {
        return empty($this->items);
    }
}

/**
 * Модель медиа поста
 */
class MediaPost
{
    public function __construct(
        public readonly int $kinopoiskId,
        public readonly string $imageUrl,
        public readonly string $title,
        public readonly string $description,
        public readonly string $url,
        public readonly string $publishedAt
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            kinopoiskId: $data['kinopoiskId'],
            imageUrl: $data['imageUrl'],
            title: $data['title'],
            description: $data['description'],
            url: $data['url'],
            publishedAt: $data['publishedAt']
        );
    }
} 