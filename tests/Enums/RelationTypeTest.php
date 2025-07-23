<?php

declare(strict_types=1);

namespace Tests\Enums;

use NotKinopoisk\Enums\RelationType;
use PHPUnit\Framework\TestCase;

class RelationTypeTest extends TestCase
{
    public function testFromString(): void
    {
        $this->assertEquals(RelationType::SIMILAR, RelationType::from('SIMILAR'));
        $this->assertEquals(RelationType::SEQUEL, RelationType::from('SEQUEL'));
        $this->assertEquals(RelationType::PREQUEL, RelationType::from('PREQUEL'));
        $this->assertEquals(RelationType::REMAKE, RelationType::from('REMAKE'));
        $this->assertEquals(RelationType::UNKNOWN, RelationType::from('UNKNOWN'));
    }

    public function testGetDescription(): void
    {
        $this->assertEquals('Похожий фильм', RelationType::SIMILAR->getDescription());
        $this->assertEquals('Сиквел', RelationType::SEQUEL->getDescription());
        $this->assertEquals('Приквел', RelationType::PREQUEL->getDescription());
        $this->assertEquals('Ремейк', RelationType::REMAKE->getDescription());
        $this->assertEquals('Неизвестный тип связи', RelationType::UNKNOWN->getDescription());
    }

    public function testInvalidValueThrowsException(): void
    {
        $this->expectException(\ValueError::class);
        RelationType::from('INVALID');
    }

    public function testAllEnumValues(): void
    {
        $cases = RelationType::cases();
        $this->assertCount(5, $cases);
    }
} 