<?php

namespace App\Utils\Extractor\Type;

interface TypeInterface
{
	public function __invoke(): mixed;
	public static function getTypeName(): string;
	public static function isValid(mixed $value): bool;
}