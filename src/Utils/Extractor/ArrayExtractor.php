<?php

namespace App\Utils\Extractor;

use App\Utils\Extractor\Exception\InvalidTypeException;
use App\Utils\Extractor\Exception\PathNotFoundException;
use App\Utils\Extractor\Type\NullType;
use App\Utils\Extractor\Type\TypeInterface;

class ArrayExtractor
{
	/**
	 * @template T of TypeInterface
	 * @param class-string<T> $type
	 * @param array<int|string, mixed> $source
	 * @return ($allowNull is false ? T : T|NullType)
	 * @throws InvalidTypeException
	 * @throws PathNotFoundException
	 */
	public static function extract(string $type, array $source, string $path, bool $allowNull = false)
	{
		$data = $source;

		if (!empty($path)) {
			$segments = explode('.', $path);
			foreach ($segments as $segment) {
				if (!is_array($data) || !array_key_exists($segment, $data)) {
					throw new PathNotFoundException(sprintf('Segment "%s" not found in path "%s".', $segment, $path));
				}

				$data = $data[$segment];
			}
		}

		if ($allowNull && is_null($data)) {
			return new NullType();
		}

		if (!$type::isValid($data)) {
			throw new InvalidTypeException(sprintf('Invalid type "%s" encountered. Expected "%s" in path "%s".', gettype($data), $type::getTypeName(), $path));
		}

		return new $type($data);
	}
}