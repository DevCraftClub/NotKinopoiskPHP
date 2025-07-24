<?php

declare(strict_types=1);

namespace NotKinopoisk\Tests\Models;

use NotKinopoisk\Enums\Sex;
use NotKinopoisk\Models\PersonByNameResult;
use PHPUnit\Framework\TestCase;

/**
 * Тесты для модели PersonByNameResult
 *
 * @package NotKinopoisk\Tests\Models
 * @since   1.0.0
 *
 * @author  Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 */
class PersonByNameResultTest extends TestCase {

	/**
	 * Тестовые данные для создания модели
	 */
	private array $testData = [
		'kinopoiskId' => 66539,
		'webUrl'      => '10096',
		'nameRu'      => 'Винс Гиллиган',
		'nameEn'      => 'Vince Gilligan',
		'sex'         => 'MALE',
		'posterUrl'   => 'https://kinopoiskapiunofficial.tech/images/actor_posters/kp/10096.jpg',
	];

	/**
	 * Тест создания модели через конструктор
	 */
	public function testConstructor(): void {
		$person = new PersonByNameResult(
			kinopoiskId: $this->testData['kinopoiskId'],
			webUrl      : $this->testData['webUrl'],
			nameRu      : $this->testData['nameRu'],
			nameEn      : $this->testData['nameEn'],
			sex         : Sex::MALE,
			posterUrl   : $this->testData['posterUrl'],
		);

		$this->assertEquals($this->testData['kinopoiskId'], $person->kinopoiskId);
		$this->assertEquals($this->testData['webUrl'], $person->webUrl);
		$this->assertEquals($this->testData['nameRu'], $person->nameRu);
		$this->assertEquals($this->testData['nameEn'], $person->nameEn);
		$this->assertEquals(Sex::MALE, $person->sex);
		$this->assertEquals($this->testData['posterUrl'], $person->posterUrl);
	}

	/**
	 * Тест создания модели из массива данных API
	 */
	public function testFromArray(): void {
		$person = PersonByNameResult::fromArray($this->testData);

		$this->assertEquals($this->testData['kinopoiskId'], $person->kinopoiskId);
		$this->assertEquals($this->testData['webUrl'], $person->webUrl);
		$this->assertEquals($this->testData['nameRu'], $person->nameRu);
		$this->assertEquals($this->testData['nameEn'], $person->nameEn);
		$this->assertEquals(Sex::MALE, $person->sex);
		$this->assertEquals($this->testData['posterUrl'], $person->posterUrl);
	}

	/**
	 * Тест создания модели из массива с nullable полями
	 */
	public function testFromArrayWithNullValues(): void {
		$data = [
			'kinopoiskId' => 12345,
			'webUrl'      => 'test',
			'nameRu'      => NULL,
			'nameEn'      => NULL,
			'sex'         => NULL,
			'posterUrl'   => 'https://example.com/poster.jpg',
		];

		$person = PersonByNameResult::fromArray($data);

		$this->assertEquals(12345, $person->kinopoiskId);
		$this->assertEquals('test', $person->webUrl);
		$this->assertNull($person->nameRu);
		$this->assertNull($person->nameEn);
		$this->assertNull($person->sex);
		$this->assertEquals('https://example.com/poster.jpg', $person->posterUrl);
	}

	/**
	 * Тест создания модели из массива с отсутствующими полями
	 */
	public function testFromArrayWithMissingFields(): void {
		$data = [
			'kinopoiskId' => 12345,
			'webUrl'      => 'test',
			'posterUrl'   => 'https://example.com/poster.jpg',
		];

		$person = PersonByNameResult::fromArray($data);

		$this->assertEquals(12345, $person->kinopoiskId);
		$this->assertEquals('test', $person->webUrl);
		$this->assertNull($person->nameRu);
		$this->assertNull($person->nameEn);
		$this->assertNull($person->sex);
		$this->assertEquals('https://example.com/poster.jpg', $person->posterUrl);
	}

	/**
	 * Тест метода getDisplayName с русским именем
	 */
	public function testGetDisplayNameWithRussianName(): void {
		$person = PersonByNameResult::fromArray($this->testData);

		$this->assertEquals('Винс Гиллиган', $person->getDisplayName());
	}

	/**
	 * Тест метода getDisplayName с английским именем (когда русского нет)
	 */
	public function testGetDisplayNameWithEnglishNameOnly(): void {
		$data = $this->testData;
		$data['nameRu'] = NULL;

		$person = PersonByNameResult::fromArray($data);

		$this->assertEquals('Vince Gilligan', $person->getDisplayName());
	}

	/**
	 * Тест метода getDisplayName без имен
	 */
	public function testGetDisplayNameWithoutNames(): void {
		$data = $this->testData;
		$data['nameRu'] = NULL;
		$data['nameEn'] = NULL;

		$person = PersonByNameResult::fromArray($data);

		$this->assertEquals('Неизвестно', $person->getDisplayName());
	}

	/**
	 * Тест метода isMale
	 */
	public function testIsMale(): void {
		$person = PersonByNameResult::fromArray($this->testData);

		$this->assertTrue($person->isMale());
	}

