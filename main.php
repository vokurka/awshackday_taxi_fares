<?php

require "vendor/autoload.php";
use Keboola\Json\Parser;

define('OUT_PATH', './out/');

$configFile = getenv('KBC_DATADIR') . DIRECTORY_SEPARATOR . 'config.json';
$config = json_decode(file_get_contents($configFile), true);

$response = file_get_contents($config['parameters']['url']);
$response = json_decode($response);

// Create parser and parse the json
$parser = Parser::create(new \Monolog\Logger('json-parser'));
$parser->process($json, $entityName);
$result = $parser->getCsvFiles();

foreach ($result as $f)
{
	mergeCsvFiles($f->getPathName(), OUT_PATH.$config['parameters']['output_bucket'].".". substr($f->getFileName(), strpos($f->getFileName(), '-')+1));
}