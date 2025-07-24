<?php

declare(strict_types=1);

namespace NotKinopoisk\Tests\Models;

use NotKinopoisk\Enums\Sex;
use NotKinopoisk\Models\PersonByNameResult;
use NotKinopoisk\Models\PersonByNameResponse;
use PHPUnit\Framework\TestCase;

/**
 * Тесты для модели PersonByNameResponse
 *
 * @package NotKinopoisk\Tests\Models
 * @since   1.0.0
 *
 * @author  Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 */
class PersonByNameResponseTest extends TestCase {

	/**
	 * Тестовые данные для создания модели
	 */
	private array $testData = [
		'items' => [
			[
				'kinopoiskId' => 66539,
				'webUrl'      => '10096',
				'nameRu'      => 'Винс Гиллиган',
				'nameEn'      => 'Vince Gilligan',
				'sex'         => 'MALE',
				'posterUrl'   => 'https://kinopoiskapiunofficial.tech/images/actor_posters/kp/10096.jpg',
			],
			[
				'kinopoiskId' => 12345,
				'webUrl'      => '67890',
				'nameRu'      => 'Джейн Смит',
				'nameEn'      => 'Jane Smith',
				'sex'         => 'FEMALE',
				'posterUrl'   => 'https://kinopoiskapiunofficial.tech/images/actor_posters/kp/12345.jpg',
			],
			[
				'kinopoiskId' => 54321,
				'webUrl'      => '11111',
				'nameRu'      => 'Неизвестный Актер',
				'nameEn'      => 'Unknown Actor',
				'sex'         => 'UNKNOWN',
				'posterUrl'   => 'https://kinopoiskapiunofficial.tech/images/actor_posters/kp/54321.jpg',
			],
		],
		'total' => 35,
	];

	/**
	 * Тест создания модели через конструктор
	 */
	public function testConstructor(): void {
		$items = [
			new PersonByNameResult(66539, '10096', 'Винс Гиллиган', 'Vince Gilligan', Sex::MALE, 'https://example.com/poster1.jpg'),
			new PersonByNameResult(12345, '67890', 'Джейн Смит', 'Jane Smith', Sex::FEMALE, 'https://example.com/poster2.jpg'),
		];

		$response = new PersonByNameResponse($items, 35);

		$this->assertCount(2, $response->items);
		$this->assertEquals(35, $response->total);
		$this->assertInstanceOf(PersonByNameResult::class, $response->items[0]);
		$this->assertInstanceOf(PersonByNameResult::class, $response->items[1]);
	}

	/**
	 * Тест создания модели из массива данных API
	 */
	public function testFromArray(): void {
		$response = PersonByNameResponse::fromArray($this->testData);

		$this->assertCount(3, $response->items);
		$this->assertEquals(35, $response->total);
		$this->assertInstanceOf(PersonByNameResult::class, $response->items[0]);
		$this->assertInstanceOf(PersonByNameResult::class, $response->items[1]);
		$this->assertInstanceOf(PersonByNameResult::class, $response->items[2]);
	}

	/**
	 * Тест создания модели из массива с пустыми items
	 */
	public function testFromArrayWithEmptyItems(): void {
		$data = [
			'items' => [],
			'total' => 0,
		];

		$response = PersonByNameResponse::fromArray($data);

		$this->assertCount(0, $response->items);
		$this->assertEquals(0, $response->total);
	}

	/**
	 * Тест создания модели из массива с отсутствующими items
	 */
	public function testFromArrayWithMissingItems(): void {
		$data = [
			'total' => 10,
		];

		$response = PersonByNameResponse::fromArray($data);

		$this->assertCount(0, $response->items);
		$this->assertEquals(10, $response->total);
	}

	/**
	 * Тест метода getCount
	 */
	public function testGetCount(): void {
		$response = PersonByNameResponse::fromArray($this->testData);

		$this->assertEquals(3, $response->getCount());
	}

	/**
	 * Тест метода getCount с пустым массивом
	 */
	public function testGetCountWithEmptyArray(): void {
		$data = [
			'items' => [],
			'total' => 0,
		];

		$response = PersonByNameResponse::fromArray($data);

		$this->assertEquals(0, $response->getCount());
	}

	/**
	 * Тест метода hasResults с результатами
	 */
	public function testHasResults(): void {
		$response = PersonByNameResponse::fromArray($this->testData);

		$this->assertTrue($response->hasResults());
	}

	/**
	 * Тест метода hasResults без результатов
	 */
	public function testHasResultsWithoutResults(): void {
		$data = [
			'items' => [],
			'total' => 0,
		];

		$response = PersonByNameResponse::fromArray($data);

		$this->assertFalse($response->hasResults());
	}

	/**
	 * Тест метода isEmpty с результатами
	 */
	public function testIsEmpty(): void {
		$response = PersonByNameResponse::fromArray($this->testData);

		$this->assertFalse($response->isEmpty());
	}

	/**
	 * Тест метода isEmpty без результатов
	 */
	public function testIsEmptyWithoutResults(): void {
		$data = [
			'items' => [],
			'total' => 0,
		];

		$response = PersonByNameResponse::fromArray($data);

		$this->assertTrue($response->isEmpty());
	}

	/**
	 * Тест метода getFirst
	 */
	public function testGetFirst(): void {
		$response = PersonByNameResponse::fromArray($this->testData);

		$first = $response->getFirst();

		$this->assertInstanceOf(PersonByNameResult::class, $first);
		$this->assertEquals(66539, $first->kinopoiskId);
		$this->assertEquals('Винс Гиллиган', $first->nameRu);
	}

