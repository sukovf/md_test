<?php

use App\Ingestor\Ingestor;
use App\Ingestor\JsonIngestor;
use JMS\Serializer\SerializerBuilder;

require_once __DIR__ . '/vendor/autoload.php';

$ingestor = new Ingestor();
$shop = $ingestor->ingest(JsonIngestor::class, 'spare_parts_feed');

$serializer = SerializerBuilder::create()->build();
$outputXml = $serializer->serialize($shop, 'xml');

file_put_contents(__DIR__ . '/output/output.xml', $outputXml);

echo "Done!\n";