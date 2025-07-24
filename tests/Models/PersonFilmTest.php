<?php

declare(strict_types=1);

namespace Tests\Models;

use NotKinopoisk\Models\PersonFilm;
use NotKinopoisk\Enums\ProfessionKey;
use PHPUnit\Framework\TestCase;

/**
 * Тесты для модели PersonFilm
 * 
 * @package Tests\Models
 * @author Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @since 1.0.0
 */
class PersonFilmTest extends TestCase
{
    /**
     * Тест создания объекта PersonFilm
     */
    public function testCreatePersonFilm(): void
    {
        $film = new PersonFilm(
            filmId: 32169,
            nameRu: 'Солист',
            nameEn: 'The Soloist',
            rating: '7.2',
            general: false,
            description: 'Steve Lopez',
            professionKey: ProfessionKey::ACTOR
        );

        $this->assertEquals(32169, $film->filmId);
        $this->assertEquals('Солист', $film->nameRu);
        $this->assertEquals('The Soloist', $film->nameEn);
        $this->assertEquals('7.2', $film->rating);
        $this->assertFalse($film->general);
        $this->assertEquals('Steve Lopez', $film->description);
        $this->assertEquals(ProfessionKey::ACTOR, $film->professionKey);
    }

    /**
     * Тест создания объекта PersonFilm из массива данных
     */
    public function testFromArray(): void
    {
        $data = [
            'filmId' => 32169,
            'nameRu' => 'Солист',
            'nameEn' => 'The Soloist',
            'rating' => '7.2',
            'general' => false,
            'description' => 'Steve Lopez',
            'professionKey' => 'ACTOR'
        ];

        $film = PersonFilm::fromArray($data);

        $this->assertEquals(32169, $film->filmId);
        $this->assertEquals('Солист', $film->nameRu);
        $this->assertEquals('The Soloist', $film->nameEn);
        $this->assertEquals('7.2', $film->rating);
        $this->assertFalse($film->general);
        $this->assertEquals('Steve Lopez', $film->description);
        $this->assertEquals(ProfessionKey::ACTOR, $film->professionKey);
    }

    /**
     * Тест создания объекта с null значениями
     */
    public function testFromArrayWithNullValues(): void
    {
        $data = [
            'filmId' => 32169,
            'nameRu' => null,
            'nameEn' => null,
            'rating' => null,
            'general' => true,
            'description' => null,
            'professionKey' => 'DIRECTOR'
        ];

        $film = PersonFilm::fromArray($data);

        $this->assertNull($film->nameRu);
        $this->assertNull($film->nameEn);
        $this->assertNull($film->rating);
        $this->assertTrue($film->general);
        $this->assertNull($film->description);
        $this->assertEquals(ProfessionKey::DIRECTOR, $film->professionKey);
    }

    /**
     * Тест метода getDisplayName()
     */
    public function testGetDisplayName(): void
    {
        $film = new PersonFilm(
            filmId: 32169,
            nameRu: 'Солист',
            nameEn: 'The Soloist',
            rating: '7.2',
            general: false,
            description: 'Steve Lopez',
            professionKey: ProfessionKey::ACTOR
        );

        $this->assertEquals('Солист', $film->getDisplayName());
    }

    /**
     * Тест метода getDisplayName() с английским названием
     */
    public function testGetDisplayNameWithEnglishName(): void
    {
        $film = new PersonFilm(
            filmId: 32169,
            nameRu: null,
            nameEn: 'The Soloist',
            rating: '7.2',
            general: false,
            description: 'Steve Lopez',
            professionKey: ProfessionKey::ACTOR
        );

        $this->assertEquals('The Soloist', $film->getDisplayName());
    }

    /**
     * Тест метода getDisplayName() без названия
     */
    public function testGetDisplayNameWithNoName(): void
    {
        $film = new PersonFilm(
            filmId: 32169,
            nameRu: null,
            nameEn: null,
            rating: '7.2',
            general: false,
            description: 'Steve Lopez',
            professionKey: ProfessionKey::ACTOR
        );

        $this->assertEquals('Без названия', $film->getDisplayName());
    }

