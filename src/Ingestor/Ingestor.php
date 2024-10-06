<?php

namespace App\Ingestor;

use App\DTO\ShoptetShop;
use App\Ingestor\Exception\FailedToReadFileContentException;
use App\Ingestor\Exception\InvalidArgumentException;
use App\Ingestor\Exception\InvalidIngestorClassException;
use Exception;

class Ingestor
{
	/**
	 * @param class-string<IngestorInterface> $ingestorClass
	 */
	public function ingest(string $ingestorClass, string $directory, int $fileCountLimit = -1): ShoptetShop
	{
		if (!is_a($ingestorClass, IngestorInterface::class, true)) {
			throw new InvalidIngestorClassException(sprintf('The provided ingestor class does not implement the "%s" interface.', IngestorInterface::class));
		}

		$ingestor = new $ingestorClass;

		$directoryToSearch = str_ends_with($directory, '/') ? $directory : $directory . '/';

		$filesInDir = scandir($directoryToSearch);
		if ($filesInDir === false) {
			throw new InvalidArgumentException(sprintf('The folder "%s" is either not a folder or is empty.', $directory));
		}

		$filesToRead = preg_grep('~\.(' . implode('|', $ingestor->getFileExtensions()) . ')$~', $filesInDir);
		if ($filesToRead === false) {
			throw new InvalidArgumentException(sprintf('The folder "%s" contains no readable files.', $directory));
		}

		$shop = new ShoptetShop();

		$fileIndex = 0;
		foreach ($filesToRead as $file) {
			$fileIndex++;

			$fullFilePath = $directoryToSearch . $file;

			$fileContents = file_get_contents($fullFilePath, true);
			if ($fileContents === false) {
				throw new FailedToReadFileContentException(sprintf('Failed to read the content of the file "%s".', $fullFilePath));
			}

			try {
				$ingestor->ingest($fileContents, $shop);

				echo sprintf('File "%s" successfully ingested (%d / %d).', $fullFilePath, $fileIndex, count($filesToRead)) . "\n";
			} catch (Exception $e) {
				echo sprintf('Failed to ingest the file "%s" (%d / %d). Reason: %s', $fullFilePath, $fileIndex, count($filesToRead), $e->getMessage()) . "\n";
			}

			unset($fileContents);

			if ($fileIndex === $fileCountLimit) {
				break;
			}
		}

		return $shop;
	}
}