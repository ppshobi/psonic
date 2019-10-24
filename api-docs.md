# Psonic API doc

## Search

You need to instantiate the Psonic/Search class to do the searching operations on sonic

```php
<?php
$password = 'SecretPassword';
$search = new Psonic/Search(new Psonic/Client($host, $port, $timeout));
echo $search->connect($password);
```

after connecting to Search channel you can call the following methods on it

| Methods                                                                                                               |                  Description                   |
| --------------------------------------------------------------------------------------------------------------------- | :--------------------------------------------: |
| `->query(string $collection, string $bucket, string $terms, [int $limit], [int $offset], [string <locale>]): array`   | Returns an array of matched object identifiers, locale is optional, which should be a valid [ISO 639-3](https://en.wikipedia.org/wiki/List_of_ISO_639-1_codes) locale (eng = English), if set to `none` lexing will be disabled  |
| `->suggest(string $collection, string $bucket, string $terms, [int $limit]): array`                                   | Returns an array of strings of autosuggestions, locale is optional, which should be a valid [ISO 639-3](https://en.wikipedia.org/wiki/List_of_ISO_639-1_codes) locale (eng = English), if set to `none` lexing will be disabled  |
| `->ping(): string`                                                                                                    |     Pings the server, should return _PONG_     |
| `->disconnect()`                                                                                                      |            Disconnects the channel             |

## Ingest

You need to instantiate the Psonic/Ingest class to do the indexing operations on sonic

```php
<?php
$password = 'SecretPassword';
$ingest = new Psonic/Ingest(new Psonic/Client($host, $port, $timeout));
echo $ingest->connect($password);
```

after connecting to Ingest channel you can call the following methods on it

| Methods                                                                      |                                          Description                                           |
| ---------------------------------------------------------------------------- | :--------------------------------------------------------------------------------------------: |
| `->push(string $collection,string $bucket, string $object_id, string "data", [string <locale>])` |                       Add an item to index and Returns a Sonic Response, locale is optional, which should be a valid [ISO 639-3](https://en.wikipedia.org/wiki/List_of_ISO_639-1_codes) locale (eng = English), if set to `none` lexing will be disabled                       |
| `->pop(string $collection,string $bucket, string $object_id, string "data"`  |                     pops an item out of index and returns a Sonic Response                     |
| `->count(string $collection,[string $bucket, [string $object_id]]): int`     |                   counts the number of items in collection, bucket or object                   |
| `->flushc(string $collection):int`                                           |  Flushes the buckets from a collection, returns a integer saying the number of items flushed   |
| `->flushb(string $collection, string $bucket):int`                           |    Flushes the objects from a bucket, returns a integer saying the number of items flushed     |
| `->flusho(string $collection, string $bucket, string $object_id):int`        | Flushes the indexed text from an objects, returns a integer saying the number of items flushed |
| `->ping(): string`                                                           |                             Pings the server, should return _PONG_                             |
| `->disconnect()`                                                             |                                    Disconnects the channel                                     |

## Control

You need to instantiate the Psonic/Control class to do the control operations on sonic

```php
<?php
$password = 'SecretPassword';
$control = new Psonic/Control(new Psonic/Client($host, $port, $timeout));
echo $control->connect($password);
```

after connecting to control channel you can call the following methods on it

| Methods                      |                                                                                                 Description                                                                                                 |
| ---------------------------- | :---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------: |
| `->trigger(string $command)` |                                                                                          Trigger a control command                                                                                          |
| `->consolidate()`            | **Saves the data to disk**, when a certain number of items are pushed to index, depending on the configuration it can happen automatically. But to be on the safe side you could call this command manually |
| `->ping(): string`           |                                                                                   Pings the server, should return _PONG_                                                                                    |
| `->info()`             |                                                                                           Get the information about the server                                                                                           |
| `->disconnect()`             |                                                                                           Disconnects the channel                                                                                           |
