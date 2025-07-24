<?php

declare(strict_types=1);

namespace Tests\Models;

use NotKinopoisk\Models\PersonSpouse;
use PHPUnit\Framework\TestCase;

/**
 * Тесты для модели PersonSpouse
 * 
 * @package Tests\Models
 * @author Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @since 1.0.0
 */
class PersonSpouseTest extends TestCase
{
    /**
     * Тест создания объекта PersonSpouse
     */
    public function testCreatePersonSpouse(): void
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

        $this->assertEquals(32169, $spouse->personId);
        $this->assertEquals('Сьюзан Дауни', $spouse->name);
        $this->assertFalse($spouse->divorced);
        $this->assertNull($spouse->divorcedReason);
        $this->assertEquals('FEMALE', $spouse->sex);
        $this->assertEquals(2, $spouse->children);
        $this->assertEquals('https://www.kinopoisk.ru/name/32169/', $spouse->webUrl);
        $this->assertEquals('супруга', $spouse->relation);
    }

    /**
     * Тест создания объекта PersonSpouse из массива данных
     */
    public function testFromArray(): void
    {
        $data = [
            'personId' => 32169,
            'name' => 'Сьюзан Дауни',
            'divorced' => false,
            'divorcedReason' => null,
            'sex' => 'FEMALE',
            'children' => 2,
            'webUrl' => 'https://www.kinopoisk.ru/name/32169/',
            'relation' => 'супруга'
        ];

        $spouse = PersonSpouse::fromArray($data);

        $this->assertEquals(32169, $spouse->personId);
        $this->assertEquals('Сьюзан Дауни', $spouse->name);
        $this->assertFalse($spouse->divorced);
        $this->assertNull($spouse->divorcedReason);
        $this->assertEquals('FEMALE', $spouse->sex);
        $this->assertEquals(2, $spouse->children);
        $this->assertEquals('https://www.kinopoisk.ru/name/32169/', $spouse->webUrl);
        $this->assertEquals('супруга', $spouse->relation);
    }

    /**
     * Тест создания объекта с null значениями
     */
    public function testFromArrayWithNullValues(): void
    {
        $data = [
            'personId' => 32169,
            'name' => null,
            'divorced' => true,
            'divorcedReason' => 'Несовместимость характеров',
            'sex' => 'MALE',
            'children' => 0,
            'webUrl' => 'https://www.kinopoisk.ru/name/32169/',
            'relation' => 'супруг'
        ];

        $spouse = PersonSpouse::fromArray($data);

        $this->assertNull($spouse->name);
        $this->assertTrue($spouse->divorced);
        $this->assertEquals('Несовместимость характеров', $spouse->divorcedReason);
        $this->assertEquals('MALE', $spouse->sex);
        $this->assertEquals(0, $spouse->children);
    }

    /**
     * Тест метода isDivorced()
     */
    public function testIsDivorced(): void
    {
        $marriedSpouse = new PersonSpouse(
            personId: 32169,
            name: 'Сьюзан Дауни',
            divorced: false,
            divorcedReason: null,
            sex: 'FEMALE',
            children: 2,
            webUrl: 'https://www.kinopoisk.ru/name/32169/',
            relation: 'супруга'
        );

        $divorcedSpouse = new PersonSpouse(
            personId: 32170,
            name: 'Джейн Смит',
            divorced: true,
            divorcedReason: 'Несовместимость',
            sex: 'FEMALE',
            children: 1,
            webUrl: 'https://www.kinopoisk.ru/name/32170/',
            relation: 'супруга'
        );

        $this->assertFalse($marriedSpouse->isDivorced());
        $this->assertTrue($divorcedSpouse->isDivorced());
    }

    /**
     * Тест метода isMarried()
     */
    public function testIsMarried(): void
    {
        $marriedSpouse = new PersonSpouse(
            personId: 32169,
            name: 'Сьюзан Дауни',
            divorced: false,
            divorcedReason: null,
            sex: 'FEMALE',
            children: 2,
            webUrl: 'https://www.kinopoisk.ru/name/32169/',
            relation: 'супруга'
        );

        $divorcedSpouse = new PersonSpouse(
            personId: 32170,
            name: 'Джейн Смит',
            divorced: true,
            divorcedReason: 'Несовместимость',
            sex: 'FEMALE',
            children: 1,
            webUrl: 'https://www.kinopoisk.ru/name/32170/',
            relation: 'супруга'
        );

        $this->assertTrue($marriedSpouse->isMarried());
        $this->assertFalse($divorcedSpouse->isMarried());
    }

    /**
     * Тест метода isMale()
     */
    public function testIsMale(): void
    {
        $maleSpouse = new PersonSpouse(
            personId: 32169,
            name: 'Джон Доу',
            divorced: false,
            divorcedReason: null,
            sex: 'MALE',
            children: 2,
            webUrl: 'https://www.kinopoisk.ru/name/32169/',
            relation: 'супруг'
        );

        $femaleSpouse = new PersonSpouse(
            personId: 32170,
            name: 'Сьюзан Дауни',
            divorced: false,
            divorcedReason: null,
            sex: 'FEMALE',
            children: 2,
            webUrl: 'https://www.kinopoisk.ru/name/32170/',
            relation: 'супруга'
        );

        $this->assertTrue($maleSpouse->isMale());
        $this->assertFalse($femaleSpouse->isMale());
    }

    /**
     * Тест метода isFemale()
     */
    public function testIsFemale(): void
    {
        $maleSpouse = new PersonSpouse(
            personId: 32169,
            name: 'Джон Доу',
            divorced: false,
            divorcedReason: null,
            sex: 'MALE',
            children: 2,
            webUrl: 'https://www.kinopoisk.ru/name/32169/',
            relation: 'супруг'
        );

        $femaleSpouse = new PersonSpouse(
            personId: 32170,
            name: 'Сьюзан Дауни',
            divorced: false,
            divorcedReason: null,
            sex: 'FEMALE',
            children: 2,
            webUrl: 'https://www.kinopoisk.ru/name/32170/',
            relation: 'супруга'
        );

        $this->assertFalse($maleSpouse->isFemale());
        $this->assertTrue($femaleSpouse->isFemale());
    }

    /**
     * Тест метода getDisplayName()
     */
    public function testGetDisplayName(): void
    {
        $spouseWithName = new PersonSpouse(
            personId: 32169,
            name: 'Сьюзан Дауни',
            divorced: false,
            divorcedReason: null,
            sex: 'FEMALE',
            children: 2,
            webUrl: 'https://www.kinopoisk.ru/name/32169/',
            relation: 'супруга'
        );

        $spouseWithoutName = new PersonSpouse(
            personId: 32170,
            name: null,
            divorced: false,
            divorcedReason: null,
            sex: 'FEMALE',
            children: 2,
            webUrl: 'https://www.kinopoisk.ru/name/32170/',
            relation: 'супруга'
        );

        $this->assertEquals('Сьюзан Дауни', $spouseWithName->getDisplayName());
        $this->assertEquals('Неизвестно', $spouseWithoutName->getDisplayName());
    }

    /**
     * Тест метода getChildrenInfo()
     */
    public function testGetChildrenInfo(): void
    {
        $noChildren = new PersonSpouse(
            personId: 32169,
            name: 'Сьюзан Дауни',
            divorced: false,
            divorcedReason: null,
            sex: 'FEMALE',
            children: 0,
            webUrl: 'https://www.kinopoisk.ru/name/32169/',
            relation: 'супруга'
        );

        $oneChild = new PersonSpouse(
            personId: 32170,
            name: 'Джейн Смит',
            divorced: false,
            divorcedReason: null,
            sex: 'FEMALE',
            children: 1,
            webUrl: 'https://www.kinopoisk.ru/name/32170/',
            relation: 'супруга'
        );

        $twoChildren = new PersonSpouse(
            personId: 32171,
            name: 'Мэри Джонс',
            divorced: false,
            divorcedReason: null,
            sex: 'FEMALE',
            children: 2,
            webUrl: 'https://www.kinopoisk.ru/name/32171/',
            relation: 'супруга'
        );

        $fiveChildren = new PersonSpouse(
            personId: 32172,
            name: 'Энн Браун',
            divorced: false,
            divorcedReason: null,
            sex: 'FEMALE',
            children: 5,
            webUrl: 'https://www.kinopoisk.ru/name/32172/',
            relation: 'супруга'
        );

        $this->assertEquals('Нет детей', $noChildren->getChildrenInfo());
        $this->assertEquals('1 ребенок', $oneChild->getChildrenInfo());
        $this->assertEquals('2 ребенка', $twoChildren->getChildrenInfo());
        $this->assertEquals('5 детей', $fiveChildren->getChildrenInfo());
    }

    /**
     * Тест метода getMarriageInfo()
     */
    public function testGetMarriageInfo(): void
    {
        $marriedSpouse = new PersonSpouse(
            personId: 32169,
            name: 'Сьюзан Дауни',
            divorced: false,
            divorcedReason: null,
            sex: 'FEMALE',
            children: 2,
            webUrl: 'https://www.kinopoisk.ru/name/32169/',
            relation: 'супруга'
        );

        $divorcedSpouse = new PersonSpouse(
            personId: 32170,
            name: 'Джейн Смит',
            divorced: true,
            divorcedReason: 'Несовместимость характеров',
            sex: 'FEMALE',
            children: 1,
            webUrl: 'https://www.kinopoisk.ru/name/32170/',
            relation: 'супруга'
        );

        $divorcedSpouseNoReason = new PersonSpouse(
            personId: 32171,
            name: 'Мэри Джонс',
            divorced: true,
            divorcedReason: null,
            sex: 'FEMALE',
            children: 0,
            webUrl: 'https://www.kinopoisk.ru/name/32171/',
            relation: 'супруга'
        );

        $this->assertEquals('В браке, 2 ребенка', $marriedSpouse->getMarriageInfo());
        $this->assertEquals('Разведен (Несовместимость характеров), 1 ребенок', $divorcedSpouse->getMarriageInfo());
        $this->assertEquals('Разведен, Нет детей', $divorcedSpouseNoReason->getMarriageInfo());
    }

    /**
     * Тест создания объекта с значениями по умолчанию
     */
    public function testFromArrayWithDefaults(): void
    {
        $data = [
            'personId' => 32169,
            'sex' => 'FEMALE',
            'webUrl' => 'https://www.kinopoisk.ru/name/32169/',
            'relation' => 'супруга'
        ];

        $spouse = PersonSpouse::fromArray($data);

        $this->assertNull($spouse->name);
        $this->assertFalse($spouse->divorced);
        $this->assertNull($spouse->divorcedReason);
        $this->assertEquals(0, $spouse->children);
    }
} 