<?php

declare(strict_types=1);

namespace Tests\Enums;

use NotKinopoisk\Enums\ProfessionKey;
use PHPUnit\Framework\TestCase;

/**
 * Тесты для enum ProfessionKey
 * 
 * @package Tests\Enums
 * @author Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @since 1.0.0
 */
class ProfessionKeyTest extends TestCase
{
    /**
     * Тест всех значений enum
     */
    public function testAllValues(): void
    {
        $this->assertEquals('WRITER', ProfessionKey::WRITER->value);
        $this->assertEquals('OPERATOR', ProfessionKey::OPERATOR->value);
        $this->assertEquals('EDITOR', ProfessionKey::EDITOR->value);
        $this->assertEquals('COMPOSER', ProfessionKey::COMPOSER->value);
        $this->assertEquals('PRODUCER_USSR', ProfessionKey::PRODUCER_USSR->value);
        $this->assertEquals('HIMSELF', ProfessionKey::HIMSELF->value);
        $this->assertEquals('HERSELF', ProfessionKey::HERSELF->value);
        $this->assertEquals('HRONO_TITR_MALE', ProfessionKey::HRONO_TITR_MALE->value);
        $this->assertEquals('HRONO_TITR_FEMALE', ProfessionKey::HRONO_TITR_FEMALE->value);
        $this->assertEquals('TRANSLATOR', ProfessionKey::TRANSLATOR->value);
        $this->assertEquals('DIRECTOR', ProfessionKey::DIRECTOR->value);
        $this->assertEquals('DESIGN', ProfessionKey::DESIGN->value);
        $this->assertEquals('PRODUCER', ProfessionKey::PRODUCER->value);
        $this->assertEquals('ACTOR', ProfessionKey::ACTOR->value);
        $this->assertEquals('VOICE_DIRECTOR', ProfessionKey::VOICE_DIRECTOR->value);
        $this->assertEquals('UNKNOWN', ProfessionKey::UNKNOWN->value);
    }

    /**
     * Тест метода getDisplayName()
     */
    public function testGetDisplayName(): void
    {
        $this->assertEquals('Актер', ProfessionKey::ACTOR->getDisplayName());
        $this->assertEquals('Режиссер', ProfessionKey::DIRECTOR->getDisplayName());
        $this->assertEquals('Сценарист', ProfessionKey::WRITER->getDisplayName());
        $this->assertEquals('Продюсер', ProfessionKey::PRODUCER->getDisplayName());
        $this->assertEquals('Продюсер', ProfessionKey::PRODUCER_USSR->getDisplayName());
        $this->assertEquals('Композитор', ProfessionKey::COMPOSER->getDisplayName());
        $this->assertEquals('Оператор', ProfessionKey::OPERATOR->getDisplayName());
        $this->assertEquals('Монтажер', ProfessionKey::EDITOR->getDisplayName());
        $this->assertEquals('Художник', ProfessionKey::DESIGN->getDisplayName());
        $this->assertEquals('Переводчик', ProfessionKey::TRANSLATOR->getDisplayName());
        $this->assertEquals('Режиссер дубляжа', ProfessionKey::VOICE_DIRECTOR->getDisplayName());
        $this->assertEquals('В роли самого себя', ProfessionKey::HIMSELF->getDisplayName());
        $this->assertEquals('В роли самой себя', ProfessionKey::HERSELF->getDisplayName());
        $this->assertEquals('За кадром (мужской голос)', ProfessionKey::HRONO_TITR_MALE->getDisplayName());
        $this->assertEquals('За кадром (женский голос)', ProfessionKey::HRONO_TITR_FEMALE->getDisplayName());
        $this->assertEquals('Неизвестно', ProfessionKey::UNKNOWN->getDisplayName());
    }

    /**
     * Тест метода isActor()
     */
    public function testIsActor(): void
    {
        $this->assertTrue(ProfessionKey::ACTOR->isActor());
        $this->assertFalse(ProfessionKey::DIRECTOR->isActor());
        $this->assertFalse(ProfessionKey::WRITER->isActor());
        $this->assertFalse(ProfessionKey::PRODUCER->isActor());
    }