    /**
     * Тест метода isActor()
     */
    public function testIsActor(): void
    {
        $actorFilm = new PersonFilm(
            filmId: 32169,
            nameRu: 'Солист',
            nameEn: 'The Soloist',
            rating: '7.2',
            general: false,
            description: 'Steve Lopez',
            professionKey: ProfessionKey::ACTOR
        );

        $directorFilm = new PersonFilm(
            filmId: 32170,
            nameRu: 'Матрица',
            nameEn: 'The Matrix',
            rating: '8.7',
            general: false,
            description: 'Режиссер',
            professionKey: ProfessionKey::DIRECTOR
        );

        $this->assertTrue($actorFilm->isActor());
        $this->assertFalse($directorFilm->isActor());
    }

    /**
     * Тест метода isDirector()
     */
    public function testIsDirector(): void
    {
        $actorFilm = new PersonFilm(
            filmId: 32169,
            nameRu: 'Солист',
            nameEn: 'The Soloist',
            rating: '7.2',
            general: false,
            description: 'Steve Lopez',
            professionKey: ProfessionKey::ACTOR
        );

        $directorFilm = new PersonFilm(
            filmId: 32170,
            nameRu: 'Матрица',
            nameEn: 'The Matrix',
            rating: '8.7',
            general: false,
            description: 'Режиссер',
            professionKey: ProfessionKey::DIRECTOR
        );

        $this->assertFalse($actorFilm->isDirector());
        $this->assertTrue($directorFilm->isDirector());
    }

    /**
     * Тест метода isWriter()
     */
    public function testIsWriter(): void
    {
        $writerFilm = new PersonFilm(
            filmId: 32169,
            nameRu: 'Солист',
            nameEn: 'The Soloist',
            rating: '7.2',
            general: false,
            description: 'Сценарист',
            professionKey: ProfessionKey::WRITER
        );

        $actorFilm = new PersonFilm(
            filmId: 32170,
            nameRu: 'Матрица',
            nameEn: 'The Matrix',
            rating: '8.7',
            general: false,
            description: 'Актер',
            professionKey: ProfessionKey::ACTOR
        );

        $this->assertTrue($writerFilm->isWriter());
        $this->assertFalse($actorFilm->isWriter());
    }

    /**
     * Тест метода isProducer()
     */
    public function testIsProducer(): void
    {
        $producerFilm = new PersonFilm(
            filmId: 32169,
            nameRu: 'Солист',
            nameEn: 'The Soloist',
            rating: '7.2',
            general: false,
            description: 'Продюсер',
            professionKey: ProfessionKey::PRODUCER
        );

        $producerUSSRFilm = new PersonFilm(
            filmId: 32170,
            nameRu: 'Матрица',
            nameEn: 'The Matrix',
            rating: '8.7',
            general: false,
            description: 'Продюсер СССР',
            professionKey: ProfessionKey::PRODUCER_USSR
        );

        $actorFilm = new PersonFilm(
            filmId: 32171,
            nameRu: 'Терминатор',
            nameEn: 'The Terminator',
            rating: '8.0',
            general: false,
            description: 'Актер',
            professionKey: ProfessionKey::ACTOR
        );

        $this->assertTrue($producerFilm->isProducer());
        $this->assertTrue($producerUSSRFilm->isProducer());
        $this->assertFalse($actorFilm->isProducer());
    }

