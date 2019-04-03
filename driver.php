<?php
require_once ('vendor/autoload.php');
//////
$search = new \Psonic\Concretes\Channels\Search(new \Psonic\Concretes\Client());
$ingest = new \Psonic\Concretes\Channels\Ingest(new \Psonic\Concretes\Client());

$search->connect();

$ingest->connect();
//
echo $search->ping();
echo $search->ping();
echo $search->ping();
//echo $search->ping();0000
//echo $search->ping();

echo $ingest->push("default", "bucket", "messages", "Hello");

//echo "\n" . $ingest->ping();

$search->disconnect();
//$ingest->disconnect();

