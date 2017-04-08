<?php

require "vendor/autoload.php";
use Keboola\Json\Parser;

define('OUT_PATH', '/data/out/tables/');

$configFile = getenv('KBC_DATADIR') . DIRECTORY_SEPARATOR . 'config.json';
$config = json_decode(file_get_contents($configFile), true);

$response = file_get_contents($config['parameters']['url']);
$response = json_decode($response);

if ($response === NULL)
{
	echo "Bad JSON.\n";
	exit;
}

// Create parser and parse the json
$parser = Parser::create(new \Monolog\Logger('json-parser'));
$parser->process($response->directions->modes->taxi->routes, 'taxi');
$result = $parser->getCsvFiles();

foreach ($result as $f)
{
	copy($f->getPathName(), OUT_PATH.$config['parameters']['output_bucket'].".". substr($f->getFileName(), strpos($f->getFileName(), '-')+1));
}