    /**
     * Тест метода getProfessionName()
     */
    public function testGetProfessionName(): void
    {
        $this->assertEquals('Актер', (new PersonFilm(1, null, null, null, false, null, ProfessionKey::ACTOR))->getProfessionName());
        $this->assertEquals('Режиссер', (new PersonFilm(1, null, null, null, false, null, ProfessionKey::DIRECTOR))->getProfessionName());
        $this->assertEquals('Сценарист', (new PersonFilm(1, null, null, null, false, null, ProfessionKey::WRITER))->getProfessionName());
        $this->assertEquals('Продюсер', (new PersonFilm(1, null, null, null, false, null, ProfessionKey::PRODUCER))->getProfessionName());
        $this->assertEquals('Продюсер', (new PersonFilm(1, null, null, null, false, null, ProfessionKey::PRODUCER_USSR))->getProfessionName());
        $this->assertEquals('Композитор', (new PersonFilm(1, null, null, null, false, null, ProfessionKey::COMPOSER))->getProfessionName());
        $this->assertEquals('Оператор', (new PersonFilm(1, null, null, null, false, null, ProfessionKey::OPERATOR))->getProfessionName());
        $this->assertEquals('Монтажер', (new PersonFilm(1, null, null, null, false, null, ProfessionKey::EDITOR))->getProfessionName());
        $this->assertEquals('Художник', (new PersonFilm(1, null, null, null, false, null, ProfessionKey::DESIGN))->getProfessionName());
        $this->assertEquals('Переводчик', (new PersonFilm(1, null, null, null, false, null, ProfessionKey::TRANSLATOR))->getProfessionName());
        $this->assertEquals('Режиссер дубляжа', (new PersonFilm(1, null, null, null, false, null, ProfessionKey::VOICE_DIRECTOR))->getProfessionName());
        $this->assertEquals('В роли самого себя', (new PersonFilm(1, null, null, null, false, null, ProfessionKey::HIMSELF))->getProfessionName());
        $this->assertEquals('В роли самой себя', (new PersonFilm(1, null, null, null, false, null, ProfessionKey::HERSELF))->getProfessionName());
        $this->assertEquals('За кадром (мужской голос)', (new PersonFilm(1, null, null, null, false, null, ProfessionKey::HRONO_TITR_MALE))->getProfessionName());
        $this->assertEquals('За кадром (женский голос)', (new PersonFilm(1, null, null, null, false, null, ProfessionKey::HRONO_TITR_FEMALE))->getProfessionName());
        $this->assertEquals('Неизвестно', (new PersonFilm(1, null, null, null, false, null, ProfessionKey::UNKNOWN))->getProfessionName());
    }

    /**
     * Тест метода isGeneral()
     */
    public function testIsGeneral(): void
    {
        $generalFilm = new PersonFilm(
            filmId: 32169,
            nameRu: 'Солист',
            nameEn: 'The Soloist',
            rating: '7.2',
            general: true,
            description: 'Steve Lopez',
            professionKey: ProfessionKey::ACTOR
        );

        $specificFilm = new PersonFilm(
            filmId: 32170,
            nameRu: 'Матрица',
            nameEn: 'The Matrix',
            rating: '8.7',
            general: false,
            description: 'Neo',
            professionKey: ProfessionKey::ACTOR
        );

        $this->assertTrue($generalFilm->isGeneral());
        $this->assertFalse($specificFilm->isGeneral());
    }

    /**
     * Тест метода getRatingInfo()
     */
    public function testGetRatingInfo(): void
    {
        $filmWithRating = new PersonFilm(
            filmId: 32169,
            nameRu: 'Солист',
            nameEn: 'The Soloist',
            rating: '7.2',
            general: false,
            description: 'Steve Lopez',
            professionKey: ProfessionKey::ACTOR
        );

        $filmWithoutRating = new PersonFilm(
            filmId: 32170,
            nameRu: 'Матрица',
            nameEn: 'The Matrix',
            rating: null,
            general: false,
            description: 'Neo',
            professionKey: ProfessionKey::ACTOR
        );

        $this->assertEquals('7.2', $filmWithRating->getRatingInfo());
        $this->assertEquals('Нет рейтинга', $filmWithoutRating->getRatingInfo());
    }

    /**
     * Тест метода getFullInfo()
     */
    public function testGetFullInfo(): void
    {
        $film = new PersonFilm(
            filmId: 32169,
            nameRu: 'Солист',
            nameEn: 'The Soloist',
            rating: '7.2',
            general: false,
            description: 'Steve Lopez',
            professionKey: ProfessionKey::ACTOR
        );

        $expected = 'Солист (Актер, 7.2) - Steve Lopez';
        $this->assertEquals($expected, $film->getFullInfo());
    }