	/**
	 * Тест метода isMale с женским полом
	 */
	public function testIsMaleWithFemale(): void {
		$data = $this->testData;
		$data['sex'] = 'FEMALE';

		$person = PersonByNameResult::fromArray($data);

		$this->assertFalse($person->isMale());
	}

	/**
	 * Тест метода isFemale
	 */
	public function testIsFemale(): void {
		$data = $this->testData;
		$data['sex'] = 'FEMALE';

		$person = PersonByNameResult::fromArray($data);

		$this->assertTrue($person->isFemale());
	}

	/**
	 * Тест метода isFemale с мужским полом
	 */
	public function testIsFemaleWithMale(): void {
		$person = PersonByNameResult::fromArray($this->testData);

		$this->assertFalse($person->isFemale());
	}

	/**
	 * Тест метода isSexUnknown с неизвестным полом
	 */
	public function testIsSexUnknownWithUnknown(): void {
		$data = $this->testData;
		$data['sex'] = 'UNKNOWN';

		$person = PersonByNameResult::fromArray($data);

		$this->assertTrue($person->isSexUnknown());
	}

	/**
	 * Тест метода isSexUnknown с null полом
	 */
	public function testIsSexUnknownWithNull(): void {
		$data = $this->testData;
		$data['sex'] = NULL;

		$person = PersonByNameResult::fromArray($data);

		$this->assertTrue($person->isSexUnknown());
	}

	/**
	 * Тест метода isSexUnknown с известным полом
	 */
	public function testIsSexUnknownWithKnownSex(): void {
		$person = PersonByNameResult::fromArray($this->testData);

		$this->assertFalse($person->isSexUnknown());
	}

	/**
	 * Тест метода getFullName с обоими именами
	 */
	public function testGetFullNameWithBothNames(): void {
		$person = PersonByNameResult::fromArray($this->testData);

		$this->assertEquals('Винс Гиллиган / Vince Gilligan', $person->getFullName());
	}

	/**
	 * Тест метода getFullName с кастомным разделителем
	 */
	public function testGetFullNameWithCustomSeparator(): void {
		$person = PersonByNameResult::fromArray($this->testData);

		$this->assertEquals('Винс Гиллиган | Vince Gilligan', $person->getFullName(' | '));
	}

	/**
	 * Тест метода getFullName с одним именем
	 */
	public function testGetFullNameWithOneName(): void {
		$data = $this->testData;
		$data['nameRu'] = NULL;

		$person = PersonByNameResult::fromArray($data);

		$this->assertEquals('Vince Gilligan', $person->getFullName());
	}

	/**
	 * Тест метода getFullName без имен
	 */
	public function testGetFullNameWithoutNames(): void {
		$data = $this->testData;
		$data['nameRu'] = NULL;
		$data['nameEn'] = NULL;

		$person = PersonByNameResult::fromArray($data);

		$this->assertEquals('Неизвестно', $person->getFullName());
	}

	/**
	 * Тест метода toArray
	 */
	public function testToArray(): void {
		$person = PersonByNameResult::fromArray($this->testData);
		$array = $person->toArray();

		$expected = [
			'kinopoiskId' => 66539,
			'webUrl'      => '10096',
			'nameRu'      => 'Винс Гиллиган',
			'nameEn'      => 'Vince Gilligan',
			'sex'         => 'MALE',
			'posterUrl'   => 'https://kinopoiskapiunofficial.tech/images/actor_posters/kp/10096.jpg',
		];

		$this->assertEquals($expected, $array);
	}

	/**
	 * Тест метода toArray с null значениями
	 */
	public function testToArrayWithNullValues(): void {
		$data = $this->testData;
		$data['nameRu'] = NULL;
		$data['nameEn'] = NULL;
		$data['sex'] = NULL;

		$person = PersonByNameResult::fromArray($data);
		$array = $person->toArray();

		$expected = [
			'kinopoiskId' => 66539,
			'webUrl'      => '10096',
			'nameRu'      => NULL,
			'nameEn'      => NULL,
			'sex'         => NULL,
			'posterUrl'   => 'https://kinopoiskapiunofficial.tech/images/actor_posters/kp/10096.jpg',
		];

		$this->assertEquals($expected, $array);
	}

	/**
	 * Тест всех enum значений для поля sex
	 */
	public function testAllSexEnumValues(): void {
		$sexValues = ['MALE', 'FEMALE', 'UNKNOWN'];

		foreach ($sexValues as $sexValue) {
			$data = $this->testData;
			$data['sex'] = $sexValue;

			$person = PersonByNameResult::fromArray($data);
			$this->assertEquals(Sex::from($sexValue), $person->sex);
		}
	}

	/**
	 * Тест readonly свойств
	 */
	public function testReadonlyProperties(): void {
		$person = PersonByNameResult::fromArray($this->testData);

		// Проверяем, что свойства действительно readonly
		$this->expectException(\Error::class);
		$person->kinopoiskId = 99999;
	}

} 