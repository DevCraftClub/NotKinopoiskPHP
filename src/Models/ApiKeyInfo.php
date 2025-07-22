<?php

declare(strict_types=1);

namespace NotKinopoisk\Models;

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