    /**
     * Тест метода getFullInfo() без описания
     */
    public function testGetFullInfoWithoutDescription(): void
    {
        $film = new PersonFilm(
            filmId: 32169,
            nameRu: 'Солист',
            nameEn: 'The Soloist',
            rating: '7.2',
            general: false,
            description: null,
            professionKey: ProfessionKey::ACTOR
        );

        $expected = 'Солист (Актер, 7.2)';
        $this->assertEquals($expected, $film->getFullInfo());
    }

    /**
     * Тест создания объекта с значениями по умолчанию
     */
    public function testFromArrayWithDefaults(): void
    {
        $data = [
            'filmId' => 32169,
            'professionKey' => 'ACTOR'
        ];

        $film = PersonFilm::fromArray($data);

        $this->assertNull($film->nameRu);
        $this->assertNull($film->nameEn);
        $this->assertNull($film->rating);
        $this->assertFalse($film->general);
        $this->assertNull($film->description);
        $this->assertEquals(ProfessionKey::ACTOR, $film->professionKey);
    }

    /**
     * Тест методов категорий профессий
     */
    public function testProfessionCategories(): void
    {
        $actorFilm = new PersonFilm(1, null, null, null, false, null, ProfessionKey::ACTOR);
        $directorFilm = new PersonFilm(1, null, null, null, false, null, ProfessionKey::DIRECTOR);
        $producerFilm = new PersonFilm(1, null, null, null, false, null, ProfessionKey::PRODUCER);
        $operatorFilm = new PersonFilm(1, null, null, null, false, null, ProfessionKey::OPERATOR);
        $himselfFilm = new PersonFilm(1, null, null, null, false, null, ProfessionKey::HIMSELF);

        // Творческие профессии
        $this->assertTrue($actorFilm->isCreativeProfession());
        $this->assertTrue($directorFilm->isCreativeProfession());
        $this->assertFalse($producerFilm->isCreativeProfession());
        $this->assertFalse($operatorFilm->isCreativeProfession());
        $this->assertFalse($himselfFilm->isCreativeProfession());

        // Технические профессии
        $this->assertFalse($actorFilm->isTechnicalProfession());
        $this->assertFalse($directorFilm->isTechnicalProfession());
        $this->assertFalse($producerFilm->isTechnicalProfession());
        $this->assertTrue($operatorFilm->isTechnicalProfession());
        $this->assertFalse($himselfFilm->isTechnicalProfession());

        // Управленческие профессии
        $this->assertFalse($actorFilm->isManagementProfession());
        $this->assertFalse($directorFilm->isManagementProfession());
        $this->assertTrue($producerFilm->isManagementProfession());
        $this->assertFalse($operatorFilm->isManagementProfession());
        $this->assertFalse($himselfFilm->isManagementProfession());

        // Специальные профессии
        $this->assertFalse($actorFilm->isSpecialProfession());
        $this->assertFalse($directorFilm->isSpecialProfession());
        $this->assertFalse($producerFilm->isSpecialProfession());
        $this->assertFalse($operatorFilm->isSpecialProfession());
        $this->assertTrue($himselfFilm->isSpecialProfession());
    }

    /**
     * Тест метода getProfessionCategory()
     */
    public function testGetProfessionCategory(): void
    {
        $this->assertEquals('Творческая', (new PersonFilm(1, null, null, null, false, null, ProfessionKey::ACTOR))->getProfessionCategory());
        $this->assertEquals('Творческая', (new PersonFilm(1, null, null, null, false, null, ProfessionKey::DIRECTOR))->getProfessionCategory());
        $this->assertEquals('Управленческая', (new PersonFilm(1, null, null, null, false, null, ProfessionKey::PRODUCER))->getProfessionCategory());
        $this->assertEquals('Техническая', (new PersonFilm(1, null, null, null, false, null, ProfessionKey::OPERATOR))->getProfessionCategory());
        $this->assertEquals('Специальная', (new PersonFilm(1, null, null, null, false, null, ProfessionKey::HIMSELF))->getProfessionCategory());
    }
} 