	/**
	 * Тест метода getFirst с пустым массивом
	 */
	public function testGetFirstWithEmptyArray(): void {
		$data = [
			'items' => [],
			'total' => 0,
		];

		$response = PersonByNameResponse::fromArray($data);

		$this->assertNull($response->getFirst());
	}

	/**
	 * Тест метода getLast
	 */
	public function testGetLast(): void {
		$response = PersonByNameResponse::fromArray($this->testData);

		$last = $response->getLast();

		$this->assertInstanceOf(PersonByNameResult::class, $last);
		$this->assertEquals(54321, $last->kinopoiskId);
		$this->assertEquals('Неизвестный Актер', $last->nameRu);
	}

	/**
	 * Тест метода getLast с пустым массивом
	 */
	public function testGetLastWithEmptyArray(): void {
		$data = [
			'items' => [],
			'total' => 0,
		];

		$response = PersonByNameResponse::fromArray($data);

		$this->assertNull($response->getLast());
	}

	/**
	 * Тест метода filterBySex
	 */
	public function testFilterBySex(): void {
		$response = PersonByNameResponse::fromArray($this->testData);

		$malePersons = $response->filterBySex(Sex::MALE);

		$this->assertCount(1, $malePersons);
		$this->assertEquals(66539, $malePersons[0]->kinopoiskId);
		$this->assertTrue($malePersons[0]->isMale());
	}

	/**
	 * Тест метода filterBySex с женским полом
	 */
	public function testFilterBySexFemale(): void {
		$response = PersonByNameResponse::fromArray($this->testData);

		$femalePersons = $response->filterBySex(Sex::FEMALE);

		$this->assertCount(1, $femalePersons);
		$this->assertEquals(12345, $femalePersons[0]->kinopoiskId);
		$this->assertTrue($femalePersons[0]->isFemale());
	}

	/**
	 * Тест метода filterBySex с неизвестным полом
	 */
	public function testFilterBySexUnknown(): void {
		$response = PersonByNameResponse::fromArray($this->testData);

		$unknownPersons = $response->filterBySex(Sex::UNKNOWN);

		$this->assertCount(1, $unknownPersons);
		$this->assertEquals(54321, $unknownPersons[0]->kinopoiskId);
		$this->assertTrue($unknownPersons[0]->isSexUnknown());
	}

	/**
	 * Тест метода filterBySex без результатов
	 */
	public function testFilterBySexNoResults(): void {
		$data = [
			'items' => [
				[
					'kinopoiskId' => 66539,
					'webUrl'      => '10096',
					'nameRu'      => 'Винс Гиллиган',
					'nameEn'      => 'Vince Gilligan',
					'sex'         => 'MALE',
					'posterUrl'   => 'https://kinopoiskapiunofficial.tech/images/actor_posters/kp/10096.jpg',
				],
			],
			'total' => 1,
		];

		$response = PersonByNameResponse::fromArray($data);

		$femalePersons = $response->filterBySex(Sex::FEMALE);

		$this->assertCount(0, $femalePersons);
	}

	/**
	 * Тест метода findById
	 */
	public function testFindById(): void {
		$response = PersonByNameResponse::fromArray($this->testData);

		$person = $response->findById(66539);

		$this->assertInstanceOf(PersonByNameResult::class, $person);
		$this->assertEquals(66539, $person->kinopoiskId);
		$this->assertEquals('Винс Гиллиган', $person->nameRu);
	}

	/**
	 * Тест метода findById с несуществующим ID
	 */
	public function testFindByIdNotFound(): void {
		$response = PersonByNameResponse::fromArray($this->testData);

		$person = $response->findById(99999);

		$this->assertNull($person);
	}

	/**
	 * Тест метода toArray
	 */
	public function testToArray(): void {
		$response = PersonByNameResponse::fromArray($this->testData);
		$array = $response->toArray();

		$this->assertArrayHasKey('items', $array);
		$this->assertArrayHasKey('total', $array);
		$this->assertEquals(35, $array['total']);
		$this->assertCount(3, $array['items']);

		// Проверяем первый элемент
		$this->assertEquals(66539, $array['items'][0]['kinopoiskId']);
		$this->assertEquals('Винс Гиллиган', $array['items'][0]['nameRu']);
		$this->assertEquals('MALE', $array['items'][0]['sex']);
	}

	/**
	 * Тест метода toArray с пустым массивом
	 */
	public function testToArrayWithEmptyArray(): void {
		$data = [
			'items' => [],
			'total' => 0,
		];

		$response = PersonByNameResponse::fromArray($data);
		$array = $response->toArray();

		$this->assertArrayHasKey('items', $array);
		$this->assertArrayHasKey('total', $array);
		$this->assertEquals(0, $array['total']);
		$this->assertCount(0, $array['items']);
	}

	/**
	 * Тест readonly свойств
	 */
	public function testReadonlyProperties(): void {
		$response = PersonByNameResponse::fromArray($this->testData);

		// Проверяем, что свойства действительно readonly
		$this->expectException(\Error::class);
		$response->total = 999;
	}

	/**
	 * Тест итерации по элементам
	 */
	public function testIteration(): void {
		$response = PersonByNameResponse::fromArray($this->testData);

		$count = 0;
		foreach ($response->items as $person) {
			$this->assertInstanceOf(PersonByNameResult::class, $person);
			$count++;
		}

		$this->assertEquals(3, $count);
	}

} 