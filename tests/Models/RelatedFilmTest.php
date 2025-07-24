<?php

declare(strict_types=1);

namespace Tests\Models;

use NotKinopoisk\Enums\RelationType;
use NotKinopoisk\Models\RelatedFilm;
use PHPUnit\Framework\TestCase;

class RelatedFilmTest extends TestCase
{
    public function testConstructor(): void
    {
        $relatedFilm = new RelatedFilm(
            filmId: 301,
            nameRu: 'Матрица',
            nameEn: 'The Matrix',
            nameOriginal: 'The Matrix',
            posterUrl: 'https://example.com/poster.jpg',
            posterUrlPreview: 'https://example.com/poster_small.jpg',
            relationType: RelationType::SIMILAR
        );

        $this->assertEquals(301, $relatedFilm->filmId);
        $this->assertEquals('Матрица', $relatedFilm->nameRu);
        $this->assertEquals('The Matrix', $relatedFilm->nameEn);
        $this->assertEquals('The Matrix', $relatedFilm->nameOriginal);
        $this->assertEquals('https://example.com/poster.jpg', $relatedFilm->posterUrl);
        $this->assertEquals('https://example.com/poster_small.jpg', $relatedFilm->posterUrlPreview);
        $this->assertEquals(RelationType::SIMILAR, $relatedFilm->relationType);
    }

    public function testFromArray(): void
    {
        $data = [
            'filmId' => 301,
            'nameRu' => 'Матрица',
            'nameEn' => 'The Matrix',
            'nameOriginal' => 'The Matrix',
            'posterUrl' => 'https://example.com/poster.jpg',
            'posterUrlPreview' => 'https://example.com/poster_small.jpg',
            'relationType' => 'SIMILAR'
        ];

        $relatedFilm = RelatedFilm::fromArray($data);

        $this->assertEquals(301, $relatedFilm->filmId);
        $this->assertEquals('Матрица', $relatedFilm->nameRu);
        $this->assertEquals('The Matrix', $relatedFilm->nameEn);
        $this->assertEquals('The Matrix', $relatedFilm->nameOriginal);
        $this->assertEquals('https://example.com/poster.jpg', $relatedFilm->posterUrl);
        $this->assertEquals('https://example.com/poster_small.jpg', $relatedFilm->posterUrlPreview);
        $this->assertEquals(RelationType::SIMILAR, $relatedFilm->relationType);
    }

    public function testToArray(): void
    {
        $relatedFilm = new RelatedFilm(
            filmId: 301,
            nameRu: 'Матрица',
            nameEn: 'The Matrix',
            nameOriginal: 'The Matrix',
            posterUrl: 'https://example.com/poster.jpg',
            posterUrlPreview: 'https://example.com/poster_small.jpg',
            relationType: RelationType::SIMILAR
        );

        $array = $relatedFilm->toArray();

        $this->assertIsArray($array);
        $this->assertEquals(301, $array['filmId']);
        $this->assertEquals('Матрица', $array['nameRu']);
        $this->assertEquals('The Matrix', $array['nameEn']);
        $this->assertEquals('The Matrix', $array['nameOriginal']);
        $this->assertEquals('https://example.com/poster.jpg', $array['posterUrl']);
        $this->assertEquals('https://example.com/poster_small.jpg', $array['posterUrlPreview']);
        $this->assertEquals('SIMILAR', $array['relationType']);
    }

    public function testGetDisplayName(): void
    {
        $relatedFilm = new RelatedFilm(
            filmId: 301,
            nameRu: 'Матрица',
            nameEn: 'The Matrix',
            nameOriginal: 'The Matrix',
            posterUrl: 'https://example.com/poster.jpg',
            posterUrlPreview: 'https://example.com/poster_small.jpg',
            relationType: RelationType::SIMILAR
        );

        $this->assertEquals('Матрица', $relatedFilm->getDisplayName());
    }

    public function testGetDisplayNameWithEnglishName(): void
    {
        $relatedFilm = new RelatedFilm(
            filmId: 301,
            nameRu: null,
            nameEn: 'The Matrix',
            nameOriginal: 'The Matrix',
            posterUrl: 'https://example.com/poster.jpg',
            posterUrlPreview: 'https://example.com/poster_small.jpg',
            relationType: RelationType::SIMILAR
        );

        $this->assertEquals('The Matrix', $relatedFilm->getDisplayName());
    }

    public function testGetDisplayNameWithOriginalName(): void
    {
        $relatedFilm = new RelatedFilm(
            filmId: 301,
            nameRu: null,
            nameEn: null,
            nameOriginal: 'The Matrix',
            posterUrl: 'https://example.com/poster.jpg',
            posterUrlPreview: 'https://example.com/poster_small.jpg',
            relationType: RelationType::SIMILAR
        );

        $this->assertEquals('The Matrix', $relatedFilm->getDisplayName());
    }

    public function testGetDisplayNameWithNoName(): void
    {
        $relatedFilm = new RelatedFilm(
            filmId: 301,
            nameRu: null,
            nameEn: null,
            nameOriginal: null,
            posterUrl: 'https://example.com/poster.jpg',
            posterUrlPreview: 'https://example.com/poster_small.jpg',
            relationType: RelationType::SIMILAR
        );

        $this->assertEquals('Без названия', $relatedFilm->getDisplayName());
    }
} 