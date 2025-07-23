<?php

declare(strict_types=1);

namespace Tests\Models;

use NotKinopoisk\Models\Person;
use NotKinopoisk\Models\PersonSearchResult;
use PHPUnit\Framework\TestCase;

class PersonSearchResultTest extends TestCase
{
    public function testConstructor(): void
    {
        $person1 = new Person(
            personId: 123,
            nameRu: 'Том Круз',
            nameEn: 'Tom Cruise',
            sex: 'MALE',
            posterUrl: 'https://example.com/poster1.jpg',
            growth: '170',
            birthday: '1962-07-03',
            death: null,
            age: 61,
            birthplace: 'Сиракузы, Нью-Йорк, США',
            deathplace: null,
            spouses: [],
            hasAwards: 1,
            profession: 'Актер',
            facts: null,
            films: [],
            biography: null,
            births: null,
            deaths: null,
            total: null
        );

        $person2 = new Person(
            personId: 456,
            nameRu: 'Том Хэнкс',
            nameEn: 'Tom Hanks',
            sex: 'MALE',
            posterUrl: 'https://example.com/poster2.jpg',
            growth: '183',
            birthday: '1956-07-09',
            death: null,
            age: 67,
            birthplace: 'Конкорд, Калифорния, США',
            deathplace: null,
            spouses: [],
            hasAwards: 1,
            profession: 'Актер',
            facts: null,
            films: [],
            biography: null,
            births: null,
            deaths: null,
            total: null
        );

        $searchResult = new PersonSearchResult(
            items: [$person1, $person2],
            total: 150
        );

        $this->assertCount(2, $searchResult->items);
        $this->assertEquals(150, $searchResult->total);
        $this->assertEquals($person1, $searchResult->items[0]);
        $this->assertEquals($person2, $searchResult->items[1]);
    }

    public function testFromArray(): void
    {
        $data = [
            'items' => [
                [
                    'personId' => 123,
                    'nameRu' => 'Том Круз',
                    'nameEn' => 'Tom Cruise',
                    'sex' => 'MALE',
                    'posterUrl' => 'https://example.com/poster1.jpg',
                    'growth' => '170',
                    'birthday' => '1962-07-03',
                    'death' => null,
                    'age' => 61,
                    'birthplace' => 'Сиракузы, Нью-Йорк, США',
                    'deathplace' => null,
                    'spouses' => [],
                    'hasAwards' => 1,
                    'profession' => 'Актер',
                    'facts' => null,
                    'films' => [],
                    'biography' => null,
                    'births' => null,
                    'deaths' => null,
                    'total' => null
                ],
                [
                    'personId' => 456,
                    'nameRu' => 'Том Хэнкс',
                    'nameEn' => 'Tom Hanks',
                    'sex' => 'MALE',
                    'posterUrl' => 'https://example.com/poster2.jpg',
                    'growth' => '183',
                    'birthday' => '1956-07-09',
                    'death' => null,
                    'age' => 67,
                    'birthplace' => 'Конкорд, Калифорния, США',
                    'deathplace' => null,
                    'spouses' => [],
                    'hasAwards' => 1,
                    'profession' => 'Актер',
                    'facts' => null,
                    'films' => [],
                    'biography' => null,
                    'births' => null,
                    'deaths' => null,
                    'total' => null
                ]
            ],
            'total' => 150
        ];

        $searchResult = PersonSearchResult::fromArray($data);

        $this->assertCount(2, $searchResult->items);
        $this->assertEquals(150, $searchResult->total);
        $this->assertInstanceOf(Person::class, $searchResult->items[0]);
        $this->assertInstanceOf(Person::class, $searchResult->items[1]);
        $this->assertEquals('Том Круз', $searchResult->items[0]->nameRu);
        $this->assertEquals('Том Хэнкс', $searchResult->items[1]->nameRu);
    }

    public function testGetCount(): void
    {
        $searchResult = new PersonSearchResult(
            items: [],
            total: 0
        );

        $this->assertEquals(0, $searchResult->getCount());

        $person = new Person(
            personId: 123,
            nameRu: 'Том Круз',
            nameEn: 'Tom Cruise',
            sex: 'MALE',
            posterUrl: 'https://example.com/poster1.jpg',
            growth: '170',
            birthday: '1962-07-03',
            death: null,
            age: 61,
            birthplace: 'Сиракузы, Нью-Йорк, США',
            deathplace: null,
            spouses: [],
            hasAwards: 1,
            profession: 'Актер',
            facts: null,
            films: [],
            biography: null,
            births: null,
            deaths: null,
            total: null
        );

        $searchResult = new PersonSearchResult(
            items: [$person],
            total: 1
        );

        $this->assertEquals(1, $searchResult->getCount());
    }

    public function testIsEmpty(): void
    {
        $searchResult = new PersonSearchResult(
            items: [],
            total: 0
        );

        $this->assertTrue($searchResult->isEmpty());

        $person = new Person(
            personId: 123,
            nameRu: 'Том Круз',
            nameEn: 'Tom Cruise',
            sex: 'MALE',
            posterUrl: 'https://example.com/poster1.jpg',
            growth: '170',
            birthday: '1962-07-03',
            death: null,
            age: 61,
            birthplace: 'Сиракузы, Нью-Йорк, США',
            deathplace: null,
            spouses: [],
            hasAwards: 1,
            profession: 'Актер',
            facts: null,
            films: [],
            biography: null,
            births: null,
            deaths: null,
            total: null
        );

        $searchResult = new PersonSearchResult(
            items: [$person],
            total: 1
        );

        $this->assertFalse($searchResult->isEmpty());
    }
} 