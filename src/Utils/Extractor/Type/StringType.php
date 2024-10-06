<?php

namespace App\Utils\Extractor\Type;

readonly class StringType implements TypeInterface
{
	public function __construct(private string $data) {}

	public function __invoke(): string
	{
		return $this->data;
	}

	public static function getTypeName(): string
	{
		return 'string';
	}

	public static function isValid(mixed $value): bool
	{
		return is_string($value);
	}
}