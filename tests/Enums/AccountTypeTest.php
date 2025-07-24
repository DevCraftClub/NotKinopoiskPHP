<?php

declare(strict_types=1);

namespace Tests\Enums;

use NotKinopoisk\Enums\AccountType;
use PHPUnit\Framework\TestCase;

/**
 * Тесты для enum AccountType
 * 
 * @package Tests\Enums
 * @author Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @since 1.0.0
 */
class AccountTypeTest extends TestCase
{
    /**
     * Тест создания enum из строки
     */
    public function testFromString(): void
    {
        $this->assertEquals(AccountType::FREE, AccountType::from('FREE'));
        $this->assertEquals(AccountType::PAID, AccountType::from('PAID'));
        $this->assertEquals(AccountType::UNLIMITED, AccountType::from('UNLIMITED'));
    }

    /**
     * Тест получения значения enum
     */
    public function testGetValue(): void
    {
        $this->assertEquals('FREE', AccountType::FREE->value);
        $this->assertEquals('PAID', AccountType::PAID->value);
        $this->assertEquals('UNLIMITED', AccountType::UNLIMITED->value);
    }

    /**
     * Тест метода isFree()
     */
    public function testIsFree(): void
    {
        $this->assertTrue(AccountType::FREE->isFree());
        $this->assertFalse(AccountType::PAID->isFree());
        $this->assertFalse(AccountType::UNLIMITED->isFree());
    }

    /**
     * Тест метода isUnlimited()
     */
    public function testIsUnlimited(): void
    {
        $this->assertFalse(AccountType::FREE->isUnlimited());
        $this->assertFalse(AccountType::PAID->isUnlimited());
        $this->assertTrue(AccountType::UNLIMITED->isUnlimited());
    }

    /**
     * Тест метода getDisplayName()
     */
    public function testGetDisplayName(): void
    {
        $this->assertEquals('Бесплатный', AccountType::FREE->getDisplayName());
        $this->assertEquals('Платный', AccountType::PAID->getDisplayName());
        $this->assertEquals('Безлимитный', AccountType::UNLIMITED->getDisplayName());
    }

    /**
     * Тест исключения при неверном значении
     */
    public function testInvalidValueThrowsException(): void
    {
        $this->expectException(\ValueError::class);
        AccountType::from('INVALID_TYPE');
    }

    /**
     * Тест сравнения enum значений
     */
    public function testEnumComparison(): void
    {
        $free1 = AccountType::FREE;
        $free2 = AccountType::FREE;
        $paid = AccountType::PAID;

        $this->assertTrue($free1 === $free2);
        $this->assertTrue($free1 === AccountType::FREE);
        $this->assertTrue($paid === AccountType::PAID);
        $this->assertTrue($free1 !== $paid);
    }

    /**
     * Тест всех доступных значений enum
     */
    public function testAllEnumValues(): void
    {
        $expectedValues = [
            'FREE',
            'PAID',
            'UNLIMITED'
        ];

        $actualValues = array_map(fn($case) => $case->value, AccountType::cases());
        $this->assertEquals($expectedValues, $actualValues);
    }
} 