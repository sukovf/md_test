<?php

namespace App\Utils\Extractor\Type;

readonly class ArrayType implements TypeInterface
{
	/**
	 * @param array<int|string, mixed> $data
	 */
	public function __construct(private array $data) {}

	/**
	 * @return array<int|string, mixed>
	 */
	public function __invoke(): array
	{
		return $this->data;
	}

	public static function getTypeName(): string
	{
		return 'array';
	}

	public static function isValid(mixed $value): bool
	{
		return is_array($value);
	}
}