<?php

declare(strict_types=1);

namespace Tests\Enums;

use NotKinopoisk\Enums\DistributionSubType;
use PHPUnit\Framework\TestCase;

class DistributionSubTypeTest extends TestCase
{
    public function testFromString(): void
    {
        $this->assertEquals(DistributionSubType::CINEMA, DistributionSubType::from('CINEMA'));
        $this->assertEquals(DistributionSubType::DVD, DistributionSubType::from('DVD'));
        $this->assertEquals(DistributionSubType::DIGITAL, DistributionSubType::from('DIGITAL'));
        $this->assertEquals(DistributionSubType::BLURAY, DistributionSubType::from('BLURAY'));
    }

    public function testGetDisplayName(): void
    {
        $this->assertEquals('Кинотеатры', DistributionSubType::CINEMA->getDisplayName());
        $this->assertEquals('DVD', DistributionSubType::DVD->getDisplayName());
        $this->assertEquals('Цифровой релиз', DistributionSubType::DIGITAL->getDisplayName());
        $this->assertEquals('Blu-ray', DistributionSubType::BLURAY->getDisplayName());
    }

    public function testInvalidValueThrowsException(): void
    {
        $this->expectException(\ValueError::class);
        DistributionSubType::from('INVALID');
    }

    public function testAllEnumValues(): void
    {
        $cases = DistributionSubType::cases();
        $this->assertCount(4, $cases);
    }
} 