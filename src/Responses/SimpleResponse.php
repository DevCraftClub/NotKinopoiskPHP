<?php

namespace NotKinopoisk\Responses;

use NotKinopoisk\Exception\KpValidationException;
use NotKinopoisk\Interfaces\ResponseInterface;

class SimpleResponse implements ResponseInterface {

	public function __construct(public array $items) {}
	/**
	 * @inheritDoc
	 */
	public static function fromArray(array $data, string $cls): object {
		self::checkClass($cls);

		return new self(array_map(
			static fn (array $itemData): object => $cls::fromArray($itemData),
			$data
		));
	}

	/**
	 * @inheritDoc
	 */
	public static function checkClass(string $cls): void {
		if (!class_exists($cls)) {
			throw new KpValidationException("Указанный класс не существует: {$cls}");
		}

		if (!method_exists($cls, 'fromArray')) {
			throw new KpValidationException("Класс {$cls} не имеет статического метода fromArray");
		}

		$reflection = new \ReflectionMethod($cls, 'fromArray');
		if (!$reflection->isStatic()) {
			throw new KpValidationException("Метод fromArray в классе {$cls} должен быть статическим");
		}
	}

	/**
	 * @inheritDoc
	 */
	public function toArray(): array {
		return array_map(fn (object $item): array => $item->toArray(), $this->items);
	}

}