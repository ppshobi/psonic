<?php
namespace Tests;

class TestCase extends \PHPUnit\Framework\TestCase
{
    protected $collection;
    protected $bucket;

    public function __construct()
    {
        parent::__construct();
        $this->collection = 'collection';
        $this->bucket     = 'conversations';
    }
}