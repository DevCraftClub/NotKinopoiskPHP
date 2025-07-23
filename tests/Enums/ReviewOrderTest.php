<?php

declare(strict_types=1);

namespace Tests\Enums;

use NotKinopoisk\Enums\ReviewOrder;
use PHPUnit\Framework\TestCase;

/**
 * Тесты для enum ReviewOrder
 * 
 * @package Tests\Enums
 * @author Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @since 1.0.0
 */
class ReviewOrderTest extends TestCase
{
    /**
     * Тест всех значений enum
     */
    public function testAllValues(): void
    {
        $this->assertEquals('DATE_DESC', ReviewOrder::DATE_DESC->value);
        $this->assertEquals('DATE_ASC', ReviewOrder::DATE_ASC->value);
        $this->assertEquals('USER_POSITIVE_RATING_ASC', ReviewOrder::USER_POSITIVE_RATING_ASC->value);
        $this->assertEquals('USER_POSITIVE_RATING_DESC', ReviewOrder::USER_POSITIVE_RATING_DESC->value);
        $this->assertEquals('USER_NEGATIVE_RATING_ASC', ReviewOrder::USER_NEGATIVE_RATING_ASC->value);
        $this->assertEquals('USER_NEGATIVE_RATING_DESC', ReviewOrder::USER_NEGATIVE_RATING_DESC->value);
    }

    /**
     * Тест метода getDisplayName()
     */
    public function testGetDisplayName(): void
    {
        $this->assertEquals('По дате (новые сначала)', ReviewOrder::DATE_DESC->getDisplayName());
        $this->assertEquals('По дате (старые сначала)', ReviewOrder::DATE_ASC->getDisplayName());
        $this->assertEquals('По положительным оценкам (низкие сначала)', ReviewOrder::USER_POSITIVE_RATING_ASC->getDisplayName());
        $this->assertEquals('По положительным оценкам (высокие сначала)', ReviewOrder::USER_POSITIVE_RATING_DESC->getDisplayName());
        $this->assertEquals('По отрицательным оценкам (низкие сначала)', ReviewOrder::USER_NEGATIVE_RATING_ASC->getDisplayName());
        $this->assertEquals('По отрицательным оценкам (высокие сначала)', ReviewOrder::USER_NEGATIVE_RATING_DESC->getDisplayName());
    }

    /**
     * Тест метода getShortName()
     */
    public function testGetShortName(): void
    {
        $this->assertEquals('Дата ↓', ReviewOrder::DATE_DESC->getShortName());
        $this->assertEquals('Дата ↑', ReviewOrder::DATE_ASC->getShortName());
        $this->assertEquals('Положительные ↑', ReviewOrder::USER_POSITIVE_RATING_ASC->getShortName());
        $this->assertEquals('Положительные ↓', ReviewOrder::USER_POSITIVE_RATING_DESC->getShortName());
        $this->assertEquals('Отрицательные ↑', ReviewOrder::USER_NEGATIVE_RATING_ASC->getShortName());
        $this->assertEquals('Отрицательные ↓', ReviewOrder::USER_NEGATIVE_RATING_DESC->getShortName());
    }

    /**
     * Тест методов проверки типа сортировки
     */
    public function testSortTypeChecks(): void
    {
        // Проверка сортировки по дате
        $this->assertTrue(ReviewOrder::DATE_DESC->isDateSort());
        $this->assertTrue(ReviewOrder::DATE_ASC->isDateSort());
        $this->assertFalse(ReviewOrder::USER_POSITIVE_RATING_DESC->isDateSort());

        // Проверка сортировки по положительным оценкам
        $this->assertTrue(ReviewOrder::USER_POSITIVE_RATING_ASC->isPositiveRatingSort());
        $this->assertTrue(ReviewOrder::USER_POSITIVE_RATING_DESC->isPositiveRatingSort());
        $this->assertFalse(ReviewOrder::DATE_DESC->isPositiveRatingSort());

        // Проверка сортировки по отрицательным оценкам
        $this->assertTrue(ReviewOrder::USER_NEGATIVE_RATING_ASC->isNegativeRatingSort());
        $this->assertTrue(ReviewOrder::USER_NEGATIVE_RATING_DESC->isNegativeRatingSort());
        $this->assertFalse(ReviewOrder::DATE_DESC->isNegativeRatingSort());
    }

    /**
     * Тест методов направления сортировки
     */
    public function testSortDirection(): void
    {
        // Проверка возрастания
        $this->assertTrue(ReviewOrder::DATE_ASC->isAscending());
        $this->assertTrue(ReviewOrder::USER_POSITIVE_RATING_ASC->isAscending());
        $this->assertTrue(ReviewOrder::USER_NEGATIVE_RATING_ASC->isAscending());
        $this->assertFalse(ReviewOrder::DATE_DESC->isAscending());

        // Проверка убывания
        $this->assertTrue(ReviewOrder::DATE_DESC->isDescending());
        $this->assertTrue(ReviewOrder::USER_POSITIVE_RATING_DESC->isDescending());
        $this->assertTrue(ReviewOrder::USER_NEGATIVE_RATING_DESC->isDescending());
        $this->assertFalse(ReviewOrder::DATE_ASC->isDescending());
    }

