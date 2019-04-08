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

    /**
     * returns the status of the read buffer
     * @return string
     */
    public function getStatus(): string;
}
