# Psonic - PHP client for sonic auto suggestion engine 
[![Build Status](https://travis-ci.com/ppshobi/psonic.svg?branch=master)](https://travis-ci.com/ppshobi/psonic)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/ppshobi/psonic/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/ppshobi/psonic/?branch=master)
[![codecov](https://codecov.io/gh/ppshobi/psonic/branch/master/graph/badge.svg)](https://codecov.io/gh/ppshobi/psonic)
## Summary

[Sonic](https://github.com/valeriansaliou/sonic) is a super fast auto-suggestion engine built by the team at [crisp.chat](crisp.chat), a customer engagement platform. it is built in Rust and they officially support a javascript client, but if you want to use sonic via PHP, this is the library that you can look for.
Completely unit tested, and adheres to modern coding standards, and offers a clean API to interact with sonic.

## Installation & Usage

you need a running sonic instance (locally or in the cloud, the port 1491 should be accessible) php7+ and composer to make this library work. [Read more on installing sonic](https://github.com/valeriansaliou/sonic/blob/master/README.md)

- goto your project directory
- execute `composer require ppshobi/psonic`
  once the installation is completed you should be able to use the library as follows

## Api Documentaion

[Full API Documentaion](api-docs.md)

## Usage

once you have psonic in place, you have access to the `Client` and `Channel` classes, Each channel instance requires a separate new client instance since sonic doesn't allow channel switching within the same connection. Read more about sonic concepts below

### Indexing

To index few objects into the sonic use the following sample code, make sure you have a running instance of sonic on port 1491

```php
$ingest  = new Psonic\Ingest(new Psonic\Client('localhost', 1491, 30));
$control = new Psonic\Control(new Psonic\Client('localhost', 1491, 30));
$ingest->connect('SecretPassword1');
$control->connect('SecretPassword1');
echo $ingest->push('messagesCollection', 'defaultBucket', "1234","hi Shobi how are you?")->getStatus(); // OK
echo $ingest->push('messagesCollection', 'defaultBucket', "1235","hi are you fine ?")->getStatus(); //OK
echo $ingest->push('messagesCollection', 'defaultBucket', "1236","Jomit? How are you?")->getStatus(); //OK

echo $control->consolidate(); // saves the data to disk

$ingest->disconnect();
$control->disconnect();

```

### Searching/AutoSuggestions

To search on the index using the following sample code

```php
$search = new Psonic\Search(new Psonic\Client('localhost', 1491, 30));
$search->connect('SecretPassword1');
var_dump($search->query('messagesCollection', 'defaultBucket', "are")); // you should be getting an array of object keys which matched with the term "are"
$search->disconnect();
```

To get autosuggestions/autocomplete for a term from the index use the following sample code

```php
$search = new Psonic\Search(new Psonic\Client('localhost', 1491, 30));
$search->connect('SecretPassword1');
var_dump($search->suggest('messagesCollection', 'defaultBucket', "sho")); // you should be getting an array of terms which matched the term "sho". Considering previous example and it should output "shobi"
$search->disconnect();
```

## Basic sonic Concepts

Sonic is more of an `identifier index` than a `document index`. Meaning, if the query matches some records it will be giving you the identifier of the matched object, than the object itself. Probably you will have to query the actual data store again with those keys. Check Basic Terminology used in sonic below as well. [Read more on sonic repository](https://github.com/valeriansaliou/sonic/blob/master/README.md)

### Channels

Sonic doesn't offer an HTTP endpoint as of now, rather it offers a TCP endpoint like Redis (They call it RESP protocol), and we call it channel.
There are 3 kind of channels

- **Ingest** (Typically offers data indexing (index), deindexing (pop) and flushing operations)
- **Search** (Offers Query and Suggest operations)
- **Control** (Offers the collection control operations such as data consolidation)

### Basic Terminology

Consider you are storing the chats of your customers from an e-commerce website.

- `collection` - which contains all your messages/products etc...
- `bucket` - you might need to store messages specific to a user so that a collection can contain one or more user buckets, so the search can be more specific,
  or according to your use case, you can put all your messages in one bucket itself and name it `default` or `generic` etc..
- `object` - The object is the key of the actual data that you have in the database, usually, the object key will be an identifier/primary_key from the source of truth, as the primary key from the messages table, this will be returned when you query matches some records from the sonic index.
- `terms` - This is the actual text data you save in the sonic.
  Read more on the sonic documentation

## Testing & Contribution

To run sonic in local, the best way is to use `docker`

Run below command in terminal. you should have a sonic instance running.

`$ docker run -d -p 1491:1491 -v /path/to/sonic.cfg:/etc/sonic.cfg -v /path/to/sonic/data/store:/var/lib/sonic/store/ valeriansaliou/sonic:v1.1.9`

Then do a git clone of this project

`$ cd ~ && git clone https://github.com/ppshobi/psonic.git`

then `cd` into the project directory and do a composer install

`$ cd psonic && composer install`

Use `phpunit` to run tests.

## Feel free to send pull requests.
