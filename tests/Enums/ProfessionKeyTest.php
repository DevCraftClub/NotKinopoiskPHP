<?php

declare(strict_types=1);

namespace Tests\Enums;

use NotKinopoisk\Enums\ProfessionKey;
use PHPUnit\Framework\TestCase;

class ProfessionKeyTest extends TestCase
{
    public function testFromString(): void
    {
        $this->assertEquals(ProfessionKey::WRITER, ProfessionKey::from('WRITER'));
        $this->assertEquals(ProfessionKey::OPERATOR, ProfessionKey::from('OPERATOR'));
        $this->assertEquals(ProfessionKey::EDITOR, ProfessionKey::from('EDITOR'));
        $this->assertEquals(ProfessionKey::COMPOSER, ProfessionKey::from('COMPOSER'));
        $this->assertEquals(ProfessionKey::PRODUCER_USSR, ProfessionKey::from('PRODUCER_USSR'));
        $this->assertEquals(ProfessionKey::TRANSLATOR, ProfessionKey::from('TRANSLATOR'));
        $this->assertEquals(ProfessionKey::DIRECTOR, ProfessionKey::from('DIRECTOR'));
        $this->assertEquals(ProfessionKey::DESIGN, ProfessionKey::from('DESIGN'));
        $this->assertEquals(ProfessionKey::PRODUCER, ProfessionKey::from('PRODUCER'));
        $this->assertEquals(ProfessionKey::ACTOR, ProfessionKey::from('ACTOR'));
        $this->assertEquals(ProfessionKey::VOICE_DIRECTOR, ProfessionKey::from('VOICE_DIRECTOR'));
        $this->assertEquals(ProfessionKey::UNKNOWN, ProfessionKey::from('UNKNOWN'));
        $this->assertEquals(ProfessionKey::HIMSELF, ProfessionKey::from('HIMSELF'));
        $this->assertEquals(ProfessionKey::HERSELF, ProfessionKey::from('HERSELF'));
        $this->assertEquals(ProfessionKey::HRONO_TITR_MALE, ProfessionKey::from('HRONO_TITR_MALE'));
        $this->assertEquals(ProfessionKey::HRONO_TITR_FEMALE, ProfessionKey::from('HRONO_TITR_FEMALE'));
    }

    public function testGetDisplayName(): void
    {
        $this->assertEquals('Сценарист', ProfessionKey::WRITER->getDisplayName());
        $this->assertEquals('Оператор', ProfessionKey::OPERATOR->getDisplayName());
        $this->assertEquals('Монтажер', ProfessionKey::EDITOR->getDisplayName());
        $this->assertEquals('Композитор', ProfessionKey::COMPOSER->getDisplayName());
        $this->assertEquals('Продюсер (СССР)', ProfessionKey::PRODUCER_USSR->getDisplayName());
        $this->assertEquals('Переводчик', ProfessionKey::TRANSLATOR->getDisplayName());
        $this->assertEquals('Режиссер', ProfessionKey::DIRECTOR->getDisplayName());
        $this->assertEquals('Дизайнер', ProfessionKey::DESIGN->getDisplayName());
        $this->assertEquals('Продюсер', ProfessionKey::PRODUCER->getDisplayName());
        $this->assertEquals('Актер', ProfessionKey::ACTOR->getDisplayName());
        $this->assertEquals('Режиссер дубляжа', ProfessionKey::VOICE_DIRECTOR->getDisplayName());
        $this->assertEquals('Неизвестно', ProfessionKey::UNKNOWN->getDisplayName());
        $this->assertEquals('Сам', ProfessionKey::HIMSELF->getDisplayName());
        $this->assertEquals('Сама', ProfessionKey::HERSELF->getDisplayName());
        $this->assertEquals('Хроно титр (муж.)', ProfessionKey::HRONO_TITR_MALE->getDisplayName());
        $this->assertEquals('Хроно титр (жен.)', ProfessionKey::HRONO_TITR_FEMALE->getDisplayName());
    }

    public function testInvalidValueThrowsException(): void
    {
        $this->expectException(\ValueError::class);
        ProfessionKey::from('INVALID');
    }

    public function testAllEnumValues(): void
    {
        $cases = ProfessionKey::cases();
        $this->assertCount(16, $cases);
    }
} 