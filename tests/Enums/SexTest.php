<?php

declare(strict_types=1);

namespace Tests\Enums;

use NotKinopoisk\Enums\Sex;
use PHPUnit\Framework\TestCase;

class SexTest extends TestCase
{
    public function testFromString(): void
    {
        $this->assertEquals(Sex::MALE, Sex::from('MALE'));
        $this->assertEquals(Sex::FEMALE, Sex::from('FEMALE'));
        $this->assertEquals(Sex::UNKNOWN, Sex::from('UNKNOWN'));
    }

    public function testGetDisplayName(): void
    {
        $this->assertEquals('Мужской', Sex::MALE->getDisplayName());
        $this->assertEquals('Женский', Sex::FEMALE->getDisplayName());
        $this->assertEquals('Неизвестно', Sex::UNKNOWN->getDisplayName());
    }

    public function testInvalidValueThrowsException(): void
    {
        $this->expectException(\ValueError::class);
        Sex::from('INVALID');
    }

    public function testAllEnumValues(): void
    {
        $cases = Sex::cases();
        $this->assertCount(3, $cases);
    }
} 