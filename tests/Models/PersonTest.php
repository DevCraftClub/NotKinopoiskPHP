<?php

declare(strict_types=1);

namespace Tests\Models;

use NotKinopoisk\Models\Person;
use NotKinopoisk\Models\PersonSpouse;
use NotKinopoisk\Models\PersonFilm;
use PHPUnit\Framework\TestCase;

/**
 * Тесты для модели Person
 * 
 * @package Tests\Models
 * @author Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @since 1.0.0
 */
class PersonTest extends TestCase
{
    /**
     * Тест создания объекта Person
     */
    public function testCreatePerson(): void
    {
        $spouse = new PersonSpouse(
            personId: 32169,
            name: 'Сьюзан Дауни',
            divorced: false,
            divorcedReason: null,
            sex: 'FEMALE',
            children: 2,
            webUrl: 'https://www.kinopoisk.ru/name/32169/',
            relation: 'супруга'
        );

        $person = new Person(
            personId: 12345,
            nameRu: 'Роберт Дауни мл.',
            nameEn: 'Robert Downey Jr.',
            sex: 'MALE',
            posterUrl: 'https://example.com/poster.jpg',
            growth: '174',
            birthday: '1965-04-04',
            death: null,
            age: 58,
            birthplace: 'Нью-Йорк, США',
            deathplace: null,
            spouses: [$spouse],
            hasAwards: 1,
            profession: 'Актер',
            facts: 'Интересные факты...',
            films: [],
            biography: 'Биография...',
            births: 'Информация о рождении...',
            deaths: null,
            total: 'Общее количество работ: 150'
        );

        $this->assertEquals(12345, $person->personId);
        $this->assertEquals('Роберт Дауни мл.', $person->nameRu);
        $this->assertEquals('Robert Downey Jr.', $person->nameEn);
        $this->assertEquals('MALE', $person->sex);
        $this->assertEquals('https://example.com/poster.jpg', $person->posterUrl);
        $this->assertEquals('174', $person->growth);
        $this->assertEquals('1965-04-04', $person->birthday);
        $this->assertNull($person->death);
        $this->assertEquals(58, $person->age);
        $this->assertEquals('Нью-Йорк, США', $person->birthplace);
        $this->assertNull($person->deathplace);
        $this->assertCount(1, $person->spouses);
        $this->assertInstanceOf(PersonSpouse::class, $person->spouses[0]);
        $this->assertEquals(1, $person->hasAwards);
        $this->assertEquals('Актер', $person->profession);
        $this->assertEquals('Интересные факты...', $person->facts);
        $this->assertEquals([], $person->films);
        $this->assertEquals('Биография...', $person->biography);
        $this->assertEquals('Информация о рождении...', $person->births);
        $this->assertNull($person->deaths);
        $this->assertEquals('Общее количество работ: 150', $person->total);
    }

