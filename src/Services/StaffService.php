<?php

declare(strict_types=1);

namespace NotKinopoisk\Services;

use NotKinopoisk\Models\Staff;

/**
 * Сервис для работы с персоналом фильма (актеры, режиссеры и т.д.)
 * Реализует CRUD операции: Read (получение)
 */
class StaffService extends AbstractService
{
    /**
     * Получает персонал фильма
     * READ операция
     *
     * @param int $filmId ID фильма в Кинопоиске
     * @return Staff[]
     */
    public function getByFilmId(int $filmId): array
    {
        $data = $this->get($this->buildV1Uri("staff"), [
            'filmId' => $filmId
        ]);
        return array_map(fn($staffData) => Staff::fromArray($staffData), $data);
    }
} 