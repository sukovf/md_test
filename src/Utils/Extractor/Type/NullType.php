<?php

namespace App\Utils\Extractor\Type;

readonly class NullType implements TypeInterface
{
	public function __construct() {}

	public function __invoke(): null
	{
		return null;
	}

	public static function getTypeName(): string
	{
		return 'null';
	}

	public static function isValid(mixed $value): bool
	{
		return is_null($value);
	}
}