    /**
     * Тест создания объекта Person из массива данных
     */
    public function testFromArray(): void
    {
        $data = [
            'personId' => 12345,
            'nameRu' => 'Роберт Дауни мл.',
            'nameEn' => 'Robert Downey Jr.',
            'sex' => 'MALE',
            'posterUrl' => 'https://example.com/poster.jpg',
            'growth' => '174',
            'birthday' => '1965-04-04',
            'death' => null,
            'age' => 58,
            'birthplace' => 'Нью-Йорк, США',
            'deathplace' => null,
            'spouses' => [
                [
                    'personId' => 32169,
                    'name' => 'Сьюзан Дауни',
                    'divorced' => false,
                    'divorcedReason' => null,
                    'sex' => 'FEMALE',
                    'children' => 2,
                    'webUrl' => 'https://www.kinopoisk.ru/name/32169/',
                    'relation' => 'супруга'
                ]
            ],
            'hasAwards' => 1,
            'profession' => 'Актер',
            'facts' => 'Интересные факты...',
            'films' => [
                [
                    'filmId' => 32169,
                    'nameRu' => 'Солист',
                    'nameEn' => 'The Soloist',
                    'rating' => '7.2',
                    'general' => false,
                    'description' => 'Steve Lopez',
                    'professionKey' => 'ACTOR'
                ]
            ],
            'biography' => 'Биография...',
            'births' => 'Информация о рождении...',
            'deaths' => null,
            'total' => 'Общее количество работ: 150'
        ];

        $person = Person::fromArray($data);

        $this->assertEquals(12345, $person->personId);
        $this->assertEquals('Роберт Дауни мл.', $person->nameRu);
        $this->assertEquals('Robert Downey Jr.', $person->nameEn);
        $this->assertEquals('MALE', $person->sex);
        $this->assertEquals('https://example.com/poster.jpg', $person->posterUrl);
        $this->assertEquals('174', $person->growth);
        $this->assertEquals('1965-04-04', $person->birthday);
        $this->assertNull($person->death);
        $this->assertEquals(58, $person->age);
        $this->assertEquals('Нью-Йорк, США', $person->birthplace);
        $this->assertNull($person->deathplace);
        $this->assertCount(1, $person->spouses);
        $this->assertInstanceOf(PersonSpouse::class, $person->spouses[0]);
        $this->assertEquals('Сьюзан Дауни', $person->spouses[0]->name);
        $this->assertEquals(1, $person->hasAwards);
        $this->assertEquals('Актер', $person->profession);
        $this->assertEquals('Интересные факты...', $person->facts);
        $this->assertCount(1, $person->films);
        $this->assertInstanceOf(PersonFilm::class, $person->films[0]);
        $this->assertEquals('Солист', $person->films[0]->nameRu);
        $this->assertEquals('Биография...', $person->biography);
        $this->assertEquals('Информация о рождении...', $person->births);
        $this->assertNull($person->deaths);
        $this->assertEquals('Общее количество работ: 150', $person->total);
    }

    /**
     * Тест создания объекта Person с null значениями
     */
    public function testFromArrayWithNullValues(): void
    {
        $data = [
            'personId' => 12345,
            'nameRu' => null,
            'nameEn' => null,
            'sex' => null,
            'posterUrl' => null,
            'growth' => null,
            'birthday' => null,
            'death' => null,
            'age' => null,
            'birthplace' => null,
            'deathplace' => null,
            'spouses' => [],
            'hasAwards' => null,
            'profession' => null,
            'facts' => null,
            'films' => [],
            'biography' => null,
            'births' => null,
            'deaths' => null,
            'total' => null
        ];

        $person = Person::fromArray($data);

        $this->assertNull($person->nameRu);
        $this->assertNull($person->nameEn);
        $this->assertNull($person->sex);
        $this->assertNull($person->posterUrl);
        $this->assertNull($person->growth);
        $this->assertNull($person->birthday);
        $this->assertNull($person->death);
        $this->assertNull($person->age);
        $this->assertNull($person->birthplace);
        $this->assertNull($person->deathplace);
        $this->assertEquals([], $person->spouses);
        $this->assertNull($person->hasAwards);
        $this->assertNull($person->profession);
        $this->assertNull($person->facts);
        $this->assertEquals([], $person->films);
        $this->assertNull($person->biography);
        $this->assertNull($person->births);
        $this->assertNull($person->deaths);
        $this->assertNull($person->total);
    }

    /**
     * Тест создания объекта Person без поля spouses
     */
    public function testFromArrayWithoutSpouses(): void
    {
        $data = [
            'personId' => 12345,
            'nameRu' => 'Роберт Дауни мл.',
            'nameEn' => 'Robert Downey Jr.',
            'sex' => 'MALE',
            'posterUrl' => 'https://example.com/poster.jpg',
            'growth' => '174',
            'birthday' => '1965-04-04',
            'age' => 58,
            'birthplace' => 'Нью-Йорк, США',
            'hasAwards' => 1,
            'profession' => 'Актер',
            'facts' => 'Интересные факты...',
            'biography' => 'Биография...',
            'births' => 'Информация о рождении...',
            'total' => 'Общее количество работ: 150'
        ];

        $person = Person::fromArray($data);

        $this->assertEquals([], $person->spouses);
        $this->assertEquals([], $person->films);
    }

