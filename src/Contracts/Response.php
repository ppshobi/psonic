<?php 
namespace Psonic\Contracts;

interface Response
{
    /**
     * fetches an item from the parsed buffer
     * @param $key
     * @return mixed
     */
    public function get($key);
}
