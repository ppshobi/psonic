# Psonic - PHP client for sonic auto suggestion engine [![Build Status](https://travis-ci.com/ppshobi/psonic.svg?branch=master)](https://travis-ci.com/ppshobi/psonic)

## Summary

Sonic is a super fast auto suggestion engine built by the team at crisp.ai, a customer engagement platform. its built in Rust and they officially support a javascript client, but if you want to use sonic via php, this is the library that you can look for.
Completely unit tested, and adheres to modern coding standards, and offers a clean api to interact with sonic.

## Installation & Usage

you need a running sonic instance (locally or in cloud, the port 1491 should be accessible) php7+ and composer to make this library work.

- goto your project directory
- execute `composer require ppshobi/psonic`
  once the installation is completed you should be able to use the library as follows

## Usage

once you psonic in place, you have access to the Client and Channel classes, Each channel instance requires a seprate client instance since sonic doesn't allow channel switching withing the same connection

To index few objects into the sonic use the following sample code, make sure you have a running instance of sonic on port 1491

```php
$ingest  = new Psonic\Ingest(new Psonic\Client('localhost', 1491, 'SecretPassword', 30));
$control = new Psonoc\Control(new Psonic\Client('localhost', 1491, 'SecretPassword', 30));
$ingest->connect();
$control->connect();
echo $ingest->push('messagesCollection', 'defaultBucket', "1234","hi Shobi how are you?")->getStatus(); // OK
echo $ingest->push('messagesCollection', 'defaultBucket', "1235","hi are you fine ?")->getStatus(); //OK
echo $ingest->push('messagesCollection', 'defaultBucket', "1236","Jomit? How are you?")->getStatus(); //OK

echo $control->consolidate(); // saves the data to disk

$ingest->disconnect();
$control->disconnect();

```

To search on the index use the following sample code

```php
$search = new Psonoc\Search(new Psonic\Client('localhost', 1491, 'SecretPassword', 30));
$search->connect();
var_dump($this->search->query($this->collection, $this->bucket, "are")); // you should be getting an array of object keys which matched the term "are"
$search->disconnect();
```

To get autosuggestions/autocomplete for a term from the index use the following sample code

```php
$search = new Psonoc\Search(new Psonic\Client('localhost', 1491, 'SecretPassword', 30));
$search->connect();
var_dump($this->search->suggest($this->collection, $this->bucket, "sho")); // you should be getting an array of terms which matched the term "sho" consider previous example and it will output "shobi"
$search->disconnect();
```

## Concepts

Sonic is an `identifier index` than a `document index`, meaning if the query matches some records it will be giving you the identifier of the matched object, than the object itself.

### Channels

Sonic doesn't offer an http endpoint as of now, rather it offers a tcp endpoint like redis (They call it RESP protocol), and we call it channel.
There is 3 kind of channels - Ingest (Typically offers data indexing (index), deindexing (pop), flushing operations) - Search (Offers Query and Suggest operations) - Control (Offers the instance control operations such as data consolidation)

### Basic Terminology

Consider you are storing the chats of your customers from an ecommerce website then

- collection - which contains all your messages/products etc...
- bucket - you might need to store messages specific to a user, so that a collection can contain one or more user buckets, so the search can be more specific,
  or according to your use case you can put all your messages in one bucket itself and name it `default` or `generic` etc..
- object - The object is the key of the actual data that you have in the database, usually the object key will be an identifier/primary_key from the source of truth, like the primary key from the messages table, this will be returned when you query the sonic index.
- terms - This is the actual text data you save in the sonic.
  Read more on the sonic documentation
