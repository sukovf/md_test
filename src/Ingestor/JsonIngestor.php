<?php

namespace App\Ingestor;

use App\DTO\ShoptetItem;
use App\DTO\ShoptetShop;
use App\Ingestor\Exception\InvalidJsonException;
use App\Utils\Extractor\ArrayExtractor;
use App\Utils\Extractor\Exception\InvalidTypeException;
use App\Utils\Extractor\Exception\PathNotFoundException;
use App\Utils\Extractor\Type\ArrayType;
use App\Utils\Extractor\Type\NumberType;
use App\Utils\Extractor\Type\StringType;
use Exception;

class JsonIngestor implements IngestorInterface
{
	const string CATEGORY_SEPARATOR = ' > ';

	/**
	 * @inheritDoc
	 */
	public function getFileExtensions(): array
	{
		return ['json'];
	}

	/**
	 * @throws InvalidJsonException
	 */
	public function ingest(string $data, ShoptetShop $shop): void
	{
		$input = json_decode($data, true);
		if (!is_array($input)) {
			throw new InvalidJsonException('Failed to decode the data.');
		}

		try {
			$mainCategoriesRaw = ArrayExtractor::extract(StringType::class, $input, 'vehicle.name')();
			$mainCategories = explode('/', $mainCategoriesRaw);

			$currCategoryName = '';
			foreach ($mainCategories as $key => $mainCategory) {
				$currCategoryName .= trim($mainCategory);

				if (array_key_last($mainCategories) !== $key) {
					$currCategoryName .= self::CATEGORY_SEPARATOR;
				}
			}

			$categories = ArrayExtractor::extract(ArrayType::class, $input, 'categories')();

			/** @var array<string, mixed> $category */
			foreach ($categories as $category) {
				$this->ingestCategory($category, $currCategoryName, $shop);
			}
		} catch (Exception $e) {
			throw new InvalidJsonException($e->getMessage(), $e->getCode(), $e->getPrevious());
		}
	}

	/**
	 * @param array<string, mixed> $input
	 *
	 * @throws InvalidTypeException
	 * @throws PathNotFoundException
	 */
	private function ingestCategory(array $input, string $currCategoryName, ShoptetShop $shop): void
	{
		$currCategoryName = $currCategoryName . self::CATEGORY_SEPARATOR . ArrayExtractor::extract(StringType::class, $input, 'name')();

		$spareParts = ArrayExtractor::extract(ArrayType::class, $input, 'spare_parts')();

		/** @var array<string, mixed> $sparePart */
		foreach ($spareParts as $sparePart) {
			$this->ingestItem($sparePart, $currCategoryName, $shop);
		}

		$categories = ArrayExtractor::extract(ArrayType::class, $input, 'categories')();

		/** @var array<string, mixed> $category */
		foreach ($categories as $category) {
			$this->ingestCategory($category, $currCategoryName, $shop);
		}
	}

	/**
	 * @param array<string, mixed> $input
	 *
	 * @throws InvalidTypeException
	 * @throws PathNotFoundException
	 */
	private function ingestItem(array $input, string $currCategoryName, ShoptetShop $shop): void
	{
		$itemName = ArrayExtractor::extract(StringType::class, $input, 'product.name', true)();
		if ($itemName === null) {
			// A couple dozen items have their name set to NULL; skip these
			return;
		}

		$itemNo = ArrayExtractor::extract(StringType::class, $input, 'product.product_no')();
		$itemPrice = ArrayExtractor::extract(NumberType::class, $input, 'product.unit_price_incl_vat', true)();

		$shop->addItem(
			(new ShoptetItem())
				->setNo($itemNo)
				->addCategories($currCategoryName)
				->setName($itemName)
				->setPrice($itemPrice ?? 0.0)
		);
	}
}