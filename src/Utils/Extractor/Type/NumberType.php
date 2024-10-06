<?php

namespace App\Utils\Extractor\Type;

readonly class NumberType implements TypeInterface
{
	public function __construct(private mixed $data) {}

	public function __invoke(): float
	{
		return is_scalar($this->data) ? floatval($this->data) : 0.0;
	}

	public static function getTypeName(): string
	{
		return 'number';
	}

	public static function isValid(mixed $value): bool
	{
		return is_numeric($value);
	}
}