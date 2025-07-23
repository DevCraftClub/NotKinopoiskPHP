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
        $this->assertEquals(ImageType::SHOOTING, ImageType::from('SHOOTING'));
        $this->assertEquals(ImageType::POSTER, ImageType::from('POSTER'));
        $this->assertEquals(ImageType::FAN_ART, ImageType::from('FAN_ART'));
        $this->assertEquals(ImageType::PROMO, ImageType::from('PROMO'));
        $this->assertEquals(ImageType::CONCEPT, ImageType::from('CONCEPT'));
        $this->assertEquals(ImageType::WALLPAPER, ImageType::from('WALLPAPER'));
        $this->assertEquals(ImageType::COVER, ImageType::from('COVER'));
        $this->assertEquals(ImageType::SCREENSHOT, ImageType::from('SCREENSHOT'));
        $this->assertEquals(ImageType::BACKGROUND, ImageType::from('BACKGROUND'));
        $this->assertEquals(ImageType::PREVIEW, ImageType::from('PREVIEW'));
    }

    /**
     * Тест получения значения enum
     */
    public function testGetValue(): void
    {
        $this->assertEquals('STILL', ImageType::STILL->value);
        $this->assertEquals('SHOOTING', ImageType::SHOOTING->value);
        $this->assertEquals('POSTER', ImageType::POSTER->value);
        $this->assertEquals('FAN_ART', ImageType::FAN_ART->value);
        $this->assertEquals('PROMO', ImageType::PROMO->value);
        $this->assertEquals('CONCEPT', ImageType::CONCEPT->value);
        $this->assertEquals('WALLPAPER', ImageType::WALLPAPER->value);
        $this->assertEquals('COVER', ImageType::COVER->value);
        $this->assertEquals('SCREENSHOT', ImageType::SCREENSHOT->value);
        $this->assertEquals('BACKGROUND', ImageType::BACKGROUND->value);
        $this->assertEquals('PREVIEW', ImageType::PREVIEW->value);
    }

    /**
     * Тест метода getDisplayName()
     */
    public function testGetDisplayName(): void
    {
        $this->assertEquals('Кадр из фильма', ImageType::STILL->getDisplayName());
        $this->assertEquals('Изображения со съемок', ImageType::SHOOTING->getDisplayName());
        $this->assertEquals('Постер', ImageType::POSTER->getDisplayName());
        $this->assertEquals('Фан-арты', ImageType::FAN_ART->getDisplayName());
        $this->assertEquals('Промо', ImageType::PROMO->getDisplayName());
        $this->assertEquals('Концепт-арты', ImageType::CONCEPT->getDisplayName());
        $this->assertEquals('Обои', ImageType::WALLPAPER->getDisplayName());
        $this->assertEquals('Обложки', ImageType::COVER->getDisplayName());
        $this->assertEquals('Скриншоты', ImageType::SCREENSHOT->getDisplayName());
        $this->assertEquals('Фон', ImageType::BACKGROUND->getDisplayName());
        $this->assertEquals('Превью', ImageType::PREVIEW->getDisplayName());
    }

    /**
     * Тест метода isMain()
     */
    public function testIsMain(): void
    {
        $this->assertTrue(ImageType::POSTER->isMain());
        $this->assertTrue(ImageType::STILL->isMain());
        $this->assertTrue(ImageType::FAN_ART->isMain());
        $this->assertTrue(ImageType::CONCEPT->isMain());
        
        $this->assertFalse(ImageType::SHOOTING->isMain());
        $this->assertFalse(ImageType::PROMO->isMain());
        $this->assertFalse(ImageType::WALLPAPER->isMain());
        $this->assertFalse(ImageType::COVER->isMain());
        $this->assertFalse(ImageType::SCREENSHOT->isMain());
        $this->assertFalse(ImageType::BACKGROUND->isMain());
        $this->assertFalse(ImageType::PREVIEW->isMain());
    }

    /**
     * Тест метода isPromo()
     */
    public function testIsPromo(): void
    {
        $this->assertTrue(ImageType::PROMO->isPromo());
        $this->assertTrue(ImageType::POSTER->isPromo());
        $this->assertTrue(ImageType::COVER->isPromo());
        $this->assertTrue(ImageType::WALLPAPER->isPromo());
        
        $this->assertFalse(ImageType::STILL->isPromo());
        $this->assertFalse(ImageType::SHOOTING->isPromo());
        $this->assertFalse(ImageType::FAN_ART->isPromo());
        $this->assertFalse(ImageType::CONCEPT->isPromo());
        $this->assertFalse(ImageType::SCREENSHOT->isPromo());
        $this->assertFalse(ImageType::BACKGROUND->isPromo());
        $this->assertFalse(ImageType::PREVIEW->isPromo());
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
            'SHOOTING',
            'POSTER',
            'FAN_ART',
            'PROMO',
            'CONCEPT',
            'WALLPAPER',
            'COVER',
            'SCREENSHOT',
            'BACKGROUND',
            'PREVIEW'
        ];

        $actualValues = array_map(fn($case) => $case->value, ImageType::cases());
        $this->assertEquals($expectedValues, $actualValues);
    }

    /**
     * Тест основных типов изображений
     */
    public function testMainImageTypes(): void
    {
        $mainTypes = [
            ImageType::POSTER,
            ImageType::STILL,
            ImageType::FAN_ART,
            ImageType::CONCEPT
        ];

        foreach ($mainTypes as $type) {
            $this->assertTrue($type->isMain());
        }
    }

    /**
     * Тест промо-типов изображений
     */
    public function testPromoImageTypes(): void
    {
        $promoTypes = [
            ImageType::PROMO,
            ImageType::POSTER,
            ImageType::COVER,
            ImageType::WALLPAPER
        ];

        foreach ($promoTypes as $type) {
            $this->assertTrue($type->isPromo());
        }
    }

    /**
     * Тест неосновных типов изображений
     */
    public function testNonMainImageTypes(): void
    {
        $nonMainTypes = [
            ImageType::SHOOTING,
            ImageType::PROMO,
            ImageType::WALLPAPER,
            ImageType::COVER,
            ImageType::SCREENSHOT,
            ImageType::BACKGROUND,
            ImageType::PREVIEW
        ];

        foreach ($nonMainTypes as $type) {
            if (!in_array($type, [ImageType::POSTER])) { // POSTER является и основным, и промо
                $this->assertFalse($type->isMain());
            }
        }
    }

    /**
     * Тест непромо-типов изображений
     */
    public function testNonPromoImageTypes(): void
    {
        $nonPromoTypes = [
            ImageType::STILL,
            ImageType::SHOOTING,
            ImageType::FAN_ART,
            ImageType::CONCEPT,
            ImageType::SCREENSHOT,
            ImageType::BACKGROUND,
            ImageType::PREVIEW
        ];

        foreach ($nonPromoTypes as $type) {
            $this->assertFalse($type->isPromo());
        }
    }
} 