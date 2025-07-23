<?php

declare(strict_types=1);

namespace Tests\Enums;

use NotKinopoisk\Enums\ImageType;
use PHPUnit\Framework\TestCase;

/**
 * Тесты для enum ImageType
 * 
 * @package Tests\Enums
 * @author Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @since 1.0.0
 */
class ImageTypeTest extends TestCase
{
    /**
     * Тест создания enum из строки
     */
    public function testFromString(): void
    {
        $this->assertEquals(ImageType::STILL, ImageType::from('STILL'));
        $this->assertEquals(ImageType::POSTER, ImageType::from('POSTER'));
        $this->assertEquals(ImageType::BACKGROUND, ImageType::from('BACKGROUND'));
        $this->assertEquals(ImageType::PREVIEW, ImageType::from('PREVIEW'));
    }

    /**
     * Тест получения значения enum
     */
    public function testGetValue(): void
    {
        $this->assertEquals('STILL', ImageType::STILL->value);
        $this->assertEquals('POSTER', ImageType::POSTER->value);
        $this->assertEquals('BACKGROUND', ImageType::BACKGROUND->value);
        $this->assertEquals('PREVIEW', ImageType::PREVIEW->value);
    }

    /**
     * Тест метода getDisplayName()
     */
    public function testGetDisplayName(): void
    {
        $this->assertEquals('Кадр из фильма', ImageType::STILL->getDisplayName());
        $this->assertEquals('Постер', ImageType::POSTER->getDisplayName());
        $this->assertEquals('Фон', ImageType::BACKGROUND->getDisplayName());
        $this->assertEquals('Превью', ImageType::PREVIEW->getDisplayName());
    }

    /**
     * Тест исключения при неверном значении
     */
    public function testInvalidValueThrowsException(): void
    {
        $this->expectException(\ValueError::class);
        ImageType::from('INVALID_TYPE');
    }

    /**
     * Тест сравнения enum значений
     */
    public function testEnumComparison(): void
    {
        $still1 = ImageType::STILL;
        $still2 = ImageType::STILL;
        $poster = ImageType::POSTER;

        $this->assertTrue($still1 === $still2);
        $this->assertTrue($still1 === ImageType::STILL);
        $this->assertTrue($poster === ImageType::POSTER);
        $this->assertTrue($still1 !== $poster);
    }

    /**
     * Тест всех доступных значений enum
     */
    public function testAllEnumValues(): void
    {
        $expectedValues = [
            'STILL',
            'POSTER',
            'BACKGROUND',
            'PREVIEW'
        ];

        $actualValues = array_map(fn($case) => $case->value, ImageType::cases());
        $this->assertEquals($expectedValues, $actualValues);
    }
} 