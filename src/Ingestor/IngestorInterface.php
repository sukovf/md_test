<?php

namespace App\Ingestor;

use App\DTO\ShoptetShop;

interface IngestorInterface
{
	/**
	 * @return string[]
	 */
	public function getFileExtensions(): array;

	public function ingest(string $data, ShoptetShop $shop): void;
}