    /**
     * Тест метода isDirector()
     */
    public function testIsDirector(): void
    {
        $this->assertTrue(ProfessionKey::DIRECTOR->isDirector());
        $this->assertFalse(ProfessionKey::ACTOR->isDirector());
        $this->assertFalse(ProfessionKey::WRITER->isDirector());
        $this->assertFalse(ProfessionKey::PRODUCER->isDirector());
    }

    /**
     * Тест метода isWriter()
     */
    public function testIsWriter(): void
    {
        $this->assertTrue(ProfessionKey::WRITER->isWriter());
        $this->assertFalse(ProfessionKey::ACTOR->isWriter());
        $this->assertFalse(ProfessionKey::DIRECTOR->isWriter());
        $this->assertFalse(ProfessionKey::PRODUCER->isWriter());
    }

    /**
     * Тест метода isProducer()
     */
    public function testIsProducer(): void
    {
        $this->assertTrue(ProfessionKey::PRODUCER->isProducer());
        $this->assertTrue(ProfessionKey::PRODUCER_USSR->isProducer());
        $this->assertFalse(ProfessionKey::ACTOR->isProducer());
        $this->assertFalse(ProfessionKey::DIRECTOR->isProducer());
        $this->assertFalse(ProfessionKey::WRITER->isProducer());
    }

    /**
     * Тест метода isTechnical()
     */
    public function testIsTechnical(): void
    {
        $this->assertTrue(ProfessionKey::OPERATOR->isTechnical());
        $this->assertTrue(ProfessionKey::EDITOR->isTechnical());
        $this->assertTrue(ProfessionKey::DESIGN->isTechnical());
        $this->assertTrue(ProfessionKey::VOICE_DIRECTOR->isTechnical());
        $this->assertFalse(ProfessionKey::ACTOR->isTechnical());
        $this->assertFalse(ProfessionKey::DIRECTOR->isTechnical());
        $this->assertFalse(ProfessionKey::WRITER->isTechnical());
        $this->assertFalse(ProfessionKey::PRODUCER->isTechnical());
    }

    /**
     * Тест метода isCreative()
     */
    public function testIsCreative(): void
    {
        $this->assertTrue(ProfessionKey::ACTOR->isCreative());
        $this->assertTrue(ProfessionKey::DIRECTOR->isCreative());
        $this->assertTrue(ProfessionKey::WRITER->isCreative());
        $this->assertTrue(ProfessionKey::COMPOSER->isCreative());
        $this->assertFalse(ProfessionKey::PRODUCER->isCreative());
        $this->assertFalse(ProfessionKey::OPERATOR->isCreative());
        $this->assertFalse(ProfessionKey::EDITOR->isCreative());
    }

    /**
     * Тест метода isManagement()
     */
    public function testIsManagement(): void
    {
        $this->assertTrue(ProfessionKey::PRODUCER->isManagement());
        $this->assertTrue(ProfessionKey::PRODUCER_USSR->isManagement());
        $this->assertFalse(ProfessionKey::ACTOR->isManagement());
        $this->assertFalse(ProfessionKey::DIRECTOR->isManagement());
        $this->assertFalse(ProfessionKey::WRITER->isManagement());
        $this->assertFalse(ProfessionKey::OPERATOR->isManagement());
    }

    /**
     * Тест метода isSpecial()
     */
    public function testIsSpecial(): void
    {
        $this->assertTrue(ProfessionKey::HIMSELF->isSpecial());
        $this->assertTrue(ProfessionKey::HERSELF->isSpecial());
        $this->assertTrue(ProfessionKey::HRONO_TITR_MALE->isSpecial());
        $this->assertTrue(ProfessionKey::HRONO_TITR_FEMALE->isSpecial());
        $this->assertTrue(ProfessionKey::TRANSLATOR->isSpecial());
        $this->assertTrue(ProfessionKey::UNKNOWN->isSpecial());
        $this->assertFalse(ProfessionKey::ACTOR->isSpecial());
        $this->assertFalse(ProfessionKey::DIRECTOR->isSpecial());
        $this->assertFalse(ProfessionKey::WRITER->isSpecial());
        $this->assertFalse(ProfessionKey::PRODUCER->isSpecial());
    }

