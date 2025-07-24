<?php

namespace NotKinopoisk\Models;

interface ModelInterface {
	public static function fromArray(array $data): static;
	public function toArray(): array;
}