    /**
     * Тест метода getDirection()
     */
    public function testGetDirection(): void
    {
        $this->assertEquals('asc', ReviewOrder::DATE_ASC->getDirection());
        $this->assertEquals('asc', ReviewOrder::USER_POSITIVE_RATING_ASC->getDirection());
        $this->assertEquals('asc', ReviewOrder::USER_NEGATIVE_RATING_ASC->getDirection());
        $this->assertEquals('desc', ReviewOrder::DATE_DESC->getDirection());
        $this->assertEquals('desc', ReviewOrder::USER_POSITIVE_RATING_DESC->getDirection());
        $this->assertEquals('desc', ReviewOrder::USER_NEGATIVE_RATING_DESC->getDirection());
    }

    /**
     * Тест метода getFieldType()
     */
    public function testGetFieldType(): void
    {
        $this->assertEquals('date', ReviewOrder::DATE_DESC->getFieldType());
        $this->assertEquals('date', ReviewOrder::DATE_ASC->getFieldType());
        $this->assertEquals('positive_rating', ReviewOrder::USER_POSITIVE_RATING_ASC->getFieldType());
        $this->assertEquals('positive_rating', ReviewOrder::USER_POSITIVE_RATING_DESC->getFieldType());
        $this->assertEquals('negative_rating', ReviewOrder::USER_NEGATIVE_RATING_ASC->getFieldType());
        $this->assertEquals('negative_rating', ReviewOrder::USER_NEGATIVE_RATING_DESC->getFieldType());
    }

    /**
     * Тест метода getDefault()
     */
    public function testGetDefault(): void
    {
        $default = ReviewOrder::getDefault();
        $this->assertEquals(ReviewOrder::DATE_DESC, $default);
        $this->assertEquals('DATE_DESC', $default->value);
    }

    /**
     * Тест метода getAll()
     */
    public function testGetAll(): void
    {
        $allOrders = ReviewOrder::getAll();
        $this->assertCount(6, $allOrders);
        $this->assertContains(ReviewOrder::DATE_DESC, $allOrders);
        $this->assertContains(ReviewOrder::DATE_ASC, $allOrders);
        $this->assertContains(ReviewOrder::USER_POSITIVE_RATING_ASC, $allOrders);
        $this->assertContains(ReviewOrder::USER_POSITIVE_RATING_DESC, $allOrders);
        $this->assertContains(ReviewOrder::USER_NEGATIVE_RATING_ASC, $allOrders);
        $this->assertContains(ReviewOrder::USER_NEGATIVE_RATING_DESC, $allOrders);
    }

    /**
     * Тест создания enum из строки
     */
    public function testFromString(): void
    {
        $this->assertEquals(ReviewOrder::DATE_DESC, ReviewOrder::from('DATE_DESC'));
        $this->assertEquals(ReviewOrder::DATE_ASC, ReviewOrder::from('DATE_ASC'));
        $this->assertEquals(ReviewOrder::USER_POSITIVE_RATING_ASC, ReviewOrder::from('USER_POSITIVE_RATING_ASC'));
        $this->assertEquals(ReviewOrder::USER_POSITIVE_RATING_DESC, ReviewOrder::from('USER_POSITIVE_RATING_DESC'));
        $this->assertEquals(ReviewOrder::USER_NEGATIVE_RATING_ASC, ReviewOrder::from('USER_NEGATIVE_RATING_ASC'));
        $this->assertEquals(ReviewOrder::USER_NEGATIVE_RATING_DESC, ReviewOrder::from('USER_NEGATIVE_RATING_DESC'));
    }

    /**
     * Тест исключения при неверном значении
     */
    public function testInvalidValue(): void
    {
        $this->expectException(\ValueError::class);
        ReviewOrder::from('INVALID_ORDER');
    }

    /**
     * Тест логики сортировки
     */
    public function testSortingLogic(): void
    {
        // Проверяем, что все типы сортировки покрывают все возможные комбинации
        $dateSorts = array_filter(ReviewOrder::getAll(), fn($order) => $order->isDateSort());
        $positiveSorts = array_filter(ReviewOrder::getAll(), fn($order) => $order->isPositiveRatingSort());
        $negativeSorts = array_filter(ReviewOrder::getAll(), fn($order) => $order->isNegativeRatingSort());

        $this->assertCount(2, $dateSorts);
        $this->assertCount(2, $positiveSorts);
        $this->assertCount(2, $negativeSorts);

        // Проверяем, что каждый тип сортировки имеет оба направления
        $ascendingSorts = array_filter(ReviewOrder::getAll(), fn($order) => $order->isAscending());
        $descendingSorts = array_filter(ReviewOrder::getAll(), fn($order) => $order->isDescending());

        $this->assertCount(3, $ascendingSorts);
        $this->assertCount(3, $descendingSorts);
    }
} 