    /**
     * Тест метода getShortName()
     */
    public function testGetShortName(): void
    {
        $this->assertEquals('За кадром', ProfessionKey::HRONO_TITR_MALE->getShortName());
        $this->assertEquals('За кадром', ProfessionKey::HRONO_TITR_FEMALE->getShortName());
        $this->assertEquals('В роли самого себя', ProfessionKey::HIMSELF->getShortName());
        $this->assertEquals('В роли самого себя', ProfessionKey::HERSELF->getShortName());
        $this->assertEquals('Актер', ProfessionKey::ACTOR->getShortName());
        $this->assertEquals('Режиссер', ProfessionKey::DIRECTOR->getShortName());
        $this->assertEquals('Сценарист', ProfessionKey::WRITER->getShortName());
    }

    /**
     * Тест метода getCategory()
     */
    public function testGetCategory(): void
    {
        $this->assertEquals('Творческая', ProfessionKey::ACTOR->getCategory());
        $this->assertEquals('Творческая', ProfessionKey::DIRECTOR->getCategory());
        $this->assertEquals('Творческая', ProfessionKey::WRITER->getCategory());
        $this->assertEquals('Творческая', ProfessionKey::COMPOSER->getCategory());
        
        $this->assertEquals('Управленческая', ProfessionKey::PRODUCER->getCategory());
        $this->assertEquals('Управленческая', ProfessionKey::PRODUCER_USSR->getCategory());
        
        $this->assertEquals('Техническая', ProfessionKey::OPERATOR->getCategory());
        $this->assertEquals('Техническая', ProfessionKey::EDITOR->getCategory());
        $this->assertEquals('Техническая', ProfessionKey::DESIGN->getCategory());
        $this->assertEquals('Техническая', ProfessionKey::VOICE_DIRECTOR->getCategory());
        
        $this->assertEquals('Специальная', ProfessionKey::HIMSELF->getCategory());
        $this->assertEquals('Специальная', ProfessionKey::HERSELF->getCategory());
        $this->assertEquals('Специальная', ProfessionKey::HRONO_TITR_MALE->getCategory());
        $this->assertEquals('Специальная', ProfessionKey::HRONO_TITR_FEMALE->getCategory());
        $this->assertEquals('Специальная', ProfessionKey::TRANSLATOR->getCategory());
        $this->assertEquals('Специальная', ProfessionKey::UNKNOWN->getCategory());
    }

    /**
     * Тест создания enum из строки
     */
    public function testFromString(): void
    {
        $this->assertEquals(ProfessionKey::ACTOR, ProfessionKey::from('ACTOR'));
        $this->assertEquals(ProfessionKey::DIRECTOR, ProfessionKey::from('DIRECTOR'));
        $this->assertEquals(ProfessionKey::WRITER, ProfessionKey::from('WRITER'));
        $this->assertEquals(ProfessionKey::PRODUCER, ProfessionKey::from('PRODUCER'));
        $this->assertEquals(ProfessionKey::PRODUCER_USSR, ProfessionKey::from('PRODUCER_USSR'));
        $this->assertEquals(ProfessionKey::COMPOSER, ProfessionKey::from('COMPOSER'));
        $this->assertEquals(ProfessionKey::OPERATOR, ProfessionKey::from('OPERATOR'));
        $this->assertEquals(ProfessionKey::EDITOR, ProfessionKey::from('EDITOR'));
        $this->assertEquals(ProfessionKey::DESIGN, ProfessionKey::from('DESIGN'));
        $this->assertEquals(ProfessionKey::TRANSLATOR, ProfessionKey::from('TRANSLATOR'));
        $this->assertEquals(ProfessionKey::VOICE_DIRECTOR, ProfessionKey::from('VOICE_DIRECTOR'));
        $this->assertEquals(ProfessionKey::HIMSELF, ProfessionKey::from('HIMSELF'));
        $this->assertEquals(ProfessionKey::HERSELF, ProfessionKey::from('HERSELF'));
        $this->assertEquals(ProfessionKey::HRONO_TITR_MALE, ProfessionKey::from('HRONO_TITR_MALE'));
        $this->assertEquals(ProfessionKey::HRONO_TITR_FEMALE, ProfessionKey::from('HRONO_TITR_FEMALE'));
        $this->assertEquals(ProfessionKey::UNKNOWN, ProfessionKey::from('UNKNOWN'));
    }

    /**
     * Тест исключения при неверном значении
     */
    public function testInvalidValue(): void
    {
        $this->expectException(\ValueError::class);
        ProfessionKey::from('INVALID_VALUE');
    }
} 