<?php

declare(strict_types=1);

namespace Tests\Models;

use NotKinopoisk\Models\Fact;
use NotKinopoisk\Enums\FactType;
use PHPUnit\Framework\TestCase;

/**
 * Тесты для модели Fact
 * 
 * @package Tests\Models
 * @author Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @since 1.0.0
 */
class FactTest extends TestCase
{
    /**
     * Тест создания объекта Fact
     */
    public function testCreateFact(): void
    {
        $fact = new Fact(
            text: 'Интересный факт о съемках',
            type: FactType::FACT,
            spoiler: false
        );

        $this->assertEquals('Интересный факт о съемках', $fact->text);
        $this->assertEquals(FactType::FACT, $fact->type);
        $this->assertFalse($fact->spoiler);
    }

    /**
     * Тест создания объекта Fact из массива данных
     */
    public function testFromArray(): void
    {
        $data = [
            'text' => 'Ошибка в сцене драки',
            'type' => 'BLOOPER',
            'spoiler' => true
        ];

        $fact = Fact::fromArray($data);

        $this->assertEquals('Ошибка в сцене драки', $fact->text);
        $this->assertEquals(FactType::BLOOPER, $fact->type);
        $this->assertTrue($fact->spoiler);
    }

    /**
     * Тест метода isBlooper()
     */
    public function testIsBlooper(): void
    {
        $blooper = new Fact(
            text: 'Ошибка в фильме',
            type: FactType::BLOOPER,
            spoiler: false
        );

        $fact = new Fact(
            text: 'Интересный факт',
            type: FactType::FACT,
            spoiler: false
        );

        $this->assertTrue($blooper->isBlooper());
        $this->assertFalse($fact->isBlooper());
    }

    /**
     * Тест метода isFact()
     */
    public function testIsFact(): void
    {
        $blooper = new Fact(
            text: 'Ошибка в фильме',
            type: FactType::BLOOPER,
            spoiler: false
        );

        $fact = new Fact(
            text: 'Интересный факт',
            type: FactType::FACT,
            spoiler: false
        );

        $this->assertFalse($blooper->isFact());
        $this->assertTrue($fact->isFact());
    }

    /**
     * Тест readonly свойств
     */
    public function testReadonlyProperties(): void
    {
        $fact = new Fact(
            text: 'Тестовый факт',
            type: FactType::FACT,
            spoiler: false
        );

        // Проверяем, что свойства действительно readonly
        $this->expectException(\Error::class);
        $fact->text = 'Новый текст';
    }

    /**
     * Тест создания факта с спойлером
     */
    public function testFactWithSpoiler(): void
    {
        $fact = new Fact(
            text: 'Спойлер о конце фильма',
            type: FactType::FACT,
            spoiler: true
        );

        $this->assertTrue($fact->spoiler);
        $this->assertEquals('Спойлер о конце фильма', $fact->text);
    }

    /**
     * Тест создания блупера
     */
    public function testCreateBlooper(): void
    {
        $blooper = new Fact(
            text: 'В сцене видно, что актер использует дублера',
            type: FactType::BLOOPER,
            spoiler: false
        );

        $this->assertEquals(FactType::BLOOPER, $blooper->type);
        $this->assertTrue($blooper->isBlooper());
        $this->assertFalse($blooper->isFact());
    }
} 