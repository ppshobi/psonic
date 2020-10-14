<?php

namespace Psonic\Commands\Ingest;

use Psonic\Commands\Command;

final class PushCommand extends Command
{
    private $command    = 'PUSH';
    private $parameters = [];

    /**
     * Push a text/object into an object/bucket respectively
     * PushCommand constructor.
     * @param string $collection
     * @param string $bucket
     * @param string $object
     * @param string $text
     * @param string $locale - a Valid ISO 639-3 locale (eng = English), if set to `none` lexing will be disabled
     */
    public function __construct(string $collection, string $bucket, string $object, string $text, string $locale=null)
    {
        $this->parameters = [
            'collection' => $collection,
            'bucket'     => $bucket,
            'object'     => $object,
            'text'       => self::wrapInQuotes($text),
            'locale'     => $locale ? "LANG($locale)": null,
        ];

        parent::__construct($this->command, $this->parameters);
    }
}