    /**
     * Тест метода getDisplayName()
     */
    public function testGetDisplayName(): void
    {
        $person = new Person(
            personId: 12345,
            nameRu: 'Роберт Дауни мл.',
            nameEn: 'Robert Downey Jr.',
            sex: 'MALE',
            posterUrl: 'https://example.com/poster.jpg',
            growth: '174',
            birthday: '1965-04-04',
            death: null,
            age: 58,
            birthplace: 'Нью-Йорк, США',
            deathplace: null,
            spouses: [],
            hasAwards: 1,
            profession: 'Актер',
            facts: 'Интересные факты...',
            films: [],
            biography: 'Биография...',
            births: 'Информация о рождении...',
            deaths: null,
            total: 'Общее количество работ: 150'
        );

        $this->assertEquals('Роберт Дауни мл.', $person->getDisplayName());
    }

    /**
     * Тест метода getDisplayName() с английским именем
     */
    public function testGetDisplayNameWithEnglishName(): void
    {
        $person = new Person(
            personId: 12345,
            nameRu: null,
            nameEn: 'Robert Downey Jr.',
            sex: 'MALE',
            posterUrl: 'https://example.com/poster.jpg',
            growth: '174',
            birthday: '1965-04-04',
            death: null,
            age: 58,
            birthplace: 'Нью-Йорк, США',
            deathplace: null,
            spouses: [],
            hasAwards: 1,
            profession: 'Актер',
            facts: 'Интересные факты...',
            films: [],
            biography: 'Биография...',
            births: 'Информация о рождении...',
            deaths: null,
            total: 'Общее количество работ: 150'
        );

        $this->assertEquals('Robert Downey Jr.', $person->getDisplayName());
    }

    /**
     * Тест метода getDisplayName() без имени
     */
    public function testGetDisplayNameWithNoName(): void
    {
        $person = new Person(
            personId: 12345,
            nameRu: null,
            nameEn: null,
            sex: 'MALE',
            posterUrl: 'https://example.com/poster.jpg',
            growth: '174',
            birthday: '1965-04-04',
            death: null,
            age: 58,
            birthplace: 'Нью-Йорк, США',
            deathplace: null,
            spouses: [],
            hasAwards: 1,
            profession: 'Актер',
            facts: 'Интересные факты...',
            films: [],
            biography: 'Биография...',
            births: 'Информация о рождении...',
            deaths: null,
            total: 'Общее количество работ: 150'
        );

        $this->assertEquals('Неизвестно', $person->getDisplayName());
    }

    /**
     * Тест работы с несколькими супругами
     */
    public function testMultipleSpouses(): void
    {
        $spouse1 = new PersonSpouse(
            personId: 32169,
            name: 'Сьюзан Дауни',
            divorced: false,
            divorcedReason: null,
            sex: 'FEMALE',
            children: 2,
            webUrl: 'https://www.kinopoisk.ru/name/32169/',
            relation: 'супруга'
        );

        $spouse2 = new PersonSpouse(
            personId: 32170,
            name: 'Дебби Фалконер',
            divorced: true,
            divorcedReason: 'Развод',
            sex: 'FEMALE',
            children: 1,
            webUrl: 'https://www.kinopoisk.ru/name/32170/',
            relation: 'супруга'
        );

        $person = new Person(
            personId: 12345,
            nameRu: 'Роберт Дауни мл.',
            nameEn: 'Robert Downey Jr.',
            sex: 'MALE',
            posterUrl: 'https://example.com/poster.jpg',
            growth: '174',
            birthday: '1965-04-04',
            death: null,
            age: 58,
            birthplace: 'Нью-Йорк, США',
            deathplace: null,
            spouses: [$spouse1, $spouse2],
            hasAwards: 1,
            profession: 'Актер',
            facts: 'Интересные факты...',
            films: [],
            biography: 'Биография...',
            births: 'Информация о рождении...',
            deaths: null,
            total: 'Общее количество работ: 150'
        );

        $this->assertCount(2, $person->spouses);
        $this->assertEquals('Сьюзан Дауни', $person->spouses[0]->name);
        $this->assertEquals('Дебби Фалконер', $person->spouses[1]->name);
        $this->assertFalse($person->spouses[0]->isDivorced());
        $this->assertTrue($person->spouses[1]->isDivorced());
    }
} 